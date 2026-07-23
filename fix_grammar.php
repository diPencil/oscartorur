<?php
use App\Models\Frontend;
use App\Models\Page;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fixes_ar = [
    'رحلات يوميةنا' => 'رحلاتنا اليومية',
    'رحلة يوميةنا' => 'رحلتنا اليومية',
    'الرحلات يومية' => 'الرحلات اليومية',
    'الرحلة يومية' => 'الرحلة اليومية',
    'Seminar plan not found!' => 'لم يتم العثور على برامج رحلات يومية!',
];

$fixes_en = [
    'Seminar plan not found!' => 'Day trip plan not found!'
];

// Fix ar.json
$arFile = 'core/resources/lang/ar.json';
if (file_exists($arFile)) {
    $ar = json_decode(file_get_contents($arFile), true);
    $newAr = [];
    foreach ($ar as $k => $v) {
        $new_v = $v;
        foreach ($fixes_ar as $bad => $good) {
            $new_v = str_replace($bad, $good, $new_v);
        }
        $newAr[$k] = $new_v;
    }
    // ensure missing key is added
    $newAr['Seminar plan not found!'] = 'لم يتم العثور على برامج رحلات يومية!';
    file_put_contents($arFile, json_encode($newAr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Fixed ar.json\n";
}

// Fix en.json
$enFile = 'core/resources/lang/en.json';
if (file_exists($enFile)) {
    $en = json_decode(file_get_contents($enFile), true);
    $newEn = [];
    foreach ($en as $k => $v) {
        $new_v = $v;
        foreach ($fixes_en as $bad => $good) {
            $new_v = str_replace($bad, $good, $new_v);
        }
        $newEn[$k] = $new_v;
    }
    $newEn['Seminar plan not found!'] = 'Day trip plan not found!';
    file_put_contents($enFile, json_encode($newEn, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "Fixed en.json\n";
}

// Fix DB Frontends
$frontends = Frontend::all();
$updated = 0;
foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    $changed = false;
    
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        
        $new_v = $v;
        foreach ($fixes_ar as $bad => $good) {
            $new_v = str_replace($bad, $good, $new_v);
        }
        
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
echo "Fixed $updated frontend records.\n";
?>
