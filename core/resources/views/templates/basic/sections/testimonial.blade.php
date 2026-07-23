@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElements = getContent('testimonial.element', orderById: true);
@endphp

<!-- testimonial section start -->
<section class="pt-100 pb-100 bg_img dark--overlay-two" style="background-image: url({{ frontendImage('testimonial', @$testimonialContent->data_values->image, '1920x660') }});">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <h2 class="section-title text-white">{{ __(@$testimonialContent->data_values->heading) }}</h2>
                    <p class="text-white mt-3">{{ __(@$testimonialContent->data_values->subheading) }}</p>
                </div>
            </div>
        </div><!-- row end -->
        <div class="testimonial-slider px-xl-5 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">

            @foreach (@$testimonialElements as $testimonial)
                <div class="single-slide">
                    <div class="testimonial-card bg_img" style="background-image: url('{{ getImage($activeTemplateTrue . 'images/bg/small-bg.jpg') }}');">
                        <p>{{ __(@$testimonial->data_values->review) }}</p>
                        <div class="client-details mt-3">
                            <h6 class="name">{{ __(@$testimonial->data_values->name) }}</h6>
                            <div class="ratings d-flex flex-wrap align-items-center mt-1">
                                @php
                                    echo rating(@$testimonial->data_values->rating);
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
