<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\DormListing;

$listing = DormListing::latest()->first();

if ($listing) {
    echo "=== DATABASE CHECK ===" . PHP_EOL;
    echo 'Furnishings type: ' . gettype($listing->furnishings) . PHP_EOL;
    echo 'Furnishings value: ' . var_export($listing->furnishings, true) . PHP_EOL;
    echo 'Appliances type: ' . gettype($listing->appliances) . PHP_EOL;
    echo 'Appliances value: ' . var_export($listing->appliances, true) . PHP_EOL;
    echo 'Bills Included type: ' . gettype($listing->bills_included) . PHP_EOL;
    echo 'Bills Included value: ' . var_export($listing->bills_included, true) . PHP_EOL;
    echo "===================" . PHP_EOL;
} else {
    echo 'No listings found in database' . PHP_EOL;
}
