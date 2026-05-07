<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ✅ Add missing model imports
use App\Models\DormListing;
use App\Models\Message;
use App\Models\SavedListing;
use App\Models\VisitSchedule;
use App\Models\VerificationDocument;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'user_type',
        'password',
        'profile_photo_path',
        'verification_status', // ✅ already correct
        'status',
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
     * Optional: Default values (VERY USEFUL)
     */
    protected $attributes = [
        'verification_status' => 'not_verified', // ✅ ensures default
    ];

    /**
     * Profile photo accessor
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }

    /**
     * Verification status accessor (for backward compatibility)
     */
    public function getIsVerifiedAttribute()
    {
        return $this->verification_status === 'approved';
    }

    /*
    |------------------------------------------------------------------
    | RELATIONSHIPS
    |------------------------------------------------------------------
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

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function savedListings()
    {
        return $this->hasMany(SavedListing::class);
    }

    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /*
    |------------------------------------------------------------------
    | VERIFICATION
    |------------------------------------------------------------------
    */

    public function verificationDocuments()
    {
        return $this->hasMany(VerificationDocument::class);
    }

    /*
    |------------------------------------------------------------------
    | HELPER METHODS (🔥 VERY USEFUL)
    |------------------------------------------------------------------
    */

    public function isOwner()
    {
        return $this->user_type === 'owner';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

        public function isNotVerified()
    {
        return $this->verification_status === 'not_verified';
    }

    public function isApproved()
    {
        return $this->verification_status === 'approved';
    }

    public function isUnderReview()
    {
        return $this->verification_status === 'under_review';
    }
}