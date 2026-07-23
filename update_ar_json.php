<?php
$arFile = __DIR__.'/core/resources/lang/ar.json';
$arData = json_decode(file_get_contents($arFile), true);

$translations = [
    // Banner
    'Discover the Magic of Egypt with Oscar Tours' => 'اكتشف سحر مصر مع أوسكار تورز!',
    'Unforgettable journeys, luxury stays, and seamless experiences await you.' => 'بنقدملك رحلات متتنسيش، إقامة مميزة، وتجربة سفر مريحة من أول يوم.',
    
    // About
    'Crafting Unforgettable Egyptian Experiences' => 'بنصنعلك أجمل ذكرياتك في مصر',
    'Your Gateway to History & Hospitality' => 'بوابتك للتاريخ وأصالة الضيافة',
    'At Oscar Tours, we believe every journey should tell a story. With years of experience, we provide curated tour packages and top-tier hotel bookings designed for your comfort and joy.' => 'في أوسكار تورز، إحنا مؤمنين إن كل رحلة وراها حكاية. بخبرتنا الطويلة في السياحة، بنوفرلك برامج سياحية متصممة مخصوص عشان راحتك، وحجوزات لأفضل الفنادق عشان تعيش أجمل اللحظات.',
    
    // How It Works
    'Your Journey Starts Here' => 'رحلتك بتبدأ من هنا',
    'Simple steps to your dream vacation' => 'خطوات بسيطة عشان تعيش أجازة أحلامك',
    
    // Testimonials
    'What Our Guests Say' => 'ضيوفنا بيقولوا إيه عننا؟',
    'Real stories from travelers who explored Egypt with us' => 'حكايات وتجارب حقيقية من ناس سافروا معانا واكتشفوا مصر',
    
    // Subscribe
    'Join Our Newsletter' => 'اشترك في النشرة البريدية',
    'Get exclusive offers and the latest news about our tours and hotels.' => 'عشان يوصلك أحدث العروض وأخبار الرحلات والفنادق بتاعتنا أول بأول.',
    
    // Footer
    'Oscar Tours is your trusted partner for exploring Egypt. We provide exceptional tour packages, seminar arrangements, and luxury hotel bookings.' => 'أوسكار تورز هي شريكك الموثوق لاكتشاف مصر. بنوفرلك باقات سياحية استثنائية، ترتيبات مؤتمرات، وحجوزات فنادق فخمة.',
    
    // Buttons & General
    'Book Now' => 'احجز رحلتك دلوقتي',
    'Learn More' => 'اعرف تفاصيل أكتر',
    'Contact Us' => 'تواصل معانا',
    'Home' => 'الرئيسية',
    'About' => 'عن الشركة',
    'Tour Package' => 'البرامج السياحية',
    'Seminar Package' => 'برامج المؤتمرات',
    'Hotels' => 'الفنادق',
    'Blog' => 'المدونة',
    'Contact' => 'تواصل معنا',
    'Hotels Search' => 'بحث الفنادق',
    'Hotels search page coming soon in Phase 4!' => 'صفحة حجز الفنادق هتكون متاحة قريباً في المرحلة الرابعة!',
    'We are currently revamping our backend systems to offer you an amazing OTA experience. Stay tuned!' => 'احنا حالياً بنطور أنظمتنا عشان نوفرلك تجربة حجز أونلاين مذهلة ومريحة جداً. خليك متابعنا!',
    'Back to Home' => 'الرجوع للرئيسية',
    'Tour Plans' => 'برامج الرحلات',
    'Search here...' => 'ابحث هنا...',
];

foreach ($translations as $key => $value) {
    $arData[$key] = $value;
}

file_put_contents($arFile, json_encode($arData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "ar.json updated successfully.\n";
