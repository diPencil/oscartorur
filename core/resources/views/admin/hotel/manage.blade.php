@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card box--shadow2 b-radius--5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-2">
                                @for ($i = 0; $i < (int)$hotel->star_rating; $i++)
                                    <i class="las la-star text--warning"></i>
                                @endfor
                                {{ app()->getLocale() == 'ar' && $hotel->name_ar ? $hotel->name_ar : ($hotel->name ?? 'New Hotel') }}
                            </h4>
                            <span class="text-muted"><i class="las la-map-marker"></i> {{ app()->getLocale() == 'ar' && @$hotel->location->name_ar ? @$hotel->location->name_ar : (@$hotel->location->name ?? 'Location') }}, {{ @$hotel->country->name ?? 'Country' }}</span>
                        </div>
                        <div class="text-end">
                            <div class="mb-2">@php echo $hotel->statusBadge; @endphp</div>
                            @if($hotel->status != 'active')
                                <button type="button" class="btn btn-sm btn--success confirmationBtn" data-action="{{ route('admin.hotel.status', $hotel->id) }}" data-question="@lang('Are you sure to activate this hotel?')">
                                    <i class="las la-check"></i> @lang('Activate Hotel')
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs--style1" id="hotelManageTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="las la-home"></i> @lang('Overview')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                <i class="las la-info-circle"></i> @lang('Basic Info')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                <i class="las la-images"></i> @lang('Images')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="room-types-tab" data-bs-toggle="tab" data-bs-target="#room-types" type="button" role="tab">
                                <i class="las la-bed"></i> @lang('Room Types')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="amenities-tab" data-bs-toggle="tab" data-bs-target="#amenities" type="button" role="tab">
                                <i class="las la-wifi"></i> @lang('Amenities')
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contracts-tab" data-bs-toggle="tab" data-bs-target="#contracts" type="button" role="tab">
                                <i class="las la-file-contract"></i> @lang('Contracts')
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-4" id="hotelManageTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                                    <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
                                        <div class="icon">
                                            <i class="las la-bed"></i>
                                        </div>
                                        <div class="details">
                                            <div class="numbers">
                                                <span class="amount">{{ $hotel->roomTypes->count() }}</span>
                                            </div>
                                            <div class="desciption">
                                                <span class="text--small">@lang('Room Types')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                                    <div class="dashboard-w1 bg--success b-radius--10 box-shadow">
                                        <div class="icon">
                                            <i class="las la-door-open"></i>
                                        </div>
                                        <div class="details">
                                            <div class="numbers">
                                                <span class="amount">{{ $hotel->roomTypes->sum('base_inventory') }}</span>
                                            </div>
                                            <div class="desciption">
                                                <span class="text--small">@lang('Rooms')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                                    <div class="dashboard-w1 bg--warning b-radius--10 box-shadow">
                                        <div class="icon">
                                            <i class="las la-image"></i>
                                        </div>
                                        <div class="details">
                                            <div class="numbers">
                                                <span class="amount">{{ $hotel->images()->where('is_cover', 0)->count() }}</span>
                                            </div>
                                            <div class="desciption">
                                                <span class="text--small">@lang('Gallery Images')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                                    <div class="dashboard-w1 bg--info b-radius--10 box-shadow">
                                        <div class="icon">
                                            <i class="las la-star"></i>
                                        </div>
                                        <div class="details">
                                            <div class="numbers">
                                                <span class="amount">{{ $hotel->star_rating }}</span>
                                            </div>
                                            <div class="desciption">
                                                <span class="text--small">@lang('Stars')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card b-radius--10 box-shadow">
                                        <div class="card-header bg--dark">
                                            <h5 class="card-title text-white mb-0"><i class="las la-clipboard-list"></i> @lang('Activation Readiness Checklist')</h5>
                                        </div>
                                        <div class="card-body p-0">
                                            @if(count($activationErrors) == 0)
                                                <div class="p-4 text-center">
                                                    <i class="las la-check-circle text--success" style="font-size: 50px;"></i>
                                                    <h4 class="mt-2 text--success">@lang('All Requirements Met!')</h4>
                                                    <p class="text-muted">@lang('This hotel is fully configured and ready to be activated on the platform.')</p>
                                                </div>
                                            @else
                                                <div class="p-3 bg--light">
                                                    <p class="mb-0 text--danger fw-bold"><i class="las la-exclamation-circle"></i> @lang('Please resolve the following issues to activate the hotel:')</p>
                                                </div>
                                                <ul class="list-group list-group-flush">
                                                    @foreach($activationErrors as $error)
                                                        <li class="list-group-item d-flex align-items-center">
                                                            <div class="me-3 ms-3">
                                                                <span class="badge badge--danger badge-pill"><i class="las la-times"></i></span>
                                                            </div>
                                                            <div class="fw-bold text-muted" dir="auto">
                                                                {{ $error }}
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-lg-0 mt-4">
                                    <div class="card b-radius--10 box-shadow">
                                        <div class="card-body text-center p-4">
                                            @php
                                                $cover = $hotel->images()->where('is_cover', 1)->first();
                                            @endphp
                                            @if($cover)
                                                <img src="{{ getImage(getFilePath('hotelImage').'/'.$cover->image, getFileSize('hotelImage')) }}" alt="Cover" class="img-fluid b-radius--10 mb-3" style="max-height: 200px; width: 100%; object-fit: cover;">
                                            @else
                                                <div class="bg--light b-radius--10 d-flex justify-content-center align-items-center mb-3" style="height: 150px;">
                                                    <span class="text-muted"><i class="las la-image" style="font-size: 40px;"></i><br>@lang('No Cover Image')</span>
                                                </div>
                                            @endif
                                            <h5 class="mb-2">{{ $hotel->name }}</h5>
                                            <p class="text-muted text-sm mb-3"><i class="las la-map-marker"></i> {{ $hotel->address }}</p>
                                            
                                            <div class="d-flex justify-content-between border-top pt-3">
                                                <span class="fw-bold">@lang('Current Status')</span>
                                                <span>@php echo $hotel->statusBadge; @endphp</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade" id="basic" role="tabpanel">
                            @include('admin.hotel.tabs.basic_info')
                        </div>

                        <!-- Images Tab -->
                        <div class="tab-pane fade" id="images" role="tabpanel">
                            @include('admin.hotel.tabs.images')
                        </div>

                        <!-- Room Types Tab -->
                        <div class="tab-pane fade" id="room-types" role="tabpanel">
                            @include('admin.hotel.tabs.room_types')
                        </div>

                        <!-- Amenities Tab -->
                        <div class="tab-pane fade" id="amenities" role="tabpanel">
                            @include('admin.hotel.tabs.amenities')
                        </div>

                        <!-- Contracts Tab -->
                        <div class="tab-pane fade" id="contracts" role="tabpanel">
                            @include('admin.hotel.tabs.contracts')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.hotel.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-undo"></i> @lang('Back to List')
    </a>
@endpush
