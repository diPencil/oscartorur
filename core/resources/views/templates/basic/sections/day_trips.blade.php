@php
    $seminarsContent = getContent('seminars.content', true);
    $seminars = \App\Models\Seminar::active()->withCount('ratings')->latest()->take(10)->get();
@endphp

<section class="pt-100 pb-100 bg_img location-section white--overlay" style="background-image: url({{ frontendImage('seminars', @$seminarsContent->data_values->background_image, '1920x1280') }});">
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
                <div class="location-slider">
                    @forelse ($seminars as $seminar)
                        <div class="single-slide">
                            <div class="location-card has--link rounded-3">
                                <a class="item--link" href="{{ route('seminar.details', [$seminar->id, slug($seminar->name)]) }}"></a>
                                <img src="{{ getImage(getFilePath('seminar') . '/' . @$seminar->images[0], getFileSize('seminar')) }}" alt="image">
                                <div class="overlay-content">
                                    <div class="d-flex flex-wrap align-items-end">
                                        <div class="col-6">
                                            <h4 class="location-name text-white">{{ __($seminar->name) }}</h4>
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
                                            <span class="text-white fs--14px"><i class="las la-clock fs--18px"></i> {{ __($seminar->duration) }} @lang('Days')</span>
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
