<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('motors', function (Blueprint $table) {
        $table->id();
        $table->string('brand');
        $table->string('model');
        $table->enum('type', ['matic', 'manual', 'sport']);
        $table->integer('year');
        $table->string('color');
        $table->string('license_plate')->unique();
        $table->decimal('daily_rent_price', 10, 2);
        $table->decimal('purchase_price', 15, 2);
        $table->integer('stock')->default(1);
        $table->text('description')->nullable();
        $table->string('image')->nullable();
        $table->enum('status', ['available', 'rented', 'sold'])->default('available');
        $table->timestamps();
    });
}
};
