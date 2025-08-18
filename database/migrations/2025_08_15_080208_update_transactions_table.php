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
        // Create transaction_status enum type
        DB::statement("DROP TYPE IF EXISTS transaction_status;");
        DB::statement("CREATE TYPE transaction_status AS ENUM ('pending', 'paid', 'cancelled', 'expired')");

        // Drop and recreate transactions table
        Schema::dropIfExists('transactions');

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('event_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('amount', 10, 2);
            $table->string('payment_reference')->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamp('payment_expired_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('user_id')->references('id')->on('users');

            // Indexes for faster lookups
            $table->index('event_id');
            $table->index('user_id');
            $table->index('status');
        });

        DB::statement("ALTER TABLE transactions ADD CONSTRAINT status_check CHECK (status IN ('pending', 'paid', 'cancelled', 'expired'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');

        // Drop the enum type
        DB::statement("DROP TYPE IF EXISTS transaction_status");
    }
};
