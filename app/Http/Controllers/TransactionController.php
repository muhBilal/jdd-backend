<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
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
            SendEmailNotification::dispatch($transaction->user, $transaction);

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }
}
