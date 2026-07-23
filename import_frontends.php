<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$translated = json_decode(file_get_contents('frontend_db_ar.json'), true);
if (!$translated) {
    die("Could not read frontend_db_ar.json\n");
}

$frontends = Frontend::all();
$updated = 0;

foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    $changed = false;
    
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        
        // Find if this string was translated
        if (isset($translated[$v]) && $translated[$v] !== $v) {
            $vals[$k] = $translated[$v];
            $changed = true;
        }
    }
    
    if ($changed) {
        $f->data_values = $vals;
        $f->save();
        $updated++;
    }
}

echo "Updated $updated frontend records successfully.\n";
?>
