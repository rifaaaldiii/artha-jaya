# 🚀 DEPLOYMENT GUIDE - ARTHA JAYA

## 📋 SETUP AWAL DI HOSTING (Sekali Saja)

### 1. Clone Repository di Hosting
```bash
# Login ke hosting via SSH/PuTTY
cd ~

# Clone dari GitHub
git clone https://github.com/YOUR_USERNAME/artha-jaya.git

# Masuk ke folder
cd artha-jaya
```

### 2. Install Dependencies (Pertama Kali)
```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Copy .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Set permissions
chmod -R 755 storage bootstrap/cache
```

### 3. Setup .env di Hosting
Edit file `.env` di hosting dengan credentials database hosting:
```bash
nano .env
```

Update:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://springgreen-lyrebird-361927.hostingersite.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u381971818_systemart
DB_USERNAME=u381971818_ryuuu
DB_PASSWORD=Aev872767@

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 4. Build Assets (Di Local, Upload ke Hosting)
```bash
# Di komputer local
cd "d:\PT. Artha Jaya Mas\Project\artha-jaya"
npm install
npm run build

# Upload folder public/build/ ke hosting:
# public_html/build/
```

### 5. Setup Script Deployment
```bash
# Di hosting
cd ~/artha-jaya

# Make scripts executable
chmod +x deploy.sh
chmod +x quick-deploy.sh
```

---

## 🔄 DEPLOYMENT SETIAP ADA UPDATE

### Opsi 1: Full Deployment (Recommended)
```bash
cd ~/artha-jaya
./deploy.sh
```

**Script ini akan:**
- ✅ Pull dari GitHub
- ✅ Install composer dependencies
- ✅ Set permissions
- ✅ Clear & cache
- ✅ Run migrations

### Opsi 2: Quick Deployment (Code Changes Only)
```bash
cd ~/artha-jaya
./quick-deploy.sh
```

**Script ini akan:**
- ✅ Pull dari GitHub
- ✅ Clear & rebuild cache

### Opsi 3: Manual Pull
```bash
cd ~/artha-jaya
git pull origin main
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan config:cache
php artisan view:cache
```

---

## 📁 STRUKTUR FOLDER DI HOSTING

```
/home/u381971818/
├── artha-jaya/              ← Laravel application
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env                 ← Environment config
│   ├── deploy.sh            ← Full deployment script
│   └── quick-deploy.sh      ← Quick deployment script
│
└── public_html/             ← Web root
    ├── build/               ← Vite build assets
    │   ├── manifest.json
    │   └── assets/
    ├── index.php            ← Entry point
    └── .htaccess
```

---

## 🛠️ TROUBLESHOOTING

### Error 500 After Pull
```bash
cd ~/artha-jaya
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### Permission Issues
```bash
chmod -R 755 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache
```

### Database Migration Needed
```bash
php artisan migrate --force
```

### Check Logs
```bash
tail -f storage/logs/laravel.log
```

---

## 💡 TIPS

1. **Selalu test di local** sebelum push ke GitHub
2. **Gunakan `deploy.sh`** untuk update besar (ada dependencies baru)
3. **Gunakan `quick-deploy.sh`** untuk update code biasa
4. **Commit `.env.example`** tapi JANGAN commit `.env`
5. **Build assets di local** sebelum push jika ada perubahan CSS/JS

---

## 🔐 KEAMANAN

- ✅ `.env` tidak ter-commit ke GitHub
- ✅ `/vendor/` tidak ter-commit
- ✅ `/node_modules/` tidak ter-commit
- ✅ `APP_DEBUG=false` di production
- ✅ Database credentials aman di `.env` hosting

---

## 📞 Need Help?

If you encounter issues:
1. Check logs: `tail -100 storage/logs/laravel.log`
2. Clear all caches manually
3. Verify `.env` configuration
4. Check database connection
