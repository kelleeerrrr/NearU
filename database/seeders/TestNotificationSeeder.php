<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class TestNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Create a test notification for student user (assuming ID 3 from seeder)
        Notification::create([
            'user_id' => 3, // student user
            'title' => 'Welcome to NearU!',
            'message' => 'Thanks for joining NearU. Start exploring dorm listings near campus.',
            'is_read' => false,
        ]);
    }
}