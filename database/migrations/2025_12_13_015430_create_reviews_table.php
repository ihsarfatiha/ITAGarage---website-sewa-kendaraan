<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic relationship (otomatis buat index)
            $table->morphs('reviewable');
            
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('rating')->comment('Rating 1-5');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Index tambahan
            $table->index('rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};