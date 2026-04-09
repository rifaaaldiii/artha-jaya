<?php

use App\Http\Controllers\PollingController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->get('/polling/triggers', PollingController::class)->name('polling.triggers');

// Report PDF generation routes
Route::middleware(['auth'])->prefix('reports')->name('reports.')->group(function () {
    Route::get('/pdf', [ReportController::class, 'generate'])->name('pdf');
});

// routes/web.php
Route::get('/session-expired', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('session.expired');