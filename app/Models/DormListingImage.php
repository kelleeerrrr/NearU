<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DormListingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'dorm_listing_id',
        'path',        // ✅ FIXED (was image_path)
        'is_cover',
    ];

    /**
     * Each image belongs to a listing
     */
    public function listing()
    {
        return $this->belongsTo(DormListing::class, 'dorm_listing_id');
    }
}