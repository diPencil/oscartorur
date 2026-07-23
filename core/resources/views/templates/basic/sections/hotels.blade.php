@php
    $hotels = \App\Models\Hotel::active()->with('location')->latest()->take(6)->get();
@endphp

<section class="pt-100 pb-100 bg_img white--overlay" style="background-color: #f7f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-10">
                <div class="section-header text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <h2 class="section-title">@lang('Top Featured Hotels')</h2>
                    <p class="mt-3">@lang('Discover the perfect accommodation for your next getaway with our handpicked selection of premium hotels')</p>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse ($hotels as $hotel)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <div class="trip-card h-100 hotel-card-custom">
                        <div class="trip-card__thumb">
                            <div class="hotel-image-carousel-home">
                                @if($hotel->images->count() > 0)
                                    @foreach($hotel->images as $img)
                                        <div>
                                            <a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}" class="w-100 h-100">
                                                <img src="{{ getImage(getFilePath('hotelImage') . '/' . $img->image) }}" alt="{{ __($hotel->name) }}" class="w-100" style="height: 250px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div>
                                        <a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}" class="w-100 h-100">
                                            <img src="{{ getImage(getFilePath('hotelImage') . '/default.png') }}" alt="{{ __($hotel->name) }}" class="w-100" style="height: 250px; object-fit: cover;">
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="trip-card__price" style="z-index: 2;"><span class="fs--14px"></span> {{ showAmount($hotel->starting_price) }} {{ gs('cur_text') }}</div>
                        </div>
                        <div class="trip-card__content p-4">
                            <h5 class="trip-card__title"><a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}">{{ __($hotel->name) }}</a></h5>
                            <ul class="trip-card__meta mt-2">
                                <li>
                                    <i class="las la-map-marked-alt"></i>
                                    <p>{{ __(@$hotel->location->name) }}</p>
                                </li>
                                <li>
                                    <i class="las la-star text-warning"></i>
                                    <p>{{ $hotel->star_rating }} @lang('Stars')</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                @include($activeTemplate . 'partials.empty', ['message' => 'No hotels found!'])
            @endforelse
        </div>

        <div class="text-center mt-5 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">
            <a href="{{ route('hotels') }}" class="btn btn--base">@lang('View All Hotels')</a>
        </div>
    </div>
</section>

@push('style')
<style>
    /* Carousel Arrows Styling for Homepage */
    .hotel-image-carousel-home {
        position: relative;
    }
    .hotel-image-carousel-home .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 5;
        background: rgba(255, 255, 255, 0.7);
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex !important;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
        color: #333;
    }
    .hotel-card-custom:hover .hotel-image-carousel-home .slick-arrow {
        opacity: 1;
    }
    .hotel-image-carousel-home .slick-prev {
        left: 10px;
    }
    .hotel-image-carousel-home .slick-next {
        right: 10px;
    }
    .hotel-image-carousel-home .slick-arrow:hover {
        background: #fff;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            if ($('.hotel-image-carousel-home').length) {
                $('.hotel-image-carousel-home').not('.slick-initialized').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: true,
                    arrows: true,
                    prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>'
                });
            }
        });
    })(jQuery);
</script>
@endpush
