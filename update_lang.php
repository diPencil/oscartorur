<?php
$file = 'core/resources/lang/ar.json';
$json = json_decode(file_get_contents($file), true);

$newTranslations = [
    'Hotel Name (Arabic)' => 'اسم الفندق (عربي)',
    'Address (Arabic)' => 'العنوان (عربي)',
    'Short Description (Arabic)' => 'وصف قصير (عربي)',
    'Full Description (Arabic)' => 'الوصف الكامل (عربي)',
    'Activation Readiness Checklist' => 'قائمة التحقق للتفعيل',
    'All Requirements Met!' => 'تم استيفاء جميع المتطلبات!',
    'This hotel is fully configured and ready to be activated on the platform.' => 'تم إعداد الفندق بالكامل وهو جاهز للتفعيل على المنصة.',
    'Please resolve the following issues to activate the hotel:' => 'يرجى حل المشكلات التالية لتفعيل الفندق:',
    'Room Types' => 'أنواع الغرف',
    'Amenities' => 'المرافق',
    'Contracts' => 'العقود',
    'Hotel Contracts' => 'عقود الفندق',
    'Manage Contracts' => 'إدارة العقود',
    'Select Hotel Amenities' => 'اختر مرافق الفندق',
    'Choose all the facilities and amenities available at this hotel.' => 'اختر جميع المرافق والخدمات المتاحة في هذا الفندق.',
    'Save Amenities' => 'حفظ المرافق',
    'English (Default)' => 'الإنجليزية (الافتراضية)',
    'Arabic (عربي)' => 'العربية (عربي)',
    'Manage Room Types' => 'إدارة أنواع الغرف',
    'Gallery Images' => 'صور المعرض',
    'Stars' => 'النجوم',
    'No Cover Image' => 'لا توجد صورة غلاف',
    'Current Status' => 'الحالة الحالية',
    'Save Changes' => 'حفظ التغييرات',
    'Property Type' => 'نوع العقار',
    'Star Rating' => 'تصنيف النجوم',
    'Country' => 'الدولة',
    'City/Location' => 'المدينة/الموقع',
    'Area (Optional)' => 'المنطقة (اختياري)',
    'Latitude' => 'خط العرض',
    'Longitude' => 'خط الطول',
    'Check-in Time' => 'وقت تسجيل الدخول',
    'Check-out Time' => 'وقت تسجيل الخروج',
    'Timezone' => 'المنطقة الزمنية',
    'Hotel Email' => 'البريد الإلكتروني للفندق',
    'Reservation Email' => 'البريد الإلكتروني للحجوزات',
    'Hotel Phone' => 'هاتف الفندق',
    'WhatsApp' => 'واتساب',
    'Website' => 'الموقع الإلكتروني',
    'Contact Person' => 'مسؤول التواصل',
    'Primary Supplier (Optional)' => 'المورد الأساسي (اختياري)',
    'Featured Hotel' => 'فندق مميز',
    'Hotel Name' => 'اسم الفندق',
    'Address' => 'العنوان',
    'Short Description' => 'وصف قصير',
    'Full Description' => 'الوصف الكامل',
    'Basic Info' => 'المعلومات الأساسية',
    'Images' => 'الصور',
    'Overview' => 'نظرة عامة',
    'Activate Hotel' => 'تفعيل الفندق',
    'Room Name' => 'اسم الغرفة',
    'Max Adults' => 'أقصى عدد للبالغين',
    'Max Children' => 'أقصى عدد للأطفال',
    'Status' => 'الحالة',
    'Cover Image' => 'صورة الغلاف',
    'Active' => 'مفعل',
    'Inactive' => 'غير مفعل',
    'Yes' => 'نعم',
    'No' => 'لا',
    'No room types added yet. Please manage room types to add one.' => 'لم تتم إضافة أنواع غرف بعد. يرجى إدارة أنواع الغرف لإضافة واحدة.',
    'Title' => 'العنوان',
    'Supplier' => 'المورد',
    'Inventory Mode' => 'نظام الحصص',
    'No contracts added yet. Please manage contracts to add one.' => 'لم تتم إضافة عقود بعد. يرجى إدارة العقود لإضافة واحد.',
];

foreach ($newTranslations as $key => $value) {
    if (!isset($json[$key])) {
        $json[$key] = $value;
    }
}

file_put_contents($file, json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "Translations added successfully.";
