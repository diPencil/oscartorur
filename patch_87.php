<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$f = Frontend::find(87);
if ($f) {
    $vals = (array)$f->data_values;
    $vals['heading'] = 'Discover our exclusive Day Trips';
    $vals['subheading'] = 'Join our exclusive day trips combined with luxury travel experiences and world-class networking.';
    $f->data_values = $vals;
    $f->save();
    echo "Row 87 restored to English.\n";
}

$arFile = 'core/resources/lang/ar.json';
$arJson = json_decode(file_get_contents($arFile), true);
$arJson['Discover our exclusive Day Trips'] = 'اكتشف رحلاتنا اليومية الحصرية';
$arJson['Join our exclusive day trips combined with luxury travel experiences and world-class networking.'] = 'انضم إلى رحلاتنا اليومية الحصرية الممزوجة بتجارب السفر الفاخرة والشبكات ذات المستوى العالمي.';
file_put_contents($arFile, json_encode($arJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "ar.json updated.\n";
?>
