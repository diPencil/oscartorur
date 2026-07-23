<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $admin = App\Models\Admin::first();
    if ($admin) {
        $admin->password = Hash::make('123456');
        $admin->save();
        echo "Password Reset to 123456 for username: " . $admin->username . "\n";
    } else {
        echo "Admin not found!\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
