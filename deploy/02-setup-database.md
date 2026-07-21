# Langkah 2: Setup Database PostgreSQL (Neon)

Vercel **tidak support** SQLite. Project ini butuh database persisten menggunakan **Neon** (Postgres serverless, gratis).

## Langkah:

1. Buka https://console.neon.tech → Login (bisa pakai GitHub/Google)

2. Klik **Create Project**:
   - Name: `renpro-upbu-budiarto`
   - Region: pilih terdekat (Singapore `ap-southeast-1`)
   - PostgreSQL version: 16 atau 17

3. Klik **Create**

4. Copy **connection string** dari dashboard Neon:
   ```
   postgresql://user:pass@ep-xxx.region.aws.neon.tech/neondb?sslmode=require
   ```

5. Simpan connection string — akan dipakai sebagai `DB_URL` di Vercel.

Connection string ini aman disimpan karena hanya bisa akses dari Vercel (via env vars).

---
Berikutnya: [03-setup-env-vercel.md](03-setup-env-vercel.md)
