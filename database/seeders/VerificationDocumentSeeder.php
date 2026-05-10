<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VerificationDocument;
use Illuminate\Database\Seeder;

class VerificationDocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Get verified owner
        $owner = User::where('email', 'verified@gmail.com')->first();
        
        if ($owner) {
            // Create sample verification documents
            VerificationDocument::create([
                'user_id' => $owner->id,
                'type' => 'government_id',
                'file_path' => 'verification-documents/id-sample.jpg',
            ]);

            VerificationDocument::create([
                'user_id' => $owner->id,
                'type' => 'proof_of_ownership',
                'file_path' => 'verification-documents/id-sample.jpg',
            ]);

            VerificationDocument::create([
                'user_id' => $owner->id,
                'type' => 'property_photo',
                'file_path' => 'verification-documents/id-sample.jpg',
            ]);
        }
    }
}
