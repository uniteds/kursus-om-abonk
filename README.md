# Om Abonk - Learning Management System

Platform kursus IT berbasis web untuk pemula hingga mahir. Dibangun dengan CodeIgniter 4, Docker, dan AdminLTE 3.

**Live:** https://ayodaftar.web.id

## Fitur

### Admin
- Dashboard statistik (user, kursus, kelas, enrollment)
- CRUD Users, Kategori, Kursus, Kelas, Konten, Pengumuman
- Approve / Reject enrollment peserta
- Pengaturan site (nama, deskripsi, tagline, logo, footer)
- Upload thumbnail kursus & avatar profile

### Member (Siswa)
- Jelajahi kursus yang tersedia
- Daftar / enroll rombongan belajar
- Akses materi setelah enrollment disetujui
- Lihat konten per kelas (video, document, link)
- Profil & update avatar

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

## Struktur Database

```
users           → akun admin & member
categories      → kategori kursus
courses         → kursus (judul, deskripsi, kurikulum, thumbnail)
classes         → rombongan belajar per kursus
enrollments     → pendaftaran siswa ke kelas
content         → materi per kelas (video/document/link)
announcements   → pengumuman per kelas
site_settings   → pengaturan site (key-value)
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

## Deployment ke Production

1. Set `CI_ENVIRONMENT = production` di `.env`
2. Set `app.baseURL` ke domain kamu
3. Set `app.forceGlobalSecureRequests = true`
4. Jalankan migration di server production
5. Buat admin pertama melalui seeder atau database manual

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

## License

MIT
