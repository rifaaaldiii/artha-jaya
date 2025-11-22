# Alur Notifikasi Reminder Progress dan Progress Jasa

## 📋 Konsep Dasar

Sistem notifikasi reminder bekerja dengan cara:
1. User dengan role tertentu mengupdate status dan menambahkan catatan reminder
2. Sistem mengirim notifikasi ke **role yang akan melakukan update SELANJUTNYA** (bukan role untuk status saat ini)
3. Notifikasi muncul di fitur notifikasi database Filament untuk role target

---

## 🔄 Alur Progress Produksi

### Status Flow:
```
produksi baru → siap produksi → dalam pengerjaan → selesai dikerjakan → lolos qc → produksi siap diambil → selesai
```

### Contoh Alur Lengkap:

#### **Contoh 1: Admin Gudang → Kepala Teknisi Gudang**
1. **Admin Gudang** login dan membuka halaman Progress
2. Pilih produksi dengan status **"produksi baru"**
3. Update status menjadi **"siap produksi"**
4. Isi catatan reminder: *"Bahan sudah siap, mohon segera diproses"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `kepala_teknisi_gudang`
7. **Kepala Teknisi Gudang** akan melihat notifikasi di panel notifikasi Filament dengan pesan:
   - **Title:** "Reminder Catatan dari [Nama Admin Gudang]"
   - **Body:** "Produksi [No. Produksi] memiliki catatan reminder untuk Anda.\n\nCatatan: Bahan sudah siap, mohon segera diproses"
   - **URL:** Link langsung ke halaman Progress dengan produksi yang dimaksud

#### **Contoh 2: Kepala Teknisi Gudang → Petukang**
1. **Kepala Teknisi Gudang** login dan membuka halaman Progress
2. Pilih produksi dengan status **"siap produksi"**
3. Update status menjadi **"dalam pengerjaan"**
4. Isi catatan reminder: *"Perhatikan kualitas finishing, ada permintaan khusus dari pelanggan"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `petukang`
7. **Petukang** akan melihat notifikasi di panel notifikasi Filament

#### **Contoh 3: Petukang → Kepala Teknisi Gudang**
1. **Petukang** login dan membuka halaman Progress
2. Pilih produksi dengan status **"dalam pengerjaan"**
3. Update status menjadi **"selesai dikerjakan"**
4. Isi catatan reminder: *"Sudah selesai, mohon dicek kualitasnya"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `kepala_teknisi_gudang`
7. **Kepala Teknisi Gudang** akan melihat notifikasi untuk melakukan QC

#### **Contoh 4: Kepala Teknisi Gudang → Admin Gudang**
1. **Kepala Teknisi Gudang** login dan membuka halaman Progress
2. Pilih produksi dengan status **"selesai dikerjakan"**
3. Update status menjadi **"lolos qc"**
4. Isi catatan reminder: *"Produk sudah lolos QC, siap untuk diambil"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `admin_gudang`
7. **Admin Gudang** akan melihat notifikasi untuk menyiapkan pengambilan

#### **Contoh 5: Admin Gudang → Admin Gudang (Final)**
1. **Admin Gudang** login dan membuka halaman Progress
2. Pilih produksi dengan status **"lolos qc"**
3. Update status menjadi **"produksi siap diambil"**
4. Isi catatan reminder: *"Produk sudah diambil oleh pelanggan"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `admin_gudang` (untuk update final ke "selesai")

---

## 🔄 Alur Progress Jasa

### Status Flow:
```
jasa baru → terjadwal → selesai dikerjakan → selesai
```

### Contoh Alur Lengkap:

#### **Contoh 1: Kepala Teknisi Lapangan → Petugas**
1. **Kepala Teknisi Lapangan** login dan membuka halaman Progress Jasa
2. Pilih jasa dengan status **"jasa baru"**
3. Isi jadwal petugas dan pilih petugas yang akan menangani
4. Update status menjadi **"terjadwal"**
5. Isi catatan reminder: *"Jadwal sudah ditentukan, mohon petugas datang tepat waktu"*
6. Klik "Simpan Status & Jadwalkan Petugas"
7. **Notifikasi dikirim ke:** Semua user dengan role `petugas`
8. **Petugas** akan melihat notifikasi di panel notifikasi Filament dengan pesan:
   - **Title:** "Reminder Catatan dari [Nama Kepala Teknisi Lapangan]"
   - **Body:** "Jasa [No. Jasa] memiliki catatan reminder untuk Anda.\n\nCatatan: Jadwal sudah ditentukan, mohon petugas datang tepat waktu"
   - **URL:** Link langsung ke halaman Progress Jasa dengan jasa yang dimaksud

#### **Contoh 2: Petugas → Admin Toko**
1. **Petugas** login dan membuka halaman Progress Jasa
2. Pilih jasa dengan status **"terjadwal"**
3. Update status menjadi **"selesai dikerjakan"**
4. Isi catatan reminder: *"Pekerjaan sudah selesai, mohon konfirmasi ke pelanggan"*
5. Klik "Simpan Status"
6. **Notifikasi dikirim ke:** Semua user dengan role `admin_toko`
7. **Admin Toko** akan melihat notifikasi untuk melakukan finalisasi

---

## 📊 Mapping Role untuk Notifikasi

### Progress Produksi:
| Status Saat Ini | Status Berikutnya | Role yang Menerima Notifikasi |
|----------------|-------------------|-------------------------------|
| siap produksi | dalam pengerjaan | `kepala_teknisi_gudang` |
| dalam pengerjaan | selesai dikerjakan | `petukang` |
| selesai dikerjakan | lolos qc | `kepala_teknisi_gudang` |
| lolos qc | produksi siap diambil | `admin_gudang` |
| produksi siap diambil | selesai | `admin_gudang` |

### Progress Jasa:
| Status Saat Ini | Status Berikutnya | Role yang Menerima Notifikasi |
|----------------|-------------------|-------------------------------|
| terjadwal | selesai dikerjakan | `petugas` |
| selesai dikerjakan | selesai | `admin_toko` |

---

## ⚠️ Catatan Penting

1. **Catatan TIDAK disimpan ke database** - Catatan reminder hanya digunakan untuk notifikasi, tidak disimpan di kolom `catatan` pada tabel `produksis` atau `jasas`

2. **Notifikasi hanya muncul jika ada catatan** - Jika tidak mengisi catatan, tidak ada notifikasi yang dikirim

3. **Notifikasi dikirim ke SEMUA user dengan role target** - Jika ada 3 user dengan role `kepala_teknisi_gudang`, semua akan menerima notifikasi

4. **Form catatan tidak ter-refresh** - Form catatan menggunakan `wire:ignore` sehingga tidak ter-reset saat polling otomatis setiap 3 detik

5. **Autofocus pada textarea** - Textarea catatan memiliki autofocus untuk memudahkan input

---

## 🔍 Troubleshooting

### Notifikasi tidak muncul?
1. Pastikan ada user dengan role target di database
2. Pastikan format role di database menggunakan underscore (contoh: `kepala_teknisi_gudang`, bukan `kepala teknisi gudang`)
3. Pastikan catatan diisi sebelum klik "Simpan Status"
4. Cek tabel `notifications` di database untuk melihat apakah notifikasi sudah dibuat

### Cara cek notifikasi di database:
```sql
SELECT * FROM notifications 
WHERE type = 'App\Notifications\ProgressReminderNotification' 
ORDER BY created_at DESC;
```

---

## 📝 Testing Checklist

- [ ] Admin Gudang update status + catatan → Notifikasi muncul di Kepala Teknisi Gudang
- [ ] Kepala Teknisi Gudang update status + catatan → Notifikasi muncul di Petukang
- [ ] Petukang update status + catatan → Notifikasi muncul di Kepala Teknisi Gudang
- [ ] Kepala Teknisi Lapangan update status + catatan → Notifikasi muncul di Petugas
- [ ] Petugas update status + catatan → Notifikasi muncul di Admin Toko
- [ ] Form catatan tidak ter-reset saat polling
- [ ] Autofocus bekerja pada textarea catatan

