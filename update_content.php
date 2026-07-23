<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Update Banner
$banner = DB::table('frontends')->where('data_keys', 'banner.content')->first();
if ($banner) {
    $data = json_decode($banner->data_values, true);
    $data['heading'] = 'Discover the Magic of Egypt with Oscar Tours';
    $data['subheading'] = 'Unforgettable journeys, luxury stays, and seamless experiences await you.';
    DB::table('frontends')->where('data_keys', 'banner.content')->update(['data_values' => json_encode($data)]);
}

// Update About Us
$about = DB::table('frontends')->where('data_keys', 'about.content')->first();
if ($about) {
    $data = json_decode($about->data_values, true);
    $data['heading'] = 'Crafting Unforgettable Egyptian Experiences';
    $data['subheading'] = 'Your Gateway to History & Hospitality';
    $data['description'] = 'At Oscar Tours, we believe every journey should tell a story. With years of experience, we provide curated tour packages and top-tier hotel bookings designed for your comfort and joy.';
    DB::table('frontends')->where('data_keys', 'about.content')->update(['data_values' => json_encode($data)]);
}

// Update Footer
$footer = DB::table('frontends')->where('data_keys', 'footer.content')->first();
if ($footer) {
    $data = json_decode($footer->data_values, true);
    $data['description'] = 'Oscar Tours is your trusted partner for exploring Egypt. We provide exceptional tour packages, seminar arrangements, and luxury hotel bookings.';
    $data['email'] = 'info@oscartour.com';
    $data['phone'] = '+20 123 456 7890';
    DB::table('frontends')->where('data_keys', 'footer.content')->update(['data_values' => json_encode($data)]);
}

// Update How It Works
$howWork = DB::table('frontends')->where('data_keys', 'how_work.content')->first();
if ($howWork) {
    $data = json_decode($howWork->data_values, true);
    $data['heading'] = 'Your Journey Starts Here';
    $data['subheading'] = 'Simple steps to your dream vacation';
    DB::table('frontends')->where('data_keys', 'how_work.content')->update(['data_values' => json_encode($data)]);
}

// Update Testimonial
$testimonial = DB::table('frontends')->where('data_keys', 'testimonial.content')->first();
if ($testimonial) {
    $data = json_decode($testimonial->data_values, true);
    $data['heading'] = 'What Our Guests Say';
    $data['subheading'] = 'Real stories from travelers who explored Egypt with us';
    DB::table('frontends')->where('data_keys', 'testimonial.content')->update(['data_values' => json_encode($data)]);
}

// Update Subscribe
$subscribe = DB::table('frontends')->where('data_keys', 'subscribe.content')->first();
if ($subscribe) {
    $data = json_decode($subscribe->data_values, true);
    $data['heading'] = 'Join Our Newsletter';
    $data['subheading'] = 'Get exclusive offers and the latest news about our tours and hotels.';
    DB::table('frontends')->where('data_keys', 'subscribe.content')->update(['data_values' => json_encode($data)]);
}

// Update General Setting Site Name
DB::table('general_settings')->update(['site_name' => 'Oscar Tours']);

echo "Content updated successfully.\n";
