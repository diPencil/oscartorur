<div class="row">
    <div class="col-md-12">
        <div class="card border--dark">
            <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0"><i class="las la-wifi"></i> @lang('Select Hotel Amenities')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.hotel.amenities.sync', $hotel->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <p class="text-muted">@lang('Choose all the facilities and amenities available at this hotel.')</p>
                        </div>
                        
                        @php
                            $allAmenities = \App\Models\Amenity::where('status', 1)->orderBy('name')->get();
                            $hotelAmenities = $hotel->amenities->pluck('id')->toArray();
                        @endphp

                        @foreach($allAmenities as $amenity)
                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                            <div class="form-check custom--checkbox">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" @checked(in_array($amenity->id, $hotelAmenities))>
                                <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                    @if($amenity->icon)
                                        @php echo $amenity->icon; @endphp 
                                    @else
                                        <i class="las la-check"></i>
                                    @endif
                                    {{ __($amenity->name) }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn--primary h-45">@lang('Save Amenities') <i class="las la-save"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
