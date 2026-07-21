# Langkah 5: Jalankan Migrasi Database

Vercel **tidak mendukung** SSH/exec ke server. Migration harus dipicu via HTTP request.

## Generate Secret Token

Jalankan di lokal:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

Output: `a1b2c3d4e5f6...`
**Simpan token ini sebagai `MIGRATE_SECRET`** (set di Vercel Dashboard jika belum).

## Trigger Migrasi

Setelah deploy berhasil, akses URL berikut di browser:

```
https://<project>.vercel.app/__migrate/<MIGRATE_SECRET>
```

Contoh:
```
https://laravel-web.vercel.app/__migrate/a1b2c3d4e5f6...
```

Endpoint ini akan:
1. Parse `DB_URL` untuk koneksi PostgreSQL
2. Jalankan `migrate:fresh --force` (buat semua tabel)
3. Jalankan `db:seed --force` (isi data awal)

## Verifikasi

Akses endpoint debug untuk verifikasi env:

```
https://<project>.vercel.app/__env
```

Response contoh:
```
APP_KEY: base64:7r0oQonC3KFPFOABxgxVwWD+2e4LOG9tlg+M9xUJ4q4=
DB_URL: postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
DB_CONNECTION: pgsql
MIGRATE_SECRET: a1b2c3d4e5f6...
APP_ENV: production
```

---
Berikutnya: [06-verifikasi.md](06-verifikasi.md)
