@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form class="disableSubmission" action="{{ route('admin.plan.store', @$plan->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-name" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
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
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input class="form-control" name="name" type="text" value="{{ old('name', @$plan->name) }}">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-name" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Name (Arabic)')</label>
                                            <input class="form-control" name="name_ar" type="text" value="{{ old('name_ar', @$plan->name_ar) }}" dir="rtl">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Category')</label>
                                    <select class="form-control select2" name="category_id" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach (@$categories as $item)
                                            <option value="{{ $item->id }}">{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Location')</label>
                                    <select class="form-control select2" name="location_id" required>
                                        <option value="" selected disabled>@lang('Select One')</option>
                                        @foreach (@$locations as $item)
                                            <option value="{{ $item->id }}">{{ __($item->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Map Latitude')</label>
                                    <input class="form-control" name="map_latitude" type="number" value="{{ old('map_latitude', @$plan->map_latitude) }}" step="any" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Map Longitude')</label>
                                    <input class="form-control" name="map_longitude" type="number" value="{{ old('map_longitude', @$plan->map_longitude) }}" step="any" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Duration')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="duration" type="number" value="{{ old('duration', @$plan->duration) }}" step="any" required>
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Departure Time')</label>
                                    <input class="form-control timepicker" name="departure_time" type="text" value="{{ old('departure_time', @$plan->departure_time) }}" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Return Time')</label>
                                    <input class="form-control timepicker" name="return_time" type="text" value="{{ old('return_time', @$plan->return_time) }}" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Capacity of Reservation')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="capacity" type="number" value="{{ old('capacity', @$plan->capacity) }}" required>
                                        <span class="input-group-text">@lang('Seat')</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-inc" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-inc" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-inc" role="tabpanel">
                                        <div class="form-group">
                                            <label>@lang('Included')</label>
                                            <small class="ms-2 mt-2  ">@lang('Separate multiple included by') <code>,</code>(@lang('comma')) @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                            <select class="form-control select2-auto-tokenize" name="included[]" multiple="multiple" required>
                                                @if (old('included'))
                                                    @foreach (old('included') as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @elseif (isset($plan->included))
                                                    @foreach ($plan->included as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-inc" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Included (Arabic)')</label>
                                            <small class="ms-2 mt-2 d-block text-end">@lang('Separate multiple included by') <code>,</code>(@lang('comma')) @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                            <select class="form-control select2-auto-tokenize" name="included_ar[]" multiple="multiple" dir="rtl">
                                                @if (old('included_ar'))
                                                    @foreach (old('included_ar') as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @elseif (isset($plan->included_ar))
                                                    @foreach ($plan->included_ar as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-exc" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-exc" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-exc" role="tabpanel">
                                        <div class="form-group">
                                            <label>@lang('Excluded')</label>
                                            <small class="ms-2 mt-2  ">@lang('Separate multiple excluded by') <code>,</code>(@lang('comma')) @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                            <select class="form-control select2-auto-tokenize" name="excluded[]" multiple="multiple" required>

                                                @if (old('excluded'))
                                                    @foreach (old('excluded') as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @elseif (isset($plan->excluded))
                                                    @foreach ($plan->excluded as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-exc" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Excluded (Arabic)')</label>
                                            <small class="ms-2 mt-2 d-block text-end">@lang('Separate multiple excluded by') <code>,</code>(@lang('comma')) @lang('or') <code>@lang('enter')</code> @lang('key')</small>
                                            <select class="form-control select2-auto-tokenize" name="excluded_ar[]" multiple="multiple" dir="rtl">

                                                @if (old('excluded_ar'))
                                                    @foreach (old('excluded_ar') as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @elseif (isset($plan->excluded_ar))
                                                    @foreach ($plan->excluded_ar as $option)
                                                        <option value="{{ $option }}" selected>{{ __($option) }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <div class="input-group">
                                        <input class="form-control" name="price" type="number" value="{{ old('price', getAmount(@$plan->price)) }}" step="any" required>
                                        <span class="input-group-text">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-details" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-details" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-details" role="tabpanel">
                                        <div class="form-group">
                                            <label>@lang('Details')</label>
                                            <textarea class="form-control nicEdit" name="details" rows="10">{{ old('details', @$plan->details) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-details" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Details (Arabic)')</label>
                                            <textarea class="form-control nicEdit" name="details_ar" rows="10" dir="rtl">{{ old('details_ar', @$plan->details_ar) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Plan Images')</label>
                                    <div class="input-images"></div>
                                    <div class="mt-1">
                                        <small class="mt-3 text-muted"> @lang('Supported Files:')
                                            <b>@lang('.png, .jpg, .jpeg')</b> @lang('Image will be resized into') <b>{{ getFileSize('plan') }}</b>@lang('px')
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3 mb-3">
                                <div class="card border--dark">
                                    <h5 class="card-header bg--dark d-flex justify-content-between">@lang('External Image URLs (Optional)')
                                        <button class="btn btn-sm btn-outline-light addUrlBtn" type="button">
                                            <i class="las la-plus"></i>@lang('Add URL')
                                        </button>
                                    </h5>
                                    <div class="card-body">
                                        <div class="row addedUrlsInfo">
                                            <!-- Dynamic URLs will be appended here -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <ul class="nav nav-tabs nav-tabs--style1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lang-en-tour" type="button" role="tab">
                                            <i class="las la-language"></i> @lang('English (Default)')
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#lang-ar-tour" type="button" role="tab" dir="rtl">
                                            <i class="las la-language"></i> @lang('Arabic (عربي)')
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3">
                                    <div class="tab-pane fade show active" id="lang-en-tour" role="tabpanel">
                                        <div class="card">
                                            <h5 class="card-header bg--primary d-flex justify-content-between">@lang('Tour Plan Specifications')
                                                <button class="btn btn-sm btn-outline-light addBtn" type="button">
                                                    <i class="las la-plus"></i>@lang('Add New')
                                                </button>
                                            </h5>
                                            <div class="card-body">
                                                <div class="row addedPlanInfo">
                                                    @foreach ($plan->tour_plan ?? [] as $index => $tour)
                                                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removePlanInfo">
                                                            <div class="card card-body">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input class="form-control" name="tour[{{ $index }}][title]" type="text" value="{{ @$tour->title }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input class="form-control" name="tour[{{ $index }}][subtitle]" type="text" value="{{ @$tour->subtitle }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" name="tour[{{ $index }}][content]" required>{{ @$tour->content }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <button class="btn removePlanRow btn--danger border--danger" type="button"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="lang-ar-tour" role="tabpanel" dir="rtl">
                                        <div class="card">
                                            <h5 class="card-header bg--primary d-flex justify-content-between text-end">@lang('Tour Plan Specifications (Arabic)')
                                                <button class="btn btn-sm btn-outline-light addBtnAr" type="button" dir="rtl">
                                                    <i class="las la-plus"></i>@lang('Add New')
                                                </button>
                                            </h5>
                                            <div class="card-body">
                                                <div class="row addedPlanInfoAr">
                                                    @foreach ($plan->tour_plan_ar ?? [] as $index => $tour)
                                                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removePlanInfo">
                                                            <div class="card card-body">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input class="form-control" name="tour_ar[{{ $index }}][title]" type="text" value="{{ @$tour->title }}" required dir="rtl">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input class="form-control" name="tour_ar[{{ $index }}][subtitle]" type="text" value="{{ @$tour->subtitle }}" required dir="rtl">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <textarea class="form-control" name="tour_ar[{{ $index }}][content]" required dir="rtl">{{ @$tour->content }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <button class="btn removePlanRow btn--danger border--danger" type="button"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.plan.index') }}" />
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
@endpush

@push('style-lib')
    <link type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/admin/css/image-uploader.min.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.timepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD HH:mm'
                }
            });

            $('.timepicker').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
            });

            $('.timepicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            // image uploder
            @if (isset($images))
                let preloaded = @json($images);
            @else
                let preloaded = [];
            @endif

            $('.input-images').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 3 * 1024 * 1024,
                label: 'Drag & Drop files here or click to browse'
            });


            $('select[name=category_id]').val('{{ old('category_id', @$plan->category_id) }}').select2();
            $('select[name=location_id]').val('{{ old('location_id', @$plan->location_id) }}').select2();



            var count = 0;
            $('.addBtn').on('click', function() {
                $(".addedPlanInfo").append(`
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removePlanInfo">
                    <div class="card card-body">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" placeholder="@lang('Enter title')" name="tour[${count}][title]" type="text" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" placeholder="@lang('Enter subtitle')" name="tour[${count}][subtitle]" type="text" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                              <textarea class="form-control" placeholder="@lang('Enter content')" name="tour[${count}][content]" required> </textarea>
                            </div>
                        </div>

                        <button class="btn removePlanRow btn--danger border--danger" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                `)
                count++;
            });
            var countAr = 0;
            $('.addBtnAr').on('click', function() {
                $(".addedPlanInfoAr").append(`
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 removePlanInfo">
                    <div class="card card-body">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" placeholder="@lang('Enter title')" name="tour_ar[${countAr}][title]" type="text" required dir="rtl">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" placeholder="@lang('Enter subtitle')" name="tour_ar[${countAr}][subtitle]" type="text" required dir="rtl">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                              <textarea class="form-control" placeholder="@lang('Enter content')" name="tour_ar[${countAr}][content]" required dir="rtl"> </textarea>
                            </div>
                        </div>

                        <button class="btn removePlanRow btn--danger border--danger" type="button"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                `)
                countAr++;
            });

            $(document).on('click', '.removePlanRow', function() {
                $(this).closest('.removePlanInfo').remove();
            });

            $('.addUrlBtn').on('click', function() {
                $(".addedUrlsInfo").append(`
                <div class="col-md-6 removeUrlInfo">
                    <div class="form-group">
                        <div class="input-group">
                            <input class="form-control" placeholder="@lang('https://example.com/image.jpg')" name="image_urls[]" type="url" required>
                            <button class="btn input-group-text btn--danger border--danger removeUrlRow" type="button"><i class="fas fa-times text-white"></i></button>
                        </div>
                    </div>
                </div>
                `);
            });

            $(document).on('click', '.removeUrlRow', function() {
                $(this).closest('.removeUrlInfo').remove();
            });


        })(jQuery);
    </script>
@endpush
