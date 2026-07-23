<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$hotels = \App\Models\Hotel::all();
foreach($hotels as $h) {
    echo "ID: $h->id, Name: {$h->name}, Name_ar: {$h->name_ar}\n";
}
