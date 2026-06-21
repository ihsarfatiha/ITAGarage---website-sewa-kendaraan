<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Motor;
use App\Models\Car;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== CREATE USERS ==========
        $admin = User::create([
            'name' => 'Admin MotorKu',
            'email' => 'admin@motorku.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567891',
            'address' => 'Bandung, Indonesia',
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567892',
            'address' => 'Surabaya, Indonesia',
            'role' => 'user',
        ]);

        $user3 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
            'phone' => '081234567893',
            'address' => 'Medan, Indonesia',
            'role' => 'user',
        ]);

        // ========== MOTOR DATA ==========
        $motors = [
            [
                'brand' => 'Honda',
                'model' => 'Vario 125',
                'type' => 'matic',
                'year' => 2023,
                'color' => 'Merah',
                'license_plate' => 'B 1234 XYZ',
                'daily_rent_price' => 75000,
                'purchase_price' => 18000000,
                'stock' => 5,
                'description' => 'Motor matic paling laris dengan teknologi terbaru, irit dan nyaman',
                'status' => 'available',
            ],
            [
                'brand' => 'Yamaha',
                'model' => 'NMAX',
                'type' => 'matic',
                'year' => 2023,
                'color' => 'Hitam',
                'license_plate' => 'B 5678 ABC',
                'daily_rent_price' => 85000,
                'purchase_price' => 30000000,
                'stock' => 3,
                'description' => 'Motor matic premium dengan kenyamanan maksimal untuk touring',
                'status' => 'available',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Beat Street',
                'type' => 'matic',
                'year' => 2022,
                'color' => 'Putih',
                'license_plate' => 'B 9012 DEF',
                'daily_rent_price' => 65000,
                'purchase_price' => 16000000,
                'stock' => 4,
                'description' => 'Motor matic stylish untuk anak muda yang energik',
                'status' => 'available',
            ],
            [
                'brand' => 'Yamaha',
                'model' => 'R15',
                'type' => 'sport',
                'year' => 2023,
                'color' => 'Biru',
                'license_plate' => 'B 3456 GHI',
                'daily_rent_price' => 100000,
                'purchase_price' => 35000000,
                'stock' => 2,
                'description' => 'Motor sport berperforma tinggi untuk pecinta kecepatan',
                'status' => 'available',
            ],
            [
                'brand' => 'Suzuki',
                'model' => 'GSX-R150',
                'type' => 'sport',
                'year' => 2023,
                'color' => 'Merah',
                'license_plate' => 'B 7890 JKL',
                'daily_rent_price' => 110000,
                'purchase_price' => 38000000,
                'stock' => 2,
                'description' => 'Motor sport agresif dengan desain yang memukau',
                'status' => 'available',
            ],
            [
                'brand' => 'Honda',
                'model' => 'PCX 160',
                'type' => 'matic',
                'year' => 2023,
                'color' => 'Silver',
                'license_plate' => 'B 1122 MNO',
                'daily_rent_price' => 95000,
                'purchase_price' => 32000000,
                'stock' => 3,
                'description' => 'Motor matic premium dengan fitur lengkap',
                'status' => 'available',
            ],
        ];

        $createdMotors = [];
        foreach ($motors as $motor) {
            $createdMotors[] = Motor::create($motor);
        }

        // ========== MOBIL DATA ==========
        $cars = [
            [
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'body_type' => 'mpv',
                'transmission' => 'manual',
                'year' => 2023,
                'color' => 'Silver',
                'license_plate' => 'B 1111 AAA',
                'seats' => 7,
                'fuel_type' => 'bensin',
                'engine_capacity' => 1500,
                'daily_rent_price' => 350000,
                'purchase_price' => 220000000,
                'stock' => 3,
                'description' => 'MPV keluarga terpopuler di Indonesia, cocok untuk perjalanan keluarga',
                'features' => ['ac', 'power_steering', 'audio_system'],
                'status' => 'available',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Brio',
                'body_type' => 'hatchback',
                'transmission' => 'automatic',
                'year' => 2023,
                'color' => 'Merah',
                'license_plate' => 'B 2222 BBB',
                'seats' => 5,
                'fuel_type' => 'bensin',
                'engine_capacity' => 1200,
                'daily_rent_price' => 250000,
                'purchase_price' => 160000000,
                'stock' => 4,
                'description' => 'Mobil city car yang irit dan lincah untuk perkotaan',
                'features' => ['ac', 'power_steering', 'airbag', 'audio_system'],
                'status' => 'available',
            ],
            [
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'body_type' => 'mpv',
                'transmission' => 'automatic',
                'year' => 2023,
                'color' => 'Putih',
                'license_plate' => 'B 3333 CCC',
                'seats' => 7,
                'fuel_type' => 'bensin',
                'engine_capacity' => 1500,
                'daily_rent_price' => 400000,
                'purchase_price' => 280000000,
                'stock' => 2,
                'description' => 'MPV modern dengan desain stylish dan kabin luas',
                'features' => ['ac', 'power_steering', 'airbag', 'audio_system', 'bluetooth', 'parking_sensor'],
                'status' => 'available',
            ],
            [
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'body_type' => 'suv',
                'transmission' => 'automatic',
                'year' => 2022,
                'color' => 'Hitam',
                'license_plate' => 'B 4444 DDD',
                'seats' => 7,
                'fuel_type' => 'diesel',
                'engine_capacity' => 2400,
                'daily_rent_price' => 800000,
                'purchase_price' => 550000000,
                'stock' => 1,
                'description' => 'SUV tangguh untuk segala medan, cocok untuk adventure',
                'features' => ['ac', 'power_steering', 'airbag', 'abs', 'audio_system', 'bluetooth', 'parking_sensor', 'camera', 'leather_seats'],
                'status' => 'available',
            ],
            [
                'brand' => 'Honda',
                'model' => 'Civic',
                'body_type' => 'sedan',
                'transmission' => 'cvt',
                'year' => 2023,
                'color' => 'Abu-abu',
                'license_plate' => 'B 5555 EEE',
                'seats' => 5,
                'fuel_type' => 'bensin',
                'engine_capacity' => 1500,
                'daily_rent_price' => 600000,
                'purchase_price' => 480000000,
                'stock' => 2,
                'description' => 'Sedan sporty dengan performa tinggi dan desain elegant',
                'features' => ['ac', 'power_steering', 'airbag', 'abs', 'audio_system', 'bluetooth', 'parking_sensor', 'camera', 'sunroof'],
                'status' => 'available',
            ],
            [
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'body_type' => 'mpv',
                'transmission' => 'manual',
                'year' => 2022,
                'color' => 'Biru',
                'license_plate' => 'B 6666 FFF',
                'seats' => 7,
                'fuel_type' => 'bensin',
                'engine_capacity' => 1500,
                'daily_rent_price' => 300000,
                'purchase_price' => 210000000,
                'stock' => 3,
                'description' => 'MPV ekonomis dan efisien untuk keluarga',
                'features' => ['ac', 'power_steering', 'audio_system'],
                'status' => 'available',
            ],
        ];

        $createdCars = [];
        foreach ($cars as $car) {
            $createdCars[] = Car::create($car);
        }

        // ========== REVIEWS DATA ==========
        // Reviews untuk Motor
        $motorReviews = [
            // Honda Vario 125
            [
                'user_id' => $user1->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[0]->id,
                'rating' => 5,
                'comment' => 'Motor sangat irit dan nyaman! Cocok banget untuk harian. Recommended!',
            ],
            [
                'user_id' => $user2->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[0]->id,
                'rating' => 4,
                'comment' => 'Bagus, tapi agak bising di kecepatan tinggi. Overall puas!',
            ],
            // Yamaha NMAX
            [
                'user_id' => $user1->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[1]->id,
                'rating' => 5,
                'comment' => 'NMAX emang juara! Nyaman banget buat touring jauh. Fitur lengkap!',
            ],
            [
                'user_id' => $user3->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[1]->id,
                'rating' => 5,
                'comment' => 'Best scooter ever! Mantap pokoknya.',
            ],
            // Honda Beat Street
            [
                'user_id' => $user2->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[2]->id,
                'rating' => 4,
                'comment' => 'Stylish dan irit. Cocok buat anak muda!',
            ],
            // Yamaha R15
            [
                'user_id' => $user3->id,
                'reviewable_type' => 'App\Models\Motor',
                'reviewable_id' => $createdMotors[3]->id,
                'rating' => 5,
                'comment' => 'Motor sport kenceng banget! Handling mantap. Love it!',
            ],
        ];

        // Reviews untuk Car
        $carReviews = [
            // Toyota Avanza
            [
                'user_id' => $user1->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[0]->id,
                'rating' => 4,
                'comment' => 'Mobil keluarga yang reliable. Cukup nyaman untuk 7 orang.',
            ],
            [
                'user_id' => $user2->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[0]->id,
                'rating' => 5,
                'comment' => 'Sempurna untuk mudik! Bagasi luas, irit BBM. Rekomended!',
            ],
            // Mitsubishi Xpander
            [
                'user_id' => $user3->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[2]->id,
                'rating' => 5,
                'comment' => 'Xpander keren! Desain modern, kabin lapang. Mesin responsif!',
            ],
            [
                'user_id' => $user1->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[2]->id,
                'rating' => 4,
                'comment' => 'Overall bagus, cuma ground clearance agak rendah.',
            ],
            // Toyota Fortuner
            [
                'user_id' => $user2->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[3]->id,
                'rating' => 5,
                'comment' => 'SUV tangguh! Cocok banget buat off-road. Mesin diesel bertenaga!',
            ],
            // Honda Civic
            [
                'user_id' => $user3->id,
                'reviewable_type' => 'App\Models\Car',
                'reviewable_id' => $createdCars[4]->id,
                'rating' => 5,
                'comment' => 'Civic memang legend! Sporty, elegant, dan kencang. Best sedan!',
            ],
        ];

        // Insert all reviews
        $allReviews = array_merge($motorReviews, $carReviews);
        foreach ($allReviews as $review) {
            Review::create($review);
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 4 Users (1 Admin, 3 Customers)');
        $this->command->info('   - 6 Motors');
        $this->command->info('   - 6 Cars');
        $this->command->info('   - 12 Reviews');
    }
}