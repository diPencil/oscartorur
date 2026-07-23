@php
    $plansContent = getContent('tour_plans.content', true);
    $plans = \App\Models\Plan::active()->with('location', 'category')->latest()->take(12)->get();
@endphp

<section class="pt-100 pb-100 bg_img white--overlay" style="background-image: url({{ frontendImage('tour_plans', @$plansContent->data_values->background_image, '1920x1280') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <h2 class="section-title">{{ __(@$plansContent->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$plansContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($plans as $plan)
                @include($activeTemplate . 'partials.plan', ['col' => 'col-sm-6 col-lg-4 col-xl-3'])
            @empty
                @include($activeTemplate . 'partials.empty', ['message' => 'Tour plan not found!'])
            @endforelse
        </div>
    </div>
</section>
