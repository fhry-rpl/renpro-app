# Langkah 3: Setup Environment Variables di Vercel

## Generate APP_KEY (Production)

Jalankan di lokal:

```bash
cd /public/laravel-web
php artisan key:generate --show
```

Output: `base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx=`
**Simpan key ini.**

## Set Environment Variables

Buka **Vercel Dashboard → Project → Settings → Environment Variables**

Tambahkan variabel berikut:

| Key | Value | Environment |
|-----|-------|-------------|
| `APP_KEY` | `base64:xxxxxxxx...` (dari generate di atas) | Production |
| `APP_ENV` | `production` | Production |
| `APP_DEBUG` | `false` | Production |
| `APP_URL` | `https://<project>.vercel.app` | Production |
| `APP_NAME` | `RENPRO UPBU Budiarto` | Production |
| `APP_LOCALE` | `id` | Production |
| `APP_FALLBACK_LOCALE` | `id` | Production |
| `APP_FAKER_LOCALE` | `id_ID` | Production |
| `APP_TIMEZONE` | `Asia/Jakarta` | Production |
| `DB_CONNECTION` | `pgsql` | Production |
| `DB_URL` | `postgresql://user:pass@ep-xxx...` (dari Neon) | Production |
| `SESSION_DRIVER` | `cookie` | Production |
| `QUEUE_CONNECTION` | `sync` | Production |
| `CACHE_STORE` | `array` | Production |
| `LOG_CHANNEL` | `stderr` | Production |
| `LOG_LEVEL` | `error` | Production |
| `MIGRATE_SECRET` | (random string, lihat di langkah 6) | Production |

### Via Vercel CLI (Alternatif)

```bash
cd /public/laravel-web
vercel env add APP_KEY production
vercel env add DB_URL production
# ... dan seterusnya
```

---
Berikutnya: [04-deploy-ke-vercel.md](04-deploy-ke-vercel.md)
