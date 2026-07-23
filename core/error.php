<?php
$lines = file(__DIR__ . '/storage/logs/laravel.log');
$last = array_slice($lines, -250);
foreach ($last as $line) {
    if (strpos($line, 'Exception') !== false || strpos($line, 'Error') !== false || strpos($line, 'error') !== false) {
        echo $line;
    }
}
