<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Tambah vehicle_type
            $table->enum('vehicle_type', ['motor', 'car'])->after('user_id');
            
            // Tambah motor_id (nullable)
            $table->foreignId('motor_id')->nullable()->after('vehicle_type')->constrained()->cascadeOnDelete();
            
            // Tambah car_id (nullable)
            $table->foreignId('car_id')->nullable()->after('motor_id')->constrained()->cascadeOnDelete();
            
            // Index
            $table->index('vehicle_type');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['motor_id']);
            $table->dropForeign(['car_id']);
            $table->dropIndex(['vehicle_type']);
            $table->dropColumn(['vehicle_type', 'motor_id', 'car_id']);
        });
    }
};