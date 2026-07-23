<?php
use App\Models\Frontend;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cu = Frontend::where('data_keys', 'contact_us.content')->first();
if ($cu) {
    $vals = (array)$cu->data_values;
    $vals['email_address'] = 'info@oscartour.com';
    $vals['contact_number'] = '+201004816164';
    $cu->data_values = $vals;
    $cu->save();
}

$footer = Frontend::where('data_keys', 'footer.content')->first();
if ($footer) {
    $vals = (array)$footer->data_values;
    $vals['email'] = 'info@oscartour.com';
    $vals['phone'] = '+201004816164';
    $footer->data_values = $vals;
    $footer->save();
}

$fb = Frontend::where('data_keys', 'social_icon.element')->where('data_values', 'like', '%facebook%')->first();
if ($fb) {
    $vals = (array)$fb->data_values;
    $vals['url'] = 'https://www.facebook.com/profile.php?id=61574442372029';
    $fb->data_values = $vals;
    $fb->save();
}

echo "Updated contact info successfully.\n";
?>
