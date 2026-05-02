<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DormListing extends Model
{
    protected $fillable = [
        'owner_id',
        'street',
        'type',
        'price',
        'gender_policy',
        'walk_minutes',
        'bathroom',
        'furnishings',
        'appliances',
        'bills_included',
        'curfew',
        'wifi_included',
        'pets_allowed',
        'nearby_landmarks',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'wifi_included' => 'boolean',
        'pets_allowed' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Owner
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Saved listings
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_listings');
    }

    // Visits
    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class);
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGES (REAL DATABASE SYSTEM)
    |--------------------------------------------------------------------------
    */

    // All images
    public function images()
    {
        return $this->hasMany(DormListingImage::class, 'dorm_listing_id');
    }

    // Cover image
    public function coverImage()
    {
        return $this->hasOne(DormListingImage::class, 'dorm_listing_id')
            ->where('is_cover', true);
    }

    // Helper: get first image safely
    public function getFirstImageUrlAttribute()
    {
        $image = $this->images()->first();

        return $image
            ? asset('storage/' . $image->image_path)
            : 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=400';
    }
}