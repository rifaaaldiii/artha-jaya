# 🚀 ARTHA JAYA - DEPLOYMENT GUIDE

## ⚡ Quick Start (2 Steps)

### Step 1: Login to PuTTY

### Step 2: Copy-Paste This Command

```bash
cd ~ && git clone https://github.com/YOUR_USERNAME/artha-jaya.git && cd artha-jaya && chmod +x deploy.sh && ./deploy.sh
```

**That's it!** The script will handle everything automatically.

---

## 📋 What the Script Does

1. ✅ Clone repository
2. ✅ Install dependencies
3. ✅ Setup .env file
4. ✅ Set permissions
5. ✅ Create storage link
6. ✅ Clear caches
7. ✅ Run migrations
8. ✅ Check admin user
9. ✅ Optimize for production
10. ✅ Verify deployment

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
