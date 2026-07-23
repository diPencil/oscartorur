@php
    $howWorkContent = getContent('how_work.content', true);
    $howWorkElements = getContent('how_work.element', false, null, true);
@endphp

<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-10">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$howWorkContent->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach (@$howWorkElements as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="step-card wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                        <div class="step-card__content">
                            <div class="line-shape"><img src="{{ asset($activeTemplateTrue . 'images/elements/step-line-1.png') }}" alt="image"></div>
                            <div class="icon-image h2">
                                @php echo @$item->data_values->icon @endphp
                            </div>
                            <h3 class="mb-3">{{ __(@$item->data_values->title) }}</h3>
                            <p>{{ __(@$item->data_values->content) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
