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

// Invoice download route
Route::middleware(['auth'])->get('/admin/report/download-invoice', function (\Illuminate\Http\Request $request) {
    $number = $request->query('number');
    $type = $request->query('type', 'jasa');
    
    if (!$number) {
        abort(400, 'Number parameter is required');
    }
    
    $reportPage = new \App\Filament\Pages\Report();
    return $reportPage->openInvoice($number, $type);
});

// Invoice preview route (inline PDF preview)
Route::middleware(['auth'])->get('/admin/report/preview-invoice', function (\Illuminate\Http\Request $request) {
    $number = $request->query('number');
    $type = $request->query('type', 'jasa');
    
    if (!$number) {
        abort(400, 'Number parameter is required');
    }
    
    $reportPage = new \App\Filament\Pages\Report();
    return $reportPage->previewInvoice($number, $type);
});

// routes/web.php
Route::get('/session-expired', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('session.expired');