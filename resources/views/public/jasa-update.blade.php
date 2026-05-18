@extends('layouts.public')

@section('title', 'Update Hasil Pengerjaan - ' . ($jasa->no_jasa ?? 'Artha Jaya'))

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
@if(isset($error))
<div class="card">
    <div class="card-body">
        <div class="alert alert-error">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <strong>Terjadi Kesalahan</strong>
                <p>{{ $error }}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body">
        <form action="{{ route('jasa.public.update.submit', $token) }}" 
              method="POST" 
              enctype="multipart/form-data"
              id="updateForm">
            @csrf
            
            <!-- Informasi Jasa & Layanan -->
            <div class="info-section">
                <div class="info-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Informasi Jasa & Layanan
                </div>
                            
                <div class="info-layout">
                    <!-- Left Column -->
                    <div class="info-column">          
                        <div class="info-item">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="4" y1="9" x2="20" y2="9"></line>
                                <line x1="4" y1="15" x2="20" y2="15"></line>
                                <line x1="10" y1="3" x2="8" y2="21"></line>
                                <line x1="16" y1="3" x2="14" y2="21"></line>
                            </svg>
                            <div class="info-content">
                                <div class="info-label">No. Ref</div>
                                <div class="info-value">{{ $jasa->no_ref ?? '-' }}</div>
                            </div>
                        </div>
                                    
                        <div class="info-item">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <div class="info-content">
                                <div class="info-label">Nama Customer</div>
                                <div class="info-value">{{ $jasa->pelanggan->nama ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <div class="info-content">
                                <div class="info-label">Jadwal</div>
                                <div class="info-value">{{ $jasa->jadwal_petugas->format('d M Y') }}</div>
                            </div>
                        </div>
                                    
                        <div class="info-item">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <div class="info-content">
                                <div class="info-label">Kontak</div>
                                <div class="info-value">{{ $jasa->pelanggan->kontak ?? '-' }}</div>
                            </div>
                        </div>
                                    
                        <div class="info-item">
                            <svg class="info-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <div class="info-content">
                                <div class="info-label">Alamat Jasa Instalasi</div>
                                <div class="info-value">{{ $jasa->alamat ?? $jasa->pelanggan->alamat ?? '-' }}</div>
                            </div>
                        </div>

                        @if($jasa->catatan)
                        <div class="catatan-box">
                            <svg class="catatan-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <div class="catatan-content">
                                <div class="catatan-label">Catatan</div>
                                <div class="catatan-text">{{ $jasa->catatan }}</div>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                                
                    <!-- Right Column -->
                    <div class="info-column">
                        <!-- Jasa Items Table -->
                        <div class="jasa-items-section">
                            <div class="jasa-items-title">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
                                </svg>
                                Daftar Item
                            </div>
                            <div class="jasa-items-table-wrapper">
                                <table class="jasa-items-table">
                                    <thead>
                                        <tr>
                                            <th class="th-item">Nama Item</th>
                                            <th class="th-qty">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jasa->items as $item)
                                        <tr>
                                            <td class="td-item">{{ $item->jenis_layanan }}</td>
                                            <td class="td-qty">{{ $item->jumlah }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="td-empty">Tidak ada item layanan</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($jasa->note)
                        <div class="catatan-note">
                            <svg class="catatan-icon-note" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <div class="catatan-content-note">
                                <div class="catatan-label-note">Catatan Internal</div>
                                <div class="catatan-text-note">{{ $jasa->note }}</div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            
            <!-- Upload Foto -->
            <div class="upload-section">
                <div class="section-header">
                    <div class="section-title">Upload Foto Bukti Pengerjaan</div>
                    <div class="section-hint">Minimal 1 foto, maksimal 5 foto. Format: JPG, PNG, WEBP (Maks. 5MB per file)</div>
                </div>
                
                <div class="upload-area" id="uploadArea">
                    <input type="file" 
                           name="images[]" 
                           multiple 
                           accept="image/*"
                           id="imageInput"
                           required>
                    <div class="upload-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <div class="upload-text">Drag & drop foto ke sini</div>
                    <div class="upload-divider">atau</div>
                    <div class="upload-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Pilih File
                    </div>
                </div>
                
                <div class="preview-section" id="previewSection" style="display: none;">
                    <div class="preview-header">
                        <div class="preview-title">Preview Foto (<span id="previewCount">0</span>/5)</div>
                        <div class="preview-count"><span id="fileCount">0</span> foto dipilih</div>
                    </div>
                    <div class="preview-grid" id="imagePreview"></div>
                    <div class="preview-hint">ℹ️ Pastikan foto jelas dan semua bagian pekerjaan terlihat dengan baik.</div>
                </div>
                
                @error('images')
                    <div style="color: var(--aj-error); font-size: 14px; margin-top: 12px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Catatan Tambahan -->
            <div class="form-group">
                <label class="form-label">
                    Catatan Tambahan <span class="form-label-hint">(Opsional)</span>
                </label>
                <textarea name="notes" 
                          rows="4" 
                          maxlength="250"
                          class="form-textarea"
                          placeholder="Tuliskan catatan tambahan jika ada hal yang perlu disampaikan..."
                          id="notesTextarea">{{ old('notes') }}</textarea>
                <div class="char-counter"><span id="charCount">0</span>/250</div>
                @error('notes')
                    <div style="color: var(--aj-error); font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Konfirmasi -->
            <div class="confirmation-box">
                <label class="confirmation-label">
                    <svg class="confirmation-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="confirmation-text">
                        Saya menyatakan bahwa pekerjaan telah selesai sesuai dengan ketentuan dan foto yang diupload adalah hasil pekerjaan yang sebenarnya.
                    </div>
                    <input type="checkbox" 
                           name="confirm" 
                           value="1" 
                           required
                           class="confirmation-checkbox"
                           id="confirmCheckbox">
                </label>
                @error('confirm')
                    <div style="color: var(--aj-error); font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Buttons -->
            <div class="button-group">
                <button type="button" 
                        class="btn btn-secondary" 
                        id="resetBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                    </svg>
                    Reset Form
                </button>
                <button type="submit" 
                        class="btn btn-primary" 
                        id="submitBtn"
                        disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                    Kirim Hasil Pengerjaan
                </button>
            </div>
        </form>
    </div>
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
    const fileCount = document.getElementById('fileCount');
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
            fileCount.textContent = '0';
            updateSubmitButton();
            return;
        }
        
        previewSection.style.display = 'block';
        previewCount.textContent = selectedFiles.length;
        fileCount.textContent = selectedFiles.length;
        
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
