<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\SessionService;
use App\Models\AuthSession;

class VerifyApiCsrf
{
    public function __construct(
        protected SessionService $sessionService
    ) {}

    /**
     * Handle an incoming request.
     * Implements double-submit cookie pattern for CSRF protection.
     *
     * The client must:
     * 1. Read the 'api_csrf_token' cookie value
     * 2. Send it in the 'X-CSRF-TOKEN' header
     * 3. We verify it matches the session's stored CSRF token
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip CSRF check for safe methods
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        // Get CSRF token from header
        $headerToken = $request->header('X-CSRF-TOKEN') ?? $request->header('X-Api-Csrf-Token');

        if (!$headerToken) {
            return response()->json([
                'message' => 'CSRF token required.',
                'error' => 'missing_csrf',
            ], 403);
        }

        // Get the session from request (set by ValidateAccessToken middleware)
        $session = $request->get('jwt_session');

        if (!$session instanceof AuthSession) {
            // Try to get session from refresh token cookie for non-JWT routes
            $refreshToken = $request->cookie('refresh_token');
            if ($refreshToken) {
                $session = $this->sessionService->findByRefreshToken($refreshToken);
            }
        }

        if (!$session) {
            return response()->json([
                'message' => 'No valid session found.',
                'error' => 'no_session',
            ], 403);
        }

        // Validate the CSRF token
        if (!$this->sessionService->validateCsrfToken($session, $headerToken)) {
            return response()->json([
                'message' => 'Invalid CSRF token.',
                'error' => 'invalid_csrf',
            ], 403);
        }

        return $next($request);
    }
}
