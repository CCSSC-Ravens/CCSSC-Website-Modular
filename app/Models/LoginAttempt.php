<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'email',
        'guard',
        'successful',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'successful' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    /**
     * Log a login attempt
     */
    public static function log(
        string $ipAddress,
        ?string $email = null,
        string $guard = 'web',
        bool $successful = false,
        ?string $failureReason = null
    ): self {
        return self::create([
            'ip_address' => $ipAddress,
            'email' => $email,
            'guard' => $guard,
            'successful' => $successful,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);
    }

    /**
     * Count failed attempts for an IP in the last N minutes
     */
    public static function recentFailedAttemptsForIp(string $ipAddress, int $minutes = 15): int
    {
        return self::where('ip_address', $ipAddress)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Count failed attempts for an email in the last N minutes
     */
    public static function recentFailedAttemptsForEmail(string $email, int $minutes = 15): int
    {
        return self::where('email', $email)
            ->where('successful', false)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();
    }

    /**
     * Check if IP is rate limited (too many failed attempts)
     */
    public static function isIpRateLimited(string $ipAddress, int $maxAttempts = 5, int $minutes = 15): bool
    {
        return self::recentFailedAttemptsForIp($ipAddress, $minutes) >= $maxAttempts;
    }

    /**
     * Check if email is rate limited (too many failed attempts)
     */
    public static function isEmailRateLimited(string $email, int $maxAttempts = 5, int $minutes = 15): bool
    {
        return self::recentFailedAttemptsForEmail($email, $minutes) >= $maxAttempts;
    }

    /**
     * Clear old attempts (for cleanup job)
     */
    public static function clearOldAttempts(int $days = 7): int
    {
        return self::where('attempted_at', '<', now()->subDays($days))->delete();
    }
}
