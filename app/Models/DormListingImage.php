<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DormListingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'dorm_listing_id',
        'path',
        'is_cover',
    ];

    /**
     * Each image belongs to a dorm listing
     */
    public function dormListing()
    {
        return $this->belongsTo(DormListing::class, 'dorm_listing_id');
    }
}