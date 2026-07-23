<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;

// Update blog.content
$blogContent = Frontend::where('data_keys', 'blog.content')->first();
if ($blogContent) {
    $values = $blogContent->data_values;
    $values->heading = "Our Travel Blog";
    $values->subheading = "Tips, guides, and stories for your next adventure in Egypt and beyond.";
    $blogContent->data_values = $values;
    $blogContent->save();
}

// Update blog.element
$blogs = Frontend::where('data_keys', 'blog.element')->get();
$blogTitles = [
    "Top 10 Hidden Gems in Cairo You Must Visit",
    "A Complete Guide to Cruising the Nile River",
    "Essential Tips for Booking Your First Seminar in Egypt",
    "How to Choose the Best Luxury Hotel for Your Vacation",
    "The Magic of the Pyramids: A Traveler's Diary"
];
$blogDescriptions = [
    "Cairo is full of surprises. While the Pyramids of Giza are a must-see, the city has hidden historical and cultural gems that most tourists miss. In this guide, we will walk you through the secret spots of old Cairo...",
    "Cruising the Nile is one of the most magical experiences in Egypt. From Luxor to Aswan, you will discover temples, tombs, and incredible landscapes. Here is what you need to know before booking your cruise...",
    "Attending a seminar in Egypt? Here is how you can combine business with leisure. Oscar Tours specializes in providing seminar attendees with exceptional travel packages and top-tier hotel accommodations...",
    "Finding the perfect hotel can make or break your trip. Whether you are looking for a resort by the Red Sea or a boutique hotel in downtown Cairo, we have compiled the ultimate checklist to help you decide...",
    "There is nothing quite like seeing the Great Pyramids for the first time. Read our traveler's diary to understand the majestic scale of these ancient wonders and get practical tips for your visit..."
];

foreach ($blogs as $index => $blog) {
    if (isset($blogTitles[$index])) {
        $values = $blog->data_values;
        $values->title = $blogTitles[$index];
        $values->description = $blogDescriptions[$index];
        $blog->data_values = $values;
        $blog->save();
    }
}

echo "Blogs updated successfully.\n";

?>
