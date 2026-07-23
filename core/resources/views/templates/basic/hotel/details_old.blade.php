@extends('Template::layouts.frontend')

@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <!-- Hotel Header -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h2 class="mb-2">{{ $hotel->name }}</h2>
                <div class="text-warning mb-2 fs-5">
                    @for($i = 0; $i < $hotel->star_rating; $i++)
                        <i class="las la-star"></i>
                    @endfor
                </div>
                <p class="text-muted fs-5"><i class="las la-map-marker"></i> {{ $hotel->location->name }} - {{ $hotel->address }}</p>
            </div>
        </div>

        <!-- Hotel Images -->
        <div class="row mb-5 g-2">
            @php $images = $hotel->images; @endphp
            @if($images->isNotEmpty())
                <div class="col-md-8">
                    <img src="{{ getImage(getFilePath('hotelImage').'/'.$images->first()->image, getFileSize('hotelImage')) }}" class="img-fluid w-100 rounded object-fit-cover" style="height: 400px;" alt="Main Image">
                </div>
                <div class="col-md-4">
                    <div class="row g-2">
                        @foreach($images->skip(1)->take(2) as $img)
                            <div class="col-12">
                                <img src="{{ getImage(getFilePath('hotelImage').'/'.$img->image, getFileSize('hotelImage')) }}" class="img-fluid w-100 rounded object-fit-cover" style="height: 195px;" alt="Image">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Search Form to Check Availability -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 bg-light rounded">
                <h5 class="mb-3">@lang('Check Availability')</h5>
                <form action="{{ route('hotel.details', [$hotel->id, slug($hotel->name)]) }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">@lang('Check In')</label>
                        <input type="date" name="check_in" class="form-control" value="{{ $params['check_in'] ?? '' }}" required>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn--base w-100">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="row">
            <div class="col-lg-12">
                <h4 class="mb-4">@lang('Available Rooms')</h4>
                
                @if(empty($params['check_in']))
                    <div class="alert alert-info">
                        <i class="las la-info-circle fs-3"></i> @lang('Please enter your travel dates to see available rooms and prices.')
                    </div>
                @else
                    @forelse($availableRooms as $roomData)
                        @php $roomType = $roomData['room_type']; @endphp
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ getImage(getFilePath('room_type').'/'.@$roomType->images->first()->image, getFileSize('room_type')) }}" class="img-fluid rounded-start h-100 object-fit-cover" alt="{{ $roomType->name }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $roomType->name }}</h4>
                                        <p class="text-muted mb-2">
                                            <i class="las la-user-friends"></i> @lang('Max Adults'): {{ $roomType->max_adults }} | 
                                            <i class="las la-child"></i> @lang('Max Children'): {{ $roomType->max_children }}
                                        </p>
                                        <div class="amenities d-flex flex-wrap gap-2 text-muted mb-3">
                                            @foreach($roomType->amenities as $amenity)
                                                <span class="badge bg-light text-dark border"><i class="{{ $amenity->icon }}"></i> {{ $amenity->name }}</span>
                                            @endforeach
                                        </div>

                                        <h6 class="border-bottom pb-2 mb-3">@lang('Available Rate Plans')</h6>
                                        <ul class="list-group list-group-flush">
                                            @foreach($roomData['rate_plans'] as $ratePlanData)
                                                @php $ratePlan = $ratePlanData['rate_plan']; @endphp
                                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $ratePlan->name }}</h6>
                                                        <small class="text-muted d-block">
                                                            @if($ratePlan->refundable)
                                                                <span class="text-success"><i class="las la-check"></i> @lang('Refundable')</span>
                                                            @else
                                                                <span class="text-danger"><i class="las la-times"></i> @lang('Non-Refundable')</span>
                                                            @endif
                                                            | @lang('Payment'): {{ ucfirst(str_replace('_', ' ', $ratePlan->payment_type)) }}
                                                        </small>
                                                    </div>
                                                    <div class="text-end">
                                                        <h4 class="text--base mb-1">{{ $general->cur_sym }}{{ showAmount($ratePlanData['total_price']) }}</h4>
                                                        <small class="text-muted d-block mb-2">@lang('Total for') {{ request()->rooms }} @lang('room(s)')</small>
                                                        
                                                        <form action="{{ route('hotel.checkout') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                                                            <input type="hidden" name="rate_plan_id" value="{{ $ratePlan->id }}">
                                                            <input type="hidden" name="check_in" value="{{ $params['check_in'] }}">
                                                            <input type="hidden" name="check_out" value="{{ $params['check_out'] }}">
                                                            <input type="hidden" name="rooms" value="{{ $params['rooms'] }}">
                                                            <input type="hidden" name="adults" value="{{ $params['adults'] }}">
                                                            <button type="submit" class="btn btn-sm btn--base">@lang('Book Now')</button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning">
                            <i class="las la-exclamation-triangle"></i> @lang('Sorry, no rooms are available for the selected dates and guests. Please try different dates.')
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
        
        <!-- Description -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-3">@lang('About') {{ $hotel->name }}</h4>
                        <div class="description-content">
                            @php echo $hotel->description; @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
