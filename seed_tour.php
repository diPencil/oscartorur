<?php
use App\Models\Plan;
use App\Models\Category;
use App\Models\Location;
use Carbon\Carbon;

require __DIR__.'/core/vendor/autoload.php';
$app = require_once __DIR__.'/core/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$category = Category::where('status', 1)->first();
if (!$category) {
    $category = new Category();
    $category->name = 'Cultural Tours';
    $category->status = 1;
    $category->save();
}

$location = Location::where('status', 1)->first();
if (!$location) {
    $location = new Location();
    $location->name = 'Cairo, Egypt';
    $location->status = 1;
    $location->save();
}

$plan = new Plan();
$plan->category_id = $category->id;
$plan->location_id = $location->id;
$plan->name = 'Historical Cairo & Pyramids Day Tour';
$plan->name_ar = 'رحلة يومية لتاريخ القاهرة وأهرامات الجيزة';
$plan->map_latitude = '29.9792';
$plan->map_longitude = '31.1342';
$plan->duration = 1;
$plan->departure_time = Carbon::now()->addDays(5)->setTime(8, 0);
$plan->return_time = Carbon::now()->addDays(5)->setTime(18, 0);
$plan->capacity = 25;
$plan->sold = 5;
$plan->price = 150.00;
$plan->images = [
    'https://images.unsplash.com/photo-1539650116574-8efeb43e2750?q=80&w=1920&auto=format&fit=crop', // Pyramids
    'https://images.unsplash.com/photo-1572252009286-268acec5ca0a?q=80&w=1920&auto=format&fit=crop', // Sphinx
    'https://images.unsplash.com/photo-1553913861-c0fddf2619ee?q=80&w=1920&auto=format&fit=crop'  // Cairo museum
];

$plan->details = "Experience the magic of ancient Egypt with our comprehensive Historical Cairo & Pyramids Day Tour. This full-day adventure will take you back in time to marvel at the Great Pyramids of Giza, the enigmatic Sphinx, and the stunning artifacts housed in the Egyptian Museum. Perfect for history enthusiasts and first-time visitors alike!";
$plan->details_ar = "اكتشف سحر مصر القديمة مع رحلتنا اليومية الشاملة لمعالم القاهرة التاريخية وأهرامات الجيزة. ستأخذك هذه المغامرة المليئة بالأحداث في رحلة عبر الزمن لتشاهد أهرامات الجيزة العظيمة، وأبو الهول الغامض، والقطع الأثرية المذهلة في المتحف المصري. هذه الرحلة مثالية لعشاق التاريخ والزوار الجدد على حد سواء!";

$plan->included = ['Air-conditioned vehicle', 'Expert tour guide', 'Entrance fees', 'Lunch at a local restaurant'];
$plan->included_ar = ['مركبة مكيفة بالكامل', 'مرشد سياحي خبير', 'رسوم الدخول للأماكن', 'غداء في مطعم محلي'];

$plan->excluded = ['Personal expenses', 'Gratuities', 'Any extra beverages'];
$plan->excluded_ar = ['المصاريف الشخصية', 'الإكراميات', 'أي مشروبات إضافية غير المشمولة'];

$plan->tour_plan = [
    [
        'title' => 'Morning: Giza Plateau',
        'subtitle' => 'The Great Pyramids & Sphinx',
        'content' => 'Start your day exploring the iconic Great Pyramids of Cheops, Chephren, and Mykerinos. Stand in awe of the Great Sphinx and visit the Valley Temple.'
    ],
    [
        'title' => 'Afternoon: Egyptian Museum',
        'subtitle' => 'Treasures of King Tutankhamun',
        'content' => 'After a traditional Egyptian lunch, head to the Egyptian Museum to witness the world\'s most extensive collection of pharaonic antiquities, including the golden mask of King Tut.'
    ]
];

$plan->tour_plan_ar = [
    [
        'title' => 'الصباح: هضبة الجيزة',
        'subtitle' => 'الأهرامات العظيمة وأبو الهول',
        'content' => 'ابدأ يومك باستكشاف أهرامات خوفو وخفرع ومنكاورع الشهيرة. قف بإعجاب أمام تمثال أبو الهول العظيم وقم بزيارة معبد الوادي الخاص بخفرع.'
    ],
    [
        'title' => 'بعد الظهر: المتحف المصري',
        'subtitle' => 'كنوز الملك توت عنخ آمون',
        'content' => 'بعد الاستمتاع بوجبة غداء مصرية تقليدية، توجه إلى المتحف المصري لمشاهدة أكبر مجموعة من الآثار الفرعونية في العالم، بما في ذلك القناع الذهبي للملك توت.'
    ]
];

$plan->status = 1;
$plan->save();

echo "Successfully created Tour Plan ID: " . $plan->id . "\n";
?>
