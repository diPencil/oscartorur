<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Update pages table
$pages = [
    '/' => [
        'name' => 'Home',
        'seo_desc' => 'Oscar Tours offers the best tour packages, seminars, and hotel bookings in Egypt. Unforgettable luxury travel experiences await you.',
        'seo_keywords' => 'Egypt tours, Cairo hotels, Pyramids trips, Oscar Tours, luxury travel Egypt'
    ],
    'about' => [
        'name' => 'About Us',
        'seo_desc' => 'Learn more about Oscar Tours. We are your gateway to history and hospitality in Egypt, offering expert tour planning and luxury hotel reservations.',
        'seo_keywords' => 'About Oscar Tours, Egypt travel agency, best tours in Egypt'
    ],
    'tour-package' => [
        'name' => 'Tour Packages',
        'seo_desc' => 'Explore our exclusive tour packages in Egypt. From the Pyramids to the Red Sea, Oscar Tours brings you the ultimate vacation experiences.',
        'seo_keywords' => 'Egypt tour packages, Pyramids tours, Red sea trips, Oscar Tours packages'
    ],
    'seminar-package' => [
        'name' => 'Seminar Packages',
        'seo_desc' => 'Book your professional seminar packages in Egypt with Oscar Tours. We organize top-tier conferences and business events.',
        'seo_keywords' => 'Seminar packages Egypt, business events Cairo, corporate travel Egypt'
    ],
    'hotels' => [
        'name' => 'Hotels',
        'seo_desc' => 'Find and book the best luxury hotels in Egypt with Oscar Tours. Exceptional stays and unbeatable prices.',
        'seo_keywords' => 'Egypt hotels, Cairo luxury hotels, book hotels Egypt, Oscar Tours hotels'
    ],
];

foreach ($pages as $slug => $data) {
    $page = DB::table('pages')->where('slug', $slug)->first();
    if ($page) {
        $seo_content = json_decode($page->seo_content, true) ?? [];
        $seo_content['description'] = $data['seo_desc'];
        $seo_content['social_description'] = $data['seo_desc'];
        $seo_content['keywords'] = explode(', ', $data['seo_keywords']);
        $seo_content['social_title'] = $data['name'] . ' - Oscar Tours';
        
        DB::table('pages')->where('slug', $slug)->update([
            'name' => $data['name'],
            'seo_content' => json_encode($seo_content)
        ]);
    }
}

// Update global SEO data in frontends
$seoData = DB::table('frontends')->where('data_keys', 'seo.data')->first();
if ($seoData) {
    $data = json_decode($seoData->data_values, true);
    $data['keywords'] = ['Egypt tours', 'Cairo hotels', 'Pyramids trips', 'Oscar Tours', 'برامج سياحية في مصر', 'حجز فنادق', 'رحلات سياحية', 'أوسكار تورز'];
    $data['description'] = 'Oscar Tours offers the best tour packages, seminars, and hotel bookings in Egypt. Unforgettable luxury travel experiences await you.';
    $data['social_title'] = 'Oscar Tours - The Magic of Egypt';
    $data['social_description'] = 'Oscar Tours offers the best tour packages, seminars, and hotel bookings in Egypt. Unforgettable luxury travel experiences await you.';
    DB::table('frontends')->where('data_keys', 'seo.data')->update(['data_values' => json_encode($data)]);
}

echo "SEO updated successfully.\n";
