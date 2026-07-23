@php
    $aboutContent = getContent('about.content', true);
    $aboutElements = getContent('about.element', false, null, true);
    $counterElements = getContent('counter.element', false, null, true);
@endphp
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row gy-5">
            <div class="col-xl-6">
                <div class="about-thumb  wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <img src="{{ frontendImage('about', @$aboutContent->data_values->image, '606x565') }}" alt="">
                </div>
            </div>
            <div class="col-xl-6 ps-lg-5">
                <h2 class="section-title mb-3">{{ __(@$aboutContent->data_values->heading) }}</h2>
                <p>{{ __(@$aboutContent->data_values->subheading) }}</p>
                <div class="row gy-4 mt-5">
                    @foreach (@$aboutElements as $about)
                        <div class="col-sm-6">
                            <div class="about-item wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                                <div class="about-item__icon">
                                    @php echo @$about->data_values->icon @endphp
                                </div>
                                <div class="about-item__content">
                                    <h5 class="mb-2">{{ __(@$about->data_values->title) }}</h5>
                                    <p>{{ __(@$about->data_values->content) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="line-area">
        <img src="{{ asset($activeTemplateTrue . 'images/elements/line.png') }}" alt="image">
        <div class="container">
            <div class="row">
                @foreach (@$counterElements as $counter)
                    <div class="col-sm-3 col-6 overview-single">
                        <div class="overview-item">
                            <h4 class="overview-item__number">{{ __(@$counter->data_values->counter_digit) }}</h4>
                            <p class="overview-item__caption mt-3">{{ __(@$counter->data_values->title) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
