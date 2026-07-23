@extends('Template::layouts.frontend')

@section('content')
<section class="pt-100 pb-100">
    <div class="container">
        <form action="{{ route('hotel.book') }}" method="POST">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
            <input type="hidden" name="rate_plan_id" value="{{ $ratePlan->id }}">
            <input type="hidden" name="check_in" value="{{ $params['check_in'] }}">
            <input type="hidden" name="check_out" value="{{ $params['check_out'] }}">
            <input type="hidden" name="rooms" value="{{ $params['rooms'] }}">
            <input type="hidden" name="adults" value="{{ $params['adults'] }}">

            <div class="row">
                <div class="col-lg-8">
                    <!-- Guest Details -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">@lang('Guest Details')</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="row mb-4">
                                <div class="col-md-6 form-group">
                                    <label class="form-label">@lang('Email Address') <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" value="{{ $user ? $user->email : old('email') }}" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label class="form-label">@lang('Phone Number') <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user ? $user->mobile : old('phone') }}" required>
                                </div>
                            </div>

                            @for($i = 0; $i < $params['rooms']; $i++)
                                <h5 class="mb-3 border-bottom pb-2">@lang('Room') {{ $i + 1 }} {{ $i == 0 ? '(Lead Guest)' : '' }}</h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">@lang('First Name') <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name[]" class="form-control" value="{{ ($i == 0 && $user) ? $user->firstname : '' }}" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label">@lang('Last Name') <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name[]" class="form-control" value="{{ ($i == 0 && $user) ? $user->lastname : '' }}" required>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Booking Summary -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">@lang('Booking Summary')</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ getImage(getFilePath('hotelImage').'/'.@$hotel->images->first()->image, getFileSize('hotelImage')) }}" class="img-thumbnail me-3 object-fit-cover" style="width: 80px; height: 80px;" alt="{{ $hotel->name }}">
                                <div>
                                    <h6 class="mb-1">{{ $hotel->name }}</h6>
                                    <p class="text-muted small mb-0"><i class="las la-map-marker"></i> {{ $hotel->location->name }}</p>
                                </div>
                            </div>
                            
                            <hr>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">@lang('Check In'):</span>
                                <strong>{{ showDateTime($params['check_in'], 'd M Y') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">@lang('Check Out'):</span>
                                <strong>{{ showDateTime($params['check_out'], 'd M Y') }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">@lang('Stay'):</span>
                                <strong>{{ \Carbon\Carbon::parse($params['check_in'])->diffInDays(\Carbon\Carbon::parse($params['check_out'])) }} @lang('Night(s)')</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">@lang('Guests & Rooms'):</span>
                                <strong>{{ $params['adults'] }} @lang('Adults') / {{ $params['rooms'] }} @lang('Room(s)')</strong>
                            </div>

                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="mb-2">{{ $ratePlan->contractRoomType->roomType->name }}</h6>
                                <p class="small text-muted mb-0">{{ $ratePlan->name }} ({{ $ratePlan->refundable ? trans('Refundable') : trans('Non-Refundable') }})</p>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">@lang('Total Price')</h5>
                                <h4 class="text--base mb-0">{{ showAmount($totalPrice) }}</h4>
                            </div>

                            <button type="submit" class="btn btn--base w-100 fs-5 py-3">@lang('Confirm Booking')</button>
                            
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="las la-lock"></i> @lang('Your information is safe and secure')
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
