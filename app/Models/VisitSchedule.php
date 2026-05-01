<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'dorm_listing_id',
        'visit_date',
        'visit_time',
        'notes',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (FIXED + IMPROVED)
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dormListing(): BelongsTo
    {
        return $this->belongsTo(DormListing::class);
    }
}