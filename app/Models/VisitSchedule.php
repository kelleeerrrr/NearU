<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\DormListing;

class VisitSchedule extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TABLE FILLABLE FIELDS
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'dorm_listing_id',
        'visit_date',
        'visit_time',
        'notes',
        'status',
        'cancelled_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS (FIXED)
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'visit_date' => 'date',
        'cancelled_at' => 'datetime',
        // visit_time is TIME in DB, so keep it as string to avoid issues
        'visit_time' => 'string',
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
        return $this->belongsTo(DormListing::class, 'dorm_listing_id');
    }

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL: STATUS HELPERS (RECOMMENDED)
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING = 'Pending';
    const STATUS_CONFIRMED = 'Confirmed';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_CANCELLED = 'Cancelled';

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL: FORMATTER (nice for UI)
    |--------------------------------------------------------------------------
    */

    public function getFormattedTimeAttribute()
    {
        return date('h:i A', strtotime($this->visit_time));
    }
}