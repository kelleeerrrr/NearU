<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

use App\Http\Controllers\DormController;
use Illuminate\Http\Request;

// Create a test request to fix validation
$request = new Request([
    'street' => 'Neptune',
    'complete_address' => '24 blk 7',
    'price' => '2700',
    'type' => 'Bedspace',
    'bathroom' => 'Shared',
    'gender_policy' => 'Male',
    'walk_minutes' => '2',
    'furnishings' => ['Bed', 'Table', 'Chair'],
    'appliances' => ['Fan', 'Refrigerator', 'Stove'],
    'bills_included' => ['Electricity', 'Water', 'WiFi'],
    'curfew' => '12', // This should be converted to '12 AM'
    'nearby_landmarks' => 'Alfamart',
    'latitude' => '13.785299',
    'longitude' => '121.072201',
]);

// Test the curfew conversion logic
$curfewValue = is_numeric($request->curfew) ? ($request->curfew == '12' ? '12 AM' : $request->curfew . ' PM') : $request->curfew;

echo "Curfew conversion test:\n";
echo "Input: {$request->curfew}\n";
echo "Output: {$curfewValue}\n";
echo "This should match database enum: ['No curfew', '10 PM', '11 PM', '12 AM']\n";

// Test type validation
$allowedTypes = ['Room', 'Bedspace', 'Unit'];
$typeValid = in_array($request->type, $allowedTypes);

echo "\nType validation test:\n";
echo "Input: {$request->type}\n";
echo "Valid: " . ($typeValid ? 'Yes' : 'No') . "\n";
echo "Allowed: " . implode(', ', $allowedTypes) . "\n";
