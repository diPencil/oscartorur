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
                                    <th>@lang('Contract')</th>
                                    <th>@lang('Room Type')</th>
                                    <th>@lang('Allotment')</th>
                                    <th>@lang('Max Extra Beds')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contractRoomTypes as $crt)
                                    <tr>
                                        <td>{{ $loop->index + $contractRoomTypes->firstItem() }}</td>
                                        <td>{{ optional($crt->contract)->contract_name ?? 'N/A' }}</td>
                                        <td>{{ optional($crt->roomType)->name ?? 'N/A' }}</td>
                                        <td>{{ $crt->allotment }}</td>
                                        <td>{{ $crt->max_extra_beds }}</td>
                                        <td>
                                            <a href="{{ route('admin.contract.room.type.edit', $crt->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i>@lang('Edit')
                                            </a>
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
                @if ($contractRoomTypes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($contractRoomTypes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.contract.room.type.create', $contract_id ?? '') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
