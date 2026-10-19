# Cara Online-kan ITAGarage (Laravel) ke Internet

Project ini **bukan web statis**, jadi tidak bisa pakai GitHub Pages.
Yang dipakai: **Render** (hosting gratis, tanpa kartu kredit) yang akan build
project ini pakai Dockerfile, lalu menjalankannya dengan PHP + Apache + database.

Semua file yang dibutuhkan sudah disiapkan:

| File | Fungsi |
|---|---|
| `Dockerfile` | Resep build: install PHP, Composer, compile CSS/JS Vite |
| `docker/entrypoint.sh` | Jalan otomatis saat start: migrasi DB, isi data contoh, cache |
| `docker/seed-if-empty.php` | Isi data contoh hanya kalau database masih kosong |
| `docker/apache.conf` | Arahkan web ke folder `public/`, aktifkan HTTPS |
| `docker/php.ini` | Setting upload gambar & cache PHP |
| `.dockerignore` | Kecualikan `.env`, `vendor`, `node_modules` dari image |
| `render.yaml` | Blueprint: bikin web service + database sekaligus |

---

## Langkah 1 — Kirim file baru ke GitHub

Di folder project, jalankan:

```bash
git add .
git commit -m "Tambah konfigurasi deploy Docker"
git push
```

> Cek dulu: `git status` harus menampilkan `.env` **tidak** ikut ter-commit.
> Kalau `.env` muncul di daftar, jangan di-`git add` — isinya password.

---

## Langkah 2 — Deploy di Render

> **PENTING:** Render bisa memunculkan dialog **"Add Card — Add credit card to
> verify your identity"** sebelum deploy. Kalau itu terjadi dan kamu tidak punya
> kartu kredit, **Render tidak bisa dipakai**. Tutup dialognya dan pindah ke
> bagian **Kalau tidak punya kartu kredit** di bawah.

1. Buka <https://render.com> → **Sign up** pakai akun GitHub.
2. Pilih **New** → **Blueprint**.
3. Pilih repo `ihsarfatiha/ITAGarage---website-sewa-kendaraan` → **Connect**.
4. Render akan membaca `render.yaml` dan menampilkan 2 resource:
   - `itagarage` (Free web service)
   - `itagarage-db` (Free Postgres)
5. Render minta isi **`APP_URL`**. Isi dengan alamat yang akan dipakai, contoh:
   `https://itagarage.onrender.com`
   (nama persisnya muncul setelah deploy; kalau beda, tinggal ubah di menu Environment)
6. Klik **Apply** / **Create**.
7. Tunggu build selesai (± 5–10 menit untuk pertama kali). Lihat log-nya di tab **Logs**.
   Kalau muncul `==> Siap. Menjalankan Apache...` berarti berhasil.

**Masuk ke panel admin:**
- Alamat: `https://<nama-service>.onrender.com/admin`
- Email: `admin@motorku.com`
- Password: `password`

> Segera ganti password admin setelah bisa login.

---

## Langkah 3 — Kalau ada error

| Gejala | Penyebab & solusi |
|---|---|
| Halaman putih / 500 | Buka tab **Logs**. Biasanya `APP_KEY` kosong atau database belum siap. |
| Tampilan polos tanpa CSS | Build Vite gagal. Cek log bagian `npm run build`. |
| `Database tidak bisa dihubungi` | Cek env `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` di menu Environment. |
| Redirect ke `http://` / CSS diblokir browser | `APP_URL` salah. Harus pakai `https://` dan tanpa garis miring di akhir. |
| Bangun ulang dari nol | Tombol **Manual Deploy** → **Clear build cache & deploy**. |

---

## Catatan penting tentang paket gratis

1. **Website "tidur" setelah 15 menit tidak ada yang buka.** Kunjungan berikutnya
   butuh ± 1 menit untuk bangun. Ini normal di paket gratis Render, bukan bug.

2. **Database Postgres gratis Render hangus setelah 30 hari.** Setelah itu data
   hilang. Kalau mau permanen dan tetap gratis, pindah ke **Neon**
   (<https://neon.tech> — Postgres gratis tanpa batas waktu):
   - Buat project di Neon, copy `host`, `user`, `password`, `database`
   - Di Render → tab **Environment**, isi:
     ```
     DB_CONNECTION = pgsql
     DB_HOST       = <host dari Neon>
     DB_PORT       = 5432
     DB_DATABASE   = <nama database>
     DB_USERNAME   = <user dari Neon>
     DB_PASSWORD   = <password dari Neon>
     ```
   - **Manual Deploy** ulang. Migrasi + data contoh akan diisi otomatis.

3. **Gambar hasil upload dari panel admin akan hilang saat redeploy**, karena
   filesystem container bersifat sementara. Untuk menyimpan permanen, gunakan
   penyimpanan cloud (Cloudflare R2 / AWS S3) dan isi `AWS_*` di Environment.
   Gambar bawaan di folder `public/image/` aman karena ikut di-commit ke git.

4. **Data yang ada di MySQL lokal (`db_sewamotor`) tidak otomatis pindah.**
   Setelah deploy, database diisi ulang dari `database/seeders/DatabaseSeeder.php`
   (6 motor, 6 mobil, 12 review, 4 user). Kalau ada data asli yang mau dibawa,
   beri tahu saya — perlu langkah ekspor-impor terpisah.

5. **GitHub Pages di repo ini sebaiknya dimatikan.** Alamat
   `https://ihsarfatiha.github.io/ITAGarage---website-sewa-kendaraan/` sekarang
   menampilkan README Laravel bawaan, bukan web aplikasinya, karena Pages hanya
   bisa menyajikan file statis dan repo ini isinya PHP. Cara mematikan:
   repo → **Settings** → **Pages** → **Source: None** → **Save**.
   Alamat web yang benar adalah alamat dari hosting yang kamu pilih di bawah.

---

## Kalau tidak punya kartu kredit

Render kadang meminta verifikasi kartu sebelum deploy, dan kalau kamu kena ini
tanpa punya kartu, Render memang tidak bisa dipakai. Jangan dipaksa — pakai
salah satu jalur berikut. Semuanya **tanpa kartu sama sekali**.

### Jalur A — SnapDeploy + Neon (paling permanen)

Dua layanan, keduanya gratis tanpa kartu dan tanpa batas waktu:

| Bagian | Layanan | Alasan |
|---|---|---|
| Aplikasi | SnapDeploy | Langsung membaca `Dockerfile` kita, gratis 100 jam/bulan |
| Database | Neon | Postgres gratis permanen, 1 GB |

**Langkah 1 — buat database di Neon**

1. <https://neon.com> → **Sign up** (bisa pakai akun GitHub)
2. **New Project** → beri nama `itagarage` → **Create**
3. Buka halaman project → **Connect** → pilih tab **Parameters only**
   (bukan "Connection string")
4. Catat 4 nilai: `host`, `database`, `user`, `password`

**Langkah 2 — deploy aplikasi di SnapDeploy**

1. <https://snapdeploy.dev> → **Sign up** pakai GitHub
2. **New Container** → **GitHub repo** → pilih `ITAGarage---website-sewa-kendaraan`
3. Kalau ditanya port, isi `8080` (sesuai `EXPOSE` di Dockerfile kita)
4. Tambahkan Environment Variables:
   ```
   APP_NAME=ITAGarage
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:ISI_HASIL_KEY_GENERATE
   APP_URL=https://<alamat-yang-diberi-snapdeploy>
   FILESYSTEM_DISK=public
   SESSION_DRIVER=file
   CACHE_DRIVER=file
   QUEUE_CONNECTION=sync
   RUN_SEEDER=true

   DB_CONNECTION=pgsql
   DB_HOST=<host dari Neon>
   DB_PORT=5432
   DB_DATABASE=<database dari Neon>
   DB_USERNAME=<user dari Neon>
   DB_PASSWORD=<password dari Neon>
   ```
   `APP_KEY` dibuat dengan perintah: `php artisan key:generate --show`
5. Deploy, lalu buka **Logs** sampai muncul `==> Siap. Menjalankan Apache...`

Catatan: web gratis SnapDeploy "tidur" setelah 15 menit tidak dipakai dan
bangun dalam ± 1 menit. Itu normal, bukan error.

### Jalur B — Cloudflare Tunnel (paling cepat, tanpa daftar)

Kalau kamu cuma butuh webnya tampil online sebentar (untuk demo, presentasi,
atau dikumpulkan), ini yang tercepat. Tidak ada pendaftaran, tidak ada kartu,
tidak ada biaya.

Syarat: Laragon menyala dan webnya sudah jalan di lokal.

1. Buka Laragon → **Start All** → tes dulu `http://sewamotor.test` bisa dibuka
2. Download `cloudflared`:
   <https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/downloads/>
3. Jalankan di PowerShell:
   ```
   cloudflared tunnel --url http://sewamotor.test
   ```
4. Dalam beberapa detik akan muncul alamat publik seperti
   `https://xxxx-xxxx.trycloudflare.com` — itulah alamat webnya

Kekurangan: alamat berubah setiap kali dijalankan, dan web hanya hidup selama
komputermu menyala serta perintah itu masih berjalan.

### Jalur C — Railway (satu platform, langsung ada MySQL)

Lihat bagian **Alternatif: Railway** di bawah. Paket gratisnya tidak butuh
kartu, dan MySQL sudah disediakan sehingga tidak perlu akun Neon terpisah.

---

## Alternatif: Railway (kalau ingin MySQL)

Railway cocok kalau kamu ingin tetap pakai MySQL seperti di lokal, dan Dockerfile
yang sudah dibuat bisa langsung dipakai. **Paket gratisnya tidak butuh kartu
kredit**, dan MySQL sudah disediakan sehingga tidak perlu akun Neon terpisah.

1. <https://railway.com> → login pakai GitHub
2. **New Project** → **Deploy from GitHub repo** → pilih `ITAGarage---website-sewa-kendaraan`
3. **Add Variables**, minimal:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:...        (hasil dari: php artisan key:generate --show)
   APP_URL=https://<domain-railway-kamu>
   FILESYSTEM_DISK=public
   DB_CONNECTION=mysql
   DB_HOST=${{MySQL.MYSQLHOST}}
   DB_PORT=${{MySQL.MYSQLPORT}}
   DB_DATABASE=${{MySQL.MYSQLDATABASE}}
   DB_USERNAME=${{MySQL.MYSQLUSER}}
   DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
   ```
4. **New** → **Database** → **Add MySQL**
5. Tab **Settings** → **Networking** → **Generate Domain**

Catatan: paket gratis Railway berisi kredit `$1`/bulan dan bisa kena batas
"deploy jam sibuk", jadi untuk pemakaian terus-menerus lebih aman Render.

---

## Tes di komputer sendiri (opsional)

Kalau sudah install Docker Desktop, pakai MySQL lokal Laragon supaya tidak
perlu setup database baru.

1. Ambil kunci aplikasi, lalu copy seluruh hasilnya:
   ```bash
   php artisan key:generate --show
   ```
2. Build dan jalankan (ganti `APP_KEY='...'` dengan hasil langkah 1):
   ```bash
   docker build -t itagarage .
   ```
   ```bash
   docker run --rm -p 8080:8080 -e APP_KEY='base64:ISI_HASIL_DI_SINI' -e APP_URL=http://localhost:8080 -e DB_CONNECTION=mysql -e DB_HOST=host.docker.internal -e DB_PORT=3306 -e DB_DATABASE=db_sewamotor -e DB_USERNAME=root -e DB_PASSWORD= itagarage
   ```
3. Buka <http://localhost:8080>. Pertama jalan akan otomatis migrasi tabel di
   `db_sewamotor`. Hati-hati: tabel yang sudah ada akan dilewati, tapi kalau
   database lokal masih kosong, data contoh akan dimasukkan otomatis.

Cara ini memakai database lokal yang sama dengan Laragon, jadi tidak mengubah
apa pun di sisi hosting.
