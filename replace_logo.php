<?php
function replaceLogoInDir($dir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $filesModified = 0;
    foreach ($iterator as $item) {
        if ($item->isFile()) {
            $ext = strtolower($item->getExtension());
            if (in_array($ext, ['php', 'html', 'css', 'js', 'json', 'txt', 'md', 'env', 'xml'])) {
                $content = file_get_contents($item->getPathname());
                if ($content === false) continue;
                
                $originalContent = $content;
                
                $content = str_replace(
                    'https://panel.dipencil.com/pencil-logo.png', 
                    'https://panel.dipencil.com/pencil-logo.png', 
                    $content
                );
                
                if ($content !== $originalContent) {
                    file_put_contents($item->getPathname(), $content);
                    $filesModified++;
                    echo "Modified: " . $item->getPathname() . "\n";
                }
            }
        }
    }
    echo "Total files modified: $filesModified\n";
}

replaceLogoInDir(__DIR__);
