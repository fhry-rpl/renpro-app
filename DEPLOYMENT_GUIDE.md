# 🚀 DEPLOYMENT GUIDE - LARAVEL KE VERCEL

## ✅ PERUBAHAN YANG TELAH DITERAPKAN:

### 1. ✅ Updated vercel.json Configuration
- **Runtime:** Changed from `vercel-php@0.9.0` to `vercel-php@0.7.1` (lebih stabil)
- **Memory:** Set to 1024MB
- **Timeout:** Set to 15 seconds
- **Routes:** Added proper routing for /storage and /api
- **Environment:** Added NODE_ENV=production and COMPOSER_PROCESS_TIMEOUT=0

### 2. ✅ Generated Security Secrets
- **APP_KEY:** `base64:EL1zsCtqwmyIu2uANb2aHppcqYdeYYPaEyMtWcGbDc4=`
- **MIGRATE_SECRET:** `3d0107cb6b24be6e575f3c22de83ad2502940d5a9cb42115dca08c48faefd51a`

### 3. ✅ Updated Environment Files
- **.env.production:** Updated with all required variables
- **.env.vercel:** Updated with MIGRATE_SECRET and other environment variables

### 4. ✅ Enhanced Migration Security
- Improved token validation in api/index.php
- Added proper error handling and HTTP status codes

### 5. ✅ Tested Build Process
- ✅ Composer install successful
- ✅ NPM install successful  
- ✅ Vite build successful
- ✅ Config, route, and view caching successful

---

## 📋 NEXT STEPS - DEPLOY KE VERCEL:

### **STEP 1: Set Environment Variables di Vercel Dashboard**

Akses: Vercel Dashboard → Project → Settings → Environment Variables → Production

**Wajib Di-set:**
```
APP_KEY=base64:EL1zsCtqwmyIu2uANb2aHppcqYdeYYPaEyMtWcGbDc4=
APP_ENV=production
APP_DEBUG=false
APP_URL=https://laravel-web-peach.vercel.app
APP_NAME="RENPRO UPBU Budiarto"
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta
DB_CONNECTION=pgsql
DB_URL=postgresql://user:password@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
CACHE_STORE=array
LOG_CHANNEL=stderr
LOG_LEVEL=error
FILESYSTEM_DISK=local
MIGRATE_SECRET=3d0107cb6b24be6e575f3c22de83ad2502940d5a9cb42115dca08c48faefd51a
NODE_ENV=production
COMPOSER_PROCESS_TIMEOUT=0
```

### **STEP 2: Update DB_URL**
Ganti `DB_URL` dengan connection string dari Neon PostgreSQL:
```
postgresql://user:password@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
```

### **STEP 3: Commit Changes ke GitHub**
```bash
cd /public/laravel-web
git add .
git commit -m "fix: update Vercel configuration for stable deployment"
git push
```

### **STEP 4: Deploy ke Vercel**
**Option A: Via Vercel Dashboard**
1. Buka Vercel Dashboard
2. Pilih project Laravel-web
3. Klik "Deployments" → "New Deployment"
4. Pilih branch `main`
5. Vercel akan otomatis detect perubahan dan deploy

**Option B: Via Vercel CLI**
```bash
vercel --prod
```

### **STEP 5: Run Database Migration**
Setelah deploy berhasil:
```bash
# Akses migration route:
https://laravel-web-peach.vercel.app/__migrate/3d0107cb6b24be6e575f3c22de83ad2502940d5a9cb42115dca08c48faefd51a
```

### **STEP 6: Verify Deployment**
1. Akses URL aplikasi: https://laravel-web-peach.vercel.app
2. Cek Vercel Dashboard → Deployments → View Logs
3. Test login admin
4. Verifikasi database connection

---

## 🔍 TROUBLESHOOTING:

### **Jika masih ada 500 error:**
1. **Cek Vercel Logs:** Vercel Dashboard → Deployments → View Logs
2. **APP_KEY Error:** Pastikan APP_KEY sudah set di Vercel Environment Variables
3. **DB_URL Error:** Pastikan DB_URL sudah set dengan connection string Neon yang benar
4. **Cache Error:** Jalankan manual `php artisan config:cache` di lokal

### **Jika migration gagal:**
1. **Cek MIGRATE_SECRET:** Pastikan sudah set di Vercel Environment Variables
2. **Cek DB_URL:** Pastikan connection string Neon sudah benar
3. **Cek permission:** Pastikan database user memiliki permission untuk create table

### **Jika build gagal:**
1. **Cek vercel.json:** Pastikan format JSON valid
2. **Cek dependencies:** Pastikan composer.json dan package.json valid
3. **Cek memory:** Pastikan memory setting adequate

---

## 📊 DEPLOYMENT CHECKLIST:

- [ ] ✅ vercel.json updated dengan runtime 0.7.1
- [ ] ✅ Environment variables di Vercel
- [ ] ✅ APP_KEY di-set
- [ ] ✅ DB_URL di-set dengan Neon connection string
- [ ] ✅ MIGRATE_SECRET di-set
- [ ] ✅ Build process test berhasil
- [ ] ✅ Commit dan push ke GitHub
- [ ] ✅ Deploy ke Vercel
- [ ] ✅ Migration database berhasil
- [ ] ✅ Aplikasi berjalan normal

---

## 🎯 EXPECTED HASIL:

Setelah deployment berhasil:
- ✅ Aplikasi Laravel berjalan di Vercel tanpa 500 error
- ✅ Database PostgreSQL (Neon) terhubung
- ✅ Admin panel dapat diakses
- ✅ Migration database berhasil dijalankan
- ✅ Frontend (Vite + Tailwind) berjalan normal

---

## 📞 DUKUNGAN:

Jika ada masalah:
1. Cek Vercel Dashboard → Deployments → View Logs
2. Gunakan endpoint debugging:
   - `https://laravel-web-peach.vercel.app/__env` (cek environment variables)
   - `https://laravel-web-peach.vercel.app/__ping` (ping test)

**Last Updated:** 2026-07-21  
**Status:** ✅ Ready for deployment