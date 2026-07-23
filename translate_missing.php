<?php
$arFilePath = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFilePath), true) ?? [];

$newKeys = [
    'Data not found' => 'لم يتم العثور على بيانات',
    'Search...' => 'بحث...',
    'Search here...' => 'ابحث هنا...',
    'No search result found' => 'لم يتم العثور على نتائج بحث'
];

foreach ($newKeys as $en => $ar_text) {
    $ar[$en] = $ar_text;
}

file_put_contents($arFilePath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Missing dashboard terms translated successfully.\n";
?>
