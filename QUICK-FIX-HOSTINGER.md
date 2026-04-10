# Quick Fix for Hostinger - Image 403 & JavaScript Error

## Issues Fixed

### 1. ❌ JavaScript Error: `Livewire.onError is not a function`
**Status**: ✅ FIXED

**What changed**:
- Updated error handler to be compatible with Livewire v3
- Added fallback mechanism using `Livewire.hook('request.failed')`
- Files modified:
  - `resources/views/filament/pages/progress.blade.php`
  - `resources/views/filament/pages/progressJasa.blade.php`

### 2. ❌ Image 403 Forbidden Error
**Status**: ✅ CODE FIXED + ⚠️ MANUAL ACTION REQUIRED

**What changed**:
- Updated `getImageUrl()` method to use Laravel's Storage facade
- Added proper error handling and fallback
- Files modified:
  - `app/Filament/Pages/Progress.php`
  - `app/Filament/Pages/ProgressJasa.php`

**⚠️ ACTION REQUIRED ON HOSTINGER**:

You MUST run this command on your Hostinger server:

```bash
# SSH to Hostinger
cd /path/to/your/project

# Create storage symlink (THIS IS REQUIRED!)
php artisan storage:link
```

## Deploy to Hostinger - Step by Step

### Option 1: Using Git (Recommended)

```bash
# 1. SSH into Hostinger
ssh your-username@your-host

# 2. Navigate to project
cd ~/artha-jaya

# 3. Pull latest changes
git pull origin main

# 4. Run deployment script
./deploy.sh

# OR manually:
composer install --no-dev --optimize-autoloader
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Option 2: Using File Manager

1. **Upload these modified files**:
   - `app/Filament/Pages/Progress.php`
   - `app/Filament/Pages/ProgressJasa.php`
   - `resources/views/filament/pages/progress.blade.php`
   - `resources/views/filament/pages/progressJasa.blade.php`
   - `storage/app/public/.htaccess`

2. **SSH to Hostinger** (required for symlink):
   ```bash
   ssh your-username@your-host
   cd ~/artha-jaya
   php artisan storage:link
   ```

3. **Clear cache via SSH**:
   ```bash
   php artisan config:cache
   php artisan view:clear
   ```

## Verify Fix

After deployment, check:

1. **No JavaScript errors** in browser console (F12)
2. **Images load properly** on progress page
3. **Storage symlink exists**:
   ```bash
   ls -la public/storage
   # Should show: storage -> ../storage/app/public
   ```

## Still Getting 403? Try This:

```bash
# SSH to Hostinger
cd ~/artha-jaya

# 1. Remove old symlink
rm -f public/storage

# 2. Recreate it
php artisan storage:link

# 3. Set permissions
chmod -R 755 storage/app/public
chmod 644 storage/app/public/.htaccess

# 4. Verify
ls -la public/storage
ls -la storage/app/public/progress/produksi/
```

## Files Changed Summary

```
✅ app/Filament/Pages/Progress.php
✅ app/Filament/Pages/ProgressJasa.php
✅ resources/views/filament/pages/progress.blade.php
✅ resources/views/filament/pages/progressJasa.blade.php
✅ storage/app/public/.htaccess (already created)
```

## Need Help?

Check these resources:
- Full deployment guide: `DEPLOYMENT-HOSTINGER.md`
- Laravel logs: `storage/logs/laravel.log`
- Browser console: Press F12 → Console tab

---

**Important**: The storage symlink must be created on the server. It cannot be uploaded via FTP/File Manager!
