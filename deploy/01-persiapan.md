# Langkah 1: Persiapan Awal

## Prasyarat

| No | Kebutuhan | Keterangan |
|----|-----------|------------|
| 1 | Akun GitHub | Untuk repository |
| 2 | Akun Vercel | Bisa login pakai GitHub (gratis) |
| 3 | Akun Neon | PostgreSQL serverless (https://neon.tech, gratis) |

## Buat Repository GitHub

```bash
cd /public/laravel-web

git init
git add .
git commit -m "init: project RENPRO UPBU Budiarto"

# Ganti dengan repo GitHub Anda
git remote add origin https://github.com/username/renpro-upbu-budiarto.git
git push -u origin main
```

## Install Vercel CLI (Opsional)

```bash
npm install -g vercel
```

---
Berikutnya: [02-setup-database.md](02-setup-database.md)
