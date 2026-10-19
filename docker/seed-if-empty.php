<?php

/**
 * Isi data contoh ke database, tapi hanya kalau tabel users masih kosong.
 *
 * Dipanggil oleh docker/entrypoint.sh setiap container start.
 * Tujuan: aman dijalankan berkali-kali tanpa bikin data ganda,
 * dan tidak bergantung pada parsing output `php artisan tinker`.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $jumlahUser = App\Models\User::count();
} catch (Throwable $e) {
    fwrite(STDERR, 'Gagal membaca tabel users: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

if ($jumlahUser > 0) {
    echo "   Database sudah berisi {$jumlahUser} user, seeder dilewati." . PHP_EOL;
    exit(0);
}

echo '   Database masih kosong, mengisi data contoh...' . PHP_EOL;

$exitCode = $kernel->call('db:seed', ['--force' => true]);

echo $kernel->output();

exit($exitCode);
