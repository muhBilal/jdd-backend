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
        // Create form_datatype enum type
        DB::statement("CREATE TYPE form_datatype AS ENUM ('text', 'number', 'date', 'datetime', 'checkbox', 'dropdown', 'file')");

        Schema::create('event_forms', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('event_id');
            $table->string('label');
            $table->string('datatype');
            $table->json('options')->nullable(); // Using JSON for PostgreSQL array
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('event_id')->references('id')->on('events');

            // Index for faster lookups
            $table->index('event_id');
        });

        DB::statement("ALTER TABLE event_forms ADD CONSTRAINT datatype_check CHECK (datatype IN ('text', 'number', 'date', 'datetime', 'checkbox', 'dropdown', 'file'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_forms');

        // Drop the enum type
        DB::statement("DROP TYPE IF EXISTS form_datatype");
    }
};
