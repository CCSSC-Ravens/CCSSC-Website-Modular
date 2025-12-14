<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all auth sessions for this admin
     */
    public function authSessions()
    {
        return $this->morphMany(AuthSession::class, 'authenticatable');
    }

    /**
     * Get active (non-revoked, non-expired) sessions
     */
    public function activeSessions()
    {
        return $this->authSessions()
            ->where('is_revoked', false)
            ->where('expires_at', '>', now());
    }
}

