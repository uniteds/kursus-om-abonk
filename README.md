# Om Abonk - Learning Management System

Platform kursus IT berbasis web untuk pemula hingga mahir. Dibangun dengan CodeIgniter 4, Docker, AdminLTE 3, integrasi pembayaran DOKU, dan sertifikat otomatis.

**Live:** https://ayodaftar.web.id

## Fitur

### Admin
- Dashboard statistik (user, kursus, kelas, enrollment, pembayaran)
- CRUD Users, Kategori, Kursus, Kelas, Konten, Pengumuman
- Approve / Reject enrollment peserta
- Approve / Reject pembayaran manual
- **Mark Complete** — tandai siswa selesai kursus (untuk terbitkan sertifikat)
- Pengaturan site (nama, deskripsi, tagline, logo, footer)
- **Pengaturan Sertifikat** — logo, penandatangan, warna, judul
- Upload thumbnail kursus & avatar profile

### Member (Siswa)
- Jelajahi kursus yang tersedia
- Beli & daftar kursus berbayar via DOKU (popup checkout)
- Upload bukti bayar untuk kursus manual
- Daftar / enroll rombongan belajar gratis
- Akses materi setelah enrollment disetujui
- Lihat konten per kelas (video, document, link, slide, tugas)
- Riwayat pembayaran & status transaksi
- **Profil lengkap** — nama, email, WhatsApp, alamat, tanggal lahir, bio, avatar
- **Sertifikat** — download PDF sertifikat setelah kursus selesai

### Sertifikat
- PDF sertifikat landscape A4 dengan desain profesional
- **Logo** — kustomisasi logo sertifikat dari admin
- **Penandatangan** — nama & jabatan penandatangan
- **QR Code** — scan untuk validasi keaslian sertifikat
- **Halaman Validasi** — `/certificate/validate/{nomor}` (publik)
- Auto-numbered: `CERT-YYYYMMDD-XXXX`
- Kalkulasi jam pelajaran otomatis (1 jam pelajaran = 45 menit)

### Pembayaran
- **DOKU Checkout** — integrasi popup pembayaran online (VA, QRIS, kartu kredit, GoPay, OVO, Dana, dll)
- **Manual** — upload bukti bayar, menunggu approval admin
- Auto-enrollment setelah pembayaran berhasil
- Auto-refresh status pembayaran
- Callback URL untuk notifikasi dari DOKU
- Replay payment dengan invoice number baru (anti-expired)

### Landing Page
- Hero section dengan statistik dinamis
- Fitur unggulan
- Daftar kursus (klik → detail kursus publik)
- CTA register

### Autentikasi
- Register dengan email verification (Gmail SMTP)
- Forgot password → reset password via email
- Admin bisa login tanpa verifikasi email
- **Login dengan Google** — OAuth2, auto register jika email belum terdaftar

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
| OAuth2 | league/oauth2-client (Google Login) |
| Pembayaran | DOKU Checkout (Popup JS) |
| PDF | Dompdf 3.x |
| QR Code | chillerlan/php-qrcode 6.x |

## Struktur Database

```
users                 → akun admin & member (nama, email, whatsapp, bio, alamat, tgl lahir, avatar)
categories            → kategori kursus
courses               → kursus (judul, deskripsi, kurikulum, thumbnail, harga, meetings_count, meeting_duration)
classes               → rombongan belajar per kursus
enrollments           → pendaftaran siswa ke kelas (pending/approved/rejected/completed)
content               → materi per kelas (video/document/link/slide/tugas)
announcements         → pengumuman per kelas
site_settings         → pengaturan site (key-value)
payments              → riwayat pembayaran (DOKU & manual)
certificates          → sertifikat peserta (nomor, nama, kursus, durasi, tanggal)
certificate_settings  → pengaturan sertifikat (logo, penandatangan, warna)
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

## Konfigurasi Google OAuth (Login dengan Google)

Untuk mengaktifkan login via Google:

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang ada
3. Aktifkan **Google+ API** dan **People API**
4. Buka **APIs & Services → Credentials** → klik **Create Credentials → OAuth client ID**
5. Pilih **Web application**, isi nama app
6. Di **Authorized redirect URIs**, tambahkan:
   ```
   https://yourdomain.com/auth/google/callback
   ```
7. Copy **Client ID** dan **Client Secret**
8. **Update `.env`:**
   ```env
   google.client_id = your-client-id.apps.googleusercontent.com
   google.client_secret = your-client-secret
   google.redirect_uri = https://yourdomain.com/auth/google/callback
   ```

> **Catatan:** Redirect URI di Google Cloud Console harus persis sama dengan yang di `.env` (termasuk `https://`, domain, port, dan path).

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

## Sertifikat

### Konfigurasi
1. Login admin → **Pengaturan → Sertifikat**
2. Upload logo (PNG/JPG/SVG, background transparan)
3. Isi nama & jabatan penandatangan
4. Atur warna border & aksen
5. Simpan

### Flow Sertifikat
1. Admin klik tombol "Selesai" (flag-checkered) di tab Siswa pada detail kelas
2. Enrollment status berubah ke `completed`
3. Member klik tombol "Sertifikat" di halaman Kursus Saya
4. PDF otomatis di-generate & didownload

### Validasi Sertifikat
- Setiap sertifikat memiliki QR Code yang mengarah ke halaman validasi
- URL: `https://yourdomain.com/certificate/validate/CERT-XXXXXXXX-XXXX`
- Halaman validasi menampilkan status: **Valid** atau **Tidak Ditemukan**

## Deployment ke Production

1. Set `CI_ENVIRONMENT = production` di `.env`
2. Set `app.baseURL` ke domain kamu
3. Set `app.forceGlobalSecureRequests = true`
4. Jalankan migration di server production
5. Buat admin pertama melalui seeder atau database manual
6. Set DOKU notification URL ke domain production
7. Konfigurasi sertifikat (logo, penandatangan) di admin
8. Konfigurasi Google OAuth (Client ID, Secret, Redirect URI) di `.env`

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
| 000018 | Tambah kolom `whatsapp`, `bio`, `address`, `date_of_birth` ke `users` |
| 000019 | Tabel `certificates` (sertifikat peserta) |
| 000020 | Tabel `certificate_settings` (pengaturan sertifikat) |
| 000021 | Tambah kolom `logo` ke `certificate_settings` |
| 000022 | Tambah kolom `google_id`, `avatar_url` ke `users` |

## License

MIT
