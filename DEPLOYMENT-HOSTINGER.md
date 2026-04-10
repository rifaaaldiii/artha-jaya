# Deployment Guide for Hostinger

## Important Steps After Deploying to Hostinger

### 1. Create Storage Symlink (REQUIRED)

After deploying your code to Hostinger, you **MUST** create the storage symlink for images to work:

```bash
# SSH into your Hostinger account
ssh your-username@your-host

# Navigate to your project directory
cd /path/to/your/project

# Create the storage symlink
php artisan storage:link

# If you get an error that the link already exists, remove it first:
rm public/storage
php artisan storage:link
```

### 2. Set Correct Permissions

Ensure proper file permissions:

```bash
# Set directory permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Set file permissions
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

# Ensure storage directories are writable
chmod -R 775 storage/app/public
```

### 3. Clear and Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Verify Storage Link

Test if the storage link is working:

```bash
# Check if symlink exists
ls -la public/storage

# Should show something like:
# storage -> /path/to/storage/app/public

# Test file access
# Upload a test image through the app and try to access it at:
# https://your-domain.com/storage/progress/produksi/test.jpg
```

### 5. Environment Configuration

Make sure your `.env` file has the correct settings:

```env
APP_URL=https://your-domain.com
APP_ENV=production
APP_DEBUG=false

FILESYSTEM_DISK=public

# If using custom Hostinger storage path
# HOSTINGER_STORAGE_PATH=/home/username/storage
# HOSTINGER_STORAGE_URL=https://your-domain.com/storage
```

### 6. Database Migration

If there are new migrations:

```bash
php artisan migrate --force
```

### 7. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install and build frontend assets (if needed)
npm install
npm run build
```

## Troubleshooting

### Images Return 403 Forbidden

**Problem**: Images show 403 Forbidden error

**Solutions**:
1. Ensure storage symlink exists: `php artisan storage:link`
2. Check file permissions: `chmod -R 755 storage/app/public`
3. Verify `.htaccess` exists in `storage/app/public/.htaccess`
4. Check if files actually exist in `storage/app/public/progress/produksi/`

### JavaScript Errors (Livewire.onError)

**Problem**: `Livewire.onError is not a function`

**Solution**: This has been fixed in the latest code. The error handler now checks for Livewire v3 API compatibility.

### Files Upload But Don't Show

**Problem**: Images upload successfully but don't display

**Solutions**:
1. Check if symlink is broken: `ls -la public/storage`
2. Recreate symlink: `rm public/storage && php artisan storage:link`
3. Verify file exists: `ls -la storage/app/public/progress/produksi/`
4. Check Laravel logs: `storage/logs/laravel.log`

## Quick Deployment Checklist

- [ ] Upload all files to Hostinger
- [ ] Run `composer install --no-dev`
- [ ] Copy and configure `.env` file
- [ ] Run `php artisan storage:link`
- [ ] Set correct permissions (755 for directories, 644 for files)
- [ ] Run `php artisan migrate --force` (if needed)
- [ ] Clear and cache configuration
- [ ] Test image upload and display
- [ ] Check for JavaScript errors in browser console

## Hostinger-Specific Notes

1. **SSH Access**: Enable SSH access in Hostinger control panel
2. **PHP Version**: Ensure PHP 8.1+ is selected
3. **Document Root**: Point to `public/` directory
4. **File Manager**: You can also use Hostinger's file manager to:
   - Delete the `public/storage` folder if it exists
   - Create a symbolic link manually (if supported)
   - Upload the `.htaccess` file to `storage/app/public/`

## Support

If issues persist:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true` in `.env`
3. Check browser console for JavaScript errors
4. Verify file permissions and ownership
