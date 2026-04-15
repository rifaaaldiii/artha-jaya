<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle 404 Not Found - Redirect to custom 404 page
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception, \Illuminate\Http\Request $request) {
            // Jika request dari Livewire, Polling, atau AJAX
            if ($request->is('livewire/*') || $request->is('polling/*') || $request->header('X-Livewire') || $request->ajax()) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'Halaman tidak ditemukan.',
                    'not_found' => true,
                    'redirect' => route('filament.admin.pages.dashboard'),
                ], 404);
            }
            
            // Untuk request biasa, redirect ke custom 404 page
            return response()->view('errors.404', [
                'exception' => $exception
            ], 404);
        });

        // Handle 419 CSRF Token Expired
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $exception, \Illuminate\Http\Request $request) {
            // Jika request dari Livewire atau AJAX
            if ($request->is('livewire/*') || $request->header('X-Livewire') || $request->ajax()) {
                // Cek apakah user masih authenticated
                if (!\Illuminate\Support\Facades\Auth::check()) {
                    // Session expired - return JSON untuk redirect
                    return response()->json([
                        'error' => 'Session expired',
                        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                        'logout' => true,
                        'redirect' => route('filament.admin.auth.login'),
                    ], 419);
                }
                
                // User masih authenticated, CSRF token expired
                return response()->json([
                    'error' => 'CSRF token expired',
                    'message' => 'Token telah diperbarui. Silakan coba lagi.',
                    'retry' => true,
                    'csrf_token' => csrf_token(),
                ], 419);
            }
            
            // Untuk request biasa, redirect ke login
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return redirect()
                    ->route('filament.admin.auth.login')
                    ->with('danger', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
            
            // Regenerate token dan redirect back
            $request->session()->regenerateToken();
            return redirect()->back()->with('info', 'Token telah diperbarui. Silakan coba lagi.');
        });

        // Handle 401 Unauthorized
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $exception, \Illuminate\Http\Request $request) {
            if ($exception->getStatusCode() === 401) {
                // Jika request dari Livewire, Polling, atau AJAX
                if ($request->is('livewire/*') || $request->is('polling/*') || $request->header('X-Livewire') || $request->ajax()) {
                    return response()->json([
                        'error' => 'Unauthorized',
                        'message' => 'Sesi Anda telah berakhir. Silakan login kembali.',
                        'logout' => true,
                        'redirect' => route('filament.admin.auth.login'),
                    ], 401);
                }
                
                // Untuk request biasa, redirect ke login
                return redirect()
                    ->route('filament.admin.auth.login')
                    ->with('danger', 'Sesi Anda telah berakhir. Silakan login kembali.');
            }
            
            return null; // Biarkan Laravel handle error lainnya
        });
    })->create();
