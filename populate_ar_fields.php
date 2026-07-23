<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$arFile = 'core/resources/lang/ar.json';
$arJson = json_decode(file_get_contents($arFile), true);

$frontends = Frontend::all();
$updatedCount = 0;

foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    $changed = false;
    
    $keysToTranslate = [];
    foreach ($vals as $k => $v) {
        // If the key is not an _ar key and is a string
        if (is_string($v) && substr($k, -3) !== '_ar' && $k !== 'has_image' && !str_contains($k, 'image') && !str_contains($k, 'icon')) {
            $keysToTranslate[$k] = $v;
        }
    }
    
    foreach ($keysToTranslate as $k => $v) {
        // Find if this english string has a translation
        if (isset($arJson[$v])) {
            $ar_val = $arJson[$v];
            if (!isset($vals[$k.'_ar']) || $vals[$k.'_ar'] !== $ar_val) {
                $vals[$k.'_ar'] = $ar_val;
                $changed = true;
            }
        }
    }
    
    if ($changed) {
        $f->data_values = $vals;
        $f->save();
        $updatedCount++;
    }
}

echo "Updated $updatedCount frontend records with _ar fields.\n";
?>
