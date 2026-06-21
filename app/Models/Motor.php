<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand',
        'model',
        'type',
        'year',
        'color',
        'license_plate',
        'daily_rent_price',
        'purchase_price',
        'stock',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'daily_rent_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    // Relationships
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'rentable');
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // Scopes
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeRented(Builder $query): Builder
    {
        return $query->where('status', 'rented');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->brand} {$this->model} ({$this->year})";
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }
}