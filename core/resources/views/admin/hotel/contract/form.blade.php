@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.hotel.contract.store', @$contract->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
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
                                            <label>@lang('Contract Name')</label>
                                            <input type="text" name="contract_name" class="form-control" value="{{ old('contract_name', @$contract->contract_name) }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                                        <div class="form-group">
                                            <label class="d-block text-end">@lang('Contract Name (Arabic)')</label>
                                            <input type="text" name="contract_name_ar" class="form-control" value="{{ old('contract_name_ar', @$contract->contract_name_ar) }}" dir="rtl">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Hotel')</label>
                                <select name="hotel_id" class="form-control" required>
                                    <option value="">@lang('Select Hotel')</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id }}" @selected(old('hotel_id', @$contract->hotel_id ?? @$hotel_id) == $hotel->id)>{{ __($hotel->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Supplier')</label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">@lang('Select Supplier (Optional)')</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" @selected(old('supplier_id', @$contract->supplier_id) == $supplier->id)>{{ __($supplier->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Start Date')</label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', @$contract->start_date) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('End Date')</label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', @$contract->end_date) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Market / Nationality Restriction')</label>
                                <input type="text" name="market" class="form-control" value="{{ old('market', @$contract->market) }}" placeholder="@lang('e.g. GCC Only')">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Release Days')</label>
                                <input type="number" name="release_days" class="form-control" value="{{ old('release_days', @$contract->release_days ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Confirmation Mode')</label>
                                <select name="confirmation_mode" class="form-control" required>
                                    <option value="instant" @selected(old('confirmation_mode', @$contract->confirmation_mode) == 'instant')>@lang('Instant Confirmation')</option>
                                    <option value="on_request" @selected(old('confirmation_mode', @$contract->confirmation_mode) == 'on_request')>@lang('On Request')</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Payment Terms')</label>
                                <input type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms', @$contract->payment_terms) }}" placeholder="@lang('e.g. Prepaid 7 Days')">
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
    <a href="{{ route('admin.hotel.contract.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-undo"></i>@lang('Back')
    </a>
@endpush
