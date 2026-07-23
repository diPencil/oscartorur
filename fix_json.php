<?php
$file = 'core/resources/views/admin/partials/sidenav.json';
$content = file_get_contents($file);

// Strip BOM
$bom = pack('H*','EFBBBF');
$content = preg_replace("/^$bom/", '', $content);

// Replace "seminar_plans" with "day_trip_plans"
$content = str_replace('"seminar_plans"', '"day_trip_plans"', $content);

// Replace remaining "Seminar" keyword
$content = str_replace('"Seminar",', '"Day Trip",', $content);

// Save back
file_put_contents($file, $content);
echo "Fixed sidenav.json and removed BOM.\n";
