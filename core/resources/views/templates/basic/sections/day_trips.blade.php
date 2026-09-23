@php
    $seminarsContent = getContent('day_trips.content', true);
    $seminars = \App\Models\Seminar::publiclyAvailable()->withCount('ratings')->latest()->take(10)->get();
@endphp

<section class="pt-100 pb-100 bg_img location-section white--overlay" style="background-image: url({{ frontendImage('day_trips', @$seminarsContent->data_values->background_image, '1920x1280') }});">
    <div class="container-fluid">
        <div class="row justify-content-xl-end justify-content-center">
            <div class="col-xl-3 col-lg-6 col-md-8 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="section-header text-xl-start text-center mb-0">
                    <h2 class="section-title">{{ __(@$seminarsContent->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$seminarsContent->data_values->subheading) }}</p>
                    <a class="btn btn--base mt-4" href="{{ route('seminars') }}">@lang('Discover All')</a>
                </div>
            </div>
            <div class="col-xxl-7 col-xl-9 ps-5">
                <div class="location-slider day-trip-slider">
                    @forelse ($seminars as $seminar)
                        <div class="single-slide">
                            <div class="location-card has--link rounded-3">
                                <a class="item--link" href="{{ route('seminar.details', [$seminar->id, slug($seminar->display_name)]) }}"></a>
                                <img src="{{ $seminar->display_image_url }}" alt="{{ $seminar->display_name }}">
                                <div class="overlay-content">
                                    <div class="d-flex flex-wrap align-items-end">
                                        <div class="col-6">
                                            <h4 class="location-name text-white">{{ $seminar->display_name }}</h4>
                                            <div class="ratings fs--14px mt-2">
                                                @php
                                                    $rating = $seminar->ratings()->avg('rating') + 0;
                                                @endphp
                                                <span class="rating-stars">
                                                    @php echo rating($rating); @endphp
                                                </span>
                                                <span class="text-white">({{ @$seminar->ratings_count }} @lang('reviews'))</span>
                                            </div>
                                        </div>
                                        <div class="col-6 text-end">
                                            <div class="location-card__price text-white">{{ showAmount($seminar->price) }}</div>
                                            <span class="text-white fs--14px">
                                                <i class="las la-clock fs--18px"></i>
                                                @if ($seminar->is_year_round)
                                                    @lang('Available all year')
                                                @else
                                                    {{ __($seminar->duration) }} @lang('Days')
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        @include($activeTemplate . 'partials.empty', ['message' => 'Seminar plan not found!'])
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function($) {
            "use strict";

            const isRtl = document.documentElement.dir === 'rtl';
            const slider = $('.day-trip-slider');

            if (slider.length) {
                if (slider.hasClass('slick-initialized')) {
                    slider.slick('unslick');
                }

                slider.slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: true,
                    arrows: true,
                    rtl: isRtl,
                    prevArrow: '<div class="prev"><i class="las ' + (isRtl ? 'la-angle-right' : 'la-angle-left') + '"></i></div>',
                    nextArrow: '<div class="next"><i class="las ' + (isRtl ? 'la-angle-left' : 'la-angle-right') + '"></i></div>',
                    responsive: [{
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 3,
                            },
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 2,
                            },
                        },
                        {
                            breakpoint: 520,
                            settings: {
                                slidesToShow: 1,
                            },
                        },
                    ],
                });
            }
        })(jQuery);
    </script>
@endpush
