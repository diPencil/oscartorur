<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\RoomType::where('base_inventory', 0)->update(['base_inventory' => 1]);
echo "Updated!";
