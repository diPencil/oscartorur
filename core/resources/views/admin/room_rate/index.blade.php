@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--primary">
                    <h5 class="card-title text-white mb-0">@lang('Search Rates')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.rate.index') }}" method="GET" class="form-row align-items-center">
                        <div class="col-md-4 col-sm-12 mb-2">
                            <select name="rate_plan_id" class="form-control" required>
                                <option value="">@lang('Select Rate Plan')</option>
                                @foreach($ratePlans as $rp)
                                    @if($rp->contractRoomType && $rp->contractRoomType->roomType && $rp->contractRoomType->contract)
                                        <option value="{{ $rp->id }}" @selected(isset($rate_plan_id) && $rate_plan_id == $rp->id)>
                                            {{ $rp->name }} - {{ $rp->contractRoomType->roomType->name }} ({{ $rp->contractRoomType->contract->contract_name }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-2">
                            <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
                        </div>
                        <div class="col-md-3 col-sm-12 mb-2">
                            <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
                        </div>
                        <div class="col-md-2 col-sm-12 mb-2">
                            <button type="submit" class="btn btn--primary w-100"><i class="las la-search"></i> @lang('View')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($rate_plan_id)
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--dark">
                    <h5 class="card-title text-white mb-0">@lang('Bulk Update Rates')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.rate.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="rate_plan_id" value="{{ $rate_plan_id }}">
                        
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>@lang('From Date')</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('To Date')</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('Cost Price') ({{ gs()->cur_text }})</label>
                                <input type="number" name="cost_price" step="any" class="form-control" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('Selling Price') ({{ gs()->cur_text }})</label>
                                <input type="number" name="selling_price" step="any" class="form-control" min="0" placeholder="@lang('Leave empty to skip')">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('Single Supplement')</label>
                                <input type="number" name="single_supplement" step="any" class="form-control" min="0" placeholder="@lang('Leave empty to skip')">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('Extra Adult Price')</label>
                                <input type="number" name="extra_adult_price" step="any" class="form-control" min="0" placeholder="@lang('Leave empty to skip')">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('Min Stay')</label>
                                <input type="number" name="minimum_stay" class="form-control" min="1" placeholder="@lang('Skip')">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('CTA (Closed to Arrival)')</label>
                                <select name="closed_to_arrival" class="form-control">
                                    <option value="">@lang('Skip')</option>
                                    <option value="1">@lang('Yes')</option>
                                    <option value="0">@lang('No')</option>
                                </select>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('CTD (Closed to Departure)')</label>
                                <select name="closed_to_departure" class="form-control">
                                    <option value="">@lang('Skip')</option>
                                    <option value="1">@lang('Yes')</option>
                                    <option value="0">@lang('No')</option>
                                </select>
                            </div>
                            
                            <div class="col-md-12 text-end mt-3">
                                <button type="submit" class="btn btn--primary"><i class="las la-save"></i> @lang('Update Rates')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Cost Price')</th>
                                    <th>@lang('Selling Price')</th>
                                    <th>@lang('SGL Supp.')</th>
                                    <th>@lang('Extra Adult')</th>
                                    <th>@lang('Min Stay')</th>
                                    <th>@lang('CTA')</th>
                                    <th>@lang('CTD')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ratesMap = [];
                                    foreach($rates as $r) {
                                        $ratesMap[$r->date] = $r;
                                    }
                                    
                                    $current = \Carbon\Carbon::parse($start_date);
                                    $end = \Carbon\Carbon::parse($end_date);
                                @endphp
                                
                                @while($current->lte($end))
                                    @php
                                        $dateStr = $current->format('Y-m-d');
                                        $rate = $ratesMap[$dateStr] ?? null;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $current->format('d M Y') }}</strong><br><small>{{ $current->format('l') }}</small></td>
                                        <td>{{ gs()->cur_sym }}{{ showAmount($rate ? $rate->cost_price : 0) }}</td>
                                        <td><strong>{{ gs()->cur_sym }}{{ showAmount($rate ? $rate->selling_price : 0) }}</strong></td>
                                        <td>{{ gs()->cur_sym }}{{ showAmount($rate ? $rate->single_supplement : 0) }}</td>
                                        <td>{{ gs()->cur_sym }}{{ showAmount($rate ? $rate->extra_adult_price : 0) }}</td>
                                        <td>{{ $rate ? $rate->minimum_stay : 1 }} @lang('Night(s)')</td>
                                        <td>
                                            @if($rate && $rate->closed_to_arrival)
                                                <span class="badge badge--danger">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--success">@lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rate && $rate->closed_to_departure)
                                                <span class="badge badge--danger">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--success">@lang('No')</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $current->addDay(); @endphp
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-lg-12 text-center mt-5">
            <h4 class="text-muted">@lang('Please select a Rate Plan and Date Range to view/update rates.')</h4>
        </div>
    </div>
    @endif
@endsection
