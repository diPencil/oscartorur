<?php
function findInDir($dir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isFile()) {
            $ext = strtolower($item->getExtension());
            if (in_array($ext, ['php', 'html', 'css', 'js', 'json', 'txt', 'md', 'env', 'xml'])) {
                $content = file_get_contents($item->getPathname());
                if ($content === false) continue;
                
                if (stripos($content, 'viserlab') !== false) {
                    echo "Found ViserLab in: " . $item->getPathname() . "\n";
                    // echo an excerpt
                    preg_match('/.{0,30}viserlab.{0,30}/i', $content, $matches);
                    if (isset($matches[0])) {
                        echo "  Excerpt: " . trim($matches[0]) . "\n";
                    }
                }
            }
        }
    }
}

findInDir(__DIR__);
