<?php

namespace Database\Seeders;

use App\Models\DormListing;
use App\Models\DormListingImage;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        // Get existing dorm listings
        $dorms = DormListing::all();
        
        $sampleImages = [
            'images/dorm1.jpg',
            'images/dorm2.jpg', 
            'images/dorm3.jpg'
        ];

        foreach ($dorms as $index => $dorm) {
            // Add 2-3 images per dorm
            $imageCount = rand(2, 3);
            for ($i = 0; $i < $imageCount; $i++) {
                DormListingImage::create([
                    'dorm_listing_id' => $dorm->id,
                    'path' => $sampleImages[$i % 3],
                    'is_cover' => $i === 0, // First image is cover
                ]);
            }
        }
    }
}
