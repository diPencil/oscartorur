<?php
$file = 'assets/templates/basic/css/custom.css';
$content = file_get_contents($file);

// Remove the UTF-16 broken line
$content = preg_replace('/h t m l.*/', '', $content);
// Remove any existing .contact-thumb fix
$content = preg_replace('/html\[dir="rtl"\] \.contact-thumb \{.*?\}/s', '', $content);

$content = trim($content);

$fix = "\n\n/* Fix RTL Contact Thumb */\nhtml[dir=\"rtl\"] .contact-thumb { float: none !important; width: 100% !important; margin: 0 auto; }\n";
$content .= $fix;

file_put_contents($file, $content);
echo "Fixed custom.css";
?>
