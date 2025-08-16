<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class EventTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_event_index(): void
    {
        $response = $this->get('/api/event');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'start_date',
                    'images' => [],
                    'end_date',
                    'created_at',
                    'updated_at',
                ],
            ],
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
            ],
        ]);
        $data = $response->json('data');
        $this->assertGreaterThan(0, count($data));
    }

    public function test_event_detail()
    {
        $randomEvent = DB::table('events')->first();
        $response = $this->get('/api/event/' . $randomEvent->id);
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'title',
                'description',
                'start_date',
                'images' => [],
                'end_date',
                'created_at',
                'updated_at',
                'forms' => [
                    '*' => [
                        'label',
                        'options' => []
                    ],
                ],
                'tickets' => [
                    '*' => [
                        'price',
                        'quota',
                        'sold',
                    ],
                ],
            ],
        ]);
        $response->assertStatus(200);
    }
}
