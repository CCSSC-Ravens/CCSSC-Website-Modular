<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\AuthSession;
use Illuminate\Database\Eloquent\Model;

class JwtService
{
    private string $secret;
    private string $algorithm = 'HS256';
    private int $accessTokenTTL = 15; // minutes

    public function __construct()
    {
        $this->secret = config('app.key');
    }

    /**
     * Generate a short-lived access JWT token
     */
    public function generateAccessToken(Model $user, AuthSession $session): string
    {
        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'type' => class_basename($user), // 'Admin' or 'OrganizationUser'
            'session_id' => $session->id,
            'iat' => time(),
            'exp' => time() + ($this->accessTokenTTL * 60),
        ];

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    /**
     * Validate and decode an access JWT token
     * Returns null if token is invalid or expired
     */
    public function validateAccessToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get the user model class from token type
     */
    public function getUserModelClass(string $type): ?string
    {
        return match ($type) {
            'Admin' => \App\Models\Admin::class,
            'OrganizationUser' => \App\Models\OrganizationUser::class,
            default => null,
        };
    }

    /**
     * Get remaining TTL for access tokens (in seconds)
     */
    public function getAccessTokenTTL(): int
    {
        return $this->accessTokenTTL * 60;
    }
}
