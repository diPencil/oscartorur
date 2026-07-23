<?php
$content = file_get_contents('core/resources/views/admin/partials/sidenav.json');
function json_clean_decode($json) {
    // Try to find the error position
    // We can also just print the whole string.
    return json_decode($json);
}
// Let's just output the first few chars around the error? 
// No, PHP's json doesn't give line numbers.
// But we can lint it using standard tools.
