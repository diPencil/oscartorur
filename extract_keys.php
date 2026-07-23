<?php
$keys = [];
$dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('core/app/Http/Controllers'));

foreach ($dir as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Extract $pageTitle = '...'
        preg_match_all("/\\\$pageTitle\s*=\s*['\"](.*?)['\"]/", $content, $matches);
        foreach ($matches[1] as $m) {
            $keys[trim($m)] = trim($m);
        }
        
        // Extract $notify[] = ['type', 'message']
        preg_match_all("/\\\$notify\[\]\s*=\s*\[\s*['\"][a-zA-Z0-9_-]+['\"]\s*,\s*['\"](.*?)['\"]\s*\]/", $content, $matches);
        foreach ($matches[1] as $m) {
            $keys[trim($m)] = trim($m);
        }
    }
}

$arFilePath = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFilePath), true) ?? [];

$added = 0;
foreach ($keys as $k) {
    if (!empty($k) && !isset($ar[$k])) {
        $ar[$k] = $k;
        $added++;
    }
}

file_put_contents($arFilePath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Added $added new controller keys to ar.json.\n";
?>
