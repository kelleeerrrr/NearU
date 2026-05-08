<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class TestNotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Get user IDs
        $student = User::where('user_type', 'student')->first();
        $owner1 = User::where('user_type', 'owner')->where('email', 'maria@example.com')->first();
        $owner2 = User::where('user_type', 'owner')->where('email', 'pedro@example.com')->first();
        $verifiedOwner = User::where('user_type', 'owner')->where('email', 'verified@gmail.com')->first();

        if ($student) {
            // Student notifications
            Notification::create([
                'user_id' => $student->id,
                'title' => 'Welcome to NearU!',
                'message' => 'Thanks for joining NearU. Start exploring dorm listings near university.',
                'type' => 'welcome',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $student->id,
                'title' => 'New Listing Available',
                'message' => 'A new dorm listing near university has been added. Check it out!',
                'type' => 'listing',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $student->id,
                'title' => 'Visit Reminder',
                'message' => 'You have a scheduled visit tomorrow at 2:00 PM.',
                'type' => 'visit',
                'is_read' => true,
            ]);
        }

        if ($owner1) {
            // Owner notifications
            Notification::create([
                'user_id' => $owner1->id,
                'title' => 'New Inquiry',
                'message' => 'Someone is interested in your dorm listing on Jupiter Street.',
                'type' => 'inquiry',
                'is_read' => false,
            ]);

            Notification::create([
                'user_id' => $owner1->id,
                'title' => 'Visit Request',
                'message' => 'A student wants to visit your dorm listing.',
                'type' => 'visit',
                'is_read' => false,
            ]);
        }

        if ($owner2) {
            Notification::create([
                'user_id' => $owner2->id,
                'title' => 'Listing Saved',
                'message' => 'A student saved your dorm listing on Mars Avenue.',
                'type' => 'saved',
                'is_read' => true,
            ]);
        }

        if ($verifiedOwner) {
            Notification::create([
                'user_id' => $verifiedOwner->id,
                'title' => 'Verification Approved',
                'message' => 'Your account has been verified. You can now manage your listings.',
                'type' => 'verification',
                'is_read' => true,
            ]);
        }
    }
}