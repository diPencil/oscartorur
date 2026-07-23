<form action="{{ route('admin.hotel.store', $hotel->id) }}" method="POST">
    @csrf
    
    <div class="row">
        <div class="col-12 mb-4">
            <ul class="nav nav-tabs nav-tabs--style1" id="languageTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="lang-en-tab" data-bs-toggle="tab" data-bs-target="#lang-en" type="button" role="tab">
                        <i class="las la-language"></i> @lang('English (Default)')
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="lang-ar-tab" data-bs-toggle="tab" data-bs-target="#lang-ar" type="button" role="tab" dir="rtl">
                        <i class="las la-language"></i> @lang('Arabic (عربي)')
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3 p-3 border b-radius--10 bg--white" id="languageTabsContent">
                <!-- English Tab -->
                <div class="tab-pane fade show active" id="lang-en" role="tabpanel">
                    <div class="form-group">
                        <label>@lang('Hotel Name')</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Address')</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $hotel->address) }}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Short Description')</label>
                        <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $hotel->short_description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>@lang('Full Description')</label>
                        <textarea name="description" class="form-control trumEdit" rows="10">{{ old('description', $hotel->description) }}</textarea>
                    </div>
                </div>

                <!-- Arabic Tab -->
                <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                    <div class="form-group">
                        <label class="d-block text-end">@lang('Hotel Name (Arabic)')</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', $hotel->name_ar) }}" dir="rtl">
                    </div>
                    <div class="form-group">
                        <label class="d-block text-end">@lang('Address (Arabic)')</label>
                        <input type="text" name="address_ar" class="form-control" value="{{ old('address_ar', $hotel->address_ar) }}" dir="rtl">
                    </div>
                    <div class="form-group">
                        <label class="d-block text-end">@lang('Short Description (Arabic)')</label>
                        <textarea name="short_description_ar" class="form-control" rows="3" dir="rtl">{{ old('short_description_ar', $hotel->short_description_ar) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="d-block text-end">@lang('Full Description (Arabic)')</label>
                        <textarea name="description_ar" class="form-control trumEdit" rows="10" dir="rtl">{{ old('description_ar', $hotel->description_ar) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12"><hr class="my-4"></div>

        <div class="col-md-3 form-group">
            <label>@lang('Property Type')</label>
            <select name="property_type" class="form-control" required>
                <option value="Hotel" @selected(old('property_type', $hotel->property_type) == 'Hotel')>Hotel</option>
                <option value="Resort" @selected(old('property_type', $hotel->property_type) == 'Resort')>Resort</option>
                <option value="Apartment" @selected(old('property_type', $hotel->property_type) == 'Apartment')>Apartment</option>
                <option value="Villa" @selected(old('property_type', $hotel->property_type) == 'Villa')>Villa</option>
                <option value="Guest House" @selected(old('property_type', $hotel->property_type) == 'Guest House')>Guest House</option>
            </select>
        </div>
        <div class="col-md-3 form-group">
            <label>@lang('Star Rating')</label>
            <div class="input-group">
                <input type="number" name="star_rating" class="form-control" value="{{ old('star_rating', $hotel->star_rating) }}" required min="1" max="5">
                <span class="input-group-text"><i class="las la-star"></i></span>
            </div>
        </div>

        <div class="col-md-4 form-group">
            <label>@lang('Country')</label>
            <select name="country_id" class="form-control" required id="edit_country">
                <option value="" selected disabled>@lang('Select Country')</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" @selected(old('country_id', $hotel->country_id) == $country->id)>{{ __($country->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label>@lang('City/Location')</label>
            <select name="location_id" class="form-control" required id="edit_location">
                <option value="" selected disabled>@lang('Select City')</option>
                @foreach (\App\Models\Location::where('country_id', $hotel->country_id)->get() as $loc)
                    <option value="{{ $loc->id }}" @selected($hotel->location_id == $loc->id)>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label>@lang('Area (Optional)')</label>
            <select name="area_id" class="form-control" id="edit_area">
                <option value="" selected disabled>@lang('Select Area')</option>
                @if($hotel->location_id)
                    @foreach (\App\Models\Area::where('location_id', $hotel->location_id)->get() as $ar)
                        <option value="{{ $ar->id }}" @selected($hotel->area_id == $ar->id)>{{ $ar->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label>@lang('Latitude')</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $hotel->latitude) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('Longitude')</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $hotel->longitude) }}">
        </div>

        <div class="col-md-4 form-group">
            <label>@lang('Check-in Time')</label>
            <input type="time" name="check_in_time" class="form-control" value="{{ old('check_in_time', $hotel->check_in_time ? \Carbon\Carbon::parse($hotel->check_in_time)->format('H:i') : '14:00') }}" required>
        </div>
        <div class="col-md-4 form-group">
            <label>@lang('Check-out Time')</label>
            <input type="time" name="check_out_time" class="form-control" value="{{ old('check_out_time', $hotel->check_out_time ? \Carbon\Carbon::parse($hotel->check_out_time)->format('H:i') : '12:00') }}" required>
        </div>
        <div class="col-md-4 form-group">
            <label>@lang('Timezone')</label>
            <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $hotel->timezone ?? 'UTC') }}" required>
        </div>

        <div class="col-md-6 form-group">
            <label>@lang('Hotel Email')</label>
            <input type="email" name="hotel_email" class="form-control" value="{{ old('hotel_email', $hotel->hotel_email) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('Reservation Email')</label>
            <input type="email" name="reservation_email" class="form-control" value="{{ old('reservation_email', $hotel->reservation_email) }}">
        </div>
        
        <div class="col-md-6 form-group">
            <label>@lang('Hotel Phone')</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $hotel->phone) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('WhatsApp')</label>
            <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $hotel->whatsapp) }}">
        </div>

        <div class="col-md-6 form-group">
            <label>@lang('Website')</label>
            <input type="url" name="website" class="form-control" value="{{ old('website', $hotel->website) }}">
        </div>
        <div class="col-md-6 form-group">
            <label>@lang('Contact Person')</label>
            <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $hotel->contact_person) }}">
        </div>
        
        <div class="col-md-6 form-group">
            <label>@lang('Primary Supplier (Optional)')</label>
            <select name="primary_supplier_id" class="form-control">
                <option value="" selected>@lang('Select Supplier')</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('primary_supplier_id', $hotel->primary_supplier_id) == $supplier->id)>{{ __($supplier->name) }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="col-md-6 form-group">
            <label>@lang('Featured Hotel')</label>
            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="featured"
                @if ($hotel->featured) checked @endif>
        </div>
    </div>
    
    <div class="text-end">
        <button type="submit" class="btn btn--primary h-45">@lang('Save Changes') <i class="las la-save"></i></button>
    </div>
</form>
