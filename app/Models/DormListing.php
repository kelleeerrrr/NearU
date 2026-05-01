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
        'photos',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'wifi_included' => 'boolean',
        'pets_allowed' => 'boolean',
        'photos' => 'array',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_listings');
    }

    public function visitSchedules()
    {
        return $this->hasMany(VisitSchedule::class);
    }
}
