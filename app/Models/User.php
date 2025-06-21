<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'bio',
        'profile_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the profile image with fallback.
     *
     * @return string
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return $this->profile_image;
        }
        
        // Generate initials fallback or use default avatar
        $firstLetter = substr($this->name, 0, 1);
        return 'assets/images/default-avatar.png';
    }

    // Role checking methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a field owner
     * 
     * @return bool
     */
    public function isFieldOwner()
    {
        // Accept both 'owner' and legacy 'field_owner' values for backwards compatibility
        return $this->role === 'owner' || $this->role === 'field_owner';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    // Relationships
    public function ownedFields()
    {
        return $this->hasMany(Field::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
