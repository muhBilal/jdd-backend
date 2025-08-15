<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        //Create example user
        DB::table('users')->insert([
            'full_name' => 'Fahmi Syaifudin',
            'email' => 'fahmi@example.com',
            'password_hash' => Hash::make('pecellele123'),
            'auth_provider' => 'email',
            'is_email_verified' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $eventId = Str::uuid();
        DB::table('events')->insert([
            'id' => $eventId,
            'title' => "Test Demo JDD",
            'description' => "This is the description for event",
            'start_date' => now()->addDays(rand(1, 30)),
            'end_date' => now()->addDays(rand(1, 30))->addHours(2),
            'images' => json_encode(["https://picsum.photos/200/300", "https://picsum.photos/200/300"]),
            'venue_name' => "Universitas Tambah Maju",
            'venue_address' => "Jl Simpang Lima No 3",
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // Create default form fields
        $formFields = [
            [
                'event_id' => $eventId,
                'label' => 'Age',
                'datatype' => 'number',
                'options' => null,
            ],
            [
                'event_id' => $eventId,
                'label' => 'Size T-Shirt',
                'datatype' => 'dropdown',
                'options' => json_encode(['M', 'S', 'XL', 'L']),
            ],
            [
                'event_id' => $eventId,
                'label' => 'Community',
                'datatype' => 'text',
                'options' => null,
            ],
        ];

        DB::table('event_forms')->insert($formFields);

        $ticketType = [
            [
                'event_id' => $eventId,
                'name' => 'Regular',
                'price' => 10000,
                'quota' => 100,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ],
            [
                'event_id' => $eventId,
                'name' => 'VIP',
                'price' => 50000,
                'quota' => 50,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
            ]
        ];
        DB::table('event_tickets')->insert($ticketType);
    }
}
