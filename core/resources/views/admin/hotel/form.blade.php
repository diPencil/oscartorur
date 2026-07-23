@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.hotel.store', @$hotel->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-name" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-name" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-name" role="tabpanel">
                                        <label>@lang('Hotel Name')</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', @$hotel->name) }}" required>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-name" role="tabpanel" dir="rtl">
                                        <label class="d-block text-end">@lang('Hotel Name') (Arabic)</label>
                                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', @$hotel->name_ar) }}" dir="rtl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Property Type') <span class="text-danger">*</span></label>
                                <select name="property_type" class="form-control" required>
                                    <option value="">@lang('Select Property Type')</option>
                                    <option value="Hotel" @selected(old('property_type', @$hotel->property_type) == 'Hotel')>@lang('Hotel')</option>
                                    <option value="Resort" @selected(old('property_type', @$hotel->property_type) == 'Resort')>@lang('Resort')</option>
                                    <option value="Apartment" @selected(old('property_type', @$hotel->property_type) == 'Apartment')>@lang('Apartment')</option>
                                    <option value="Villa" @selected(old('property_type', @$hotel->property_type) == 'Villa')>@lang('Villa')</option>
                                    <option value="Guest House" @selected(old('property_type', @$hotel->property_type) == 'Guest House')>@lang('Guest House')</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Star Rating') <span class="text-danger">*</span></label>
                                <select name="star_rating" class="form-control" required>
                                    <option value="">@lang('Select Rating')</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" @selected(old('star_rating', @$hotel->star_rating) == $i)>{{ $i }} @lang('Star')</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Country') <span class="text-danger">*</span></label>
                                <select name="country_id" class="form-control" required id="country">
                                    <option value="">@lang('Select Country')</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" @selected(old('country_id', @$hotel->country_id) == $country->id)>{{ __($country->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Location') <span class="text-danger">*</span></label>
                                <select name="location_id" class="form-control" required id="location">
                                    <option value="">@lang('Select Location')</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->id }}" @selected(old('location_id', @$hotel->location_id) == $location->id)>{{ __($location->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Supplier')</label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">@lang('Select Supplier (Optional)')</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', @$hotel->supplier_id) == $supplier->id)>{{ __($supplier->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 form-group">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-address" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-address" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-address" role="tabpanel">
                                        <label>@lang('Address')</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', @$hotel->address) }}" required>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-address" role="tabpanel" dir="rtl">
                                        <label class="d-block text-end">@lang('Address') (Arabic)</label>
                                        <input type="text" name="address_ar" class="form-control" value="{{ old('address_ar', @$hotel->address_ar) }}" dir="rtl">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Latitude')</label>
                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', @$hotel->latitude) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Longitude')</label>
                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', @$hotel->longitude) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Check In Time')</label>
                                <input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', @$hotel->check_in_time ? \Carbon\Carbon::parse(@$hotel->check_in_time)->format('H:i') : '') }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Check Out Time')</label>
                                <input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', @$hotel->check_out_time ? \Carbon\Carbon::parse(@$hotel->check_out_time)->format('H:i') : '') }}">
                            </div>

                            <div class="col-md-4 form-group">
                                <label>@lang('Check-in Time')</label>
                                <input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', @$hotel->check_in_time ?? '14:00') }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Check-out Time')</label>
                                <input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', @$hotel->check_out_time ?? '12:00') }}" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Timezone')</label>
                                <input type="text" name="timezone" class="form-control" value="{{ old('timezone', @$hotel->timezone ?? 'UTC') }}" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>@lang('Hotel Email')</label>
                                <input type="email" name="hotel_email" class="form-control" value="{{ old('hotel_email', @$hotel->hotel_email) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Hotel Phone')</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', @$hotel->phone) }}">
                            </div>

                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Save & Continue to Next Steps') <i class="las la-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.hotel.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-undo"></i> @lang('Back')
    </a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        // Simple chained dropdowns for Country -> City -> Area
        // NOTE: You would normally load these via AJAX in a real implementation.
        // For now, this is a skeleton structure.
        $('#country').on('change', function() {
            // Load locations for country
            // $('#location').html('...');
        });
        
        $('#location').on('change', function() {
            // Load areas for location
            // $('#area').html('...');
        });
        
    })(jQuery);
</script>
@endpush
