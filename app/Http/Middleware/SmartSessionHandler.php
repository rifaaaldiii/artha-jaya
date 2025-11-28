<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SmartSessionHandler
{
    /**
     * Handle an incoming request.
     * 
     * Smart session handler yang membedakan antara:
     * - Session expired (logout)
     * - CSRF expired (regenerate token)
     * - Rate limiting (retry)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya handle 403 errors untuk Livewire dan Polling
        if ($response->getStatusCode() === 403) {
            $isLivewire = $request->is('livewire/*') || $request->header('X-Livewire');
            $isPolling = $request->is('polling/*');
            
            if ($isLivewire || $isPolling) {
                // Check apakah user masih authenticated
                $isAuthenticated = Auth::check();
                
                if (!$isAuthenticated) {
                    // Session benar-benar expired - LOGOUT
                    return $this->handleSessionExpired($request, $isPolling);
                } else {
                    // User masih authenticated, kemungkinan CSRF token expired
                    // Regenerate token dan retry
                    return $this->handleCsrfExpired($request, $response, $isPolling);
                }
            }
        }

        return $response;
    }

    /**
     * Handle session yang benar-benar expired
     */
    private function handleSessionExpired(Request $request, bool $isPolling): Response
    {
        Log::warning('Session expired - Auto logout', [
            'url' => $request->url(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Logout user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear cookies
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));
        Cookie::queue(Cookie::forget('laravel_session'));

        // Return appropriate response
        if ($isPolling || $request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'Session expired',
                'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                'logout' => true,
                'redirect' => route('filament.admin.auth.login'),
            ], 403);
        }

        return redirect()
            ->route('filament.admin.auth.login')
            ->with('danger', 'Sesi Anda telah berakhir. Silakan login kembali.');
    }

    /**
     * Handle CSRF token expired tapi session masih valid
     */
    private function handleCsrfExpired(Request $request, Response $response, bool $isPolling): Response
    {
        Log::info('CSRF token expired but session valid - Regenerating token', [
            'url' => $request->url(),
            'user_id' => Auth::id(),
        ]);

        // Regenerate CSRF token
        $request->session()->regenerateToken();
        
        // Touch session untuk extend lifetime
        $request->session()->touch();

        // Return response dengan info untuk retry
        if ($isPolling || $request->expectsJson() || $request->ajax()) {
            return response()->json([
                'error' => 'CSRF token expired',
                'message' => 'Token telah diperbarui. Silakan coba lagi.',
                'retry' => true,
                'csrf_token' => csrf_token(),
            ], 403);
        }

        // For non-AJAX requests, return original response
        // Client akan retry dengan token baru
        return $response;
    }
}

