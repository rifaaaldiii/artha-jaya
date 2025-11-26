<?php

use App\Http\Controllers\PollingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->get('/polling/triggers', PollingController::class)->name('polling.triggers');

// routes/web.php
Route::get('/session-expired', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('session.expired');