<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id', 'amount', 'payment_method', 
        'payment_status', 'payment_date', 'payment_proof'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}