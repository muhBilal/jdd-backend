<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function process(Request $request)
    {
        try {
            $name = $request->name;
            $email = $request->email;
            $eventTicketId = $request->eventTicketId;
            $amount = (int) $request->amount;
            $tickets = $request->tickets;

            if (!is_array($tickets) || empty($tickets)) {
                return response()->json([
                    'message' => 'Tickets data is required and must be an array'
                ], 400);
            }

            // Pastikan event_ticket ada
            $eventTicket = DB::table('event_tickets')->where('id', $eventTicketId)->first();
            if (!$eventTicket) {
                return response()->json([
                    'message' => 'Invalid eventTicketId. Event ticket does not exist.'
                ], 404);
            }

            // Pastikan event ada
            $event = DB::table('events')->where('id', $eventTicket->event_id)->first();
            if (!$event) {
                return response()->json([
                    'message' => 'Invalid event ID. Event does not exist.'
                ], 404);
            }

            DB::beginTransaction();

            // Cari / buat user
            $user = DB::table('users')->where('email', $email)->first();
            if (!$user) {
                $userId = Str::uuid()->toString();
                DB::table('users')->insert([
                    'id' => $userId,
                    'email' => $email,
                    'password_hash' => Hash::make('password'),
                    'full_name' => $name,
                    'auth_provider' => 'email'
                ]);
                $user = DB::table('users')->where('id', $userId)->first();
            }

            $insertedTicketIds = [];

            // Buat transaksi dulu → ambil ID-nya
            $transactionId = Str::uuid()->toString();
            DB::table('transactions')->insert([
                'id'       => $transactionId,
                'event_id' => $event->id,
                'user_id'  => $user->id,
                'amount'   => $amount,
            ]);

            // Buat tiket sesuai jumlah amount
            for ($i = 0; $i < $amount; $i++) {
                $ticketId = Str::uuid()->toString();

                // Insert tiket
                DB::table('tickets')->insert([
                    'id' => $ticketId,
                    'name' => $name,
                    'email' => $email,
                    'code' => Str::random(10),
                    'event_ticket_id' => $eventTicketId,
                ]);

                // Hubungkan tiket dengan user
                DB::table('ticket_users')->insert([
                    'ticket_id' => $ticketId,
                    'user_id'   => $user->id,
                ]);

                // Hubungkan tiket dengan transaksi


                // Insert data form untuk tiket ini
                foreach ($tickets as $t) {
                    DB::table('event_form_tickets')->insert([
                        'event_form_id' => $t['eventFormId'],
                        'ticket_id'     => $ticketId,
                        'value'         => $t['value'],
                    ]);
                }

                $insertedTicketIds[] = $ticketId;
            }

            DB::table('transaction_tickets')->insert([
                'transaction_id' => $transactionId,
                'ticket_id'      => $ticketId,
                'quantity'      => $amount,
                'price_at_purchase' => $eventTicket->price * $amount,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transaction processed successfully',
                'transaction_id' => $transactionId,
                'ticket_ids' => $insertedTicketIds,
                'data' => $request->all()
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Transaction failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function handleTransaction(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            return response()->json([
                'message' => 'Transaction processed successfully',
                'data' => $validatedData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Transaction failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function initiatePayment(Request $request, $transactionId)
    {
        $transaction = DB::table('transactions')->where('id', $transactionId)->first();
        $response = Http::post('https://sandbox.ipaymu.com/api/payment', [
            'api_key' => env('IPAYMU_API_KEY'),
            'amount' => $transaction->amount,
            'invoice' => $transaction->id,
            'name' => $transaction->user->full_name,
            'email' => $transaction->user->email,
        ]);

        $paymentUrl = $response->json()['payment_url'];

        $transaction->payment_url = $paymentUrl;
        $transaction->save();

        return redirect()->away($paymentUrl);
    }

    public function paymentCallback(Request $request)
    {
        $paymentData = $request->all();

        $transaction = DB::table('transactions')->where('id', $paymentData['invoice'])->first();
        if ($paymentData['status'] == 'success') {
            $transaction->status = 'paid';
            $transaction->payment_reference = $paymentData['payment_reference'];
            $transaction->save();
            // SendEmailNotification::dispatch($transaction->user, $transaction);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
}
