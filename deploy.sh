#!/bin/bash

# =====================================
# ARTHA JAYA - AUTO DEPLOYMENT SCRIPT
# Run after cloning repository
# =====================================
# Usage: ./deploy.sh

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

echo ""
echo -e "${BLUE}╔════════════════════════════════════════════╗${NC}"
echo -e "${BLUE}║   ARTHA JAYA - AUTO DEPLOYMENT SCRIPT     ║${NC}"
echo -e "${BLUE}╚════════════════════════════════════════════╝${NC}"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Please run this script from artha-jaya directory${NC}"
    echo -e "${YELLOW}   cd ~/artha-jaya${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Directory verified: $(pwd)${NC}"
echo ""

# ========================================
# STEP 1: Install Dependencies
# ========================================
echo -e "${YELLOW}[1/8] Installing dependencies...${NC}"
composer install --no-dev --optimize-autoloader --no-interaction || { echo -e "${RED}❌ Error: Composer install failed${NC}"; exit 1; }
echo -e "${GREEN}✅ Dependencies installed${NC}"
echo ""

# ========================================
# STEP 2: Setup Environment
# ========================================
echo -e "${YELLOW}[2/8] Setting up environment...${NC}"

if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate --force
    echo -e "${GREEN}✅ .env created & key generated${NC}"
    echo ""
    echo -e "${YELLOW}⚠️  PENTING: Edit file .env dengan database credentials Anda!${NC}"
    echo -e "${YELLOW}   Jalankan: nano .env${NC}"
    echo ""
    read -p "Sudah edit .env? Tekan Enter untuk lanjut..."
else
    echo -e "${GREEN}✅ .env already exists${NC}"
fi
echo ""

# ========================================
# STEP 3: Set Permissions
# ========================================
echo -e "${YELLOW}[3/8] Setting permissions...${NC}"
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public
mkdir -p storage/app/public/progress/produksi
mkdir -p storage/app/public/progress/jasa
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache
echo -e "${GREEN}✅ Permissions set${NC}"
echo ""

# ========================================
# STEP 4: Create Storage Link
# ========================================
echo -e "${YELLOW}[4/8] Creating storage link...${NC}"

# Remove old symlink/file if exists
rm -f public/storage

# Try artisan command first
if php artisan storage:link 2>/dev/null; then
    echo -e "${GREEN}✅ Storage link created (artisan)${NC}"
else
    # Fallback: manual symlink
    echo -e "${YELLOW}⚠️  Artisan storage:link failed, creating manual symlink...${NC}"
    if ln -s ../storage/app/public public/storage 2>/dev/null; then
        echo -e "${GREEN}✅ Storage link created (manual symlink)${NC}"
    else
        echo -e "${RED}❌ Failed to create storage link${NC}"
        echo -e "${YELLOW}   Try manually: cd public && ln -s ../storage/app/public storage${NC}"
    fi
fi

echo ""

# ========================================
# STEP 5: Clear Caches
# ========================================
echo -e "${YELLOW}[5/8] Clearing caches...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan event:clear
php artisan queue:clear
echo -e "${GREEN}✅ Caches cleared${NC}"
echo ""

# ========================================
# STEP 6: Database Migration
# ========================================
echo -e "${YELLOW}[6/8] Running migrations...${NC}"
php artisan migrate --force
echo -e "${GREEN}✅ Migrations completed${NC}"
echo ""

# ========================================
# STEP 7: Optimize for Production
# ========================================
echo -e "${YELLOW}[7/8] Optimizing for production...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✅ Optimized for production${NC}"
echo ""

# ========================================
# STEP 8: Final Verification
# ========================================
echo -e "${YELLOW}[8/8] Final verification...${NC}"

ERRORS=0

if [ ! -f ".env" ]; then
    echo -e "${RED}❌ .env file missing${NC}"
    ERRORS=$((ERRORS + 1))
fi

if [ ! -d "vendor" ]; then
    echo -e "${RED}❌ vendor directory missing${NC}"
    ERRORS=$((ERRORS + 1))
fi

if [ ! -L "public/storage" ]; then
    echo -e "${RED}❌ storage symlink missing${NC}"
    ERRORS=$((ERRORS + 1))
fi

if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✅ All checks passed${NC}"
else
    echo -e "${RED}❌ Found $ERRORS issue(s)${NC}"
fi

echo ""
echo ""
echo -e "${GREEN}╔════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║     ✅ DEPLOYMENT BERHASIL!                 ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${BLUE}📍 Website Anda sudah live!${NC}"
echo -e "${BLUE}🔍 Test di browser sekarang${NC}"
echo ""
echo -e "${YELLOW}📝 Command penting:${NC}"
echo -e "   • Buat admin: ${BLUE}php artisan make:filament-user${NC}"
echo -e "   • Lihat log:  ${BLUE}tail -f storage/logs/laravel.log${NC}"
echo -e "   • Clear cache:${BLUE} php artisan optimize:clear${NC}"
echo ""
echo -e "${YELLOW}🔄 Untuk update selanjutnya:${NC}"
echo -e "   ${BLUE}cd ~/artha-jaya && git pull && ./deploy.sh${NC}"
echo ""
echo -e "${GREEN}════════════════════════════════════════════${NC}"
echo ""

# Show recent logs
read -p "Lihat log terakhir? (y/n): " show_logs
if [[ $show_logs == "y" ]]; then
    echo ""
    echo -e "${YELLOW}📄 Last 30 log entries:${NC}"
    echo "────────────────────────────────────"
    tail -n 30 storage/logs/laravel.log 2>/dev/null || echo "No log file found"
    echo "────────────────────────────────────"
fi

echo ""
echo -e "${GREEN}✅ Selesai! Website Anda sudah siap digunakan! 🎉${NC}"
echo ""
