<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All users can view fields list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Field $field): bool
    {
        return true; // All users can view individual fields
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isFieldOwner();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Field $field): bool
    {
        // Field owners can update their own fields
        // Admins can update any field
        return $user->isAdmin() || 
               ($user->isFieldOwner() && $user->id === $field->owner_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Field $field): bool
    {
        // Field owners can delete their own fields (if no active bookings)
        // Admins can delete any field
        return $user->isAdmin() || 
               ($user->isFieldOwner() && $user->id === $field->owner_id);
    }

    /**
     * Determine whether the user can manage bookings for this field.
     */
    public function manageBookings(User $user, Field $field): bool
    {
        // Field owners can manage bookings for their fields
        // Admins can manage bookings for any field
        return $user->isAdmin() || 
               ($user->isFieldOwner() && $user->id === $field->owner_id);
    }

    /**
     * Determine whether the user can book this field.
     */
    public function book(User $user, Field $field): bool
    {
        // All authenticated users can book fields
        // Field owners cannot book their own fields
        return $user->isUser() || $user->isAdmin() || 
               ($user->isFieldOwner() && $user->id !== $field->owner_id);
    }
}
