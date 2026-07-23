<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'hotel_contracts', 
    'hotel_suppliers', 
    'amenities', 
    'room_types', 
    'bed_types', 
    'contract_room_types', 
    'rate_plans', 
    'cancellation_policies', 
    'room_inventories', 
    'room_rates', 
    'markup_rules', 
    'hotel_bookings', 
    'agencies'
];

foreach($tables as $table) {
    echo $table . ': ';
    echo json_encode(\Illuminate\Support\Facades\Schema::getColumnListing($table)) . "\n";
}
