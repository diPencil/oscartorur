<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$frontends = Frontend::all();
$arabic_found = [];
foreach ($frontends as $f) {
    if (!$f->data_values) continue;
    $vals = (array)$f->data_values;
    foreach ($vals as $k => $v) {
        if (!is_string($v) || empty(trim($v))) continue;
        // Check if string contains arabic characters
        if (preg_match('/\p{Arabic}/u', $v)) {
            $arabic_found[$f->id . '-' . $k] = $v;
        }
    }
}
echo json_encode($arabic_found, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
