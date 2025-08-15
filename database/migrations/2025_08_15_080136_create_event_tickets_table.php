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
        Schema::create('event_tickets', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('event_id');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('quota');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('event_id')->references('id')->on('events');

            // Index for faster lookups
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};
