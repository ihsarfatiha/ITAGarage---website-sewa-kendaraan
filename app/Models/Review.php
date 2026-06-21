<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reviewable_id',
        'reviewable_type',
        'transaction_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Accessors
    public function getVehicleNameAttribute(): string
    {
        $reviewable = $this->reviewable;
        return $reviewable ? "{$reviewable->brand} {$reviewable->model}" : '-';
    }

    public function getVehicleTypeAttribute(): string
    {
        return $this->reviewable_type === 'App\\Models\\Motor' ? 'Motor' : 'Mobil';
    }

    public function getRatingStarsAttribute(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    // Scopes
    public function scopeForMotors($query)
    {
        return $query->where('reviewable_type', 'App\\Models\\Motor');
    }

    public function scopeForCars($query)
    {
        return $query->where('reviewable_type', 'App\\Models\\Car');
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }
}