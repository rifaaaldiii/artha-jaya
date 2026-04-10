# Image Upload Fix - Direct Public Directory Storage

## Perubahan yang Dilakukan

### 1. Progress.php & ProgressJasa.php
- Menambahkan custom `saveUploadedFilesUsing()` callback pada FileUpload component
- File langsung disimpan ke directory `public/progress/produksi` atau `public/progress/jasa`
- Tidak lagi menggunakan storage symlink atau copy dari storage

### 2. Upload Flow (BARU)
```
User Upload → Livewire Temporary Upload → Custom Handler → public/progress/produksi|jasa → Database
```

### 3. Load Flow
```
Database → public_path() check → Direct URL: domain.com/progress/... → Browser
```

## Deployment di Hostinger

### Step 1: Upload File yang Sudah Diubah
Upload file-file berikut ke hosting:
- `app/Filament/Pages/Progress.php`
- `app/Filament/Pages/ProgressJasa.php`
- `resources/views/filament/pages/progress.blade.php`
- `resources/views/filament/pages/progressJasa.blade.php`

### Step 2: Buat Directory dan Set Permission
Login ke Hostinger via SSH atau File Manager, kemudian:

```bash
# Masuk ke directory project
cd /path/to/artha-jaya

# Buat directory untuk progress images
mkdir -p public/progress/produksi
mkdir -p public/progress/jasa

# Set permission yang benar
chmod -R 755 public/progress
chmod -R 755 public/progress/produksi
chmod -R 755 public/progress/jasa

# Set ownership ke web server user (biasanya sudah benar di Hostinger)
chown -R www-data:www-data public/progress
# ATAU jika tidak bisa, biarkan default
```

### Step 3: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan log:clear
```

### Step 4: Test Upload
1. Login ke aplikasi
2. Buka halaman Progress
3. Pilih produksi/jasa
4. Upload foto progress
5. Update status
6. Cek apakah foto muncul dengan benar

## Troubleshooting

### Error 404 Not Found pada Image
Jika masih muncul error 404:

1. **Cek apakah file benar-benar ada di public directory:**
   ```bash
   ls -la public/progress/produksi/
   ls -la public/progress/jasa/
   ```

2. **Cek permission directory:**
   ```bash
   ls -la public/ | grep progress
   # Harus: drwxr-xr-x (755)
   ```

3. **Cek log aplikasi:**
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Cari log dengan keyword: "IMAGE UPLOAD DEBUG" atau "Processing image"

4. **Cek URL yang di-generate:**
   - Buka browser console (F12)
   - Lihat URL image yang diminta
   - Harus format: `https://domain.com/progress/produksi/filename.jpg`
   - BUKAN: `https://domain.com/storage/progress/...`

### Error Permission Denied
Jika muncul error permission denied saat upload:

```bash
# Coba set permission lebih permisif (temporary)
chmod -R 775 public/progress

# Jika masih error, cek PHP user
# Buat file info.php di public folder
<?php echo exec('whoami'); ?>
# Kemudian set ownership sesuai user tersebut
```

### File Tidak Muncul Setelah Upload
1. Cek log untuk memastikan file berhasil dipindah
2. Cek apakah directory `public/progress/produksi` ada dan writable
3. Pastikan tidak ada error PHP saat upload

## Keuntungan Pendekatan Ini

✅ **Tidak perlu storage symlink** - Tidak bergantung pada `php artisan storage:link`
✅ **Direct access** - File langsung diakses dari public directory
✅ **Simpler deployment** - Tidak perlu setup symlink di hosting
✅ **Better compatibility** - Bekerja di semua hosting bahkan yang disable exec()
✅ **Faster access** - Tidak ada overhead symlink resolution

## Struktur File

```
public/
├── progress/
│   ├── produksi/
│   │   ├── abc123.jpg
│   │   └── def456.jpg
│   └── jasa/
│       ├── ghi789.jpg
│       └── jkl012.jpg
```

## Database Storage

Di database, path disimpan sebagai relative path dari public root:
```json
[
  {
    "path": "progress/produksi/abc123.jpg",
    "uploaded_at": "2026-04-10 10:30:00",
    "status_from": "produksi baru",
    "status_to": "siap produksi",
    "uploaded_by": 1
  }
]
```

## Catatan Penting

⚠️ **Backup Images**: Pastikan untuk backup directory `public/progress` secara teratur
⚠️ **Disk Space**: Monitor disk usage karena file disimpan di public directory
⚠️ **Migration**: Image lama yang ada di `storage/app/public` tidak akan terpengaruh, tetap bisa diakses dengan fallback mechanism
