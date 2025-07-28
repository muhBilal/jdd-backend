<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
