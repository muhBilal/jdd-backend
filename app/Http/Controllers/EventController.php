<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);
            $search = $request->query('search');
            $query = DB::table('events');
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }
            $events = $query->paginate($perPage, ['*'], 'page', $page);

            $events->getCollection()->transform(function ($event) {
                $event->images = $event->images ? json_decode($event->images, true) : [];
                return $event;
            });

            return response()->json([
                'message' => 'Events retrieved successfully',
                'data' => $events->items(),
                'pagination' => [
                    'total' => $events->total(),
                    'current_page' => $events->currentPage(),
                    'per_page' => $events->perPage(),
                    'last_page' => $events->lastPage(),
                    'next_page_url' => $events->nextPageUrl(),
                    'prev_page_url' => $events->previousPageUrl(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve events',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $eventId = Str::uuid()->toString();
            $dataToInsert = $request->only([
                'title',
                'description',
                'start_date',
                'end_date',
                'venue_name',
                'venue_address'
            ]);
            $dataToInsert['id'] = $eventId;

            if ($request->hasFile('images')) {
                $imagePaths = collect($request->file('images'))
                    ->filter(fn($image) => $image->isValid())
                    ->map(fn($image) => Storage::url($image->store('public/images')))
                    ->toArray();

                $dataToInsert['images'] = json_encode($imagePaths);
            } else {
                $dataToInsert['images'] = json_encode([]);
            }

            $dataToInsert['created_at'] = now();
            $dataToInsert['updated_at'] = now();

            DB::table('events')->insert($dataToInsert);

            $newEvent = DB::table('events')->where('id', $eventId)->first();
            $newEvent->images = json_decode($newEvent->images, true) ?: [];

            return response()->json([
                'message' => 'Event created successfully',
                'data' => $newEvent
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $event = DB::table('events')->where('id', $id)->first();

            if (!$event) {
                return response()->json([
                    'message' => 'Event not found'
                ], 404);
            }

            if ($event->images) {
                $event->images = json_decode($event->images, true);
            } else {
                $event->images = [];
            }

            return response()->json([
                'message' => 'Event retrieved successfully',
                'data' => $event
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $event = DB::table('events')->where('id', $id)->first();
            if (!$event) {
                return response()->json([
                    'message' => 'Event not found'
                ], 404);
            }

            $dataToUpdate = $request->only([
                'title',
                'description',
                'start_date',
                'end_date',
                'venue_name',
                'venue_address'
            ]);

            if ($request->has('images')) {
                $images = is_array($request->input('images')) ? $request->input('images') : [];
                $dataToUpdate['images'] = json_encode($images);
            }

            $dataToUpdate['updated_at'] = now();

            $affectedRows = DB::table('events')->where('id', $id)->update($dataToUpdate);

            if ($affectedRows === 0) {
                $updatedEvent = DB::table('events')->where('id', $id)->first();
                $updatedEvent->images = json_decode($updatedEvent->images, true) ?: [];
                return response()->json([
                    'message' => 'No new changes detected for the event',
                    'data' => $updatedEvent
                ], 200);
            }

            $updatedEvent = DB::table('events')->where('id', $id)->first();
            $updatedEvent->images = json_decode($updatedEvent->images, true) ?: [];

            return response()->json([
                'message' => 'Event updated successfully',
                'data' => $updatedEvent
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $event = DB::table('events')->where('id', $id)->first();

            if (!$event) {
                return response()->json([
                    'message' => 'Event not found'
                ], 404);
            }

            $deleted = DB::table('events')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'message' => 'Event deleted successfully'
                ], 204);
            } else {
                return response()->json([
                    'message' => 'Failed to delete event'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function tmp(Request $request, string $id)
    {
        dd($request->all(), $id);
        // This method is a placeholder for testing purposes
        // You can implement your logic here as needed
        return response()->json([
            'message' => 'Temporary endpoint reached',
            'id' => $id,
            'data' => $request->all()
        ], 200);
    }
}
