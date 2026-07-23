<?php
function replaceInDir($dir) {
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
                
                // Replace OscarTour with OscarTour (preserving case for the rest if possible, or just exact match)
                $content = str_ireplace('OscarTour', 'OscarTour', $content);
                // The user also mentioned Oscar Tour (with space), so maybe some places use "OscarTour"
                // Let's replace "All Right Reserved by <a href="https://dipencil.com/" target="_blank">Pencil Studio</a>"
                // It might be "All Rights Reserved" or "All Right Reserved"
                $content = preg_replace('/All\s+Rights?\s+Reserved\s+by\s+ViserLab(?:\s+LLC)?/i', 'All Right Reserved by <a href="https://dipencil.com/" target="_blank">Pencil Studio</a>', $content);
                // Also just ViserLab in copyrights
                // Sometimes it's written as ViserLab LLC without "All Right Reserved" but let's stick to the user's request.
                // Replace ViserLab with Pencil Studio as a fallback, but let's just do exactly what's requested for the copyright line.
                // It's safer to just replace 'ViserLab' with 'Pencil Studio' in general for copyright texts.
                
                if ($content !== $originalContent) {
                    file_put_contents($item->getPathname(), $content);
                    $filesModified++;
                }
            }
        }
    }
    echo "Modified $filesModified files.\n";
}

replaceInDir(__DIR__);
