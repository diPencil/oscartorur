<?php
require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Frontend;

// Update testimonial.element
$testimonials = Frontend::where('data_keys', 'testimonial.element')->get();

$names = [
    "Ahmed Youssef",
    "Sarah Jenkins",
    "Mahmoud Khaled",
    "Elena Rossi",
    "Omar Tariq",
    "Jessica Wong"
];

$reviews = [
    "رحلتي مع أسرتي إلى الغردقة كانت أكثر من رائعة. حجز الفندق كان ممتازاً والتنظيم من شركة أوسكار كان دقيقاً جداً. شكراً لاهتمامكم بأدق التفاصيل!",
    "The Nile Cruise from Luxor to Aswan was breathtaking. Our tour guide was incredibly knowledgeable about ancient Egyptian history. I can't wait to come back and book with you again.",
    "حضرت ندوة طبية في الإسكندرية وتم تنظيم الإقامة والانتقالات بالكامل عبر الشركة. كل شيء كان مرتباً واحترافياً لأبعد الحدود. تجربة ممتازة وتستحق التقييم الكامل.",
    "We booked our honeymoon trip to Sharm El-Sheikh through Oscar Tours. The resort selection was absolutely perfect and the snorkeling trip was the highlight of our vacation. 5 stars!",
    "تعامل راقي جداً والتزام بالمواعيد. حجزت رحلة لأسوان وفعلاً كانت من أجمل الرحلات، الفندق كان نظيف جداً والمرشد السياحي كان قمة في الاحترام.",
    "Organizing a corporate trip for 20 people seemed like a nightmare until we found Oscar Tours. They handled the hotel bookings, airport transfers, and even a lovely dinner by the Pyramids effortlessly."
];

$ratings = ["5", "5", "5", "5", "4.5", "5"];

foreach ($testimonials as $index => $test) {
    if (isset($names[$index])) {
        $values = $test->data_values;
        $values->name = $names[$index];
        $values->review = $reviews[$index];
        $values->rating = $ratings[$index];
        $test->data_values = $values;
        $test->save();
    }
}

echo "Testimonials updated successfully.\n";

?>
