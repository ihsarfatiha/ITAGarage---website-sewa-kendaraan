<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'motor_id',
        'car_id',
        'transaction_type',
        'start_date',
        'end_date',
        'duration_days',
        'total_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Helper untuk get kendaraan
    public function getVehicleAttribute()
    {
        return $this->vehicle_type === 'motor' ? $this->motor : $this->car;
    }

    public function getVehicleNameAttribute()
    {
        $vehicle = $this->vehicle;
        return $vehicle ? "{$vehicle->brand} {$vehicle->model}" : '-';
    }
}