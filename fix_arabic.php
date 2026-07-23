<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Fix Hotels
$h1 = \App\Models\Hotel::find(1);
if ($h1) {
    $h1->name = 'Great Pyramids Hotel';
    $h1->save();
}

$h13 = \App\Models\Hotel::find(13);
if ($h13) {
    $h13->name = 'Sunrise Arabian Beach Resort';
    $h13->save();
}

// Check locations
$locations = \App\Models\Location::all();
foreach($locations as $l) {
    if (preg_match('/\p{Arabic}/u', $l->name)) {
        echo "Location {$l->id} has Arabic in name: {$l->name}\n";
    }
}

// Check plans
$plans = \App\Models\Plan::all();
foreach($plans as $p) {
    if (preg_match('/\p{Arabic}/u', $p->name)) {
        echo "Plan {$p->id} has Arabic in name: {$p->name}\n";
    }
}

// Check day trips (seminars)
$seminars = \App\Models\Seminar::all();
foreach($seminars as $s) {
    if (preg_match('/\p{Arabic}/u', $s->name)) {
        echo "Seminar {$s->id} has Arabic in name: {$s->name}\n";
    }
}

echo "Done fixing hotel names.\n";
