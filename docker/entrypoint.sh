#!/bin/sh
# Dijalankan otomatis setiap container start (Render / Railway / Fly / VPS).
set -e

cd /var/www/html

PORT="${PORT:-8080}"
echo "==> ITAGarage start di port ${PORT}"

# Hosting memberi port lewat variable PORT, jadi Apache harus ikut pindah
# dari port 80 ke port tersebut.
sed -ri "s/^Listen 80\$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Kalau APP_KEY belum diisi, aplikasi akan error 500. Kita buat sementara
# supaya deploy pertama tetap jalan, tapi tetap harus diisi permanen.
if [ -z "${APP_KEY}" ]; then
    echo "==> APP_KEY kosong, membuat key sementara."
    echo "    Isi APP_KEY di menu Environment hosting supaya login tidak reset tiap restart."
    [ -f .env ] || cp .env.example .env
    php artisan key:generate --force
fi

# Folder tulis + symlink storage (untuk gambar hasil upload Filament)
mkdir -p storage/app/public \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/logs \
         bootstrap/cache

php artisan storage:link || true

# Tunggu database siap, baru jalankan migrasi.
echo "==> Migrasi database..."
percobaan=0
until php artisan migrate --force --no-interaction; do
    percobaan=$((percobaan + 1))
    if [ "$percobaan" -ge 10 ]; then
        echo "!! Database tidak bisa dihubungi. Cek DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD."
        exit 1
    fi
    echo "   Database belum siap, coba lagi 5 detik... (${percobaan}/10)"
    sleep 5
done

# Isi data contoh, tapi hanya kalau tabel users masih kosong.
# Jadi aman walau container restart berkali-kali.
if [ "${RUN_SEEDER:-true}" = "true" ]; then
    php docker/seed-if-empty.php
fi

# Cache produksi
echo "==> Menyiapkan cache..."
php artisan config:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true

# config:cache penting supaya baca .env/environment cuma sekali.
php artisan config:cache

# routes/api.php masih pakai closure, jadi artisan route:cache akan menolak.
# Kegagalan di sini tidak masalah, website tetap jalan seperti biasa.
php artisan route:cache || echo "   route:cache dilewati (ada closure di routes/api.php) - tidak masalah."

php artisan view:cache || echo "   view:cache dilewati - tidak masalah."

php artisan filament:assets || true

echo "==> Siap. Menjalankan Apache..."
exec "$@"
