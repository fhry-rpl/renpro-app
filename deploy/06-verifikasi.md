# Langkah 6: Verifikasi & Troubleshooting

## Cek Halaman Utama

Buka URL Vercel project Anda:
```
https://<project>.vercel.app/
```

## Cek Admin Login

1. Buka `https://<project>.vercel.app/login`
2. Login dengan:
   - Email: `admin@budiartoairport.com`
   - Password: `admin123`

## Cek Endpoint Debug

Akses:
```
https://<project>.vercel.app/__ping
```
Response: `pong`

## Troubleshooting

### Error 500 — `No application encryption key has been specified`

**Penyebab:** `APP_KEY` tidak diset atau kosong.
**Solusi:** Generate key & set di Vercel Environment Variables.

### Halaman Kosong / 404

**Penyebab:** Route cache bermasalah.
**Solusi:** Hapus cache dan deploy ulang:
```bash
git rm -r bootstrap/cache/*.php
git commit -m "fix: hapus cache"
git push
```

### Database Error — `Table not found`

**Penyebab:** Migration belum dijalankan.
**Solusi:** Jalankan `__migrate` endpoint (lihat langkah 5).

### Session / Login Tidak Berfungsi

**Penyebab:** Session driver tidak sesuai.
**Solusi:** Pastikan `SESSION_DRIVER=cookie` di Vercel env vars.

### Storage (Upload File)

Upload file saat ini **belum berfungsi** — file disimpan ke local disk yang tidak persist di Vercel.
Akan ditambahkan S3/Cloudflare R2 setelah deploy berhasil.

## Setup Domain Kustom (Opsional)

1. Buka **Vercel Dashboard → Project → Settings → Domains**
2. Masukkan domain (misal: `renpro.budiarto.go.id`)
3. Ikuti instruksi setup DNS (CNAME ke `cname.vercel-dns.com`)

---
**Selesai!** 🎉
