<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AuthSession extends Model
{
    use HasUuids;

    protected $table = 'auth_sessions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'authenticatable_type',
        'authenticatable_id',
        'refresh_token_hash',
        'previous_token_hash',
        'rotation_count',
        'last_rotated_at',
        'expires_at',
        'last_activity_at',
        'inactivity_timeout_minutes',
        'ip_address',
        'user_agent',
        'device_name',
        'location',
        'csrf_token_hash',
        'is_revoked',
        'revoked_at',
        'revoke_reason',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'last_rotated_at' => 'datetime',
        'revoked_at' => 'datetime',
        'is_revoked' => 'boolean',
        'rotation_count' => 'integer',
        'inactivity_timeout_minutes' => 'integer',
    ];

    /**
     * Polymorphic relationship to the authenticatable user (Admin or OrganizationUser)
     */
    public function authenticatable()
    {
        return $this->morphTo();
    }

    /**
     * Scope for active (non-revoked, non-expired) sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_revoked', false)
            ->where('expires_at', '>', now());
    }

    /**
     * Scope for sessions belonging to a specific user
     */
    public function scopeForUser($query, Model $user)
    {
        return $query->where('authenticatable_type', get_class($user))
            ->where('authenticatable_id', $user->id);
    }

    /**
     * Check if session is valid (not expired, not inactive, not revoked)
     */
    public function isValid(): bool
    {
        if ($this->is_revoked) {
            return false;
        }

        if ($this->expires_at < now()) {
            return false;
        }

        // Check inactivity timeout
        $inactiveThreshold = $this->last_activity_at
            ->addMinutes($this->inactivity_timeout_minutes);

        if ($inactiveThreshold < now()) {
            return false;
        }

        return true;
    }

    /**
     * Get time remaining until absolute expiry
     */
    public function getExpiresInAttribute(): ?int
    {
        if ($this->expires_at < now()) {
            return null;
        }

        return now()->diffInSeconds($this->expires_at);
    }

    /**
     * Get time remaining until inactivity timeout
     */
    public function getInactivityExpiresInAttribute(): ?int
    {
        $threshold = $this->last_activity_at->addMinutes($this->inactivity_timeout_minutes);

        if ($threshold < now()) {
            return null;
        }

        return now()->diffInSeconds($threshold);
    }
}
