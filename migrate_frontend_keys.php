<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;

$migrated = 0;

// 1. Migrate seminars.* keys to day_trips.*
$seminars = Frontend::where('data_keys', 'LIKE', 'seminars.%')->get();
foreach ($seminars as $f) {
    $f->data_keys = str_replace('seminars.', 'day_trips.', $f->data_keys);
    $f->save();
    $migrated++;
    echo "Migrated: seminars -> day_trips (id: {$f->id})\n";
}

// 2. Migrate seminar_breadcrumb.* keys to day_trip_breadcrumb.*
$crumbs = Frontend::where('data_keys', 'LIKE', 'seminar_breadcrumb.%')->get();
foreach ($crumbs as $f) {
    $f->data_keys = str_replace('seminar_breadcrumb.', 'day_trip_breadcrumb.', $f->data_keys);
    $f->save();
    $migrated++;
    echo "Migrated: seminar_breadcrumb -> day_trip_breadcrumb (id: {$f->id})\n";
}

// 3. Ensure image files exist in new folders (copy from old folders if needed)
$oldNewFolders = [
    'seminars' => 'day_trips',
    'seminar_breadcrumb' => 'day_trip_breadcrumb',
];
$base = __DIR__ . '/assets/images/frontend/';
foreach ($oldNewFolders as $old => $new) {
    if (is_dir($base . $old)) {
        if (!is_dir($base . $new)) {
            mkdir($base . $new, 0755, true);
        }
        foreach (glob($base . $old . '/*') as $file) {
            $dest = $base . $new . '/' . basename($file);
            if (!file_exists($dest)) {
                copy($file, $dest);
                echo "Copied: $old/" . basename($file) . " -> $new/\n";
            }
        }
    }
}

echo "\nDone. $migrated record(s) migrated.\n";
