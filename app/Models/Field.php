<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'location',
        'price',
        'image_url',
        'gallery',
        'category',
        'open_time',
        'close_time',
        'facilities',
        'capacity',
        'size',
        'surface',
        'promo',
        'owner_id',
        'is_available'
    ];
    
    protected $casts = [
        'facilities' => 'array',
        'gallery' => 'array',
        'promo' => 'boolean',
        'is_available' => 'boolean',
    ];
    
    protected $appends = ['rating'];
    
    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
