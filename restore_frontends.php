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

// Create reverse map: Arabic -> English
$reverse_map = [];
foreach ($translated as $en => $ar) {
    // If we changed grammar later (like Seminar -> رحلة يومية), the DB has the modified Arabic
    // Let's also include the modified Arabic strings in the reverse map
    $modified_ar = str_replace(['رحلات يوميةنا', 'رحلة يوميةنا', 'الرحلات يومية', 'الرحلة يومية'], ['رحلاتنا اليومية', 'رحلتنا اليومية', 'الرحلات اليومية', 'الرحلة اليومية'], $ar);
    $modified_ar = str_replace(['الندوات', 'ندوات', 'المؤتمرات', 'مؤتمرات', 'الندوة', 'ندوة', 'رحلات اليوم الواحد', 'رحلة اليوم الواحد'], ['رحلات يومية', 'رحلات يومية', 'رحلات يومية', 'رحلات يومية', 'رحلة يومية', 'رحلة يومية', 'رحلات يومية', 'رحلة يومية'], $modified_ar);
    
    // Also replace seminar to day trip in english string
    $en_mod = str_ireplace(['Seminar Packages', 'Seminar Package', 'Seminars', 'seminars', 'Seminar', 'seminar'], ['Day Trips', 'Day Trip', 'Day Trips', 'day trips', 'Day Trip', 'day trip'], $en);
    
    $reverse_map[$modified_ar] = $en_mod;
    $reverse_map[$ar] = $en_mod;
}

$frontends = Frontend::all();
$restored = 0;

foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    $changed = false;
    
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        
        // Find if this string is Arabic and in reverse map
        if (isset($reverse_map[$v])) {
            $vals[$k] = $reverse_map[$v];
            $changed = true;
        }
    }
    
    if ($changed) {
        $f->data_values = $vals;
        $f->save();
        $restored++;
    }
}

echo "Restored $restored frontend records to English.\n";

// Now add the translations to ar.json
$arFile = 'core/resources/lang/ar.json';
$arJson = json_decode(file_get_contents($arFile), true);
$added = 0;
foreach ($translated as $en => $ar) {
    $en_mod = str_ireplace(['Seminar Packages', 'Seminar Package', 'Seminars', 'seminars', 'Seminar', 'seminar'], ['Day Trips', 'Day Trip', 'Day Trips', 'day trips', 'Day Trip', 'day trip'], $en);
    
    $modified_ar = str_replace(['رحلات يوميةنا', 'رحلة يوميةنا', 'الرحلات يومية', 'الرحلة يومية'], ['رحلاتنا اليومية', 'رحلتنا اليومية', 'الرحلات اليومية', 'الرحلة اليومية'], $ar);
    $modified_ar = str_replace(['الندوات', 'ندوات', 'المؤتمرات', 'مؤتمرات', 'الندوة', 'ندوة', 'رحلات اليوم الواحد', 'رحلة اليوم الواحد'], ['رحلات يومية', 'رحلات يومية', 'رحلات يومية', 'رحلات يومية', 'رحلة يومية', 'رحلة يومية', 'رحلات يومية', 'رحلة يومية'], $modified_ar);
    
    if (!isset($arJson[$en_mod])) {
        $arJson[$en_mod] = $modified_ar;
        $added++;
    } else {
        // If it exists, update it to the modified ar
        $arJson[$en_mod] = $modified_ar;
    }
}

file_put_contents($arFile, json_encode($arJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Added/Updated $added translations to ar.json.\n";
?>
