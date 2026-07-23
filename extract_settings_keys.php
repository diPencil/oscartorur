<?php
$settings = json_decode(file_get_contents('core/resources/views/admin/setting/settings.json'), true);
$arFile = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFile), true);

$added = 0;
foreach ($settings as $setting) {
    if (!isset($ar[$setting['title']])) {
        $ar[$setting['title']] = $setting['title'];
        $added++;
    }
    if (!isset($ar[$setting['subtitle']])) {
        $ar[$setting['subtitle']] = $setting['subtitle'];
        $added++;
    }
}

file_put_contents($arFile, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Added $added new keys to ar.json from settings.json\n";
?>
