@extends('layouts.public')

@section('title', 'Update Hasil Pengerjaan - ' . ($jasa->no_jasa ?? 'Artha Jaya'))

@if($jasa->note)
<div class="broadcast-banner">
    <div class="broadcast-container">
        <svg class="broadcast-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
            <path d="M2 8c0-2.5 1.5-4.5 4-5.5"></path>
            <path d="M22 8c0-2.5-1.5-4.5-4-5.5"></path>
        </svg>
        <!-- <div class="broadcast-label">PENTING</div> -->
        <div class="broadcast-text-wrapper">
            <div class="broadcast-text-scroll">
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
                <span class="broadcast-text">
                    <svg class="broadcast-text-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    {{ $jasa->note }}
                </span>
            </div>
        </div>
    </div>
</div>
@endif

@section('header')
<div class="fi-header">
    <h1 class="fi-header-title">Update Hasil Pengerjaan</h1>
    <p class="fi-header-subtitle">No. Jasa: <code class="fi-header-code">{{ $jasa->no_jasa }}</code></p>
</div>
@endsection

@section('content')
@if(isset($error))
<div class="fi-section">
    <div class="fi-section-content">
        <div class="fi-alert fi-alert-danger">
            <div class="fi-alert-icon">⚠️</div>
            <div class="fi-alert-content">
                <strong>Terjadi Kesalahan</strong>
                <p>{{ $error }}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="fi-form">
    <form action="{{ route('jasa.public.update.submit', $token) }}" 
          method="POST" 
          enctype="multipart/form-data"
          id="updateForm">
        @csrf
        
        <!-- Grid Layout: Info + Items -->
        <div class="fi-grid-layout">
            <!-- Informasi Jasa Section -->
            <div class="fi-section">
                <div class="fi-section-header">
                    <h3 class="fi-section-header-heading">
                        <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Informasi Jasa
                    </h3>
                    <p class="fi-section-header-description">Detail layanan dan pelanggan</p>
                </div>
            
                <div class="fi-section-content">
                    <div class="fi-info-grid">
                        <div class="fi-info-grid-column">
                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">Nama Customer</div>
                                    <div class="fi-info-value">{{ $jasa->pelanggan->nama ?? '-' }}</div>
                                </div>
                            </div>
                            
                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">Jadwal Pengerjaan</div>
                                    <div class="fi-info-value">{{ $jasa->jadwal_petugas->format('d M Y') }}</div>
                                </div>
                            </div>
                            
                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">Kontak</div>
                                    <div class="fi-info-value">{{ $jasa->pelanggan->kontak ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="fi-info-grid-column">
                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">Alamat Instalasi</div>
                                    <div class="fi-info-value">{{ $jasa->alamat ?? $jasa->pelanggan->alamat ?? '-' }}</div>
                                </div>
                            </div>
                            
                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="4" y1="9" x2="20" y2="9"></line>
                                        <line x1="4" y1="15" x2="20" y2="15"></line>
                                        <line x1="10" y1="3" x2="8" y2="21"></line>
                                        <line x1="16" y1="3" x2="14" y2="21"></line>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">No. Referensi</div>
                                    <div class="fi-info-value fi-info-value-mono">{{ $jasa->no_ref ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="fi-info-item">
                                <div class="fi-info-icon-wrapper">
                                    <svg class="fi-info-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div class="fi-info-content">
                                    <div class="fi-info-label">Catatan</div>
                                    <div class="fi-info-value fi-info-value-mono">{{ $jasa->catatan }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Items Section -->
            <div class="fi-section">
                <div class="fi-section-header">
                    <h3 class="fi-section-header-heading">
                        <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
                        </svg>
                        Daftar Layanan
                    </h3>
                    <p class="fi-section-header-description">Item yang harus dikerjakan</p>
                </div>
            
                <div class="fi-section-content">
                    <div class="fi-table-wrapper">
                        <table class="fi-table">
                            <thead>
                                <tr>
                                    <th class="fi-table-header" style="width: 60px;">No.</th>
                                    <th class="fi-table-header">Layanan</th>
                                    <th class="fi-table-header" style="width: 120px;">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jasa->items as $item)
                                <tr class="fi-table-row">
                                    <td class="fi-table-cell fi-table-cell-center">{{ $loop->iteration }}</td>
                                    <td class="fi-table-cell">{{ $item->jenis_layanan }}</td>
                                    <td class="fi-table-cell">
                                        <span class="fi-table-badge">{{ $item->jumlah }} unit</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="fi-table-empty">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        <p class="fi-table-empty-text">Tidak ada item layanan</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upload Section -->
        <div class="fi-section">
            <div class="fi-section-header">
                <h3 class="fi-section-header-heading">
                    <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    Foto Bukti Pengerjaan
                </h3>
                <p class="fi-section-header-description">Upload minimal 1 foto, maksimal 5 foto (JPG, PNG, WEBP - Maks. 5MB)</p>
            </div>
            
            <div class="fi-section-content">
                <div class="fi-upload-area" id="uploadArea">
                    <input type="file" 
                           name="images[]" 
                           multiple 
                           accept="image/*"
                           id="imageInput"
                           required>
                    <div class="fi-upload-content">
                        <div class="fi-upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <p class="fi-upload-text">Drag & drop foto di sini</p>
                        <div class="fi-upload-divider">
                            <span>atau</span>
                        </div>
                        <button type="button" class="fi-upload-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Buka Kamera / Pilih File
                        </button>
                    </div>
                </div>
                
                <div class="fi-preview-section" id="previewSection" style="display: none;">
                    <div class="fi-preview-header">
                        <span class="fi-preview-label">Preview Foto</span>
                        <span class="fi-preview-count"><span id="previewCount">0</span>/5</span>
                    </div>
                    <div class="fi-preview-grid" id="imagePreview"></div>
                    <p class="fi-preview-hint">ℹ️ Pastikan foto Terlihat jelas dan menunjukkan hasil pekerjaan dengan baik</p>
                
                </div>
                
                @error('images')
                    <div class="fi-error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Notes Section -->
        <div class="fi-section">
            <div class="fi-section-header">
                <h3 class="fi-section-header-heading">
                    <svg class="fi-section-header-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Catatan Tambahan <span class="fi-optional-badge">Opsional</span>
                </h3>
                <p class="fi-section-header-description">Tambahkan catatan jika diperlukan</p>
            </div>
            
            <div class="fi-section-content">
                <div class="fi-textarea-wrapper">
                    <textarea name="notes" 
                              rows="4" 
                              maxlength="250"
                              class="fi-textarea"
                              placeholder="Tuliskan catatan tambahan jika ada hal yang perlu disampaikan..."
                              id="notesTextarea">{{ old('notes') }}</textarea>
                    <div class="fi-char-counter"><span id="charCount">0</span>/250</div>
                </div>
                
                @error('notes')
                    <div class="fi-error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Confirmation -->
        <div class="fi-section">
            <div class="fi-section-content">
                <label class="fi-checkbox-label">
                    <input type="checkbox" 
                           name="confirm" 
                           value="1" 
                           required
                           class="fi-input-checkbox"
                           id="confirmCheckbox">
                    <span class="fi-checkbox-text">
                        Saya menyatakan bahwa pekerjaan telah selesai sesuai dengan ketentuan dan foto yang diupload adalah hasil pekerjaan yang sebenarnya.
                    </span>
                </label>
                @error('confirm')
                    <div class="fi-error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="fi-actions">
            <button type="button" 
                    class="fi-action-btn fi-action-btn-secondary" 
                    id="resetBtn">
                Reset
            </button>
            <button type="submit" 
                    class="fi-action-btn fi-action-btn-primary" 
                    id="submitBtn"
                    disabled>
                Kirim Hasil Pengerjaan
            </button>
        </div>
    </form>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const uploadArea = document.getElementById('uploadArea');
        const imagePreview = document.getElementById('imagePreview');
        const previewSection = document.getElementById('previewSection');
        const previewCount = document.getElementById('previewCount');
        const confirmCheckbox = document.getElementById('confirmCheckbox');
        const submitBtn = document.getElementById('submitBtn');
        const resetBtn = document.getElementById('resetBtn');
        const notesTextarea = document.getElementById('notesTextarea');
        const charCount = document.getElementById('charCount');
        
        let selectedFiles = [];
        
        // Click to upload
        uploadArea.addEventListener('click', function() {
            imageInput.click();
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
            handleFiles(files);
        });
        
        // File input change
        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            handleFiles(files);
        });
        
        function handleFiles(files) {
            // Validate file count
            if (selectedFiles.length + files.length > 5) {
                alert('Maksimal 5 foto!');
                return;
            }
            
            // Validate and add files
            files.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar (max 5MB)`);
                    return;
                }
                
                if (!file.type.startsWith('image/')) {
                    alert(`File ${file.name} bukan gambar`);
                    return;
                }
                
                selectedFiles.push(file);
            });
            
            updatePreview();
        }
        
        function updatePreview() {
            imagePreview.innerHTML = '';
            
            if (selectedFiles.length === 0) {
                previewSection.style.display = 'none';
                previewCount.textContent = '0';
                updateSubmitButton();
                return;
            }
            
            previewSection.style.display = 'block';
            previewCount.textContent = selectedFiles.length;
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="preview-remove" onclick="removeFile(${index})">×</button>
                    `;
                    imagePreview.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
            
            // Add button if less than 5 files
            if (selectedFiles.length < 5) {
                const addBtn = document.createElement('div');
                addBtn.className = 'preview-add';
                addBtn.onclick = function() {
                    imageInput.click();
                };
                addBtn.innerHTML = `
                    <svg class="preview-add-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <div class="preview-add-text">Tambah foto<br>(max. 5 foto)</div>
                `;
                imagePreview.appendChild(addBtn);
            }
            
            updateSubmitButton();
        }
        
        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
        };
        
        // Update submit button state
        function updateSubmitButton() {
            const hasFiles = selectedFiles.length > 0;
            const isChecked = confirmCheckbox.checked;
            submitBtn.disabled = !(hasFiles && isChecked);
        }
        
        confirmCheckbox.addEventListener('change', updateSubmitButton);
        
        // Character counter
        notesTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Reset form
        resetBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin mereset form?')) {
                document.getElementById('updateForm').reset();
                selectedFiles = [];
                updatePreview();
                charCount.textContent = '0';
            }
        });
        
        // Create DataTransfer and update input files before submit
        document.getElementById('updateForm').addEventListener('submit', function(e) {
            if (selectedFiles.length === 0) {
                e.preventDefault();
                alert('Minimal 1 foto bukti pengerjaan wajib diupload!');
                return;
            }
            
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageInput.files = dataTransfer.files;
        });
    });
</script>
@endpush