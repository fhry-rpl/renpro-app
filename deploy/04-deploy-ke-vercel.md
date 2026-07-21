# Langkah 4: Deploy ke Vercel

## Opsi A: Via Git (Rekomendasi)

1. Push project ke GitHub:
   ```bash
   git add .
   git commit -m "fix: perbaiki vercel.json & composer.json untuk deploy"
   git push origin main
   ```

2. Buka https://vercel.com → **Add New → Project**

3. Import repository GitHub yang sudah di-push

4. Vercel akan otomatis deteksi `vercel.json`:
   - **Framework**: Other
   - **Build Command**: `npm run build` (otomatis dari vercel.json)
   - **Output Directory**: `public` (otomatis dari vercel.json)
   - **Install Command**: `npm install` (otomatis dari vercel.json)

5. Di halaman **Environment Variables**, isi semua variabel dari [Langkah 3](03-setup-env-vercel.md)

6. Klik **Deploy**

## Opsi B: Via Vercel CLI

```bash
cd /public/laravel-web
vercel login
vercel --prod
```

Vercel CLI akan otomatis membaca `vercel.json` dan environment dari `.env.vercel`.

## Cara Kerja Build

```
Git Push → Vercel Build
  ├── npm install (installCommand)
  ├── npm run build (buildCommand) → Vite build frontend
  └── output ke /public
       │
       ▼
Vercel Runtime (Serverless Function)
  └── api/index.php
      ├── vercel-php@0.7.5 sediakan PHP runtime
      ├── composer install --no-dev --optimize-autoloader (otomatis)
      │   └── post-install-cmd →
      │       ├── package:discover
      │       ├── config:cache ✓
      │       ├── route:cache ✓
      │       └── view:cache ✓
      └── require public/index.php → Laravel bootstrap
          └── PostgreSQL (Neon) via DB_URL
```

---
Berikutnya: [05-migrasi-database.md](05-migrasi-database.md)
