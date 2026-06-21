<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 15, 2);
        $table->enum('payment_method', ['cash', 'transfer', 'credit_card', 'e-wallet']);
        $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
        $table->date('payment_date');
        $table->string('payment_proof')->nullable();
        $table->timestamps();
    });
}
};
