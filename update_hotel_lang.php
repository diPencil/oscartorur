<?php
$arFile = __DIR__.'/core/resources/lang/ar.json';
$arData = json_decode(file_get_contents($arFile), true) ?? [];

$newStrings = [
    'Filter' => 'تصفية',
    'Search hotel name...' => 'ابحث عن اسم الفندق...',
    'Location' => 'المدينة',
    'Star Rating' => 'التقييم بالنجوم',
    'Starts from' => 'يبدأ من',
    'View Details' => 'عرض التفاصيل',
    'No hotels found!' => 'عفواً، لم يتم العثور على فنادق!',
    'Hotels Found' => 'فنادق متاحة',
    'per night' => 'في الليلة',
    'more' => 'المزيد',
    'Destination / Hotel' => 'الوجهة / الفندق',
    'Anywhere' => 'أي مكان',
    'Check In' => 'تسجيل الوصول',
    'Check Out' => 'المغادرة',
    'Rooms' => 'الغرف',
    'Adults' => 'البالغين',
    'Update' => 'تحديث',
    'Check Availability' => 'التحقق من التوافر',
    'Please enter your travel dates to see available rooms and prices.' => 'يرجى إدخال تواريخ الرحلة لرؤية الغرف المتاحة والأسعار.',
    'Available Rate Plans' => 'خطط الأسعار المتاحة',
    'Refundable' => 'مسترد',
    'Non-Refundable' => 'غير مسترد',
    'Payment' => 'الدفع',
    'Total for' => 'الإجمالي لـ',
    'room(s)' => 'غرفة',
    'Book Now' => 'احجز الآن',
    'Guest Details' => 'بيانات الضيوف',
    'Room' => 'غرفة',
    'Lead Guest' => 'الضيف الرئيسي',
    'Email Address' => 'البريد الإلكتروني',
    'Phone Number' => 'رقم الهاتف',
    'First Name' => 'الاسم الأول',
    'Last Name' => 'اسم العائلة'
];

foreach ($newStrings as $en => $ar) {
    $arData[$en] = $ar;
}

file_put_contents($arFile, json_encode($arData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Language files updated successfully.\n";
