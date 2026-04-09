# 🚀 ARTHA JAYA - DEPLOYMENT GUIDE

## ⚡ Quick Start (3 Easy Steps)

### Step 1: Clone Repository
```bash
cd ~
git clone https://github.com/YOUR_USERNAME/artha-jaya.git
cd artha-jaya
```

### Step 2: Make Script Executable
```bash
chmod +x deploy.sh
```

### Step 3: Run Deployment
```bash
./deploy.sh
```

**That's it!** The script will handle everything automatically.

---

## 📋 What the Script Does

1. ✅ Install dependencies
2. ✅ Setup .env file
3. ✅ Set permissions
4. ✅ Create storage link
5. ✅ Clear caches
6. ✅ Run migrations
7. ✅ Optimize for production
8. ✅ Verify deployment

---

## 🔧 For Future Updates

```bash
cd ~/artha-jaya
git pull
./deploy.sh
```

---

## 🆘 Troubleshooting

**If error occurs:**
```bash
cd ~/artha-jaya
php artisan optimize:clear
chmod -R 755 storage bootstrap/cache
tail -f storage/logs/laravel.log
```

---

**Time:** ~5-7 minutes  
**Difficulty:** Easy  
