<?php
use App\Models\Frontend;
use App\Models\Page;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function replaceTranslations($file, $isAr) {
    if (!file_exists($file)) return;
    $ar = json_decode(file_get_contents($file), true);
    $newAr = [];
    
    $replaces_en = [
        'Seminar Packages' => 'Day Trips',
        'Seminar Package' => 'Day Trip',
        'Seminars' => 'Day Trips',
        'seminars' => 'day trips',
        'Seminar' => 'Day Trip',
        'seminar' => 'day trip'
    ];
    
    $replaces_ar = [
        'Seminar Packages' => 'برامج رحلات اليوم الواحد',
        'Seminar Package' => 'برنامج رحلة اليوم الواحد',
        'Seminars' => 'رحلات اليوم الواحد',
        'seminars' => 'رحلات اليوم الواحد',
        'Seminar' => 'رحلة اليوم الواحد',
        'seminar' => 'رحلة اليوم الواحد',
        'الندوات' => 'رحلات اليوم الواحد',
        'الندوة' => 'رحلة اليوم الواحد',
        'ندوات' => 'رحلات اليوم الواحد',
        'ندوة' => 'رحلة اليوم الواحد',
        'المؤتمرات' => 'رحلات اليوم الواحد',
        'مؤتمرات' => 'رحلات اليوم الواحد'
    ];
    
    foreach ($ar as $k => $v) {
        $new_v = $v;
        if ($isAr) {
            foreach ($replaces_ar as $search => $replace) {
                // If it's a full match for the key, set translation
                if (strtolower($k) == strtolower($search) && isset($replaces_ar[$k])) {
                    $new_v = $replaces_ar[$k];
                }
                $new_v = str_ireplace($search, $replace, $new_v);
            }
        } else {
            foreach ($replaces_en as $search => $replace) {
                $new_v = str_ireplace($search, $replace, $new_v);
            }
        }
        $newAr[$k] = $new_v;
    }
    
    file_put_contents($file, json_encode($newAr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Updated $file\n";
}

replaceTranslations('core/resources/lang/ar.json', true);
replaceTranslations('core/resources/lang/en.json', false);

// Pages table
$page = Page::where('name', 'like', '%Seminar%')->first();
if ($page) {
    $page->name = 'Day Trips';
    $page->save();
    echo "Updated Pages table\n";
}

// Frontends table
$frontends = Frontend::all();
$updated = 0;
foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    $changed = false;
    
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        
        $new_v = $v;
        $new_v = str_ireplace('Seminar Packages', 'Day Trips', $new_v);
        $new_v = str_ireplace('Seminar Package', 'Day Trip', $new_v);
        $new_v = str_ireplace('Seminars', 'Day Trips', $new_v);
        $new_v = str_ireplace('seminars', 'day trips', $new_v);
        $new_v = str_ireplace('Seminar', 'Day Trip', $new_v);
        $new_v = str_ireplace('seminar', 'day trip', $new_v);
        
        // Also fix arabic ones inside DB if any
        $new_v = str_replace(['الندوات', 'ندوات', 'المؤتمرات', 'مؤتمرات', 'الندوة', 'ندوة'], ['رحلات اليوم الواحد', 'رحلات اليوم الواحد', 'رحلات اليوم الواحد', 'رحلات اليوم الواحد', 'رحلة اليوم الواحد', 'رحلة اليوم الواحد'], $new_v);
        
        if ($new_v !== $v) {
            $vals[$k] = $new_v;
            $changed = true;
        }
    }
    
    if ($changed) {
        $f->data_values = $vals;
        $f->save();
        $updated++;
    }
}
echo "Updated $updated frontend records.\n";
?>
