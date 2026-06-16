# Om Abonk - Learning Management System

Platform kursus IT berbasis web untuk pemula hingga mahir. Dibangun dengan CodeIgniter 4, Docker, AdminLTE 3, dan integrasi pembayaran DOKU.

**Live:** https://ayodaftar.web.id

## Fitur

### Admin
- Dashboard statistik (user, kursus, kelas, enrollment, pembayaran)
- CRUD Users, Kategori, Kursus, Kelas, Konten, Pengumuman
- Approve / Reject enrollment peserta
- Approve / Reject pembayaran manual
- Pengaturan site (nama, deskripsi, tagline, logo, footer)
- Upload thumbnail kursus & avatar profile

### Member (Siswa)
- Jelajahi kursus yang tersedia
- Beli & daftar kursus berbayar via DOKU (popup checkout)
- Upload bukti bayar untuk kursus manual
- Daftar / enroll rombongan belajar gratis
- Akses materi setelah enrollment disetujui
- Lihat konten per kelas (video, document, link)
- Riwayat pembayaran & status transaksi
- Profil & update avatar

### Pembayaran
- **DOKU Checkout** — integrasi popup pembayaran online (VA, QRIS, kartu kredit, GoPay, OVO, Dana, dll)
- **Manual** — upload bukti bayar, menunggu approval admin
- Auto-enrollment setelah pembayaran berhasil
- Auto-refresh status pembayaran
- Callback URL untuk notifikasi dari DOKU

### Landing Page
- Hero section dengan statistik dinamis
- Fitur unggulan
- Daftar kursus (klik → detail kursus publik)
- CTA register

### Autentikasi
- Register dengan email verification (Gmail SMTP)
- Forgot password → reset password via email
- Admin bisa login tanpa verifikasi email

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | CodeIgniter 4.7 |
| Database | MySQL 8.0 |
| Frontend | AdminLTE 3.2 + Bootstrap 4 |
| Font | Inter (Google Fonts) |
| Icons | Font Awesome 6.5 |
| Server | Nginx + PHP 8.2 FPM |
| Container | Docker Compose |
| Email | Gmail SMTP (App Password) |
| Pembayaran | DOKU Checkout (Popup JS) |

## Struktur Database

```
users              → akun admin & member
categories         → kategori kursus
courses            → kursus (judul, deskripsi, kurikulum, thumbnail, harga)
classes            → rombongan belajar per kursus
enrollments        → pendaftaran siswa ke kelas
content            → materi per kelas (video/document/link)
announcements      → pengumuman per kelas
site_settings      → pengaturan site (key-value)
payments           → riwayat pembayaran (DOKU & manual)
```

## Instalasi

### Prasyarat
- Docker & Docker Compose
- Git

### Setup

```bash
# Clone repository
git clone https://github.com/uniteds/kursus-om-abonk.git
cd kursus-om-abonk

# Copy .env
cp .env.example .env

# Jalankan Docker
docker compose up -d

# Install dependencies
docker exec omabonk-app composer install

# Jalankan migration
docker exec omabonk-app php spark migrate --all

# Jalankan seeder
docker exec omabonk-app php spark db:seed SiteSettingSeeder
docker exec omabonk-app php spark db:seed UserSeeder
```

Aplikasi bisa diakses di:
- **Website:** http://localhost:8000
- **phpMyAdmin:** http://localhost:8080

### Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@omabonk.com | password |
| Member | member@omabonk.com | password |

## Konfigurasi Gmail SMTP

Untuk mengaktifkan email verification & reset password:

1. **Aktifkan 2FA** di Google Account
   → https://myaccount.google.com/signinoptions/two-step-verification

2. **Generate App Password**
   → https://myaccount.google.com/apppasswords
   → Pilih "Mail" + "Other (Custom name)" → isi "Om Abonk"

3. **Update `.env`:**
```env
email.fromEmail = your-email@gmail.com
email.SMTPUser = your-email@gmail.com
email.SMTPPass = xxxx-xxxx-xxxx-xxxx
```

4. **Update `app/Config/Email.php`** (jika perlu mengubah `fromEmail`)

## Konfigurasi DOKU Payment Gateway

Untuk mengaktifkan pembayaran online via DOKU:

1. **Daftar akun DOKU** di https://www.doku.com
2. **Buat Aplikasi** di DOKU Dashboard → dapatkan `Client ID` & `Secret Key`
3. **Set Notification URL** di DOKU Dashboard: `https://yourdomain.com/doku/notification`
4. **Update `.env`:**
```env
DOKU_CLIENT_ID = your-client-id
DOKU_SECRET_KEY = your-secret-key
DOKU_ENV = production
```

> **Catatan:** Set `DOKU_ENV = sandbox` untuk testing, `production` untuk live.

### Flow Pembayaran DOKU

1. Member pilih kursus berbayar → klik "Bayar & Daftar"
2. Halaman konfirmasi → klik "Bayar Sekarang"
3. Popup DOKU Checkout muncul (pilih metode bayar)
4. Member selesaikan pembayaran
5. DOKU kirim notifikasi ke server → auto-enrollment aktif
6. Member diarahkan ke halaman kursus

### Flow Pembayaran Manual

1. Member pilih kursus berbayar → klik "Bayar & Daftar"
2. Upload bukti bayar → simpan
3. Admin review → Approve / Reject
4. Jika approve → enrollment aktif

## Deployment ke Production

1. Set `CI_ENVIRONMENT = production` di `.env`
2. Set `app.baseURL` ke domain kamu
3. Set `app.forceGlobalSecureRequests = true`
4. Jalankan migration di server production
5. Buat admin pertama melalui seeder atau database manual
6. Set DOKU notification URL ke domain production

## Urutan Migrate

| Migration | Deskripsi |
|-----------|-----------|
| 000001 | Tabel `users` |
| 000002 | Tabel `categories` |
| 000003 | Tabel `courses` |
| 000004 | Tabel `classes` |
| 000005 | Tabel `enrollments` |
| 000006 | Tabel `content` |
| 000007 | Tabel `announcements` |
| 000008 | Tabel `site_settings` |
| 000009 | Tambah kolom `meetings_count`, `meeting_duration`, `curriculum` ke `courses` |
| 000010 | Tambah kolom `email_verified_at`, `reset_token`, `reset_expires` ke `users` |
| 000011 | Tambah kolom `course_price` ke `courses` |
| 000012 | Tabel `payments` |
| 000013 | Tambah kolom `course_id` ke `enrollments` |
| 000014 | Tambah kolom `type`, `meeting_number`, `url`, `file_path` ke `content` |
| 000015 | Tambah kolom `target_role` ke `announcements` |
| 000016 | Tabel `payments` (final schema) |
| 000017 | Tambah kolom DOKU ke `payments` (`invoice_number`, `doku_session_id`, `doku_token_id`, `doku_payment_url`, `payment_channel`, `external_id`) |

## License

MIT
