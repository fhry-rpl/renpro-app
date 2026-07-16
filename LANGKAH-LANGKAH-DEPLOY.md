# Langkah-Langkah Deploy Laravel ke Vercel

**Project:** RENPRO UPBU Budiarto
**Stack:** Laravel 13 + PHP 8.3 + PostgreSQL (Neon) + Vite + Tailwind CSS

---

## Daftar Isi

- [1. Prasyarat](#1-prasyarat)
- [2. Setup Database — PostgreSQL (Neon)](#2-setup-database--postgresql-neon)
- [3. Konfigurasi Project](#3-konfigurasi-project)
- [4. Setup Environment Variables di Vercel](#4-setup-environment-variables-di-vercel)
- [5. Deploy ke Vercel](#5-deploy-ke-vercel)
- [6. Post-Deploy](#6-post-deploy)
- [7. Troubleshooting](#7-troubleshooting)

---

## 1. Prasyarat

| No | Kebutuhan | Keterangan |
|----|-----------|------------|
| 1 | Akun GitHub | Untuk menyimpan repository |
| 2 | Akun Vercel | Bisa login pakai GitHub |
| 3 | Akun Neon | PostgreSQL serverless (gratis) |
| 4 | Domain (opsional) | Custom domain kalo punya |

Install Vercel CLI (opsional, untuk deploy dari terminal):

```bash
npm install -g vercel
```

---

## 2. Setup Database — PostgreSQL (Neon)

Vercel tidak support SQLite. Project ini butuh database persisten. Kita pakai **Neon** (Postgres serverless, gratis).

### Langkah:

1. Buka https://console.neon.tech → Login
2. Klik **Create Project**
   - Name: `renpro-upbu-budiarto`
   - Region: pilih yang terdekat (misal Singapore `ap-southeast-1`)
   - PostgreSQL version: 16 atau 17
3. Klik **Create**
4. Copy **connection string** dari dashboard Neon. Formatnya:
   ```
   postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
   ```

Connection string ini akan dipakai sebagai `DB_URL` di Vercel.

> **Catatan:** Alternatif lain → **Supabase** (juga Postgres gratis) atau **Aiven**.

---

## 3. Konfigurasi Project

### 3.1. `composer.json` — Tambah `post-install-cmd`

Pastikan di bagian `"scripts"` ada `post-install-cmd` untuk menjalankan artisan caching otomatis setelah `composer install`:

```json
"scripts": {
    "post-autoload-dump": [
        "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
        "@php artisan package:discover --ansi"
    ],
    "post-install-cmd": [
        "@php artisan config:cache --ansi",
        "@php artisan route:cache --ansi",
        "@php artisan view:cache --ansi"
    ]
}
```

### 3.2. `vercel.json` — Konfigurasi Build

File `vercel.json` sudah ada di project. Isinya:

```json
{
    "functions": {
        "api/index.php": {
            "runtime": "vercel-php@0.7.1"
        }
    },
    "routes": [
        { "src": "/build/(.*)", "dest": "/public/build/$1" },
        { "src": "/(.*)", "dest": "/api/index.php" }
    ],
    "outputDirectory": "public",
    "buildCommand": "npm run build"
}
```

**Penjelasan:**
- `runtime: vercel-php@0.7.1` → builder PHP untuk Vercel Functions
- `buildCommand: "npm run build"` → build frontend (Vite). **JANGAN** jalankan PHP di sini karena PHP belum tersedia.
- Composer install + artisan caching akan jalan otomatis di runtime function oleh `vercel-php`.

### 3.3. `api/index.php` — Entry Point

File ini sudah ada dan isinya:

```php
<?php
require __DIR__ . '/../public/index.php';
```

Ini adalah bridge agar Laravel bisa jalan sebagai serverless function di Vercel.

### 3.4. `.env.production` — Template Environment Production

File ini sudah ada. Sesuaikan isinya:

```env
APP_NAME="RENPRO UPBU Budiarto"
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://PROJECT.vercel.app

DB_CONNECTION=pgsql
DB_URL=postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require

SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
CACHE_STORE=array

FILESYSTEM_DISK=local
# TODO: nanti ganti ke s3 dan tambah AWS_* env vars setelah storage siap
```

> **Catatan Storage:** Untuk sementara file upload disimpan ke local disk. Di Vercel (serverless), file tidak persist. Setup S3/Cloudflare R2 menyusul setelah deploy berhasil.

### 3.5. Generate `APP_KEY`

Jalankan perintah berikut di lokal untuk generate APP_KEY:

```bash
php artisan key:generate --show
```

Outputnya akan seperti: `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=`

Simpan key ini. **Wajib** diset di Vercel.

---

## 4. Setup Environment Variables di Vercel

### Melalui Vercel Dashboard

Buka **Vercel Dashboard → Project → Settings → Environment Variables**

Tambahkan variabel berikut:

| Key | Value | Environment |
|-----|-------|-------------|
| `APP_KEY` | `base64:xxxxxxxx...` | Production |
| `APP_ENV` | `production` | Production |
| `APP_DEBUG` | `false` | Production |
| `APP_URL` | `https://<project>.vercel.app` | Production |
| `APP_NAME` | `RENPRO UPBU Budiarto` | Production |
| `APP_LOCALE` | `id` | Production |
| `APP_FALLBACK_LOCALE` | `id` | Production |
| `APP_FAKER_LOCALE` | `id_ID` | Production |
| `APP_TIMEZONE` | `Asia/Jakarta` | Production |
| `DB_CONNECTION` | `pgsql` | Production |
| `DB_URL` | `postgresql://user:pass@...` | Production |
| `SESSION_DRIVER` | `cookie` | Production |
| `QUEUE_CONNECTION` | `sync` | Production |
| `CACHE_STORE` | `array` | Production |
| `LOG_CHANNEL` | `stderr` | Production |
| `LOG_LEVEL` | `error` | Production |

> **PENTING:** Jangan commit `.env` atau `.env.production` ke Git. Semua env diset via Vercel Dashboard.

### Melalui Vercel CLI (Alternatif)

```bash
vercel env add APP_KEY production
vercel env add APP_ENV production
# ... dan seterusnya
```

---

## 5. Deploy ke Vercel

### Opsi A: Deploy via Git (Rekomendasi)

```bash
# 1. Buat repository di GitHub
# 2. Push project ke GitHub
git init
git add .
git commit -m "init: project RENPRO UPBU Budiarto"
git remote add origin https://github.com/username/renpro-upbu-budiarto.git
git push -u origin main

# 3. Buka https://vercel.com → Add New → Project
# 4. Import repository GitHub
# 5. Setelan build otomatis terbaca dari vercel.json
# 6. Set Environment Variables (sesuai tabel di atas)
# 7. Klik Deploy
```

### Opsi B: Deploy via Vercel CLI

```bash
# Login Vercel
vercel login

# Deploy dari folder project
cd /public/laravel-web
vercel --prod
```

Vercel akan mendeteksi `vercel.json` dan menjalankan build.

---

## 6. Post-Deploy

### 6.1. Jalankan Migration

Setelah deploy berhasil, migration harus dijalankan untuk membuat tabel-tabel di PostgreSQL.

Vercel **tidak mendukung** SSH/exec ke server, jadi migration harus dipicu via HTTP request.

#### Cara Aman: Via route sementara dengan secret token

**Langkah-langkah:**

1. Generate random secret (jalankan di lokal):
   ```bash
   php -r "echo bin2hex(random_bytes(32));"
   ```
   Simpan outputnya (contoh: `a1b2c3d4e5f6...`).

2. Tambahkan `MIGRATE_SECRET` ke Vercel Environment Variables:
   ```bash
   vercel env add MIGRATE_SECRET production
   ```
   Atau via Dashboard Vercel → Settings → Environment Variables.

3. Route migrasi **sudah tersedia** di `routes/web.php`:
   ```php
   Route::get('/_migrate/{token}', function (string $token) {
       if ($token !== env('MIGRATE_SECRET')) {
           abort(404);
       }
       Artisan::call('migrate', ['--force' => true]);
       return response(Artisan::output());
   })->middleware('throttle:3,1');
   ```

4. Deploy ulang aplikasi:
   ```bash
   vercel --prod
   ```
   Atau push ke GitHub (auto-deploy).

5. Akses URL migrasi:
   ```
   https://<project>.vercel.app/_migrate/<MIGRATE_SECRET>
   ```

6. **⚠️ HAPUS ROUTE `/_migrate`** dari `routes/web.php` setelah migration selesai, lalu deploy ulang.

> **Catatan:** Route ini dilindungi oleh:
> - Secret token (cocok dengan `MIGRATE_SECRET`)
> - Rate limiter (max 3 request per menit via `throttle:3,1`)

### 6.2. Setup Domain Kustom (Opsional)

1. Buka **Vercel Dashboard → Project → Settings → Domains**
2. Masukkan domain (misal: `renpro.budiarto.go.id`)
3. Ikuti instruksi untuk setup DNS (biasanya CNAME ke `cname.vercel-dns.com`)

### 6.3. Generate Ulang APP_KEY (jika perlu)

```bash
php artisan key:generate
```

Update `APP_KEY` di Vercel Environment Variables.

---

## 7. Troubleshooting

### 7.1. Build Gagal — `php: command not found`

**Penyebab:** `buildCommand` di `vercel.json` menjalankan perintah PHP.
**Solusi:** `buildCommand` hanya untuk frontend (`npm run build`). PHP & Composer otomatis jalan di runtime `vercel-php`.

### 7.2. Error 500 — `No application encryption key has been specified`

**Penyebab:** `APP_KEY` tidak diset atau kosong.
**Solusi:** Generate key (`php artisan key:generate --show`) dan set di Vercel Environment Variables.

### 7.3. Halaman Kosong / Route Not Found

**Penyebab:** Cache route/config tidak kebangun.
**Solusi:** Pastikan `post-install-cmd` di `composer.json` sudah benar, atau jalankan manual:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7.4. Database Error — `Table not found`

**Penyebab:** Migration belum dijalankan.
**Solusi:** Jalankan `php artisan migrate --force` (lihat bagian [6.1](#61-jalankan-migration)).

### 7.5. Session / Login Tidak Berfungsi

**Penyebab:** Session database tidak tersedia atau session driver tidak sesuai.
**Solusi:** Set `SESSION_DRIVER=cookie` di production (tidak perlu database).

---

## Diagram Alur Deploy

```
Git Push
    │
    ▼
Vercel Build
    ├── npm install (installCommand)
    ├── npm run build (buildCommand) → Vite build frontend
    └── output ke /public
    │
    ▼
Vercel Runtime (Serverless Function)
    └── api/index.php
        ├── PHP runtime (vercel-php@0.7.1)
        ├── composer install (otomatis)
        │   └── post-install-cmd → artisan config:cache, route:cache, view:cache
        └── require public/index.php → Laravel bootstrap
            └── PostgreSQL (Neon) via DB_URL
    │
    ▼
Siap diakses via URL Vercel
```

---

## Catatan Penting — Storage Sementara

Project ini membutuhkan S3-compatible storage (Cloudflare R2 / AWS S3) untuk upload file (gambar, dokumen). **Untuk deploy pertama, storage belum diaktifkan.**

**Apa yang bisa dilakukan sekarang:**
- Semua halaman publik bisa diakses (berita, galeri, dokumen, staf)
- Admin bisa login dan mengelola konten teks
- Migration database bisa dijalankan

**Yang belum bisa (perlu S3 nanti):**
- Upload thumbnail post
- Upload dokumen
- Upload gambar galeri
- Upload foto staf
- Download dokumen
- Tampilan gambar/foto (hanya placeholder)

### Setup Storage Belakangan

Kalau sudah siap setup S3 / Cloudflare R2:

1. Ikuti panduan setup S3 (Cloudflare R2 / AWS S3)
2. Set `FILESYSTEM_DISK=s3` di Vercel Environment Variables
3. Tambahkan env vars: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_ENDPOINT`, `AWS_URL`
4. Migrasi file yang sudah terlanjur diupload (jika ada) dari local ke S3

---

## Referensi

- [Vercel PHP Runtime](https://github.com/juicyfx/vercel-php)
- [Laravel Documentation — Deployment](https://laravel.com/docs/deployment)
- [Neon Serverless PostgreSQL](https://neon.tech)
- [Cloudflare R2](https://developers.cloudflare.com/r2/)
- [Vercel Environment Variables](https://vercel.com/docs/projects/environment-variables)
