<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create verified owner account (hardcoded)
        $verifiedOwner = User::create([
            'name' => 'Verified Owner',
            'email' => 'verified@gmail.com',
            'phone' => '09123456789',
            'user_type' => 'owner',
            'password' => bcrypt('12345678'),
            'verification_status' => 'approved',
        ]);

        // Create sample users
        $owner1 = User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'phone' => '09171234567',
            'user_type' => 'owner',
            'password' => bcrypt('password'),
        ]);

        $owner2 = User::create([
            'name' => 'Pedro Cruz',
            'email' => 'pedro@example.com',
            'phone' => '09182345678',
            'user_type' => 'owner',
            'password' => bcrypt('password'),
        ]);

        $student = User::create([
            'name' => 'Juan Dela Cruz',
            'email' => 'student@example.com',
            'phone' => '09193456789',
            'user_type' => 'student',
            'password' => bcrypt('password'),
        ]);

        // Create sample dorm listings
        \App\Models\DormListing::create([
            'owner_id' => $owner1->id,
            'street' => 'Jupiter Street, Blk 5',
            'type' => 'Room',
            'price' => 2800,
            'gender_policy' => 'Female',
            'walk_minutes' => 15,
            'bathroom' => 'Shared',
            'furnishings' => 'Bed, Cabinet, Study Table',
            'appliances' => 'Fan',
            'bills_included' => 'Water & WiFi',
            'curfew' => '10 PM',
            'wifi_included' => true,
            'pets_allowed' => false,
            'nearby_landmarks' => '7-11, Laundry shop',
            'latitude' => 14.5995,
            'longitude' => 120.9842,
            'status' => 'Available',
        ]);

        \App\Models\DormListing::create([
            'owner_id' => $owner2->id,
            'street' => 'Mars Avenue, Lot 12',
            'type' => 'Bedspace',
            'price' => 2200,
            'gender_policy' => 'Any',
            'walk_minutes' => 8,
            'bathroom' => 'Shared',
            'furnishings' => 'Bed, Locker',
            'appliances' => 'Fan, Rice Cooker',
            'bills_included' => 'All bills included',
            'curfew' => 'No curfew',
            'wifi_included' => true,
            'pets_allowed' => true,
            'nearby_landmarks' => 'Carinderia, Pharmacy',
            'latitude' => 14.6015,
            'longitude' => 120.9862,
            'status' => 'Available',
        ]);

        \App\Models\DormListing::create([
            'owner_id' => $owner1->id,
            'street' => 'Venus Street, Blk 8',
            'type' => 'Unit',
            'price' => 4500,
            'gender_policy' => 'Any',
            'walk_minutes' => 12,
            'bathroom' => 'Private',
            'furnishings' => 'Full furnishings',
            'appliances' => 'AC, Refrigerator, Washing Machine',
            'bills_included' => 'Water & Electricity',
            'curfew' => '11 PM',
            'wifi_included' => true,
            'pets_allowed' => false,
            'nearby_landmarks' => 'Mall, Fast food',
            'latitude' => 14.5985,
            'longitude' => 120.9822,
            'status' => 'Available',
        ]);
    }
}
