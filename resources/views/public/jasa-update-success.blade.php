@extends('layouts.public')

@section('title', 'Update Berhasil - Artha Jaya')

@section('header')
<div class="header-icon-wrapper">
    <div class="header-icon-circle">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </div>
    <div class="header-content-wrapper">
        <div class="header-title">Update Hasil Pengerjaan</div>
        <div class="header-subtitle">ID: <strong>{{ $jasa->no_jasa }}</strong></div>
    </div>
</div>
@endsection

@section('content')
<div class="card success-card">
    <div class="success-icon-large">✓</div>
    
    <h1 class="success-title">Update Berhasil!</h1>
    
    <p class="success-text">
        Status pengerjaan <strong>{{ $jasa->no_jasa }}</strong> telah berhasil diperbarui<br>
        <strong class="success-highlight">Selesai Dikerjakan</strong>
    </p>
    
    <p style="margin-top: 24px; color: var(--aj-text-secondary);">
        Terima kasih telah mengupdate status pengerjaan.
    </p>
    
    @if($jasa->completion_notes)
    <div class="completion-notes">
        <strong> Catatan Pengerjaan:</strong>
        <p>{{ $jasa->completion_notes }}</p>
    </div>
    @endif
</div>
@endsection
