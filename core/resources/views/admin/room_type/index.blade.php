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
                                    <th>@lang('Room Type')</th>
                                    <th>@lang('Hotel')</th>
                                    <th>@lang('Capacity')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roomTypes as $roomType)
                                    <tr>
                                        <td>{{ $loop->index + $roomTypes->firstItem() }}</td>
                                        <td>{{ __($roomType->name) }}</td>
                                        <td>{{ optional($roomType->hotel)->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="d-block">@lang('Base'): {{ $roomType->base_capacity }}</span>
                                            <span class="d-block text--small">@lang('Max Adult'): {{ $roomType->max_adults }}, @lang('Max Child'): {{ $roomType->max_children }}</span>
                                        </td>
                                        <td> @php echo $roomType->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.room.type.edit', $roomType->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </a>
                                                @if ($roomType->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.room.type.status', $roomType->id) }}" data-question="@lang('Are you sure to enable this room type')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.room.type.status', $roomType->id) }}" data-question="@lang('Are you sure to disable this room type')?" type="button">
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
                @if ($roomTypes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($roomTypes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />

    <a href="{{ route('admin.room.type.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
