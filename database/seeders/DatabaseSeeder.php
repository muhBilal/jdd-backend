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
        // for ($i = 1; $i <= 30; $i++) {
        //     DB::table('users')->insert([
        //         'email' => "user{$i}@example.com",
        //         'password_hash' => Hash::make('password123'),
        //         'full_name' => "User {$i}",
        //         'auth_provider' => 'email', // Assuming auth_provider is email
        //         'google_id' => null,
        //         'is_email_verified' => true,
        //         'email_verification_token' => Str::random(60),
        //         'email_verification_expires_at' => now()->addMinutes(30),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Insert 10 sample events
        for ($i = 1; $i <= 10; $i++) {
            DB::table('events')->insert([
                'title' => "Event {$i}",
                'description' => "This is the description for event {$i}",
                'start_date' => now()->addDays(rand(1, 30)),
                'end_date' => now()->addDays(rand(1, 30))->addHours(2),
                'images' => json_encode(["image1.jpg", "image2.jpg"]),
                'venue_name' => "Venue {$i}",
                'venue_address' => "Address {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert 15 sample event tickets
        // for ($i = 1; $i <= 15; $i++) {
        //     DB::table('event_tickets')->insert([
        //         'event_id' => rand(1, 10),
        //         'name' => "Ticket {$i}",
        //         'price' => rand(1000, 10000) / 100,
        //         'quota' => rand(10, 100),
        //         'start_date' => now()->addDays(rand(1, 30)),
        //         'end_date' => now()->addDays(rand(1, 30))->addHours(2),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Insert 5 sample event forms
        // for ($i = 1; $i <= 5; $i++) {
        //     DB::table('event_forms')->insert([
        //         'event_id' => rand(1, 10), // Random event id
        //         'label' => "Form {$i}",
        //         'datatype' => rand(0, 1) ? 'text' : 'number', // Random data type
        //         'options' => json_encode(['Option 1', 'Option 2']),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Insert 30 sample transactions
        // for ($i = 1; $i <= 30; $i++) {
        //     DB::table('transactions')->insert([
        //         'event_id' => rand(1, 10),
        //         'user_id' => rand(1, 30),
        //         'status' => 'pending',
        //         'amount' => rand(1000, 10000) / 100,
        //         'payment_reference' => 'REF' . $i,
        //         'payment_url' => "https://payment.url/transaction/{$i}",
        //         'payment_expired_at' => now()->addDays(5),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // Insert 30 sample tickets for users
        // for ($i = 1; $i <= 30; $i++) {
        //     DB::table('ticket_users')->insert([
        //         'user_id' => rand(1, 30),
        //         'ticket_id' => rand(1, 15),
        //     ]);
        // }

        // Insert 30 sample event form tickets
        // for ($i = 1; $i <= 30; $i++) {
        //     DB::table('event_form_tickets')->insert([
        //         'event_form_id' => rand(1, 5),
        //         'ticket_id' => rand(1, 15),
        //         'value' => 'Sample value ' . $i,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
