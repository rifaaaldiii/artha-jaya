@extends('layouts.public')

@section('title', 'Jasa & Layanan - ' . ($jasa->no_jasa ?? 'Artha Jaya Mas'))

{{-- @section('subtitle', 'Update Status Pengerjaan') --}}

@section('content')
<div class="card">
    @if(isset($error))
    <div class="card-body">
        <div class="alert alert-error">
            <div class="alert-icon">⚠️</div>
            <div class="alert-content">
                <strong>Terjadi Kesalahan</strong>
                <p>{{ $error }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="card-header">
        <h2>Update Status Pengerjaan</h2>
        <p>Lengkapi form berikut untuk mengupdate status pengerjaan jasa instalasi</p>
    </div>
    
    <div class="card-body">
        <!-- Jasa Info -->
        <div style="background: var(--aj-bg); border: 1px solid var(--aj-border); border-radius: 8px; padding: 20px; margin-bottom: 28px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width: 20px; height: 20px; color: var(--aj-primary);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 style="font-size: 15px; font-weight: 600; color: var(--aj-text); margin: 0;">Informasi Jasa & Layanan</h3>
            </div>
            <div style="display: grid; gap: 12px; font-size: 14px;">
                <div style="display: flex; justify-content: space-between; padding: 1px 0; border-bottom: 1px solid var(--aj-divider);">
                    <span style="color: var(--aj-text-secondary);">No. Jasa</span>
                    <strong style="color: var(--aj-text);">{{ $jasa->no_jasa }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1px 0; border-bottom: 1px solid var(--aj-divider);">
                    <span style="color: var(--aj-text-secondary);">No. Ref</span>
                    <strong style="color: var(--aj-text);">{{ $jasa->no_ref ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1px 0; border-bottom: 1px solid var(--aj-divider);">
                    <span style="color: var(--aj-text-secondary);">Nama Customer</span>
                    <strong style="color: var(--aj-text);">{{ $jasa->pelanggan->nama ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1px 0; border-bottom: 1px solid var(--aj-divider);">
                    <span style="color: var(--aj-text-secondary);">Kontak</span>
                    <strong style="color: var(--aj-text);">{{ $jasa->pelanggan->kontak ?? '-' }}</strong>
                </div>
                <div style="padding: 8px 0;">
                    <span style="color: var(--aj-text-secondary); display: block; margin-bottom: 4px;">Alamat Jasa Instalasi</span>
                    <strong style="color: var(--aj-text);">{{ $jasa->alamat ?? $jasa->pelanggan->alamat ?? '-' }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px; background: var(--aj-bg); border-radius: 6px; margin-top: 8px;">
                    <span style="color: #bf2626; font-weight: 500;">Catatan</span>
                    <strong style="color: #bf2626;">{{ $jasa->catatan ?? '-' }}</strong>
                </div>
                @if($jasa->jadwal_petugas)
                <div style="display: flex; justify-content: space-between; padding: 12px; background: var(--aj-primary-light); border-radius: 6px; margin-top: 8px;">
                    <span style="color: var(--aj-primary-dark); font-weight: 500;">📅 Jadwal Pengerjaan</span>
                    <strong style="color: var(--aj-primary-dark);">{{ $jasa->jadwal_petugas->format('d F Y, H:i') }} WIB</strong>
                </div>
                @endif
            </div>
        </div>
        
        <form action="{{ route('jasa.public.update.submit', $token) }}" 
              method="POST" 
              enctype="multipart/form-data"
              id="updateForm">
            @csrf
            
            <!-- Image Upload -->
            <div class="form-group">
                <label>
                    Foto Bukti Pengerjaan
                    <span class="required">*</span>
                    <span class="form-hint" style="display: inline; margin-left: 8px;">Minimal 1 foto, maksimal 5 foto</span>
                </label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" 
                           name="images[]" 
                           multiple 
                           accept="image/*"
                           id="imageInput"
                           required>
                    <div class="upload-placeholder">
                        <div class="upload-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                        </div>
                        <p>Klik atau drag foto ke sini</p>
                        <small>Format: JPG, PNG, WEBP (Max 5MB per file)</small>
                    </div>
                </div>
                <div class="image-preview" id="imagePreview"></div>
                <span class="file-count" id="fileCount">0/5 foto</span>
                @error('images')
                    <span style="color: var(--aj-error); font-size: 13px; margin-top: 8px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Notes -->
            <div class="form-group">
                <label>
                    Catatan Pengerjaan
                    <span class="form-hint" style="display: inline; margin-left: 8px;">Opsional</span>
                </label>
                <textarea name="notes" 
                          rows="4" 
                          maxlength="1000"
                          placeholder="Tambahkan catatan tentang pengerjaan (misal: kendala, catatan khusus, dll)">{{ old('notes') }}</textarea>
                @error('notes')
                    <span style="color: var(--aj-error); font-size: 13px; margin-top: 8px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- Confirmation -->
            <div class="form-group">
                <label class="checkbox-label">
                    <div style="align-items: center; display: flex; gap: 15px;">
                        <input type="checkbox" 
                               name="confirm" 
                               value="1" 
                               required
                               id="confirmCheckbox">
                        <span>
                            Saya menyatakan bahwa pengerjaan jasa ini sudah <strong>selesai.</strong> sesuai standar kualitas Artha Jaya.
                        </span>
                        
                    </div>
                </label>
                @error('confirm')
                    <span style="color: var(--aj-error); font-size: 13px; margin-top: 8px; display: block;">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" 
                    class="btn-submit" 
                    id="submitBtn"
                    disabled>
                Kirim Hasil Pengerjaan
            </button>
        </form>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('imageInput');
    const uploadArea = document.getElementById('uploadArea');
    const imagePreview = document.getElementById('imagePreview');
    const fileCount = document.getElementById('fileCount');
    const confirmCheckbox = document.getElementById('confirmCheckbox');
    const submitBtn = document.getElementById('submitBtn');
    
    let selectedFiles = [];
    
    // Click to upload
    uploadArea.addEventListener('click', function() {
        imageInput.click();
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#667eea';
        uploadArea.style.background = '#f0f4ff';
    });
    
    uploadArea.addEventListener('dragleave', function() {
        uploadArea.style.borderColor = '#d1d5db';
        uploadArea.style.background = '#f9fafb';
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.style.borderColor = '#d1d5db';
        uploadArea.style.background = '#f9fafb';
        
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
            if (file.size > 2 * 1024 * 1024) {
                alert(`File ${file.name} terlalu besar (max 2MB)`);
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
        
        fileCount.textContent = `${selectedFiles.length}/5 foto`;
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
