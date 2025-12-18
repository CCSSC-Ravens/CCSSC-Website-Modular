<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\LoginAttempt;
use App\Services\SessionService;
use App\Services\JwtService;

class AuthController extends Controller
{
    public function __construct(
        protected SessionService $sessionService,
        protected JwtService $jwtService
    ) {}

    public function showLogin()
    {
        return view('admin::login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Rate limiting check
        if (LoginAttempt::isIpRateLimited($request->ip())) {
            throw ValidationException::withMessages([
                'email' => __('Too many login attempts. Please try again later.'),
            ]);
        }

        if (LoginAttempt::isEmailRateLimited($credentials['email'])) {
            throw ValidationException::withMessages([
                'email' => __('Too many login attempts for this account. Please try again later.'),
            ]);
        }

        // Validate credentials
        if (!Auth::guard('admin')->validate($credentials)) {
            // Log failed attempt
            LoginAttempt::log(
                $request->ip(),
                $credentials['email'],
                'admin',
                false,
                'invalid_credentials'
            );

            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records.'),
            ]);
        }

        // Get the authenticated user
        $user = Auth::guard('admin')->getProvider()->retrieveByCredentials($credentials);

        // Log successful attempt
        LoginAttempt::log(
            $request->ip(),
            $credentials['email'],
            'admin',
            true
        );

        // Create secure session with refresh token
        $sessionData = $this->sessionService->createSession(
            $user,
            $request->ip(),
            $request->userAgent() ?? 'Unknown'
        );

        // Log the user in using Laravel's session (for web routes)
        Auth::guard('admin')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Store session ID in Laravel session for reference
        $request->session()->put('auth_session_id', $sessionData['session']->id);

        // Set HttpOnly cookie for refresh token (30 days)
        $refreshCookie = cookie(
            'refresh_token',
            $sessionData['refresh_token'],
            60 * 24 * 30, // 30 days in minutes
            '/',
            null,
            $request->secure(), // secure flag based on request
            true, // httpOnly
            false,
            'Lax' // SameSite
        );

        // Set CSRF token cookie (readable by JS for API calls)
        $csrfCookie = cookie(
            'api_csrf_token',
            $sessionData['csrf_token'],
            60 * 24 * 30,
            '/',
            null,
            $request->secure(),
            false, // NOT httpOnly - JS needs to read it
            false,
            'Lax'
        );

        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('success', 'Welcome back! You have successfully logged in.')
            ->withCookie($refreshCookie)
            ->withCookie($csrfCookie);
    }

    /**
     * API login endpoint - returns JWT token
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Rate limiting check
        if (LoginAttempt::isIpRateLimited($request->ip())) {
            return response()->json([
                'message' => 'Too many login attempts. Please try again later.',
            ], 429);
        }

        // Validate credentials
        if (!Auth::guard('admin')->validate($credentials)) {
            LoginAttempt::log(
                $request->ip(),
                $credentials['email'],
                'admin',
                false,
                'invalid_credentials'
            );

            return response()->json([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::guard('admin')->getProvider()->retrieveByCredentials($credentials);

        LoginAttempt::log(
            $request->ip(),
            $credentials['email'],
            'admin',
            true
        );

        // Create secure session
        $sessionData = $this->sessionService->createSession(
            $user,
            $request->ip(),
            $request->userAgent() ?? 'Unknown'
        );

        // Generate JWT access token
        $accessToken = $this->jwtService->generateAccessToken($user, $sessionData['session']);

        // Set refresh token cookie
        $refreshCookie = cookie(
            'refresh_token',
            $sessionData['refresh_token'],
            60 * 24 * 30,
            '/',
            null,
            $request->secure(),
            true,
            false,
            'Strict'
        );

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->jwtService->getAccessTokenTTL(),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ])->withCookie($refreshCookie);
    }

    /**
     * Refresh access token using refresh token from cookie
     */
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json(['message' => 'No refresh token provided.'], 401);
        }

        // Rotate token (validates and issues new one)
        $result = $this->sessionService->rotateToken($refreshToken);

        if (!$result) {
            // Token invalid, expired, or reused - clear cookie
            return response()->json(['message' => 'Invalid or expired session.'], 401)
                ->withCookie(cookie()->forget('refresh_token'));
        }

        $session = $result['session'];
        $user = $session->authenticatable;

        // Generate new access token
        $accessToken = $this->jwtService->generateAccessToken($user, $session);

        // Set new refresh token cookie
        $refreshCookie = cookie(
            'refresh_token',
            $result['refresh_token'],
            60 * 24 * 30,
            '/',
            null,
            $request->secure(),
            true,
            false,
            'Strict'
        );

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->jwtService->getAccessTokenTTL(),
        ])->withCookie($refreshCookie);
    }

    public function logout(Request $request)
    {
        // Revoke the secure session if exists
        $sessionId = $request->session()->get('auth_session_id');
        if ($sessionId) {
            $authSession = \App\Models\AuthSession::find($sessionId);
            if ($authSession) {
                $this->sessionService->revokeSession($authSession, 'logout');
            }
        }

        // Also try to revoke by refresh token cookie
        $refreshToken = $request->cookie('refresh_token');
        if ($refreshToken) {
            $session = $this->sessionService->findByRefreshToken($refreshToken);
            if ($session) {
                $this->sessionService->revokeSession($session, 'logout');
            }
        }

        // Laravel logout
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear cookies
        return redirect()->route('admin.login')
            ->with('success', 'Logged out successfully.')
            ->withCookie(cookie()->forget('refresh_token'))
            ->withCookie(cookie()->forget('api_csrf_token'));
    }

    /**
     * Revoke all other sessions (security feature)
     */
    public function revokeOtherSessions(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $currentSessionId = $request->session()->get('auth_session_id');

        $this->sessionService->revokeAllUserSessions($user, $currentSessionId);

        return back()->with('status', 'All other sessions have been revoked.');
    }

    /**
     * List active sessions for the user
     */
    public function sessions(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $sessions = $this->sessionService->getUserSessions($user);
        $currentSessionId = $request->session()->get('auth_session_id');

        return response()->json([
            'sessions' => $sessions->map(function ($session) use ($currentSessionId) {
                return [
                    'id' => $session->id,
                    'device_name' => $session->device_name,
                    'ip_address' => $session->ip_address,
                    'last_activity' => $session->last_activity_at->diffForHumans(),
                    'created_at' => $session->created_at->diffForHumans(),
                    'is_current' => $session->id === $currentSessionId,
                ];
            }),
        ]);
    }

    /**
     * Revoke a specific session
     */
    public function revokeSession(Request $request, string $sessionId)
    {
        $user = Auth::guard('admin')->user();

        $session = \App\Models\AuthSession::where('id', $sessionId)
            ->where('authenticatable_type', get_class($user))
            ->where('authenticatable_id', $user->id)
            ->first();

        if (!$session) {
            return response()->json(['message' => 'Session not found.'], 404);
        }

        $this->sessionService->revokeSession($session, 'user_revoked');

        return response()->json(['message' => 'Session revoked successfully.']);
    }
}
