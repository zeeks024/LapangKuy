<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view bookings list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Users can view their own bookings
        // Field owners can view bookings for their fields
        // Admins can view all bookings
        return $user->isAdmin() || 
               $user->id === $booking->user_id || 
               ($user->isFieldOwner() && $user->id === $booking->field->owner_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isUser() || $user->isFieldOwner() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Only the booking owner can update, and only if booking is pending
        return $user->id === $booking->user_id && $booking->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Users can cancel their own pending bookings
        // Admins can delete any booking
        return $user->isAdmin() || 
               ($user->id === $booking->user_id && $booking->status === 'pending');
    }

    /**
     * Determine whether the user can cancel the booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Users can cancel their own bookings if pending or confirmed
        // Field owners can cancel bookings for their fields
        // Admins can cancel any booking
        return $user->isAdmin() ||
               ($user->id === $booking->user_id && in_array($booking->status, ['pending', 'confirmed'])) ||
               ($user->isFieldOwner() && $user->id === $booking->field->owner_id);
    }

    /**
     * Determine whether the user can process payment for the booking.
     */
    public function pay(User $user, Booking $booking): bool
    {
        // Only the booking owner can pay
        if ($user->id !== $booking->user_id) {
            return false;
        }
        
        // Allow payment for unpaid bookings that are not cancelled or completed
        if ($booking->payment_status === 'unpaid' && 
            !in_array($booking->status, ['cancelled', 'completed'])) {
            return true;
        }
        
        // Allow re-payment for failed payments
        if (in_array($booking->payment_status, ['failed', 'expired']) && 
            !in_array($booking->status, ['cancelled', 'completed'])) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can update booking status.
     */
    public function updateStatus(User $user, Booking $booking): bool
    {
        // Field owners can update status for their field bookings
        // Admins can update any booking status
        return $user->isAdmin() ||
               ($user->isFieldOwner() && $user->id === $booking->field->owner_id);
    }
}
