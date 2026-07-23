@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Start Time') | @lang('End Time')</th>
                                    <th>@lang('Capacity')</th>
                                    <th>@lang('Sold')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($seminars as $item)
                                    <tr>
                                        <td>{{ __($item->name) }}</td>
                                        <td>{{ __($item->category->name) }}</td>
                                        <td>{{ __($item->location->name) }}</td>
                                        <td>{{ $item->duration }} @lang('Days')</td>
                                        <td> <span class="text--primary">{{ showDateTime($item->start_time) }}</span> <br>-<br> <span class="text--warning">{{ showDateTime($item->end_time) }}</span></td>

                                        <td>{{ $item->capacity }}</td>
                                        <td>{{ $item->sold }}</td>
                                        <td>{{ showAmount($item->price) }}</td>
                                        <td> @php echo $item->statusBadge; @endphp </td>
                                        <td>

                                            <div class="button--group">
                                                <button class="btn btn-outline--info btn--sm" data-bs-toggle="dropdown" type="button" aria-expanded="false">
                                                    <i class="las la-ellipsis-v"></i>@lang('More')
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('admin.seminar.edit', $item->id) }}">
                                                        <i class="la la-pencil"></i> @lang('Edit')
                                                    </a>
                                                    @if ($item->status)
                                                        <button class="dropdown-item confirmationBtn" data-action="{{ route('admin.seminar.status', $item->id) }}" data-question="@lang('Are you sure to enable this seater')?" type="button">
                                                            <i class="la la-eye-slash"></i> @lang('Disable')
                                                        </button>
                                                    @else
                                                        <button class="dropdown-item confirmationBtn" data-action="{{ route('admin.seminar.status', $item->id) }}" data-question="@lang('Are you sure to disable this seater')?" type="button">
                                                            <i class="la la-eye"></i> @lang('Enable')
                                                        </button>
                                                    @endif
                                                    <a class="dropdown-item" href="{{ route('admin.seminar.page.seo', $item->id) }}">
                                                        <i class="la la-cog"></i> @lang('SEO Setting')
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($seminars->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($seminars) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Name|Location" />
    <a class="btn btn-sm btn-outline--primary" href="{{ route('admin.seminar.add') }}">
        <i class="las la-plus"></i>@lang('Add New')
    </a>
@endpush
