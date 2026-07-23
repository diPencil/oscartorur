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
                                    <th>@lang('Icon')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($amenities as $amenity)
                                    <tr>
                                        <td>{{ $loop->index + $amenities->firstItem() }}</td>
                                        <td>@php echo $amenity->icon; @endphp</td>
                                        <td>{{ __($amenity->name) }}</td>
                                        <td>{{ ucfirst($amenity->type) }}</td>
                                        <td> @php echo $amenity->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $amenity }}" data-modal_title="@lang('Edit Amenity')" data-has_status="1" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($amenity->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.amenity.status', $amenity->id) }}" data-question="@lang('Are you sure to enable this amenity')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.amenity.status', $amenity->id) }}" data-question="@lang('Are you sure to disable this amenity')?" type="button">
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
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($amenities->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($amenities) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>

    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.amenity.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <ul class="nav nav-tabs nav-tabs--style1 mb-3" role="tablist">
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

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="lang-en" role="tabpanel">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input class="form-control" name="name" type="text" required>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                                <div class="form-group">
                                    <label class="d-block text-end">@lang('Name (Arabic)')</label>
                                    <input class="form-control" name="name_ar" type="text" dir="rtl">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Icon')</label>
                            <input class="form-control" name="icon" type="text" placeholder="<i class='las la-wifi'></i>">
                            <small class="text-muted">@lang('Use FontAwesome or LineAwesome icon HTML tag')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Type')</label>
                            <select class="form-control" name="type" required>
                                <option value="hotel">@lang('Hotel Amenity')</option>
                                <option value="room">@lang('Room Amenity')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-search-form />

    <button class="btn btn-sm btn-outline--primary cuModalBtn" type="button" data-modal_title="@lang('Add Amenity')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
