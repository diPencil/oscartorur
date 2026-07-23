<?php

$files = [
    'core/resources/views/admin/reports.blade.php',
    'core/resources/views/admin/system/info.blade.php',
    'core/resources/views/admin/partials/sidenav.blade.php',
    'core/resources/views/admin/partials/topnav.blade.php',
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_ireplace('https://viserlab.com/support', '#', $content);
        $content = str_ireplace('ViserAdmin Version', 'System Version', $content);
        $content = str_ireplace('ViserAdmin', 'OscarAdmin', $content);
        $content = str_ireplace('ViserLab', 'Oscar Tours', $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}

$langs = ['core/resources/lang/ar.json', 'core/resources/lang/en.json'];
foreach ($langs as $lang) {
    if (file_exists($lang)) {
        $content = file_get_contents($lang);
        $content = str_ireplace('ViserAdmin Version', 'System Version', $content);
        $content = str_ireplace('نسخة ViserAdmin', 'إصدار النظام', $content);
        $content = str_ireplace('ViserAdmin', 'OscarAdmin', $content);
        file_put_contents($lang, $content);
        echo "Updated $lang\n";
    }
}
?>
