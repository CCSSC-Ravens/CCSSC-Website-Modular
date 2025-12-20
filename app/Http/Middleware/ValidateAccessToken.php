<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\JwtService;
use App\Services\SessionService;
use App\Models\AuthSession;

class ValidateAccessToken
{
    public function __construct(
        protected JwtService $jwtService,
        protected SessionService $sessionService
    ) {}

    /**
     * Handle an incoming request.
     * Validates JWT access token from Authorization header.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): Response
    {
        $token = $this->extractToken($request);

        if (!$token) {
            return response()->json([
                'message' => 'Access token required.',
                'error' => 'missing_token',
            ], 401);
        }

        // Validate and decode JWT
        $payload = $this->jwtService->validateAccessToken($token);

        if (!$payload) {
            return response()->json([
                'message' => 'Invalid or expired access token.',
                'error' => 'invalid_token',
            ], 401);
        }

        // Verify the session is still valid (not revoked)
        $session = AuthSession::find($payload['session_id']);

        if (!$session || !$session->isValid()) {
            return response()->json([
                'message' => 'Session has been revoked or expired.',
                'error' => 'session_invalid',
            ], 401);
        }

        // Check guard type if specified
        if ($guard && $payload['type'] !== ucfirst($guard)) {
            return response()->json([
                'message' => 'Unauthorized for this resource.',
                'error' => 'wrong_guard',
            ], 403);
        }

        // Get the user model class and retrieve user
        $modelClass = $this->jwtService->getUserModelClass($payload['type']);

        if (!$modelClass) {
            return response()->json([
                'message' => 'Unknown user type.',
                'error' => 'unknown_type',
            ], 401);
        }

        $user = $modelClass::find($payload['sub']);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
                'error' => 'user_not_found',
            ], 401);
        }

        // Update session activity (sliding expiry)
        $this->sessionService->touchSession($session);

        // Attach user and session to request for use in controllers
        $request->merge([
            'jwt_user' => $user,
            'jwt_session' => $session,
            'jwt_payload' => $payload,
        ]);

        // Also set the user on the request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    /**
     * Extract Bearer token from Authorization header
     */
    private function extractToken(Request $request): ?string
    {
        $header = $request->header('Authorization', '');

        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }

        return null;
    }
}
