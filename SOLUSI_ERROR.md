# Solusi untuk Masalah 403 Forbidden pada POST dan GET Requests

## Masalah yang Ditemukan

Sistem mengalami error **403 Forbidden** pada:
1. **POST `/livewire/update`** - Terjadi ketika CSRF token expired setelah banyak request (~100 requests)
2. **GET `/polling/triggers`** - Terjadi ketika session expired atau rate limiting saat banyak polling requests

Masalah ini terjadi karena sistem multi-user yang membutuhkan real-time data update, sehingga banyak request polling yang dilakukan secara bersamaan.

## Solusi yang Diimplementasikan

### 1. **Improved Polling Script dengan Retry Logic** 
   **File: `resources/views/filament/hooks/polling-script.blade.php`**

   - ✅ **Exponential Backoff**: Implementasi retry dengan delay yang meningkat secara eksponensial
   - ✅ **Error Handling**: Handle 403 errors dengan lebih baik tanpa menghentikan polling
   - ✅ **Request Throttling**: Minimum interval 500ms antara requests untuk mencegah overload
   - ✅ **Session Recovery**: Auto-recovery mechanism untuk handle session expiration
   - ✅ **Consecutive Error Tracking**: Stop polling setelah 5 consecutive errors, restart setelah 1 menit
   - ✅ **Visibility Change Handler**: Restart polling ketika user kembali ke tab

### 2. **Enhanced Error Handling Middleware**
   **File: `app/Http/Middleware/Handle405FromLivewire.php`**

   - ✅ **JSON Response untuk Polling**: Return JSON error untuk polling endpoint instead of redirect
   - ✅ **CSRF Token Regeneration**: Auto-regenerate CSRF token untuk Livewire requests
   - ✅ **Session Refresh**: Refresh session sebelum return error untuk polling
   - ✅ **Smart Error Handling**: Different handling untuk authenticated vs unauthenticated users

### 3. **Improved PollingController**
   **File: `app/Http/Controllers/PollingController.php`**

   - ✅ **Session Touch**: Touch session pada setiap request untuk prevent expiration
   - ✅ **Periodic CSRF Regeneration**: Regenerate CSRF token setiap 10 requests
   - ✅ **Better Error Logging**: Log errors dengan context untuk debugging
   - ✅ **Cache Headers**: Add proper cache headers untuk prevent caching issues
   - ✅ **Authentication Check**: Verify authentication sebelum process request

### 4. **Rate Limiting Middleware**
   **File: `app/Http/Middleware/ThrottlePollingRequests.php`** (NEW)

   - ✅ **Request Throttling**: Limit 60 requests per minute per user/IP
   - ✅ **Rate Limit Headers**: Return rate limit info dalam response headers
   - ✅ **Graceful Degradation**: Return 429 dengan retry-after header instead of blocking

### 5. **Custom CSRF Token Middleware**
   **File: `app/Http/Middleware/VerifyCsrfToken.php`** (NEW)

   - ✅ **Livewire-Friendly**: More lenient CSRF validation untuk Livewire requests
   - ✅ **Error Handling**: Catch CSRF errors dan allow Livewire to handle them
   - ✅ **Logging**: Log CSRF validation failures untuk debugging

### 6. **Route Updates**
   **File: `routes/web.php`**

   - ✅ **Rate Limiting Applied**: Apply ThrottlePollingRequests middleware ke polling route

## Fitur-Fitur Utama

### Exponential Backoff
Polling script akan retry dengan delay yang meningkat:
- Error 1: 1 detik
- Error 2: 2 detik  
- Error 3: 4 detik
- Error 4: 8 detik
- Error 5: 16 detik
- Max: 30 detik

### Session Management
- Session di-touch pada setiap polling request untuk prevent expiration
- CSRF token di-regenerate setiap 10 requests
- Auto-recovery mechanism untuk handle expired sessions

### Error Recovery
- Polling tidak langsung stop pada error pertama
- Auto-restart setelah 1 menit jika terlalu banyak errors
- Graceful handling untuk 403 errors tanpa logout user

### Rate Limiting
- 60 requests per minute per user/IP
- Response dengan rate limit headers untuk client-side handling
- 429 status code dengan retry-after header

## Konfigurasi

### Polling Interval
Default: 3000ms (3 detik)
Dapat diubah via `.env`:
```env
POLLING_INTERVAL_MS=3000
```

### Session Lifetime
Default: 120 menit
Dapat diubah via `.env`:
```env
SESSION_LIFETIME=120
```

### Rate Limiting
Dapat disesuaikan di `app/Http/Middleware/ThrottlePollingRequests.php`:
- `$maxRequests`: Maximum requests per time window
- `$decayMinutes`: Time window dalam menit

## Testing

Untuk test solusi ini:

1. **Test dengan Multiple Users**: Buka aplikasi di beberapa browser/tab
2. **Monitor Console**: Check browser console untuk error messages
3. **Check Network Tab**: Monitor polling requests di browser DevTools
4. **Load Test**: Gunakan tool seperti Apache Bench atau JMeter untuk simulate 100+ requests

## Monitoring

Error akan di-log di Laravel log file:
- CSRF validation failures: `storage/logs/laravel.log`
- Polling errors: Check PollingController error logs

## Catatan Penting

1. **Session Storage**: Pastikan session driver (database/redis) dapat handle high load
2. **Cache Driver**: Polling menggunakan cache untuk store trigger versions, pastikan cache driver optimal
3. **Server Resources**: Pastikan server memiliki resources yang cukup untuk handle concurrent requests
4. **Database**: Jika menggunakan database session, pastikan connection pool cukup

## Rekomendasi Tambahan

Untuk sistem multi-user dengan real-time requirements yang tinggi, pertimbangkan:

1. **WebSockets**: Implementasi WebSockets (Laravel Echo + Pusher/Soketi) untuk true real-time
2. **Server-Sent Events (SSE)**: Alternative untuk polling yang lebih efficient
3. **Redis Session**: Gunakan Redis untuk session storage yang lebih cepat
4. **Queue System**: Gunakan queues untuk handle heavy operations
5. **CDN/Caching**: Implementasi caching layer untuk reduce server load

## Troubleshooting

Jika masih mengalami 403 errors:

1. **Check Session Configuration**: Pastikan session lifetime cukup panjang
2. **Check CSRF Token**: Verify CSRF token di-regenerate dengan benar
3. **Check Rate Limiting**: Pastikan rate limiting tidak terlalu ketat
4. **Check Server Logs**: Monitor server logs untuk mod_security atau firewall blocks
5. **Check Browser Console**: Monitor browser console untuk error details

## Update History

- **2024**: Initial implementation dengan retry logic dan error handling
- Enhanced session management dan CSRF token regeneration
- Added rate limiting untuk prevent server overload
