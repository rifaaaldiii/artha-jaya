# Messenger — Deployment (Niagahoster & shared hosting)

Panduan ini melengkapi PRD di `MESSENGER.md` untuk menjalankan chat internal di production.

## Struktur folder di hosting

Contoh layout Niagahoster:

```
/home/<user>/artha-jaya/          ← root project Laravel (composer, app, .env)
/home/<user>/public_html/         ← document root (isi: isi folder public/)
```

Pastikan `public_html/index.php` mengarah ke `../artha-jaya/bootstrap/app.php` (sesuai setup upload Anda).

## Yang wajib di server

| Komponen | Fungsi |
|----------|--------|
| PHP 8.2+ | Aplikasi & artisan |
| MySQL | Data chat |
| `php artisan migrate` | Tabel messenger |
| Queue worker | Notifikasi database (pesan baru) |
| Reverb **atau** polling | Realtime WebSocket vs fallback |

## 1. Environment production

Di `.env` pada folder `artha-jaya`:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

BROADCAST_CONNECTION=reverb

REVERB_APP_ID=artha-jaya
REVERB_APP_KEY=<generate-random-string>
REVERB_APP_SECRET=<generate-random-secret>
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

QUEUE_CONNECTION=database
```

Generate key Reverb (jalankan sekali di SSH):

```bash
cd ~/artha-jaya
php artisan reverb:install
# atau set manual REVERB_APP_KEY / REVERB_APP_SECRET
```

Setelah deploy front-end:

```bash
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 2. Queue worker (`php artisan queue:work`)

Broadcast event chat memakai `ShouldBroadcastNow` (langsung). **Notifikasi Filament** memakai queue (`ShouldQueue`).

### Opsi A — Cron (cocok shared hosting Niagahoster)

Di cPanel → **Cron Jobs**, tambahkan setiap menit:

```bash
cd /home/<user>/artha-jaya && php artisan queue:work --stop-when-empty --max-time=55 >> /dev/null 2>&1
```

Artinya: setiap menit worker jalan ±55 detik lalu berhenti (tidak melanggar batas proses lama di shared hosting).

### Opsi B — Supervisor (VPS / Cloud)

Jika hosting mendukung proses daemon:

```ini
[program:artha-jaya-queue]
command=php /home/<user>/artha-jaya/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=<user>
```

## 3. Laravel Reverb (`php artisan reverb:start`)

Reverb adalah **server WebSocket** yang harus berjalan terus. Kebanyakan paket **shared hosting Niagahoster tidak mengizinkan** proses WebSocket/custom port yang listen 24/7.

### Jika shared hosting (tanpa VPS) — disarankan: **Pusher**

[Pusher](https://dashboard.pusher.com) adalah layanan WebSocket cloud (gratis untuk development). **Tidak perlu VPS** — Laravel mengirim event lewat HTTP API Pusher, browser subscribe lewat `pusher-js`.

1. Buat app di [dashboard.pusher.com](https://dashboard.pusher.com)
2. Salin **app_id**, **key**, **secret**, **cluster** (mis. `ap1`)
3. Set di `.env` hosting:

```env
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-key
PUSHER_APP_SECRET=your-secret
PUSHER_APP_CLUSTER=ap1

MESSENGER_NOTIFICATIONS_ENABLED=true
MESSENGER_NOTIFICATION_SOUND=true
```

4. `php artisan config:clear`
5. Upload `public/build/` setelah `npm run build` (termasuk `messenger-notifications-*.js`)

**Fitur yang aktif:**
- Chat realtime di halaman Messenger
- Notifikasi + **suara** di semua halaman admin saat ada pesan masuk
- Badge navigasi Messenger ter-update

**Fallback polling** (jika Pusher belum dikonfigurasi):

```env
BROADCAST_CONNECTION=log
```

Halaman Messenger memakai `wire:poll` — pesan masuk tanpa refresh, tanpa notifikasi realtime global.

### Jika punya VPS / cloud kecil (disarankan untuk realtime)

1. Jalankan Reverb di VPS (bisa satu VPS kecil khusus websocket):

```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

2. Reverse proxy Nginx ke Reverb + SSL (wss).
3. Di `.env` aplikasi utama:

```env
REVERB_HOST=ws.domain-anda.com
REVERB_PORT=443
REVERB_SCHEME=https
```

4. Supervisor untuk Reverb:

```ini
[program:artha-jaya-reverb]
command=php /path/to/artha-jaya/artisan reverb:start
autostart=true
autorestart=true
```

### Niagahoster khusus

- Tanyakan support apakah **SSH** dan **cron** tersedia (biasanya ya pada paket tertentu).
- Tanyakan apakah **port custom** / WebSocket diizinkan (sering **tidak** pada shared).
- Jika tidak ada WebSocket: tetap pakai `BROADCAST_CONNECTION=log` + polling.

## 4. Checklist setelah upload

1. `composer install --no-dev --optimize-autoloader`
2. `php artisan migrate --force`
3. `npm run build` (atau upload folder `public/build` dari CI lokal)
4. Cron queue (lihat atas)
5. Reverb + supervisor **hanya jika** infrastruktur mendukung
6. Buka `/admin/messenger` — uji kirim pesan antar 2 user

## 5. Upload via PuTTY / FTP (penting)

Messenger memakai **Vite build** (`public/build/`). Hanya meng-upload file PHP (`app/`, `resources/views/`) **tidak cukup**.

### Yang harus ada di document root (biasanya `public_html/`)

```
public_html/
├── index.php
├── build/
│   ├── manifest.json
│   └── assets/
│       ├── messenger-BQ9GFyiY.js   ← hash berubah setiap npm run build
│       ├── app-xxxxx.js
│       └── app-xxxxx.css
└── ...
```

Jika `manifest.json` menyebut `messenger-BQ9GFyiY.js` tetapi file itu tidak ada di server → **404** → `messengerPage is not defined`.

### Cara deploy asset (pilih salah satu)

**A — Build di komputer lokal, upload folder build**

```bash
# Di laptop (folder project)
npm ci
npm run build
```

Lalu upload seluruh folder `public/build/` ke server:

- Target: `public_html/build/` (jika document root = `public_html`)
- **atau** `artha-jaya/public/build/` jika `public_html` hanya symlink ke `public/`

**B — Build lewat SSH di server** (jika Node.js tersedia)

```bash
cd ~/artha-jaya
npm ci
npm run build
# Salin ke public_html jika perlu:
cp -r public/build ~/public_html/build
```

**C — Git: commit folder `public/build`**

Pastikan baris `# /public/build` di `.gitignore` tetap dikomentari, lalu commit `public/build` agar ikut ter-pull di server.

### Setelah upload

```bash
php artisan view:clear
php artisan config:clear
```

Hard refresh browser (Ctrl+F5).

## 6. Troubleshooting

| Gejala | Penyebab umum | Solusi |
|--------|----------------|--------|
| `404` pada `/build/assets/messenger-*.js` | Folder `public/build` tidak di-upload atau hash lama | `npm run build` lalu upload ulang **seluruh** `public/build/` |
| `messengerPage is not defined` | JS messenger gagal load (404 di atas) | Perbaiki upload build; cek Network tab browser |
| `showChat is not defined` | Efek berantai dari `messengerPage` gagal init | Sama seperti di atas |
| Pesan tidak realtime | Reverb mati / shared hosting | `BROADCAST_CONNECTION=log` atau jalankan Reverb di VPS |
| Notifikasi tidak muncul | Queue tidak jalan | Aktifkan cron `queue:work` |
| 403 pada WebSocket | Auth channel | Pastikan user login; cek `routes/channels.php` |
| CSRF broadcasting | Session | Pastikan `APP_URL` benar & HTTPS konsisten |

## 7. Ringkasan rekomendasi

| Lingkungan | Broadcast | Queue |
|------------|-----------|-------|
| Lokal dev | `reverb` + `php artisan reverb:start` | `queue:listen` |
| Niagahoster shared | `log` (polling) | Cron tiap menit |
| VPS production | `reverb` + supervisor | Supervisor |
