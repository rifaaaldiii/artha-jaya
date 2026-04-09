# Fix Deployment Guide - JavaScript Error & Image 403

## Issues Fixed

### 1. JavaScript TypeError: `(intermediate value)(...) is not a function`
**Root Cause:** Duplicate property declarations in `ProgressJasa.php` causing Livewire state conflicts.

**Fixed Files:**
- `app/Filament/Pages/ProgressJasa.php` - Removed duplicate `$imageData` and `$terjadwalData` declarations

### 2. Image 403 Forbidden Errors
**Root Cause:** Missing or broken storage symlink on hosting server.

**Fixed Files:**
- `quick-deploy.sh` - Added `php artisan storage:link` command
- `storage/app/public/.htaccess` - Updated to allow image file access

---

## Deployment Steps

### Option 1: Full Deployment (Recommended)

Push the changes to your repository, then on your hosting server:

```bash
cd /path/to/your/artha-jaya
./deploy.sh
```

### Option 2: Quick Deployment

If you've already pushed the code changes:

```bash
cd /path/to/your/artha-jaya
./quick-deploy.sh
```

### Option 3: Manual Deployment

If the scripts don't work, run these commands manually on your hosting:

```bash
# Navigate to your Laravel project
cd /path/to/your/artha-jaya

# 1. Pull latest changes
git pull origin main

# 2. Create storage directories
mkdir -p storage/app/public/progress/produksi
mkdir -p storage/app/public/progress/jasa

# 3. Fix permissions
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public

# 4. Create/recreate storage symlink
php artisan storage:link

# 5. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 6. Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Verification Steps

After deployment, verify the fixes:

### 1. Check Storage Link
```bash
ls -la public/storage
```
You should see: `storage -> ../../storage/app/public`

### 2. Check Image Accessibility
Visit in browser: `https://yourdomain.com/storage/progress/jasa/test.jpg`
- If symlink is correct, you'll get a 404 (file doesn't exist - this is OK)
- If symlink is broken, you'll get a 403 Forbidden

### 3. Check JavaScript Console
1. Open the Progress Jasa page in browser
2. Open Developer Tools (F12)
3. Go to Console tab
4. Refresh the page
5. **Expected:** No `(intermediate value)(...) is not a function` error

### 4. Test Image Upload
1. Go to Progress Jasa page
2. Select a Jasa record
3. Upload an image
4. Update status
5. **Expected:** Image uploads successfully and displays without 403 error

---

## What Was Changed

### `app/Filament/Pages/ProgressJasa.php`
- **Removed** duplicate `public array $imageData = [];` declaration (line 237)
- **Removed** duplicate `public array $terjadwalData = [];` declaration (line 284)
- **Result:** Clean property declarations that Livewire can properly bind to

### `quick-deploy.sh`
- **Added** `php artisan storage:link` command to ensure symlink exists
- **Result:** Quick deployments now properly create storage links

### `storage/app/public/.htaccess`
- **Updated** to explicitly allow access to image files (jpg, jpeg, png, gif, webp, svg, ico)
- **Added** proper MIME type declarations
- **Result:** Apache server will serve images correctly without 403 errors

---

## Troubleshooting

### Still Getting 403 Errors?

1. **Check symlink exists:**
   ```bash
   ls -la public_html/storage
   # Should show: storage -> ../../storage/app/public
   ```

2. **If symlink is missing, create it manually:**
   ```bash
   cd public_html
   rm -f storage
   ln -s ../storage/app/public storage
   ```

3. **Check directory permissions:**
   ```bash
   chmod -R 755 storage/app/public
   chmod 755 storage
   chmod 755 storage/app
   chmod 755 storage/app/public
   ```

4. **Verify .htaccess is copied:**
   ```bash
   ls -la storage/app/public/.htaccess
   ```

### Still Getting JavaScript Errors?

1. **Clear browser cache:**
   - Press `Ctrl + Shift + Delete`
   - Clear cached images and files
   - Refresh page

2. **Check Livewire version:**
   ```bash
   composer show livewire/livewire
   ```

3. **Clear all Laravel caches:**
   ```bash
   php artisan optimize:clear
   php artisan optimize
   ```

---

## Notes

- The JavaScript error was caused by Livewire trying to bind to duplicate property declarations
- The 403 errors were caused by missing storage symlink on the hosting server
- Both issues are now resolved in the codebase
- Future deployments will automatically handle the storage link creation

---

## Support

If issues persist after following these steps:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check server error logs (cPanel/Apache logs)
3. Verify PHP version is compatible with Laravel 11
4. Ensure all Composer dependencies are installed
