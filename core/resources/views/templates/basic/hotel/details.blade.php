@extends('Template::layouts.frontend')

@push('style')
<style>
    /* Hotel Details Redesign Styles */
    .hotel-header-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    .hotel-header-subtitle {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    .rating-badge {
        background-color: #28a745;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    /* Image Grid */
    .hotel-image-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 250px 250px;
        gap: 10px;
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .grid-img-main {
        grid-row: 1 / span 2;
    }
    
    .grid-right-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 250px 250px;
        gap: 10px;
    }
    
    .grid-img-item {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .grid-img-top-left, .grid-img-top-right {
        height: 100%;
    }
    
    .grid-img-bottom {
        grid-column: 1 / span 2;
        height: 100%;
    }
    
    /* Fix for Lightcase scrollbars and cut off images */
    #lightcase-content img {
        max-width: 100vw !important;
        max-height: 90vh !important;
        object-fit: contain !important;
    }

    @media (max-width: 768px) {
        .hotel-image-grid {
            grid-template-columns: 1fr;
            grid-template-rows: auto;
        }
        .grid-right-col {
            display: none; /* Hide secondary images on mobile for simplicity, or stack them */
        }
    }

    /* Facility Icons */
    .facility-box {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px 10px;
        text-align: center;
        min-width: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fff;
    }
    .facility-box i {
        font-size: 1.5rem;
        color: #555;
    }
    .facility-box span {
        font-size: 0.85rem;
        color: #333;
    }
    
    /* Sidebar */
    .sidebar-sticky {
        position: sticky;
        top: 150px;
    }
    
    .price-box {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 20px;
    }
    
    .price-amount {
        font-size: 1.8rem;
        font-weight: bold;
        color: #0056b3;
    }
    
    /* Room Card */
    .room-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .room-card-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
    }
    .room-rate-row {
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }
    .room-rate-row:last-child {
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        padding-bottom: 5px;
        color: #0b2a5c;
    }/* Utility */
    .text-primary-brand { color: #0056b3; }
    .bg-primary-brand { background-color: #0056b3; color: white; }
    .bg-primary-brand:hover { background-color: #004494; color: white; }
    
</style>
@endpush

@section('content')
@php
    $cheapestPrice = null;
    foreach($availableRooms as $roomData) {
        foreach($roomData['rate_plans'] as $rp) {
            if($cheapestPrice === null || $rp['total_price'] < $cheapestPrice) {
                $cheapestPrice = $rp['total_price'];
            }
        }
    }
@endphp

<section class="pt-5 pb-100 bg-white">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hotels') }}">@lang('Hotels')</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $hotel->name }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                @if(app()->getLocale() == 'ar')
                    <h1 class="hotel-header-title">{{ $hotel->name_ar ?: $hotel->name }}</h1>
                @else
                    <h1 class="hotel-header-title">{{ $hotel->name }}</h1>
                @endif
                
                <div class="d-flex align-items-center flex-wrap gap-3 mb-2">
                    <span class="rating-badge"><i class="las la-check-circle"></i> @lang('Excellent')</span>
                    <div class="text-warning">
                        @for($i = 0; $i < $hotel->star_rating; $i++)
                            <i class="las la-star"></i>
                        @endfor
                        <span class="text-dark ms-1 fw-bold">{{ $hotel->star_rating }}.0</span>
                    </div>
                </div>
                
                <p class="text-muted mb-2"><i class="las la-map-marker-alt text-primary-brand"></i> {{ $hotel->location->name }} - {{ app()->getLocale() == 'ar' ? ($hotel->address_ar ?: $hotel->address) : $hotel->address }}</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary rounded-pill me-2"><i class="las la-share-alt"></i> @lang('Share')</button>
                <button class="btn btn-outline-danger rounded-pill"><i class="lar la-heart"></i> @lang('Add to Favorites')</button>
            </div>
        </div>

        <!-- Images Grid -->
        <div class="hotel-image-grid">
            @php $images = $hotel->images; @endphp
            @if($images->isNotEmpty())
                <div class="grid-img-main">
                    <a href="{{ getImage(getFilePath('hotelImage').'/'.$images->first()->image) }}" data-rel="lightcase:hotelGallery" class="d-block w-100 h-100" title=" ">
                        <img src="{{ getImage(getFilePath('hotelImage').'/'.$images->first()->image, getFileSize('hotelImage')) }}" class="grid-img-item" alt="">
                    </a>
                </div>
                <div class="grid-right-col">
                    @php 
                        $topImages = $images->skip(1)->take(2);
                        $bottomImage = $images->skip(3)->first();
                        $hiddenImages = $images->skip(4);
                    @endphp
                    
                    @foreach($topImages as $img)
                        <div class="w-100 h-100">
                            <a href="{{ getImage(getFilePath('hotelImage').'/'.$img->image) }}" data-rel="lightcase:hotelGallery" class="d-block w-100 h-100" title=" ">
                                <img src="{{ getImage(getFilePath('hotelImage').'/'.$img->image, getFileSize('hotelImage')) }}" class="grid-img-item grid-img-top-left" alt="">
                            </a>
                        </div>
                    @endforeach
                    
                    @if($bottomImage)
                        <div class="grid-img-bottom w-100 h-100">
                            <a href="{{ getImage(getFilePath('hotelImage').'/'.$bottomImage->image) }}" data-rel="lightcase:hotelGallery" class="d-block w-100 h-100" title=" ">
                                <img src="{{ getImage(getFilePath('hotelImage').'/'.$bottomImage->image, getFileSize('hotelImage')) }}" class="grid-img-item" alt="">
                            </a>
                        </div>
                    @endif
                    
                    {{-- Hidden images for the gallery album to be complete --}}
                    @foreach($hiddenImages as $img)
                        <a href="{{ getImage(getFilePath('hotelImage').'/'.$img->image) }}" data-rel="lightcase:hotelGallery" class="d-none" title=" "></a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="row flex-column-reverse flex-lg-row mt-5">
            <!-- Main Content (Right in RTL, Left in LTR) -->
            <div class="col-lg-8 mb-4">
                
                <!-- Facilities -->
                <h4 class="mb-3">@lang('Facilities and Services')</h4>
                <div class="d-flex flex-wrap gap-2 mb-5 overflow-auto pb-2">
                    @forelse($hotel->amenities as $amenity)
                        <div class="facility-box"><i class="{{ $amenity->icon }}"></i> <span>{{ app()->getLocale() == 'ar' ? ($amenity->name_ar ?: $amenity->name) : $amenity->name }}</span></div>
                    @empty
                        <div class="text-muted">@lang('No facilities added yet.')</div>
                    @endforelse
                </div>

                <!-- Short Description -->
                @if($hotel->short_description || $hotel->short_description_ar)
                <h3 class="section-title mt-5">@lang('Overview')</h3>
                <div class="mb-5 text-secondary fs-5 lh-base">
                    {{ app()->getLocale() == 'ar' ? ($hotel->short_description_ar ?: $hotel->short_description) : $hotel->short_description }}
                </div>
                @endif
                
                <!-- Description Section -->
                <h3 class="section-title mt-5">@lang('About the Hotel')</h3>
                <div class="mb-5 text-muted lh-lg" style="font-size: 1.1rem;">
                    @php echo app()->getLocale() == 'ar' ? ($hotel->description_ar ?: $hotel->description) : $hotel->description; @endphp
                </div>

                <!-- Choose Your Room -->
                <h3 class="section-title" id="rooms">@lang('Choose Your Room')</h3>
                
                @if(empty($params['check_in']))
                    <div class="alert alert-info">
                        <i class="las la-info-circle fs-3"></i> @lang('Please enter your travel dates to see available rooms and prices.')
                    </div>
                @else
                    @forelse($availableRooms as $roomData)
                        @php $roomType = $roomData['room_type']; @endphp
                        <div class="room-card shadow-sm">
                            <div class="room-card-header d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h4 class="mb-2 text-primary-brand">{{ app()->getLocale() == 'ar' ? ($roomType->name_ar ?: $roomType->name) : $roomType->name }}</h4>
                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                        <span><i class="las la-user-friends"></i> @lang('Adults'): {{ $roomType->max_adults }}</span>
                                        <span><i class="las la-child"></i> @lang('Children'): {{ $roomType->max_children }}</span>
                                        @foreach($roomType->amenities->take(3) as $amenity)
                                            <span><i class="{{ $amenity->icon }}"></i> {{ $amenity->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-3 mt-md-0">
                                      @if($roomType->images->first())
                                          <a href="{{ getImage(getFilePath('roomTypeImage').'/'.@$roomType->images->first()->image) }}" data-rel="lightcase">
                                              <img src="{{ getImage(getFilePath('roomTypeImage').'/'.@$roomType->images->first()->image, getFileSize('roomTypeImage')) }}" class="rounded" style="width: 120px; height: 80px; object-fit: cover;" alt="{{ $roomType->name }}">
                                          </a>
                                      @endif
                                  </div>
                            </div>
                            <div class="room-card-body">
                                @foreach($roomData['rate_plans'] as $ratePlanData)
                                    @php $ratePlan = $ratePlanData['rate_plan']; @endphp
                                    <div class="room-rate-row row g-2">
                                        <div class="col-md-6">
                                            <h6 class="mb-2">{{ app()->getLocale() == 'ar' ? ($ratePlan->name_ar ?: $ratePlan->name) : $ratePlan->name }}</h6>
                                            <ul class="list-unstyled small text-muted mb-0">
                                                @if($ratePlan->refundable)
                                                    <li class="text-success mb-1"><i class="las la-check-circle"></i> @lang('Refundable')</li>
                                                @else
                                                    <li class="mb-1"><i class="las la-times-circle"></i> @lang('Non-Refundable')</li>
                                                @endif
                                                <li><i class="las la-credit-card"></i> @lang('Payment'): {{ ucfirst(str_replace('_', ' ', $ratePlan->payment_type)) }}</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 text-md-end d-flex flex-column justify-content-center align-items-md-end">
                                            <div class="price-amount mb-1">{{ showAmount($ratePlanData['total_price']) }}</div>
                                            <div class="text-muted small mb-3">@lang('Total for') {{ request()->rooms }} @lang('room(s)')</div>
                                            <form action="{{ route('hotel.checkout') }}" method="POST" class="text-end">
                                                @csrf
                                                <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                <input type="hidden" name="rate_plan_id" value="{{ $ratePlan->id }}">
                                                <input type="hidden" name="check_in" value="{{ $params['check_in'] }}">
                                                <input type="hidden" name="check_out" value="{{ $params['check_out'] }}">
                                                <input type="hidden" name="rooms" value="{{ $params['rooms'] }}">
                                                <input type="hidden" name="adults" value="{{ $params['adults'] }}">
                                                <button type="submit" class="btn bg-primary-brand text-white px-5 py-2 rounded-pill fw-bold">@lang('Book Now')</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <i class="las la-exclamation-triangle"></i> @lang('Sorry, no rooms are available for the selected dates and guests. Please try different dates.')
                        </div>
                    @endforelse
                @endif
                
                <!-- Mock Sections -->
                <h3 class="section-title">@lang('Cancellation Policies')</h3>
                <ul class="list-group mb-5">
                    @php
                        $cancellationPolicies = \App\Models\CancellationPolicy::active()->get();
                    @endphp
                    @forelse($cancellationPolicies as $policy)
                        <li class="list-group-item bg-light border-0 mb-2 rounded d-flex align-items-center justify-content-start text-start">
                            <i class="las la-check-circle text-success fs-4 me-3"></i> 
                            <div>
                                <h6 class="mb-0 fw-bold">{{ __($policy->name) }}</h6>
                                @if($policy->description)
                                    <p class="mb-0 text-muted fs-7 mt-1">{{ __($policy->description) }}</p>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item bg-light border-0 mb-2 rounded d-flex align-items-center justify-content-start text-start">
                            <i class="las la-info-circle text-primary fs-4 me-3"></i> 
                            <div>
                                <h6 class="mb-0">@lang('Cancellation policies vary by rate plan.')</h6>
                                <p class="mb-0 text-muted fs-7 mt-1">@lang('Exact details will be provided at the time of booking.')</p>
                            </div>
                        </li>
                    @endforelse
                </ul>
                
                <h3 class="section-title">@lang('Guest Reviews')</h3>
                <div class="card shadow-sm border-0 mb-5 p-4 rounded-4">
                    @php
                        $ratings = \App\Models\Rating::where('type', 'hotel')->where('plan_id', $hotel->id)->get();
                        $totalReviews = $ratings->count();
                        $avgRating = $totalReviews > 0 ? $ratings->avg('rating') : 0;
                        
                        $starsCount = [
                            5 => $ratings->where('rating', 5)->count(),
                            4 => $ratings->where('rating', 4)->count(),
                            3 => $ratings->where('rating', 3)->count(),
                            2 => $ratings->where('rating', 2)->count(),
                            1 => $ratings->where('rating', 1)->count(),
                        ];
                    @endphp
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center border-md-end mb-4 mb-md-0">
                            <h1 class="display-3 text-primary-brand fw-bold mb-0">{{ number_format($avgRating, 1) }}</h1>
                            <div class="text-warning my-2 fs-4">
                                @php
                                    $fullStars = floor($avgRating);
                                    $halfStar = $avgRating - $fullStars >= 0.5 ? 1 : 0;
                                    $emptyStars = 5 - $fullStars - $halfStar;
                                @endphp
                                @for($i=0; $i<$fullStars; $i++) <i class="las la-star"></i> @endfor
                                @if($halfStar) <i class="las la-star-half-alt"></i> @endif
                                @for($i=0; $i<$emptyStars; $i++) <i class="lar la-star"></i> @endfor
                            </div>
                            <p class="text-muted mb-0">
                                @if($totalReviews == 0)
                                    @lang('No Reviews Yet')
                                @else
                                    @if($avgRating >= 4.5) @lang('Excellent') 
                                    @elseif($avgRating >= 4.0) @lang('Very Good') 
                                    @elseif($avgRating >= 3.0) @lang('Good') 
                                    @else @lang('Average') @endif 
                                    ({{ $totalReviews }} @lang('Reviews'))
                                @endif
                            </p>
                        </div>
                        <div class="col-md-8 px-md-4">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $percentage = $totalReviews > 0 ? ($starsCount[$star] / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-3">
                                    <span style="width: 60px;" class="text-muted">{{ $star }} @if($star == 1) @lang('Star') @else @lang('Stars') @endif</span>
                                    <div class="progress flex-grow-1 mx-3" style="height: 10px;">
                                        <div class="progress-bar bg-primary-brand" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span style="width: 40px; text-align: right;" class="text-muted">{{ round($percentage) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar (Left in RTL, Right in LTR) -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="sidebar-sticky">
                    
                    <!-- Search Form -->
                    <div class="card shadow-sm border-0 mb-4 rounded-4">
                        <div class="card-body p-4 bg-light rounded-4">
                            <div class="mb-4 pb-3 border-bottom">
                                <h4 class="fw-bold mb-2 text-dark">@lang('Book Now')</h4>
                                <h6 class="text-primary-brand mb-3">{{ __($hotel->name) }}</h6>
                                <p class="text-muted mb-0 small">
                                    @lang('Starting from') 
                                    <span class="fw-bold fs-5 text-dark ms-1">{{ $cheapestPrice ? showAmount($cheapestPrice) : '---' }}</span>
                                    <span class="small">/ @lang('night')</span>
                                </p>
                            </div>
                            <form action="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}" method="GET">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">@lang('Check In')</label>
                                    <input type="date" name="check_in" class="form-control form-control-lg" value="{{ $params['check_in'] ?? '' }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">@lang('Check Out')</label>
                                    <input type="date" name="check_out" class="form-control form-control-lg" value="{{ $params['check_out'] ?? '' }}" required>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-4">
                                        <label class="form-label text-muted small fw-bold">@lang('Rooms')</label>
                                        <input type="number" name="rooms" class="form-control form-control-lg" min="1" value="{{ $params['rooms'] ?? 1 }}" required>
                                    </div>
                                    <div class="col-6 mb-4">
                                        <label class="form-label text-muted small fw-bold">@lang('Adults')</label>
                                        <input type="number" name="adults" class="form-control form-control-lg" min="1" value="{{ $params['adults'] ?? 2 }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn bg-primary-brand text-white w-100 py-3 rounded-pill fw-bold fs-6">@lang('Update Search')</button>
                            </form>
                        </div>
                    </div>

                    <!-- Price Box -->
                    <div class="price-box mb-4 text-center">
                        <p class="text-muted mb-2">@lang('Starting from')</p>
                        <h3 class="price-amount mb-3">
                            {{ $cheapestPrice ? showAmount($cheapestPrice) : '---' }} <small class="fs-6 text-muted">/ @lang('night')</small>
                        </h3>
                        @if(isset($params['check_in']))
                        <p class="small text-muted mb-4 bg-white p-2 rounded">
                            <i class="las la-calendar-alt text-primary-brand"></i> {{ \Carbon\Carbon::parse($params['check_in'])->format('d M') }} - {{ \Carbon\Carbon::parse($params['check_out'])->format('d M') }}<br>
                            <i class="las la-user-friends text-primary-brand mt-2"></i> {{ $params['rooms'] }} @lang('rooms'), {{ $params['adults'] }} @lang('adults')
                        </p>
                        @endif
                        <a href="#rooms" class="btn bg-primary-brand text-white w-100 rounded-pill py-3 fw-bold">@lang('Show Available Rooms')</a>
                    </div>

                    <!-- Map Placeholder -->
                    <div class="card shadow-sm border-0 mb-4 overflow-hidden rounded-4">
                        <img src="https://via.placeholder.com/400x200?text=Map+View" class="w-100" alt="Map">
                        <div class="p-3 text-center">
                            <button class="btn btn-outline-primary rounded-pill px-4"><i class="las la-map-marked-alt"></i> @lang('Show on Map')</button>
                        </div>
                    </div>

                    <!-- Hotel Info -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4">
                            <h5 class="mb-4 text-primary-brand fw-bold">@lang('Hotel Information')</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-muted"><i class="las la-sign-in-alt fs-5 align-middle me-1"></i> @lang('Check-in')</span>
                                    <strong>{{ \Carbon\Carbon::parse($hotel->check_in_time)->format('H:i') }}</strong>
                                </li>
                                <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-muted"><i class="las la-sign-out-alt fs-5 align-middle me-1"></i> @lang('Check-out')</span>
                                    <strong>{{ \Carbon\Carbon::parse($hotel->check_out_time)->format('H:i') }}</strong>
                                </li>
                                <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-muted"><i class="las la-phone fs-5 align-middle me-1"></i> @lang('Phone')</span>
                                    <strong dir="ltr"><span dir="ltr">{{ $hotel->phone ?? '+123456789' }}</span></strong>
                                </li>
                                <li class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <span class="text-muted"><i class="las la-envelope fs-5 align-middle me-1"></i> @lang('Email')</span>
                                    <strong>{{ $hotel->hotel_email ?? 'info@hotel.com' }}</strong>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted"><i class="las la-globe fs-5 align-middle me-1"></i> @lang('Website')</span>
                                    <strong><a href="#" class="text-decoration-none text-dark">{{ $hotel->website ?? 'www.hotel.com' }}</a></strong>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
