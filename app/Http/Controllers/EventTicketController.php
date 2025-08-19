<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;

class EventTicketController extends Controller
{
    public function store(Request $request, $id)
    {
        try {
            $dataToInsert = $request->all();
            $dataToInsert['event_id'] = $id;
            DB::table('event_tickets')->insert($dataToInsert);

            return response()->json([
                'message' => 'Ticket created successfully',
                'data' => $dataToInsert
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTickets($eventId)
    {
        try {
            $tickets = DB::table('event_tickets')
                ->where('event_id', $eventId)
                ->get();

            return response()->json([
                'message' => 'Tickets retrieved successfully',
                'data' => $tickets
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserTickets($paymentRef)
    {
        try {
            $tickets = DB::table('transactions')
                ->leftJoin('transaction_tickets', 'transactions.id', '=', 'transaction_tickets.transaction_id')
                ->leftJoin('tickets', 'transaction_tickets.ticket_id', '=', 'tickets.id')
                ->where('transactions.payment_reference', $paymentRef)
                ->select('tickets.*')
                ->get();

            $tickets = $tickets->map(function ($ticket) {
                $ticket->qrcode_url = url('/api/tickets/qrcode/' . $ticket->code);
                return $ticket;
            });

            return response()->json([
                'status'  => true,
                'message' => 'User tickets retrieved successfully',
                'data'    => $tickets
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to retrieve user tickets',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function getTicketQrImage($code)
    {
        $qr = QrCode::format('svg')
            ->size(300)
            ->generate($code);

        return Response::make($qr, 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'inline; filename="qrcode.svg"'
        ]);
    }

    public function checkTicketQrImage($code)
    {
        try {
            $ticket = DB::table('tickets')
                ->where('code', $code)
                ->first();

            if (!$ticket) {
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Ticket found',
                'data' => $ticket
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error checking ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
