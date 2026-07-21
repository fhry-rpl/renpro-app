# Panduan Deploy Laravel ke Vercel

Project: **RENPRO UPBU Budiarto**  
Stack: Laravel 13 + PHP 8.3 + PostgreSQL (Neon) + Vite + Tailwind CSS 4

## Daftar Langkah

| # | Langkah | Status |
|---|---------|--------|
| 1 | [Persiapan Awal](01-persiapan.md) | ⬜ |
| 2 | [Setup Database PostgreSQL (Neon)](02-setup-database.md) | ⬜ |
| 3 | [Setup Environment Variables di Vercel](03-setup-env-vercel.md) | ⬜ |
| 4 | [Deploy ke Vercel](04-deploy-ke-vercel.md) | ⬜ |
| 5 | [Migrasi Database](05-migrasi-database.md) | ⬜ |
| 6 | [Verifikasi & Troubleshooting](06-verifikasi.md) | ⬜ |

## Perubahan Kode yang Sudah Dilakukan

| File | Perubahan |
|------|-----------|
| `vercel.json` | `installCommand` diubah dari `composer install && npm install` jadi hanya `npm install` |
| `composer.json` | `post-install-cmd` ditambah `config:cache`, `route:cache`, `view:cache` |
| `.env.production` | Template diperbarui dengan APP_KEY & catatan DB_URL |
