<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
      protected $fillable = [
        'field_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_date',
        'transaction_id',
        'payment_token',
        'booking_code',
        'paid_at',
    ];

    protected $casts = [
        'date' => 'date',
        'payment_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if booking can be reviewed
     */
    public function canBeReviewed()
    {
        return $this->status === 'completed' && !$this->hasReview();
    }

    /**
     * Check if booking has been reviewed
     */
    public function hasReview()
    {
        return $this->review()->exists();
    }

    /**
     * Scope to get completed bookings
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get reviewable bookings (completed but not reviewed)
     */
    public function scopeReviewable($query)
    {
        return $query->where('status', 'completed')
                    ->whereDoesntHave('review');
    }
}
