@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--primary">
                    <h5 class="card-title text-white mb-0">@lang('Search Inventory')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.inventory.index') }}" method="GET" class="form-row align-items-center">
                        <div class="col-md-4 col-sm-12 mb-2">
                            <select name="contract_room_type_id" class="form-control" required>
                                <option value="">@lang('Select Contract Room Type')</option>
                                @foreach($contractRoomTypes as $crt)
                                    @if($crt->roomType && $crt->contract)
                                        <option value="{{ $crt->id }}" @selected(isset($contract_room_type_id) && $contract_room_type_id == $crt->id)>
                                            {{ $crt->roomType->name }} - {{ $crt->contract->contract_name }}
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

    @if($contract_room_type_id)
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg--dark">
                    <h5 class="card-title text-white mb-0">@lang('Bulk Update Inventory')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.room.inventory.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="contract_room_type_id" value="{{ $contract_room_type_id }}">
                        
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>@lang('From Date')</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}" required>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>@lang('To Date')</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}" required>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('Total Inventory')</label>
                                <input type="number" name="total_inventory" class="form-control" min="0" placeholder="@lang('Leave empty to skip')">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('Blocked Inventory')</label>
                                <input type="number" name="blocked_inventory" class="form-control" min="0" placeholder="@lang('Leave empty to skip')">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>@lang('Stop Sale')</label>
                                <select name="stop_sale" class="form-control" required>
                                    <option value="0">@lang('No')</option>
                                    <option value="1">@lang('Yes')</option>
                                </select>
                            </div>
                            <div class="col-md-12 text-end mt-3">
                                <button type="submit" class="btn btn--primary"><i class="las la-save"></i> @lang('Update Inventory')</button>
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
                                    <th>@lang('Total')</th>
                                    <th>@lang('Reserved')</th>
                                    <th>@lang('Held')</th>
                                    <th>@lang('Blocked')</th>
                                    <th>@lang('Available')</th>
                                    <th>@lang('Stop Sale')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $inventoryMap = [];
                                    foreach($inventories as $inv) {
                                        $inventoryMap[$inv->date] = $inv;
                                    }
                                    
                                    $current = \Carbon\Carbon::parse($start_date);
                                    $end = \Carbon\Carbon::parse($end_date);
                                    
                                    // Get default allotment
                                    $selectedCrt = $contractRoomTypes->where('id', $contract_room_type_id)->first();
                                    $defaultAllotment = $selectedCrt ? $selectedCrt->allotment : 0;
                                @endphp
                                
                                @while($current->lte($end))
                                    @php
                                        $dateStr = $current->format('Y-m-d');
                                        $inv = $inventoryMap[$dateStr] ?? null;
                                        
                                        $total = $inv ? $inv->total_inventory : $defaultAllotment;
                                        $reserved = $inv ? $inv->reserved_inventory : 0;
                                        $held = $inv ? $inv->held_inventory : 0;
                                        $blocked = $inv ? $inv->blocked_inventory : 0;
                                        $stop_sale = $inv ? $inv->stop_sale : 0;
                                        
                                        $available = $total - ($reserved + $held + $blocked);
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $current->format('d M Y') }}</strong><br><small>{{ $current->format('l') }}</small></td>
                                        <td>{{ $total }}</td>
                                        <td><span class="text--warning">{{ $reserved }}</span></td>
                                        <td><span class="text--info">{{ $held }}</span></td>
                                        <td><span class="text--danger">{{ $blocked }}</span></td>
                                        <td>
                                            @if($available > 0)
                                                <span class="badge badge--success">{{ $available }}</span>
                                            @else
                                                <span class="badge badge--danger">{{ $available }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($stop_sale)
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
            <h4 class="text-muted">@lang('Please select a Contract Room Type and Date Range to view/update inventory.')</h4>
        </div>
    </div>
    @endif
@endsection
