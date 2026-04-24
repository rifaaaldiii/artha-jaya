@extends('layouts.public')

@section('title', 'Update Berhasil - Artha Jaya')

@section('subtitle', 'Status Berhasil Diperbarui')

@section('content')
<div class="card success-card">
    <div class="success-icon">✓</div>
    
    <h1>Update Berhasil!</h1>
    
    <p>
        Status pengerjaan <strong>{{ $jasa->no_jasa }}</strong> telah berhasil diperbarui menjadi<br>
        <strong style="color: var(--aj-primary); font-size: 18px; display: inline-block; margin-top: 8px;">✓ Selesai Dikerjakan</strong>
    </p>
    
    <p style="margin-top: 16px;">Terima kasih telah mengupdate status pengerjaan.</p>
    
    @if($jasa->completion_notes)
    <div class="completion-notes">
        <strong>📝 Catatan Pengerjaan:</strong>
        <p>{{ $jasa->completion_notes }}</p>
    </div>
    @endif
    
    <a href="/" class="btn-home">← Kembali ke Home</a>
</div>
@endsection
