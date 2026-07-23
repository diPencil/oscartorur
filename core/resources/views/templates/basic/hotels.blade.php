@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-3">
                    <button class="action-sidebar-open"><i class="las la-sliders-h"></i> @lang('Filter')</button>
                    <div class="action-sidebar">
                        <button class="action-sidebar-close"><i class="las la-times"></i></button>
                        <form action="{{ route('hotels') }}" method="GET">
                            <div class="action-widget widget--shadow">
                                <h4 class="action-widget__title no-icon">@lang('Search')</h4>
                                <div class="action-widget__body">
                                    <input class="form--control form-control-sm" name="search" type="search" value="{{ request()->search }}" autocomplete="off" placeholder="@lang('Search hotel name...')">
                                </div>
                                
                                @php
                                    $selectedLocations = request('location_id', []);
                                    $selectedStars = request('stars', []);
                                @endphp

                                <h4 class="action-widget__title mt-4 no-icon">@lang('Location')</h4>
                                <div class="action-widget__body">
                                    @foreach (@$locations as $location)
                                        <div class="form-check d-flex justify-content-between">
                                            <div class="left">
                                                <input class="form-check-input" id="loc-{{ $location->id }}" name="location_id[]" type="checkbox" value="{{ $location->id }}" @if (in_array($location->id, $selectedLocations)) checked @endif>
                                                <label class="form-check-label" for="loc-{{ $location->id }}">
                                                    {{ __($location->name) }}
                                                </label>
                                            </div>
                                            <label class="fs--14px mt-1" for="loc-{{ $location->id }}">({{ $location->hotels()->active()->count() }})</label>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <h4 class="action-widget__title mt-4 no-icon">@lang('Star Rating')</h4>
                                <div class="action-widget__body">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <div class="form-check d-flex justify-content-between">
                                            <div class="left">
                                                <input class="form-check-input" id="star-{{ $i }}" name="stars[]" type="checkbox" value="{{ $i }}" @if (in_array($i, $selectedStars)) checked @endif>
                                                <label class="form-check-label" for="star-{{ $i }}">
                                                    @for ($s = 0; $s < $i; $s++)
                                                    <i class="las la-star text--warning"></i>
                                                    @endfor
                                                </label>
                                            </div>
                                        </div>
                                    @endfor
                                    <div class="col-12 mt-3">
                                        <button class="btn btn-sm btn--base w-100" type="submit"> <i class="las la-filter"></i> @lang('Filter')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm border">
                        <h5 class="mb-0" style="color: #0b2a5c; font-size: 1rem;"><i class="las la-hotel"></i> @lang('Showing') <span class="text-primary-brand fw-bold">{{ $hotels->count() }}</span> @lang('of') {{ $hotels->total() }} @lang('hotels')</h5>
                        <div class="layout-view-controls">
                            <button class="btn btn-sm btn-outline-secondary active layout-btn" data-layout="grid" title="@lang('Grid View')">
                                <i class="las la-th-large fs-5"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary layout-btn" data-layout="list" title="@lang('List View')">
                                <i class="las la-list fs-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row gy-4" id="hotel-results-container">
                        @forelse($hotels as $hotel)
                            <div class="hotel-card-column col-xl-4 col-sm-6">
                                <div class="trip-card h-100 hotel-card-custom">
                                    <div class="trip-card__thumb">
                                        <div class="hotel-image-carousel">
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
                            <div class="col-12 text-center">
                                @include($activeTemplate . 'partials.empty', ['message' => 'No hotels found!'])
                            </div>
                        @endforelse
                    </div>

                    <div class="text-end mt-5 pagination-md">
                        {{ paginateLinks($hotels) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(@$sections != null)
        @foreach(json_decode($sections) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection

@push('style')
<style>
    .hotel-card-custom {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .hotel-card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    /* Carousel Arrows Styling */
    .hotel-image-carousel {
        position: relative;
    }
    .hotel-image-carousel .slick-arrow {
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
    .hotel-card-custom:hover .hotel-image-carousel .slick-arrow {
        opacity: 1;
    }
    .hotel-image-carousel .slick-prev {
        left: 10px;
    }
    .hotel-image-carousel .slick-next {
        right: 10px;
    }
    .hotel-image-carousel .slick-arrow:hover {
        background: #fff;
    }
    /* Layout Toggle Button Styles */
    .layout-view-controls .btn.active {
        background-color: #0b2a5c;
        color: #fff;
        border-color: #0b2a5c;
    }
    
    /* List Layout Styles */
    .layout-list .hotel-card-column {
        width: 100% !important; 
    }
    @media (min-width: 768px) {
        .layout-list .hotel-card-custom {
            flex-direction: row !important;
            height: auto !important;
            align-items: center;
        }
        .layout-list .tour-card__thumb {
            width: 35% !important;
            height: 200px !important;
        }
        .layout-list .tour-card__content {
            width: 65% !important;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 1.5rem !important;
        }
        .layout-list .tour-card__thumb img {
            height: 200px !important;
            min-height: auto;
        }
        .layout-list hr {
            display: none;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $('.hotel-image-carousel').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: false,
                infinite: true,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="las la-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="las la-angle-right"></i></button>'
            });
            
            // Layout Toggle Logic
            var savedLayout = localStorage.getItem('hotelLayout') || 'grid';
            if (savedLayout === 'list') {
                $('#hotel-results-container').addClass('layout-list');
                $('.layout-btn').removeClass('active');
                $('.layout-btn[data-layout="list"]').addClass('active');
                setTimeout(function(){
                    $('.hotel-image-carousel').slick('setPosition');
                }, 100);
            }

            $('.layout-btn').on('click', function() {
                $('.layout-btn').removeClass('active');
                $(this).addClass('active');
                var layout = $(this).data('layout');
                localStorage.setItem('hotelLayout', layout);
                
                if(layout === 'list') {
                    $('#hotel-results-container').addClass('layout-list');
                } else {
                    $('#hotel-results-container').removeClass('layout-list');
                }
                
                // Recalculate Slick sliders because dimensions changed
                $('.hotel-image-carousel').slick('setPosition');
            });
        });
    })(jQuery);
</script>
@endpush
