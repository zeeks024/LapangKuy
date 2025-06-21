<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'field_id',
        'user_id',
        'booking_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope to get reviews for a specific field
     */
    public function scopeForField($query, $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }

    /**
     * Scope to get reviews by rating
     */
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Get average rating for reviews
     */
    public static function averageRating($fieldId = null)
    {
        $query = static::query();
        
        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }
        
        return $query->avg('rating') ?? 0;
    }
}
