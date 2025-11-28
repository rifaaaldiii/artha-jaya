# Contoh Implementasi Smart Auto-Logout

## Opsi 1: Server-Side Middleware (Recommended)

File: `app/Http/Middleware/SmartSessionHandler.php` (sudah dibuat)

**Cara menggunakan:**

1. Register middleware di `app/Providers/Filament/AdminPanelProvider.php`:

```php
use App\Http\Middleware\SmartSessionHandler;

->middleware([
    // ... middleware lainnya
    SmartSessionHandler::class,
])
```

**Keuntungan:**
- ✅ Deteksi akurat (server tahu status session)
- ✅ Handle otomatis tanpa client-side logic
- ✅ Beda handling untuk session expired vs CSRF expired

## Opsi 2: Client-Side dengan Smart Detection

Update `polling-script.blade.php` untuk handle auto-logout:

```javascript
async tick() {
    try {
        const response = await fetch(this.endpoint + '?channels=' + encodeURIComponent(this.channels.join(',')), {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
            },
        });

        if (response.status === 403) {
            const data = await response.json().catch(() => ({}));
            
            // Smart detection dari response
            if (data.logout === true || data.error === 'Session expired') {
                // Session benar-benar expired - Auto logout
                this.handleAutoLogout(data.redirect || '/admin/login');
                return;
            } else if (data.retry === true) {
                // CSRF expired tapi session valid - Retry dengan token baru
                if (data.csrf_token) {
                    // Update CSRF token di form/meta
                    this.updateCsrfToken(data.csrf_token);
                }
                // Retry request
                setTimeout(() => this.tick(), 1000);
                return;
            }
        }

        if (! response.ok) {
            return;
        }

        // ... rest of code
    } catch (error) {
        console.error('AJTriggeredPoller error:', error);
    }
}

handleAutoLogout(redirectUrl) {
    // Show notification sebelum logout
    if (window.Livewire?.dispatch) {
        window.Livewire.dispatch('session-expired-warning');
    }
    
    // Logout setelah 2 detik (beri waktu untuk user melihat warning)
    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 2000);
}

updateCsrfToken(token) {
    // Update CSRF token di meta tag
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) {
        meta.setAttribute('content', token);
    }
    
    // Update di semua form
    document.querySelectorAll('input[name="_token"]').forEach(input => {
        input.value = token;
    });
}
```

## Opsi 3: Hybrid Approach (Best Practice)

Kombinasi server-side middleware + client-side handling:

**Server (SmartSessionHandler.php):**
- Deteksi session status
- Return JSON dengan flag `logout` atau `retry`
- Regenerate token jika perlu

**Client (polling-script.blade.php):**
- Handle response dari server
- Auto-logout jika `logout: true`
- Retry jika `retry: true`
- Show notification sebelum logout

## Opsi 4: Simple Auto-Logout (Tidak Disarankan)

Jika tetap ingin auto-logout langsung tanpa deteksi:

```php
// app/Http/Middleware/SimpleAutoLogout.php
public function handle(Request $request, Closure $next): Response
{
    $response = $next($request);
    
    if ($response->getStatusCode() === 403) {
        if ($request->is('livewire/*') || $request->is('polling/*')) {
            Auth::logout();
            $request->session()->invalidate();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Session expired',
                    'redirect' => route('filament.admin.auth.login'),
                ], 403);
            }
            
            return redirect()->route('filament.admin.auth.login');
        }
    }
    
    return $response;
}
```

**⚠️ WARNING:** Pendekatan ini akan logout user bahkan jika hanya CSRF token yang expired, bukan session. **TIDAK DISARANKAN** untuk sistem real-time.

## Rekomendasi Final

**Gunakan Opsi 3 (Hybrid Approach)** dengan:
1. ✅ Server-side detection (SmartSessionHandler)
2. ✅ Client-side handling dengan notification
3. ✅ Auto-logout hanya jika session benar-benar expired
4. ✅ Regenerate token jika hanya CSRF yang expired

Ini memberikan balance terbaik antara:
- Security (logout jika session expired)
- User Experience (tidak logout jika hanya CSRF expired)
- Real-time capability (tidak mengganggu polling)

