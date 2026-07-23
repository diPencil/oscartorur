@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.room.type.store', @$roomType->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>@lang('Hotel')</label>
                                <select name="hotel_id" class="form-control" required>
                                    <option value="">@lang('Select Hotel')</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id }}" @selected(old('hotel_id', @$roomType->hotel_id ?? @$hotel_id) == $hotel->id)>{{ __($hotel->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>
                                
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en" role="tabpanel">
                                        <div class="form-group">
                                            <label>@lang('Room Type Name')</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', @$roomType->name) }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('Description')</label>
                                            <textarea name="description" class="form-control trumEdit" rows="5">{{ old('description', @$roomType->description) }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Room Type Name (Arabic)')</label>
                                            <input type="text" name="name_ar" class="form-control" value="{{ old('name_ar', @$roomType->name_ar) }}" dir="rtl">
                                        </div>
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Description (Arabic)')</label>
                                            <textarea name="description_ar" class="form-control trumEdit" rows="5" dir="rtl">{{ old('description_ar', @$roomType->description_ar) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label>@lang('Base Capacity')</label>
                                <input type="number" name="base_capacity" class="form-control" value="{{ old('base_capacity', @$roomType->base_capacity ?? 1) }}" min="1" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Max Adults')</label>
                                <input type="number" name="max_adults" class="form-control" value="{{ old('max_adults', @$roomType->max_adults ?? 1) }}" min="1" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Max Children')</label>
                                <input type="number" name="max_children" class="form-control" value="{{ old('max_children', @$roomType->max_children ?? 0) }}" min="0" required>
                            </div>

                            <div class="col-md-12">
                                <hr>
                                <h4>@lang('Bed Types')</h4>
                                <div class="row mt-3 bed-wrapper">
                                    @if(isset($roomType) && $roomType->beds->count() > 0)
                                        @foreach($roomType->beds as $bed)
                                            <div class="col-md-4 mb-3 bed-item">
                                                <div class="input-group">
                                                    <select name="beds[]" class="form-control" required>
                                                        <option value="">@lang('Select Bed')</option>
                                                        @foreach($bedTypes as $bt)
                                                            <option value="{{ $bt->id }}" @selected($bt->id == $bed->id)>{{ __($bt->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="number" name="bed_counts[]" class="form-control" placeholder="Count" value="{{ $bed->pivot->count }}" min="1" required>
                                                    <button type="button" class="btn btn--danger remove-bed"><i class="las la-times"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="col-md-4 mb-3 bed-item">
                                        <div class="input-group">
                                            <select name="beds[]" class="form-control">
                                                <option value="">@lang('Select Bed')</option>
                                                @foreach($bedTypes as $bt)
                                                    <option value="{{ $bt->id }}">{{ __($bt->name) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" name="bed_counts[]" class="form-control" placeholder="Count" value="1" min="1">
                                            <button type="button" class="btn btn--success add-bed"><i class="las la-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <hr>
                                <h4>@lang('Room Amenities')</h4>
                                <div class="row mt-3">
                                    @foreach($amenities as $amenity)
                                        <div class="col-md-3 col-sm-6 mb-3">
                                            <div class="custom-control custom-checkbox form-check-primary">
                                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}" class="custom-control-input" id="amenity_{{ $amenity->id }}"
                                                    @if(isset($roomType) && $roomType->amenities->contains($amenity->id)) checked @endif>
                                                <label class="custom-control-label" for="amenity_{{ $amenity->id }}">
                                                    @php echo $amenity->icon; @endphp {{ __($amenity->name) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                  <hr>
                                  <h4>@lang('Room Images')</h4>
                                  <div class="form-group mt-3 border-bottom pb-3">
                                      <label>@lang('Upload Files')</label>
                                      <input type="file" name="images[]" class="form-control" multiple accept=".png, .jpg, .jpeg">
                                      <small class="text-muted">@lang('You can select multiple images.')</small>
                                  </div>
                                  <div class="form-group text-center">
                                      <span class="text-muted fw-bold">-- @lang('OR') --</span>
                                  </div>
                                  <div class="form-group mt-3">
                                      <label>@lang('Image URL')</label>
                                      <input type="url" name="image_url" class="form-control" placeholder="https://...">
                                  </div>
                                  @if(isset($roomType) && $roomType->images->count() > 0)
                                      <div class="row mt-3">
                                          @foreach($roomType->images as $img)
                                              <div class="col-md-2 col-sm-3 mb-3 text-center">
                                                  <img src="{{ getImage(getFilePath('room_type').'/'.$img->image) }}" class="img-thumbnail" style="height: 100px; object-fit: cover;" alt="Room Image">
                                              </div>
                                          @endforeach
                                      </div>
                                  @endif
                              </div>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.room.type.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-undo"></i>@lang('Back')
    </a>
@endpush

@push('script')
<script>
    (function($){
        "use strict";
        
        $(document).on('click', '.add-bed', function(){
            var html = `
            <div class="col-md-4 mb-3 bed-item">
                <div class="input-group">
                    <select name="beds[]" class="form-control" required>
                        <option value="">@lang('Select Bed')</option>
                        @foreach($bedTypes as $bt)
                            <option value="{{ $bt->id }}">{{ __($bt->name) }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="bed_counts[]" class="form-control" placeholder="Count" value="1" min="1" required>
                    <button type="button" class="btn btn--danger remove-bed"><i class="las la-times"></i></button>
                </div>
            </div>`;
            $('.bed-wrapper').append(html);
        });

        $(document).on('click', '.remove-bed', function(){
            $(this).closest('.bed-item').remove();
        });
        
    })(jQuery);
</script>
@endpush
