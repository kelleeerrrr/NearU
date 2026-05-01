<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\VerificationDocument;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'password',
        'profile_photo_path',

        // ✅ ADD THIS (IMPORTANT)
        'verification_status',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function dormListings()
    {
        return $this->hasMany(DormListing::class, 'owner_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function savedListings()
    {
        return $this->hasMany(SavedListing::class);
    }

    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class);
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFICATION RELATIONSHIP (IMPORTANT)
    |--------------------------------------------------------------------------
    */

    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class);
    }
}