<?php
$directories = ['core/resources/views/templates'];
$keys = [];

foreach ($directories as $directory) {
    if (!file_exists($directory)) continue;
    $dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($dir as $file) {
        if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) == 'php') {
            $content = file_get_contents($file->getPathname());
            
            // Match @lang('...') or @lang("...")
            preg_match_all('/@lang\([\'"](.+?)[\'"]\)/s', $content, $matches1);
            if (!empty($matches1[1])) {
                $keys = array_merge($keys, $matches1[1]);
            }
            
            // Match __(...) or __("...")
            preg_match_all('/__\([\'"](.+?)[\'"]\)/s', $content, $matches2);
            if (!empty($matches2[1])) {
                $keys = array_merge($keys, $matches2[1]);
            }
        }
    }
}

$keys = array_unique($keys);
$arFilePath = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFilePath), true) ?? [];

$addedCount = 0;
foreach ($keys as $key) {
    // Remove newlines and excess spaces from key just in case
    $key = trim(preg_replace('/\s+/', ' ', $key));
    if (empty($key)) continue;
    
    if (!isset($ar[$key])) {
        $ar[$key] = $key;
        $addedCount++;
    }
}

file_put_contents($arFilePath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Added $addedCount new frontend keys to ar.json.\n";
?>
