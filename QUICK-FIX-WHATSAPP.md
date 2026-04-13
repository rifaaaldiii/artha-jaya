# 🔧 Quick Fix: WhatsApp Not Sending

## ⚡ Fast Checklist (5 Minutes)

### 1️⃣ Check Device Status (1 minute)
```
✅ Go to: https://md.fonnte.com
✅ Login
✅ Check: Device shows "Connected" or "Terkoneksi"
❌ If "Disconnected": Re-scan QR code now!
```

### 2️⃣ Verify Configuration (1 minute)
Open `.env` file and check:
```env
FONTE_API_KEY=8rcu5U6x2WGj5MZmVDJo
FONTE_API_URL=https://api.fonnte.com/send
```

**Common mistakes:**
- ❌ Wrong URL: `https://api.fonte.id/api` (OLD)
- ✅ Correct URL: `https://api.fonnte.com/send` (NEW)
- ❌ With Bearer: `Bearer 8rcu5U6x2WGj5MZmVDJo`
- ✅ Without Bearer: `8rcu5U6x2WGj5MZmVDJo`

### 3️⃣ Clear Cache (30 seconds)
```bash
cd "d:\PT. Artha Jaya Mas\Project\artha-jaya"
php artisan config:clear
php artisan cache:clear
```

### 4️⃣ Test Send Message (2 minutes)
```bash
php artisan whatsapp:test
```
Enter your phone number when prompted.

**If success:** ✅ You'll receive a test WhatsApp
**If failed:** ❌ Check the error message shown

### 5️⃣ Check Logs (1 minute)
```bash
# Windows PowerShell
type storage\logs\laravel.log | Select-String "WhatsApp" -Tail 20

# Or open file
notepad storage\logs\laravel.log
```

Look for:
- ✅ `WhatsApp sent successfully via Fonte`
- ❌ Error messages with details

---

## 🔍 Common Issues & Quick Fixes

### Issue 1: "Device Disconnected"
**Fix:**
1. Open https://md.fonnte.com
2. Click "Reconnect" or re-scan QR
3. Wait for "Connected" status
4. Test again

### Issue 2: "Invalid Token"
**Fix:**
1. Copy token from md.fonnte.com dashboard
2. Update `.env` file
3. Run: `php artisan config:clear`
4. Test again

### Issue 3: "No recipients found"
**Fix:**
```php
php artisan tinker

// Check if users have phone numbers
$users = \App\Models\User::whereNotNull('kontak')->get();
echo "Users with phone: " . $users->count() . "\n";
foreach ($users as $u) {
    echo "{$u->name} | Branch: {$u->branch} | Phone: {$u->kontak}\n";
}
```

If count is 0 → Add phone numbers to users via admin panel.

### Issue 4: Messages not sent for specific Produksi/Jasa
**Check:**
```php
php artisan tinker

// Check latest produksi
$p = \App\Models\Produksi::latest()->first();
echo "Branch: {$p->branch}\n";

// Find matching users
$users = \App\Models\User::where('branch', $p->branch)
    ->whereNotNull('kontak')
    ->get();
echo "Matching users: {$users->count()}\n";
```

If count is 0 → No users with that branch have phone numbers.

---

## ✅ Verification Steps

After fixing, verify it works:

1. **Create test Produksi:**
   - Login as administrator
   - Create new Produksi with branch (e.g., AJP)
   - Save

2. **Check logs:**
```bash
type storage\logs\laravel.log | Select-String "Produksi created notification" -Tail 5
```

3. **Check WhatsApp:**
   - Users with branch AJP should receive WhatsApp
   - Message should contain Produksi details

4. **Update status:**
   - Change Produksi status
   - Check if status update notification sent

---

## 📞 Still Not Working?

### Collect This Info:
1. Device status from md.fonnte.com (screenshot)
2. Your `.env` Fonte config (hide token)
3. Last 50 lines of `storage/logs/laravel.log`
4. Output of: `php artisan whatsapp:test`

### Check Fonte Dashboard:
- Login to https://md.fonnte.com
- Check "Message History" or "Riwayat Pesan"
- See if messages are being sent from Fonte side
- Check if there are error statuses

### Test API Directly (Advanced):
```bash
# Using curl (if available)
curl -X POST https://api.fonnte.com/send \
  -H "Authorization: 8rcu5U6x2WGj5MZmVDJo" \
  -d "target=08123456789" \
  -d "message=Test direct API" \
  -d "countryCode=62"
```

Expected response:
```json
{
  "status": true,
  "messages": [...]
}
```

---

## 🎯 Success Indicators

You know it's working when:

✅ Test command succeeds: `php artisan whatsapp:test`
✅ Logs show: "WhatsApp sent successfully via Fonte"
✅ You receive test WhatsApp on your phone
✅ New Produksi creates notification
✅ Status updates trigger notification
✅ Only users with matching branch receive it

---

## 📝 Important Notes

1. **Device must stay connected 24/7**
   - If phone disconnects, notifications stop
   - Reconnect immediately when disconnected

2. **Message Quota**
   - Free plan has daily limits
   - Check quota at md.fonnte.com
   - Upgrade if needed

3. **Phone Number Format**
   - System accepts any format
   - Auto-converts to proper format
   - Examples: `08123456789`, `628123456789`, `+62 812-3456-789`

4. **Branch Matching is Strict**
   - Produksi branch AJP → Only AJP users notified
   - If no AJP users with phone → No notifications sent

5. **Logs Are Your Friend**
   - Always check `storage/logs/laravel.log`
   - Contains detailed error messages
   - Shows exactly what went wrong

---

**Last Updated:** 2026-04-12
**Version:** 1.1 (Fixed API endpoint and authentication)
