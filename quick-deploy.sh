#!/bin/bash

# =====================================
# QUICK DEPLOY - PULL ONLY
# =====================================
# Usage: ./quick-deploy.sh
# Use this when you only push code changes (no dependencies)

echo "🚀 Quick deployment..."
echo "================================"

# 1. Pull latest changes
echo "📦 Pulling changes..."
git pull origin main

if [ $? -ne 0 ]; then
    echo "❌ Git pull failed!"
    exit 1
fi

echo "✅ Pull successful"
echo ""

# 2. Ensure storage link exists & directories created
echo "📁 Checking storage directories..."
mkdir -p storage/app/public/progress/produksi
mkdir -p storage/app/public/progress/jasa
chmod -R 775 storage/app/public

echo "✅ Storage directories ready"
echo ""

# 3. Clear caches only
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Caches cleared"
echo ""

# 4. Cache for production
echo "⚡ Rebuilding cache..."
php artisan config:cache
php artisan view:cache

echo "✅ Cache rebuilt"
echo ""

echo "================================"
echo "✅ Quick deploy successful!"
echo "================================"
