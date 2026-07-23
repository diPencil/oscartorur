@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.contract.room.type.store', @$contractRoomType->id) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>@lang('Contract')</label>
                                <select name="contract_id" class="form-control" required>
                                    <option value="">@lang('Select Contract')</option>
                                    @foreach ($contracts as $contract)
                                        <option value="{{ $contract->id }}" @selected(old('contract_id', @$contractRoomType->contract_id ?? @$contract_id) == $contract->id)>{{ __($contract->contract_name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Room Type')</label>
                                <select name="room_type_id" class="form-control" required>
                                    <option value="">@lang('Select Room Type')</option>
                                    @foreach ($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}" @selected(old('room_type_id', @$contractRoomType->room_type_id) == $roomType->id)>{{ __($roomType->name) }} ({{ optional($roomType->hotel)->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label>@lang('Allotment (Rooms available per day)')</label>
                                <input type="number" name="allotment" class="form-control" value="{{ old('allotment', @$contractRoomType->allotment ?? 0) }}" min="0" required>
                                <small class="text-muted">@lang('Set 0 if On Request or Shared Inventory')</small>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Max Extra Beds')</label>
                                <input type="number" name="max_extra_beds" class="form-control" value="{{ old('max_extra_beds', @$contractRoomType->max_extra_beds ?? 0) }}" min="0" required>
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
    <a href="{{ route('admin.contract.room.type.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-undo"></i>@lang('Back')
    </a>
@endpush
