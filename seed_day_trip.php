<?php
use App\Models\Seminar;
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
    $category->name = 'Coastal Trips';
    $category->status = 1;
    $category->save();
}

$location = Location::where('status', 1)->where('name', 'Alexandria')->first();
if (!$location) {
    $location = new Location();
    $location->name = 'Alexandria';
    $location->name_ar = 'الإسكندرية';
    $location->status = 1;
    $location->save();
}

$seminar = new Seminar();
$seminar->category_id = $category->id;
$seminar->location_id = $location->id;
$seminar->name = 'Alexandria Coastal Day Trip';
$seminar->name_ar = 'رحلة يومية لعروس البحر الإسكندرية';
$seminar->map_latitude = '31.2001';
$seminar->map_longitude = '29.9187';
$seminar->duration = 1;
$seminar->start_time = Carbon::now()->addDays(7)->setTime(7, 0);
$seminar->end_time = Carbon::now()->addDays(7)->setTime(20, 0);
$seminar->capacity = 15;
$seminar->sold = 3;
$seminar->price = 100.00;
$seminar->images = [
    'https://images.unsplash.com/photo-1596719602568-d0dfd8736a54?q=80&w=1920&auto=format&fit=crop', // Qaitbay Citadel
    'https://images.unsplash.com/photo-1605335198904-8cbab890e03e?q=80&w=1920&auto=format&fit=crop', // Alexandria coastline
    'https://images.unsplash.com/photo-1600862088895-c9a93f1d8213?q=80&w=1920&auto=format&fit=crop'  // Alexandria library
];

$seminar->details = "Escape to the Pearl of the Mediterranean with our Alexandria Coastal Day Trip. Enjoy the fresh sea breeze as you explore the majestic Qaitbay Citadel built on the ruins of the ancient lighthouse, marvel at the modern architectural wonder of the Bibliotheca Alexandrina, and stroll through the beautiful Montazah Palace gardens.";
$seminar->details_ar = "اهرب إلى عروس البحر الأبيض المتوسط مع رحلتنا اليومية إلى الإسكندرية. استمتع بنسيم البحر المنعش أثناء استكشاف قلعة قايتباي المهيبة التي بُنيت على أنقاض الفنار القديم، وتأمل في العجائب المعمارية الحديثة لمكتبة الإسكندرية، وتجول في حدائق قصر المنتزه الجميلة.";

$seminar->included = ['Round-trip transportation', 'Professional guide', 'Entrance to Citadel and Library', 'Seafood lunch'];
$seminar->included_ar = ['مواصلات ذهاب وعودة', 'مرشد سياحي محترف', 'رسوم الدخول للقلعة والمكتبة', 'غداء مأكولات بحرية'];

$seminar->excluded = ['Personal shopping', 'Additional activities'];
$seminar->excluded_ar = ['التسوق الشخصي', 'أي أنشطة إضافية'];

$seminar->seminar_plan = [
    [
        'title' => 'Morning: The Citadel & Library',
        'subtitle' => 'History and Knowledge',
        'content' => 'We start our tour with a visit to the Qaitbay Citadel, followed by an inspiring tour inside the magnificent Bibliotheca Alexandrina.'
    ],
    [
        'title' => 'Afternoon: Montazah & Seafood',
        'subtitle' => 'Relaxation and Cuisine',
        'content' => 'After a delicious seafood lunch overlooking the Mediterranean, we will relax in the royal gardens of Montazah Palace before heading back.'
    ]
];

$seminar->seminar_plan_ar = [
    [
        'title' => 'الصباح: القلعة والمكتبة',
        'subtitle' => 'التاريخ والمعرفة',
        'content' => 'نبدأ جولتنا بزيارة قلعة قايتباي، يليها جولة ملهمة داخل مكتبة الإسكندرية الرائعة.'
    ],
    [
        'title' => 'بعد الظهر: المنتزه والمأكولات البحرية',
        'subtitle' => 'الاسترخاء والطعام',
        'content' => 'بعد تناول وجبة غداء لذيذة من المأكولات البحرية المطلة على البحر الأبيض المتوسط، سنسترخي في الحدائق الملكية لقصر المنتزه قبل العودة.'
    ]
];

$seminar->status = 1;
$seminar->save();

echo "Successfully created Day Trip (Seminar) ID: " . $seminar->id . "\n";
?>
