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
        // Create auth_provider enum type
        DB::statement('DROP TYPE IF EXISTS auth_provider;');
        DB::statement("CREATE TYPE auth_provider AS ENUM ('google', 'email')");

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('email')->unique();
            $table->string('password_hash')->nullable();
            $table->string('full_name')->nullable();
            $table->string('auth_provider')->default('email');
            $table->string('google_id')->nullable();
            $table->boolean('is_email_verified')->default(false);
            $table->string('email_verification_token')->nullable();
            $table->timestamp('email_verification_expires_at')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->timestamps();

            // Add index on email for faster lookups
            $table->index('email');
        });

        DB::statement("ALTER TABLE users ADD CONSTRAINT auth_provider_check CHECK (auth_provider IN ('google', 'email'))");

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        // Drop the enum type
        DB::statement("DROP TYPE IF EXISTS auth_provider");
    }
};
