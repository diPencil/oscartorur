<?php
$arFilePath = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFilePath), true) ?? [];

$pages = ["Home", "About Us", "Tour Packages", "Seminar Packages", "Blog", "Contact", "Hotels"];
$addedCount = 0;
foreach ($pages as $key) {
    if (!isset($ar[$key])) {
        $ar[$key] = $key;
        $addedCount++;
    }
}

file_put_contents($arFilePath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Added $addedCount new page keys to ar.json.\n";
?>
