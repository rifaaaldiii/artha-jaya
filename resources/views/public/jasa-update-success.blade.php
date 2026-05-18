@extends('layouts.public')

@section('title', 'Update Berhasil - Artha Jaya')

@section('header')
<div class="fi-header">
    <h1 class="fi-header-title">Update Hasil Pengerjaan</h1>
    <p class="fi-header-subtitle">No. Jasa: <code class="fi-header-code">{{ $jasa->no_jasa }}</code></p>
</div>
@endsection

@section('content')
<div class="fi-success-container">
    <div class="fi-success-icon-wrapper">
        <div class="fi-success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
    </div>
    
    <h1 class="fi-success-title">Update Berhasil!</h1>
    
    <div class="fi-success-card">
        <p class="fi-success-text">
            Status pengerjaan <span class="fi-success-ref">{{ $jasa->no_jasa }}</span> telah berhasil diperbarui
        </p>
        <div class="fi-status-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Selesai Dikerjakan
        </div>
    </div>
    
    <p class="fi-success-footer">
        Terima kasih telah mengupdate status pengerjaan.
    </p>
    
    @if($jasa->completion_notes)
    <div class="fi-section">
        <div class="fi-section-header">
            <h3 class="fi-section-header-heading">
                <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                Catatan Pengerjaan
            </h3>
        </div>
        <div class="fi-section-content">
            <p class="fi-notes-text">{{ $jasa->completion_notes }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
