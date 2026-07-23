<?php
use App\Models\Frontend;
use App\Models\Page;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Update sections.json
$sectionsFile = 'core/resources/views/templates/basic/sections.json';
$sections = json_decode(file_get_contents($sectionsFile), true);

if (isset($sections['seminars'])) {
    $sections['day_trips'] = $sections['seminars'];
    $sections['day_trips']['name'] = 'Day Trips';
    unset($sections['seminars']);
}
if (isset($sections['seminar_breadcrumb'])) {
    $sections['day_trip_breadcrumb'] = $sections['seminar_breadcrumb'];
    $sections['day_trip_breadcrumb']['name'] = 'Day Trip Breadcrumb';
    unset($sections['seminar_breadcrumb']);
}
file_put_contents($sectionsFile, json_encode($sections, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Updated sections.json\n";

// 2. Update frontends table
$frontends = Frontend::all();
$updated_f = 0;
foreach ($frontends as $f) {
    if (strpos($f->data_keys, 'seminars.') === 0) {
        $f->data_keys = str_replace('seminars.', 'day_trips.', $f->data_keys);
        $f->save();
        $updated_f++;
    } elseif (strpos($f->data_keys, 'seminar_breadcrumb.') === 0) {
        $f->data_keys = str_replace('seminar_breadcrumb.', 'day_trip_breadcrumb.', $f->data_keys);
        $f->save();
        $updated_f++;
    }
}
echo "Updated $updated_f frontend records.\n";

// 3. Update pages table
$pages = Page::all();
$updated_p = 0;
foreach ($pages as $p) {
    if ($p->secs && strpos($p->secs, '"seminars"') !== false) {
        $p->secs = str_replace('"seminars"', '"day_trips"', $p->secs);
        $p->save();
        $updated_p++;
    }
}
echo "Updated $updated_p page records.\n";

// 4. Rename blade file
$oldBlade = 'core/resources/views/templates/basic/sections/seminars.blade.php';
$newBlade = 'core/resources/views/templates/basic/sections/day_trips.blade.php';
if (file_exists($oldBlade)) {
    rename($oldBlade, $newBlade);
    echo "Renamed seminars.blade.php to day_trips.blade.php\n";
}
?>
