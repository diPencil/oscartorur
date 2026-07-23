<?php

$filePath = __DIR__ . '/core/resources/lang/ar.json';
$translations = [];

if (file_exists($filePath)) {
    $json = file_get_contents($filePath);
    $translations = json_decode($json, true) ?? [];
}

$newTranslations = [
    "Dashboard" => "لوحة التحكم",
    "Tour Plan" => "البرامج السياحية",
    "Tour Plans" => "البرامج السياحية",
    "Seminar" => "الندوات",
    "Seminars" => "الندوات",
    "Hotels" => "الفنادق",
    "Support" => "الدعم الفني",
    "Account" => "حسابي",
    "Login" => "تسجيل الدخول",
    "Register" => "إنشاء حساب",
    "Logout" => "تسجيل الخروج",
    "Submit" => "إرسال",
    "View All" => "عرض الكل",
    "Total Tours" => "إجمالي الجولات",
    "Upcoming Tours" => "الجولات القادمة",
    "Total Seminars" => "إجمالي الندوات",
    "Upcoming Seminars" => "الندوات القادمة",
    "Total Hotel Bookings" => "إجمالي حجوزات الفنادق",
    "Upcoming Hotel Stays" => "الإقامات الفندقية القادمة",
    "Payment History" => "سجل المدفوعات",
    "Change Password" => "تغيير كلمة المرور",
    "Profile Setting" => "إعدادات الحساب",
    "Profile Settings" => "إعدادات الحساب",
    "Search" => "بحث",
    "My Bookings" => "حجوزاتي",
    "Open New Ticket" => "فتح تذكرة جديدة",
    "My Tickets" => "تذاكري",
    "Contact Us" => "تواصل معنا",
    "About" => "من نحن",
    "Home" => "الرئيسية",
    "Tour Log" => "سجل الجولات",
    "Seminar Log" => "سجل الندوات",
    "Plans" => "الباقات",
    "Status" => "الحالة",
    "Action" => "إجراء",
    "Actions" => "إجراءات",
    "Amount" => "المبلغ",
    "Date" => "التاريخ",
    "Pending" => "قيد الانتظار",
    "Confirmed" => "مؤكد",
    "Completed" => "مكتمل",
    "Cancelled" => "ملغي",
    "Search and Find" => "ابحث واختر",
    "Select the best package that suits your schedule and budget." => "اختر الباقة الأنسب لجدولك وميزانيتك.",
    "Book and Travel" => "احجز وسافر",
    "Enjoy and Relax" => "استمتع واسترخِ",
    "Book Now" => "احجز الآن",
    "Read More" => "اقرأ المزيد",
    "Welcome to" => "مرحباً بك في",
    "Username" => "اسم المستخدم",
    "Password" => "كلمة المرور",
    "Email Address" => "البريد الإلكتروني",
    "Mobile Number" => "رقم الهاتف",
    "First Name" => "الاسم الأول",
    "Last Name" => "اسم العائلة",
    "Country" => "الدولة",
    "City" => "المدينة",
    "Address" => "العنوان",
    "Zip Code" => "الرمز البريدي",
    "State" => "المنطقة",
    "Update Profile" => "تحديث الحساب",
    "Current Password" => "كلمة المرور الحالية",
    "New Password" => "كلمة المرور الجديدة",
    "Confirm Password" => "تأكيد كلمة المرور",
    "Gateway" => "بوابة الدفع",
    "Transaction" => "المعاملة",
    "Initiated" => "تاريخ الإنشاء",
    "Details" => "التفاصيل",
    "Booking Number" => "رقم الحجز",
    "Hotel" => "الفندق",
    "Check In" => "تسجيل الدخول (Check In)",
    "Check Out" => "تسجيل الخروج (Check Out)",
    "Rooms" => "الغرف",
    "Guests" => "الضيوف",
    "Total Price" => "السعر الإجمالي",
    "No hotel bookings found" => "لا توجد حجوزات فندقية",
    "Latest Hotel Bookings" => "أحدث حجوزات الفنادق"
];

// Merge and replace
foreach ($newTranslations as $key => $val) {
    $translations[$key] = $val;
}

// Ensure the json is pretty printed
$newJson = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($filePath, $newJson);

echo "Translations updated successfully.\n";

?>
