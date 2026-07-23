<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$frontends = Frontend::all();
$strings_to_translate = [];

foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        // skip images
        if (preg_match('/\.(png|jpg|jpeg|gif|svg)$/i', $v)) continue;
        // skip urls
        if (preg_match('/^https?:\/\//i', $v)) continue;
        // skip icons
        if (preg_match('/^<i class/i', $v)) continue;
        // skip numbers
        if (is_numeric($v)) continue;
        // skip boolean like '1' or '0'
        if ($v === '1' || $v === '0') continue;
        // skip emails
        if (filter_var($v, FILTER_VALIDATE_EMAIL)) continue;
        // skip phone numbers
        if (preg_match('/^\+?\d{8,}$/', preg_replace('/\s+/', '', $v))) continue;
        
        $strings_to_translate[$v] = $v;
    }
}

file_put_contents('frontend_db.json', json_encode($strings_to_translate, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Exported " . count($strings_to_translate) . " strings to frontend_db.json\n";
?>
