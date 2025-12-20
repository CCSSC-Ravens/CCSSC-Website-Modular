<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores secure session data for Admin and OrganizationUser accounts.
     *
     * Security Features:
     * - Refresh tokens are stored as SHA-256 hashes (never plain text)
     * - Supports token rotation with previous_token_hash for reuse detection
     * - Tracks both absolute expiry and sliding (inactivity) expiry
     * - Stores device/IP info for session management UI
     * - Supports explicit revocation
     */
    public function up(): void
    {
        Schema::create('auth_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID for unpredictable session IDs

            // Polymorphic relationship: supports Admin, OrganizationUser, or future user types
            $table->string('authenticatable_type'); // e.g., 'App\Models\Admin'
            $table->unsignedBigInteger('authenticatable_id'); // User ID

            // Refresh token (SHA-256 hash) - 64 chars hex
            $table->string('refresh_token_hash', 64)->unique();

            // Previous token hash for rotation/reuse detection
            // If someone uses an old token, we know the session was compromised
            $table->string('previous_token_hash', 64)->nullable();

            // Token rotation tracking
            $table->unsignedInteger('rotation_count')->default(0);
            $table->timestamp('last_rotated_at')->nullable();

            // Expiry management (using dateTime to avoid MySQL timestamp quirks)
            $table->dateTime('expires_at'); // Absolute expiry (e.g., 30 days from creation)
            $table->dateTime('last_activity_at'); // For sliding expiry/inactivity check
            $table->unsignedInteger('inactivity_timeout_minutes')->default(60); // Configurable per session

            // Session metadata for security monitoring & UI
            $table->string('ip_address', 45)->nullable(); // IPv6 support
            $table->string('user_agent', 500)->nullable();
            $table->string('device_name', 255)->nullable(); // Parsed device name for UI
            $table->string('location', 255)->nullable(); // Optional: geo-location from IP

            // CSRF token for this session (double-submit cookie strategy)
            $table->string('csrf_token_hash', 64)->nullable();

            // Session state
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->string('revoke_reason', 255)->nullable(); // 'logout', 'security', 'admin_action', 'token_reuse'

            // Audit timestamps
            $table->timestamps(); // created_at, updated_at

            // Indexes for performance
            $table->index(['authenticatable_type', 'authenticatable_id'], 'auth_sessions_user_index');
            $table->index('expires_at'); // For cleanup jobs
            $table->index('last_activity_at'); // For inactivity queries
            $table->index('is_revoked'); // For active session queries
            $table->index('previous_token_hash'); // For reuse detection
        });

        // Optional: Table for rate limiting login attempts
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('email')->nullable();
            $table->string('guard')->default('web'); // 'admin' or 'web'
            $table->boolean('successful')->default(false);
            $table->string('failure_reason', 255)->nullable();
            $table->dateTime('attempted_at');

            // Indexes
            $table->index(['ip_address', 'attempted_at']);
            $table->index(['email', 'attempted_at']);
            $table->index(['guard', 'attempted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
        Schema::dropIfExists('auth_sessions');
    }
};
