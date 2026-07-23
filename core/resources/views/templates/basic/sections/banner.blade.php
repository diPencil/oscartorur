@php
    $banner = getContent('banner.content', true);
    $locations = App\Models\Location::active()->orderBy('name')->get();
@endphp

<section class="hero bg_img" style="background-image: url({{ frontendImage('banner', @$banner->data_values->image, '1920x1280') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-6 col-xl-7 col-lg-8 col-md-10 text-center">
                <h2 class="hero__title text-white wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    {{ __(@$banner->data_values->heading) }}</h2>
                <p class="text-white mt-3 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">{{ __(@$banner->data_values->subheading) }}</p>
            </div>
        </div>
    </div>
</section>
<div class="search-area pb-50 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-lg-12">
                <ul class="nav nav-tabs find-tabs with-indicator" id="findTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mw--120 active" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel" type="button" role="tab" aria-controls="hotel" aria-selected="true">
                            <i class="las la-building"></i>
                            <p>@lang('Hotels')</p>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mw--120" id="tour-tab" data-bs-toggle="tab" data-bs-target="#tour" type="button" role="tab" aria-controls="tour" aria-selected="false">
                            <i class="las la-globe-africa"></i>
                            <p>@lang('Tour Package')</p>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link mw--120" id="flight-tab" data-bs-toggle="tab" data-bs-target="#flight" type="button" role="tab" aria-controls="flight" aria-selected="false">
                            <i class="las la-calendar"></i>
                            <p>@lang('Seminar Package')</p>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="findTabContent">
                    <div class="tab-pane fade show active" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                        <form class="find-form p-4" action="{{ route('hotel.search') }}" method="GET" style="display: block;">
                            <div class="row align-items-end g-3 w-100 m-0">
                                <div class="col-xl-3 col-lg-3 col-md-12">
                                    <label class="mb-0">@lang('Destination')</label>
                                    <select class="select2-basic select2" name="location_id" style="width: 100%;">
                                        <option value="" selected disabled>@lang('Select location')</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-6 col-6">
                                    <label class="mb-0 text-nowrap">@lang('Check In')</label>
                                    <input class="form--control w-100" name="check_in" type="date" required>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-6 col-6">
                                    <label class="mb-0 text-nowrap">@lang('Check Out')</label>
                                    <input class="form--control w-100" name="check_out" type="date" required>
                                </div>
                                <div class="col-xl-1 col-lg-2 col-md-6 col-6">
                                    <label class="mb-0 text-nowrap">@lang('Rooms')</label>
                                    <input class="form--control w-100 px-1" name="rooms" type="number" min="1" value="1" required>
                                </div>
                                <div class="col-xl-2 col-lg-2 col-md-6 col-6">
                                    <label class="mb-0 text-nowrap">@lang('Adults')</label>
                                    <input class="form--control w-100 px-1" name="adults" type="number" min="1" value="2" required>
                                </div>
                                <div class="col-xl-2 col-lg-auto col-md-12 mt-3 mt-xl-0">
                                    <button class="btn btn--base w-100 text-nowrap h-100" type="submit" style="min-height: 45px;"><i class="las la-search"></i> @lang('Search')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="tour" role="tabpanel" aria-labelledby="tour-tab">
                        <form class="find-form" action="{{ route('plans') }}" method="GET">
                            <div class="find-form__destination">
                                <div class="left flex-grow-1">
                                    <label class="mb-0">@lang('Location')</label>
                                    <select class="select2-basic select2" name="location_id[]">
                                        <option value="" selected disabled>@lang('Select location')</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="find-form__duration">
                                <div class="left flex-grow-1">
                                    <label class="mb-0">@lang('From Date')</label>
                                    <input class="form--control timepicker" name="from_date" type="text" autocomplete="off" placeholder="Select date">
                                </div>
                                <div class="icon">
                                    <img src="{{ asset($activeTemplateTrue . 'images/icon/returning.svg') }}" alt="">
                                </div>
                                <div class="right">
                                    <label class="mb-0">@lang('To Date')</label>
                                    <input class="form--control timepicker" name="to_date" type="text" autocomplete="off" placeholder="Select date">
                                </div>
                            </div>
                            <div class="find-form__btn">
                                <button class="btn btn--base w-100" type="submit"><i class="las la-search fs--18px"></i> @lang('Find Now')
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                        <form class="find-form" action="{{ route('seminars') }}" method="GET">
                            <div class="find-form__destination">
                                <div class="left flex-grow-1">
                                    <label class="mb-0">@lang('Location From')</label>
                                    <select class="select2-basic select2" name="location_id[]">
                                        <option value="" selected disabled>@lang('Select location')</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ __($location->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="icon">
                                    <img src="{{ asset($activeTemplateTrue . 'images/icon/two-arrows.svg') }}" alt="image">
                                </div>
                                <div class="right">
                                    <label class="mb-0 mt-2">@lang('Location To')</label>
                                    <select class="select2" name="location_id[]">
                                        <option value="" selected disabled>@lang('Select location')</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ __($location->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="find-form__duration">
                                <div class="left">
                                    <label class="mb-0">@lang('From Date')</label>
                                    <input class="form--control timepicker" name="from_date" type="text" autocomplete="off" placeholder="Select date">
                                </div>
                                <div class="icon">
                                    <img src="{{ asset($activeTemplateTrue . 'images/icon/returning.svg') }}" alt="image">
                                </div>
                                <div class="right">
                                    <label class="mb-0">@lang('To Date')</label>
                                    <input class="form--control timepicker" name="to_date" type="text" autocomplete="off" placeholder="Select date">
                                </div>
                            </div>
                            <div class="find-form__btn">
                                <button class="btn btn--base w-100" type="submit"><i class="las la-search fs--18px"></i> @lang('Find Now')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
