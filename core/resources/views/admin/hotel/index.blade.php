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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Hotel Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Country')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hotels as $hotel)
                                    <tr>
                                        <td>{{ $loop->index + $hotels->firstItem() }}</td>
                                        <td>{{ $hotel->hotel_code }}</td>
                                        <td>
                                            <span class="fw-bold">{{ __($hotel->name) }}</span><br>
                                            <small>
                                            @for ($i = 0; $i < $hotel->star_rating; $i++)
                                                <i class="las la-star text--warning"></i>
                                            @endfor
                                            </small>
                                        </td>
                                        <td>{{ __($hotel->property_type) }}</td>
                                        <td>{{ optional($hotel->country)->name ?? 'N/A' }}</td>
                                        <td>{{ optional($hotel->location)->name ?? 'N/A' }}</td>
                                        <td> @php echo $hotel->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.hotel.manage', $hotel->id) }}" class="btn btn-sm btn-outline--primary">
                                                    <i class="la la-cog"></i>@lang('Manage')
                                                </a>
                                                @if ($hotel->status == 'active')
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.hotel.status', $hotel->id) }}" data-question="@lang('Are you sure to deactivate this hotel? It will no longer appear on the website.')" type="button">
                                                        <i class="la la-eye-slash"></i> @lang('Deactivate')
                                                    </button>
                                                @elseif ($hotel->status == 'inactive' || $hotel->status == 'draft')
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.hotel.status', $hotel->id) }}" data-question="@lang('Are you sure to activate this hotel? Make sure all requirements are met.')" type="button">
                                                        <i class="la la-eye"></i> @lang('Activate')
                                                    </button>
                                                    <button class="btn btn-sm btn-outline--danger ms-1 confirmationBtn" data-action="{{ route('admin.hotel.delete', $hotel->id) }}" data-question="@lang('Are you sure you want to permanently delete this hotel from the database? This action cannot be undone.')" type="button" title="@lang('Delete')">
                                                        <i class="la la-trash"></i>
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
                @if ($hotels->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($hotels) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />

    <a href="{{ route('admin.hotel.create') }}" class="btn btn-sm btn-outline--primary">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
