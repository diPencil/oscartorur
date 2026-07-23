@extends('Template::layouts.frontend')

@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <!-- Search Form -->
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form action="{{ route('hotel.search') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label">@lang('Destination / Hotel')</label>
                                <select name="location_id" class="form-select select2">
                                    <option value="">@lang('Anywhere')</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" @selected(isset($params['location_id']) && $params['location_id'] == $loc->id)>
                                            {{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">@lang('Check In')</label>
                                <input type="date" name="check_in" class="form-control" value="{{ $params['check_in'] ?? '' }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">@lang('Check Out')</label>
                                <input type="date" name="check_out" class="form-control" value="{{ $params['check_out'] ?? '' }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">@lang('Rooms')</label>
                                <input type="number" name="rooms" class="form-control" min="1" value="{{ $params['rooms'] ?? 1 }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">@lang('Adults')</label>
                                <input type="number" name="adults" class="form-control" min="1" value="{{ $params['adults'] ?? 2 }}" required>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn--base w-100"><i class="las la-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="row">
            <div class="col-lg-12">
                <h4 class="mb-4">{{ count($availableHotels) }} @lang('Hotels Found')</h4>
                
                @forelse($availableHotels as $result)
                    @php
                        $hotel = $result['hotel'];
                    @endphp
                    <div class="trip-card mb-4 hotel-card-custom" style="max-width: 100%;">
                          <div class="row g-0">
                              <div class="col-md-4 trip-card__thumb">
                                  <div class="hotel-image-carousel">
                                      @if($hotel->images->count() > 0)
                                          @foreach($hotel->images as $img)
                                              <div>
                                                  <a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}?check_in={{ $params['check_in'] }}&check_out={{ $params['check_out'] }}&rooms={{ $params['rooms'] }}&adults={{ $params['adults'] }}&children={{ $params['children'] ?? 0 }}" class="w-100 h-100">
                                                      <img src="{{ getImage(getFilePath('hotelImage').'/'.$img->image, getFileSize('hotelImage')) }}" class="w-100" style="height: 250px; object-fit: cover;" alt="{{ $hotel->name }}">
                                                  </a>
                                              </div>
                                          @endforeach
                                      @else
                                          <div>
                                              <a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}?check_in={{ $params['check_in'] }}&check_out={{ $params['check_out'] }}&rooms={{ $params['rooms'] }}&adults={{ $params['adults'] }}&children={{ $params['children'] ?? 0 }}" class="w-100 h-100">
                                                  <img src="{{ getImage(getFilePath('hotelImage').'/default.png') }}" class="w-100" style="height: 250px; object-fit: cover;" alt="{{ $hotel->name }}">
                                              </a>
                                          </div>
                                      @endif
                                  </div>
                                  <div class="trip-card__price" style="z-index: 2;"><span class="fs--14px"></span> {{ showAmount($result['starting_price']) }} {{ gs('cur_text') }}</div>
                              </div>
                              <div class="col-md-8">
                                  <div class="trip-card__content p-4 d-flex flex-column h-100 justify-content-center">
                                      <h5 class="trip-card__title"><a href="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}?check_in={{ $params['check_in'] }}&check_out={{ $params['check_out'] }}&rooms={{ $params['rooms'] }}&adults={{ $params['adults'] }}&children={{ $params['children'] ?? 0 }}">{{ __($hotel->name) }}</a></h5>
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
                                      <div class="mt-3 pt-3 border-top d-flex gap-2 text-muted">
                                          @foreach($hotel->amenities->take(5) as $amenity)
                                              <span style="font-size: 0.85rem;"><i class="{{ $amenity->icon }}"></i> {{ $amenity->name }}</span>
                                          @endforeach
                                          @if($hotel->amenities->count() > 5)
                                              <span style="font-size: 0.85rem;">+{{ $hotel->amenities->count() - 5 }} @lang('more')</span>
                                          @endif
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                @empty
                    <div class="alert alert-warning text-center">
                        <i class="las la-exclamation-triangle fs-1 d-block mb-2"></i>
                        <strong>@lang('No hotels found for the selected dates and criteria.')</strong><br>
                        @lang('Please try changing your search dates or parameters.')
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

@push('style')
<style>
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
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            if ($('.hotel-image-carousel').length) {
                $('.hotel-image-carousel').slick({
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

@endsection
