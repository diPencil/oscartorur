<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roomTypes = \App\Models\RoomType::select('id', 'hotel_id', 'name', 'base_inventory')->get()->toArray();
print_r($roomTypes);
