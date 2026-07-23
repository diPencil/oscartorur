<?php
$json = json_decode(file_get_contents('core/resources/lang/ar.json'), true);
$c = 0;
foreach($json as $k => $v) {
    if ($k === $v) $c++;
}
echo "Untranslated keys: " . $c . " out of " . count($json) . "\n";
?>
