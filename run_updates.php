<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;

// Update banner.content
$banner = Frontend::where('data_keys', 'banner.content')->first();
if ($banner) {
    $values = $banner->data_values;
    $values->heading = "Discover the Magic of Travel with Oscar Tours";
    $values->subheading = "Unforgettable journeys, luxury hotel stays, and seamless seminar experiences await you.";
    $banner->data_values = $values;
    $banner->save();
}

// Update about.content
$about = Frontend::where('data_keys', 'about.content')->first();
if ($about) {
    $values = $about->data_values;
    $values->heading = "Crafting Unforgettable Experiences";
    $values->subheading = "Your Gateway to History & Hospitality";
    $values->description = "At Oscar Tours, we believe every journey should tell a story. With years of experience, we provide curated tour packages and top-tier hotel bookings designed for your comfort and joy.";
    $about->data_values = $values;
    $about->save();
}

// Update footer.content
$footer = Frontend::where('data_keys', 'footer.content')->first();
if ($footer) {
    $values = $footer->data_values;
    $values->heading = "Let's go travel the whole world";
    $values->content = "Oscar Tours is your trusted partner for exploring the world. We provide exceptional tour packages, seminar arrangements, and luxury hotel bookings.";
    $values->description = "Oscar Tours is your trusted partner for exploring the world. We provide exceptional tour packages, seminar arrangements, and luxury hotel bookings.";
    $values->email = "info@oscartours.com";
    $values->phone = "+20 123 456 7890";
    $footer->data_values = $values;
    $footer->save();
}

// Update contact_us.content
$contact = Frontend::where('data_keys', 'contact_us.content')->first();
if ($contact) {
    $values = $contact->data_values;
    $values->short_details = "Have a question about our tours or hotels? Reach out to the Oscar Tours team and we'll be happy to assist you.";
    $values->email_address = "info@oscartours.com";
    $values->contact_details = "Cairo, Egypt";
    $values->contact_number = "+20 123 456 7890";
    $contact->data_values = $values;
    $contact->save();
}

// Update tour_plans.content
$tour = Frontend::where('data_keys', 'tour_plans.content')->first();
if ($tour) {
    $values = $tour->data_values;
    $values->heading = "Best Packages to Complete Your Holiday Plan";
    $values->subheading = "Explore our curated tours designed for the perfect vacation. Enjoy comfortable stays and expertly guided trips.";
    $tour->data_values = $values;
    $tour->save();
}

// Update seminars.content
$seminars = Frontend::where('data_keys', 'seminars.content')->first();
if ($seminars) {
    $values = $seminars->data_values;
    $values->heading = "Discover our exclusive Seminars";
    $values->subheading = "Join our exclusive seminars combined with luxury travel experiences and world-class networking.";
    $seminars->data_values = $values;
    $seminars->save();
}

// Update counter.content (all instances)
$counters = Frontend::where('data_keys', 'counter.content')->get();
foreach ($counters as $c) {
    $values = $c->data_values;
    $values->subheading = "Stay updated with the latest news and announcements from Oscar Tours.";
    $c->data_values = $values;
    $c->save();
}

// Update how_work.element
$howWorks = Frontend::where('data_keys', 'how_work.element')->get();
$howWorkContents = [
    "Search our wide range of tours, hotels, and seminars tailored to your preferences.",
    "Select the best package that suits your schedule and budget.",
    "Book securely with Oscar Tours and enjoy your journey with complete peace of mind."
];
foreach ($howWorks as $index => $hw) {
    if (isset($howWorkContents[$index])) {
        $values = $hw->data_values;
        $values->content = $howWorkContents[$index];
        $hw->data_values = $values;
        $hw->save();
    }
}

// Update about.element
$aboutElements = Frontend::where('data_keys', 'about.element')->get();
$aboutTitles = ["Luxury Transport", "Top-Tier Hotels", "Best Prices", "100% Secure"];
$aboutContents = [
    "We provide comfortable and safe transportation for all our tours.",
    "Partnering with the best hotels to ensure a luxurious stay.",
    "Offering the most competitive prices without compromising quality.",
    "Your bookings and payments are completely secure with us."
];
foreach ($aboutElements as $index => $ae) {
    if (isset($aboutTitles[$index])) {
        $values = $ae->data_values;
        $values->title = $aboutTitles[$index];
        $values->content = $aboutContents[$index];
        $ae->data_values = $values;
        $ae->save();
    }
}

// Update testimonial.element
$testimonials = Frontend::where('data_keys', 'testimonial.element')->get();
$testContents = [
    "Oscar Tours provided an amazing experience. The hotel booking was seamless and the tour guide was extremely knowledgeable.",
    "Our family trip to Egypt was unforgettable, thanks to the well-organized itinerary by Oscar Tours. Highly recommended!",
    "Great customer support and very secure booking process. The seminar I attended was perfectly arranged.",
    "I've traveled with many agencies, but Oscar Tours stands out for their attention to detail and luxury accommodations.",
    "A wonderful experience from start to finish. The tour package was affordable yet luxurious.",
    "Everything was perfect! From the airport pickup to the hotel check-out, Oscar Tours took care of it all."
];
foreach ($testimonials as $index => $test) {
    if (isset($testContents[$index])) {
        $values = $test->data_values;
        $values->review = $testContents[$index];
        $test->data_values = $values;
        $test->save();
    }
}

echo "Frontend content updated successfully.\n";

?>
