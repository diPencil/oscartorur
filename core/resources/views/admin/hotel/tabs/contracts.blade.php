<div class="row">
    <div class="col-md-12">
        <div class="card border--dark">
            <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">@lang('Hotel Contracts')</h5>
                <a href="{{ route('admin.hotel.contract.index', $hotel->id) }}" class="btn btn-sm btn-outline-light">
                    <i class="las la-cog"></i> @lang('Manage Contracts')
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Supplier')</th>
                                <th>@lang('Inventory Mode')</th>
                                <th>@lang('Status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $contracts = \App\Models\HotelContract::where('hotel_id', $hotel->id)->get();
                            @endphp
                            @forelse($contracts as $contract)
                                <tr>
                                    <td><strong>{{ $contract->title }}</strong></td>
                                    <td>{{ $contract->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $contract->inventory_mode)) }}</td>
                                    <td>
                                        @if($contract->status == 1)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted">@lang('No contracts added yet. Please manage contracts to add one.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
