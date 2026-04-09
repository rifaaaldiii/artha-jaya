#!/bin/bash

# =====================================
# DEPLOYMENT SCRIPT - ARTHA JAYA
# =====================================
# Usage: ./deploy.sh

echo "🚀 Starting deployment..."
echo "================================"

# 1. Pull latest changes from GitHub
echo "📦 Pulling latest changes from GitHub..."
git pull origin main

if [ $? -ne 0 ]; then
    echo "❌ Git pull failed!"
    exit 1
fi

echo "✅ Git pull successful"
echo ""

# 2. Install/update PHP dependencies
echo "📚 Installing Composer dependencies..."
composer install --optimize-autoloader --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Composer install failed!"
    exit 1
fi

echo "✅ Composer install successful"
echo ""

# 3. Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod 755 public

echo "✅ Permissions set"
echo ""

# 4. Create storage directories
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/progress/produksi
mkdir -p storage/app/public/progress/jasa
chmod -R 775 storage/app/public

echo "✅ Storage directories created"
echo ""

# 4. Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

echo "✅ Storage link created"
echo ""

# 5. Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "✅ Caches cleared"
echo ""

# 6. Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Optimization complete"
echo ""

# 7. Run migrations (if any)
echo "🗄️ Running migrations..."
php artisan migrate --force

echo "✅ Migrations complete"
echo ""

echo "================================"
echo "✅ Deployment successful!"
echo "🎉 Your application is up to date"
echo "================================"
