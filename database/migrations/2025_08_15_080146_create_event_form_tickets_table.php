<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_form_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('event_form_id');
            $table->uuid('ticket_id');
            $table->text('value')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('event_form_id')->references('id')->on('event_forms');
            $table->foreign('ticket_id')->references('id')->on('tickets');

            // Indexes for faster lookups
            $table->index('event_form_id');
            $table->index('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_form_tickets');
    }
};
