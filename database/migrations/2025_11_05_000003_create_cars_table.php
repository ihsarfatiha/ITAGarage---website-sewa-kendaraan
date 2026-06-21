<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->enum('body_type', ['sedan', 'suv', 'mpv', 'hatchback', 'pickup']);
            $table->enum('transmission', ['manual', 'automatic', 'cvt']);
            $table->integer('year');
            $table->string('color');
            $table->string('license_plate')->unique();
            $table->integer('seats');
            $table->enum('fuel_type', ['bensin', 'diesel', 'electric', 'hybrid']);
            $table->integer('engine_capacity')->comment('CC mesin');
            $table->decimal('daily_rent_price', 10, 2);
            $table->decimal('purchase_price', 15, 2);
            $table->integer('stock')->default(1);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('features')->nullable()->comment('AC, Power Steering, dll');
            $table->enum('status', ['available', 'rented', 'sold'])->default('available');
            $table->timestamps();
            
            $table->index(['brand', 'model']);
            $table->index('status');
            $table->index('body_type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
};