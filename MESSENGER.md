# PRD - Internal Realtime Chat System

## Laravel 12 + Filament + Laravel Reverb

Version: 1.0
Status: Draft
Author: Product Team
Date: 2026-06-03

---

# 1. Executive Summary

Sistem membutuhkan fitur komunikasi internal antar pengguna yang sudah terdaftar di aplikasi.

Fitur chat akan digunakan untuk komunikasi operasional terkait proses penginputan data, koordinasi pekerjaan, dan diskusi internal.

Karena jumlah pengguna aktif relatif kecil (< 20 user aktif bersamaan), maka sistem akan menggunakan:

* Laravel 12
* Filament
* Laravel Reverb
* Livewire
* Database MySQL/PostgreSQL yang sudah digunakan aplikasi

Tanpa integrasi layanan eksternal seperti Pusher, Firebase, Ably, atau Socket.IO.

---

# 2. Objectives

## Tujuan Utama

Menyediakan komunikasi realtime internal di dalam aplikasi tanpa berpindah platform.

## Tujuan Bisnis

* Mengurangi komunikasi melalui WhatsApp pribadi.
* Semua komunikasi terkait pekerjaan tersimpan dalam sistem.
* Mempermudah koordinasi antar operator.
* Mempercepat respon antar divisi.

---

# 3. Scope

## Included

### Direct Message (DM)

Chat antar user.

Contoh:

* Administrator ↔ Superadmin
* Admin_toko ↔  Superadmin
* Administrator ↔ Admin_toko

### Realtime Messaging

Pesan tampil otomatis tanpa refresh halaman.

### Online Presence

Menampilkan status:

* Online
* Offline

### Read Status

Status:

* Sent
* Delivered
* Read

### Notification

Notifikasi ketika menerima pesan baru.

### Message History

Riwayat chat tersimpan permanen.

### Search User

Mencari user untuk memulai percakapan.

### Unread Counter

Jumlah pesan yang belum dibaca.

---

## Excluded (Phase 2)

Tidak termasuk:

* Group Chat
* Voice Call
* Video Call
* File Sharing
* Voice Note
* Emoji Reaction
* Message Edit
* Message Delete
* Reply Message
* Thread Message

---

# 4. User Roles

## Existing User

Menggunakan tabel users yang sudah ada.

Tidak perlu role khusus.

Semua user yang dapat login dapat menggunakan fitur chat.

---

# 5. User Stories

## US-001

Sebagai user,
saya ingin melihat daftar user,
agar dapat memilih siapa yang akan saya chat.

### Acceptance Criteria

* Daftar user tampil.
* Bisa mencari user.
* Bisa membuka chat room.

---

## US-002

Sebagai user,
saya ingin mengirim pesan.

### Acceptance Criteria

* Pesan tersimpan ke database.
* Pesan muncul realtime.
* Timestamp tersimpan.

---

## US-003

Sebagai user,
saya ingin menerima pesan realtime.

### Acceptance Criteria

* Tidak perlu refresh halaman.
* Pesan muncul kurang dari 1 detik.

---

## US-004

Sebagai user,
saya ingin mengetahui apakah lawan bicara online.

### Acceptance Criteria

* Status online tampil.
* Status berubah otomatis.

---

## US-005

Sebagai user,
saya ingin melihat jumlah pesan belum dibaca.

### Acceptance Criteria

* Counter tampil pada daftar chat.
* Counter hilang setelah dibaca.

---

# 6. Functional Requirements

## FR-001 User List

Sistem harus menampilkan daftar user.

Field:

* id
* name
* avatar
* online_status
* unread_count

---

## FR-002 Start Conversation

User dapat membuka percakapan dengan user lain.

Jika belum ada conversation:

* otomatis dibuat.

---

## FR-003 Send Message

User dapat mengirim:

* Text Message

Maksimal:

2000 karakter

---

## FR-004 Receive Message

Pesan baru harus muncul realtime menggunakan Reverb.

---

## FR-005 Read Receipt

Status:

### Sent

Pesan berhasil disimpan.

### Delivered

Pesan berhasil diterima browser penerima.

### Read

Chat dibuka oleh penerima.

---

## FR-006 Presence

Status:

### Online

User aktif.

### Offline

User tidak aktif.

---

## FR-007 Notification

Saat pesan masuk:

* Counter bertambah
* Notifikasi Filament muncul

---

## FR-008 Chat History

Sistem menyimpan seluruh histori.

Pagination:

50 pesan per load.

---

# 7. Non Functional Requirements

## Performance

Target:

< 20 user aktif bersamaan.

Response:

< 1 detik.

---

## Security

User hanya dapat:

* melihat chat miliknya
* mengirim pesan atas nama dirinya

Tidak dapat:

* membaca chat user lain

---

## Availability

Mengikuti availability aplikasi utama.

---

## Scalability

Target awal:

20 user

Target maksimal:

100 user

Tanpa perubahan arsitektur besar.

---

# 8. Database Design

## conversations

| Field      | Type      |
| ---------- | --------- |
| id         | bigint    |
| created_at | timestamp |
| updated_at | timestamp |

---

## conversation_participants

| Field           | Type   |
| --------------- | ------ |
| id              | bigint |
| conversation_id | bigint |
| user_id         | bigint |

---

## messages

| Field           | Type               |
| --------------- | ------------------ |
| id              | bigint             |
| conversation_id | bigint             |
| sender_id       | bigint             |
| message         | text               |
| delivered_at    | timestamp nullable |
| read_at         | timestamp nullable |
| created_at      | timestamp          |

---

# 9. Broadcasting Architecture

## Event

### MessageSent

Broadcast ketika pesan dibuat.

Channel:

Private

---

## MessageDelivered

Broadcast ketika penerima menerima pesan.

---

## MessageRead

Broadcast ketika pesan dibaca.

---

# 10. Reverb Architecture

## Installation

```bash
composer require laravel/reverb
php artisan reverb:install
```

## Run

```bash
php artisan reverb:start
```

Production menggunakan:

```bash
supervisor
```

atau

```bash
systemd
```

---

# 11. Filament Integration

Menu baru:

## Communication

### Chat

Navigation:

```
Communication
└── Chat
```

---

## Chat Layout

### Left Panel

Conversation List

* User Avatar
* User Name
* Last Message
* Unread Count
* Online Status

---

### Right Panel

Message Area

* Message Bubble
* Timestamp
* Read Status

---

### Bottom

Message Composer

* Textarea
* Send Button

---

# 12. UI Requirements

## Conversation List

Menampilkan:

* avatar
* nama
* online indicator
* unread badge

---

## Message Bubble

Sender:

kanan

Receiver:

kiri

---

## Read Indicator

Ikon:

✓ Sent

✓✓ Delivered

✓✓ Biru Read

---

# 13. Authorization Rules

User hanya boleh:

* membuka conversation yang dia ikuti

User tidak boleh:

* mengakses conversation lain

---

# 14. Audit & Logging

Catat:

* message_sent
* message_read
* conversation_created

Untuk troubleshooting.

---

# 15. Deployment Requirements

## Queue

Gunakan queue worker.

```bash
php artisan queue:work
```

---

## Reverb

```bash
php artisan reverb:start
```

Harus berjalan terus menerus.

---

# 16. Testing

## Unit Test

* Create conversation
* Send message
* Read message

---

## Feature Test

* Realtime delivery
* Presence
* Authorization

---

## UAT

### Scenario 1

User A kirim pesan.

Expected:

User B menerima realtime.

---

### Scenario 2

User B membuka chat.

Expected:

Status read muncul.

---

### Scenario 3

User offline.

Expected:

Pesan tersimpan.

Saat login:

Pesan muncul.

---

# 17. Future Enhancements

## Phase 2

* Group Chat
* File Upload
* Image Upload
* Reply Message
* Mention User
* Emoji Reaction

## Phase 3

* Voice Note
* Video Call
* Screen Sharing

---

# 18. Technical Recommendation

Untuk kebutuhan internal (<20 user aktif):

Rekomendasi final:

* Laravel 12
* Filament
* Livewire
* Laravel Reverb
* Queue Database/Redis
* Private Channels
* Presence Channels
* Single Chat Module

Arsitektur ini sangat cukup, ringan, mudah maintenance, dan dapat digunakan bertahun-tahun tanpa perlu layanan realtime pihak ketiga.
