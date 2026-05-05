<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'dorm_listing_id',
        'listing_id',
        'is_read',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */


    protected $casts = [
        'is_read' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(DormListing::class, 'dorm_listing_id');
    }

    public function legacyListing(): BelongsTo
    {
        return $this->belongsTo(DormListing::class, 'listing_id');
    }

    public function getResolvedListingAttribute()
    {
        return $this->listing ?? $this->legacyListing;
    }
}