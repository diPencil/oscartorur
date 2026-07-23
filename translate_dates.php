<?php
$arFilePath = 'core/resources/lang/ar.json';
$ar = json_decode(file_get_contents($arFilePath), true) ?? [];

$newKeys = [
    'Today' => 'اليوم',
    'Yesterday' => 'أمس',
    'Last 7 Days' => 'آخر 7 أيام',
    'Last 15 Days' => 'آخر 15 يوماً',
    'Last 30 Days' => 'آخر 30 يوماً',
    'This Month' => 'هذا الشهر',
    'Last Month' => 'الشهر الماضي',
    'Last 6 Months' => 'آخر 6 أشهر',
    'This Year' => 'هذا العام',
    'Apply' => 'تطبيق',
    'Cancel' => 'إلغاء',
    'Custom Range' => 'فترة مخصصة',
    'Su' => 'أ',
    'Mo' => 'إ',
    'Tu' => 'ث',
    'We' => 'أر',
    'Th' => 'خ',
    'Fr' => 'ج',
    'Sa' => 'س',
    'January' => 'يناير',
    'February' => 'فبراير',
    'March' => 'مارس',
    'April' => 'أبريل',
    'May' => 'مايو',
    'June' => 'يونيو',
    'July' => 'يوليو',
    'August' => 'أغسطس',
    'September' => 'سبتمبر',
    'October' => 'أكتوبر',
    'November' => 'نوفمبر',
    'December' => 'ديسمبر',
    'Payment' => 'الدفع',
    'USD' => 'دولار',
];

foreach ($newKeys as $en => $ar_text) {
    $ar[$en] = $ar_text;
}

file_put_contents($arFilePath, json_encode($ar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Dates translated successfully.\n";
?>
