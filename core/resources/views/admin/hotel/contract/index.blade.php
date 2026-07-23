@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Contract Name')</th>
                                    <th>@lang('Hotel')</th>
                                    <th>@lang('Supplier')</th>
                                    <th>@lang('Start Date')</th>
                                    <th>@lang('End Date')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contracts as $contract)
                                    <tr>
                                        <td>{{ $loop->index + $contracts->firstItem() }}</td>
                                        <td>{{ __($contract->contract_name) }}</td>
                                        <td>{{ optional($contract->hotel)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($contract->supplier)->name ?? 'N/A' }}</td>
                                        <td>{{ showDateTime($contract->start_date, 'd M, Y') }}</td>
                                        <td>{{ showDateTime($contract->end_date, 'd M, Y') }}</td>
                                        <td>
                                            @if($contract->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Disabled')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.hotel.contract.edit', $contract->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                @if ($contract->status == 0)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.hotel.contract.status', $contract->id) }}" data-question="@lang('Are you sure to enable this contract')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.hotel.contract.status', $contract->id) }}" data-question="@lang('Are you sure to disable this contract')?" type="button">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($contracts->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($contracts) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />

    <a href="{{ route('admin.hotel.contract.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
