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
                                    <th>@lang('Name')</th>
                                    <th>@lang('Days Before Check-in')</th>
                                    <th>@lang('Penalty')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($policies as $policy)
                                    <tr>
                                        <td>{{ $loop->index + $policies->firstItem() }}</td>
                                        <td>{{ __($policy->name) }}</td>
                                        <td>{{ $policy->days_before_checkin }} @lang('Days')</td>
                                        <td>
                                            @if($policy->penalty_type == 'percentage')
                                                {{ $policy->penalty_value }}%
                                            @elseif($policy->penalty_type == 'fixed')
                                                {{ $general->cur_sym }}{{ showAmount($policy->penalty_value) }}
                                            @else
                                                {{ $policy->penalty_value }} @lang('Night(s)')
                                            @endif
                                        </td>
                                        <td> @php echo $policy->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $policy }}" data-modal_title="@lang('Edit Cancellation Policy')" data-has_status="1" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($policy->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.cancellation.policy.status', $policy->id) }}" data-question="@lang('Are you sure to enable this policy')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.cancellation.policy.status', $policy->id) }}" data-question="@lang('Are you sure to disable this policy')?" type="button">
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
                @if ($policies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($policies) }}
                    </div>
                @endif
            </div>
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
                <form action="{{ route('admin.cancellation.policy.store') }}" method="POST">
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
                                    <input class="form-control" name="name" type="text" placeholder="@lang('e.g. 7 Days Free Cancellation')" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                                <div class="form-group">
                                    <label class="d-block text-end">@lang('Name (Arabic)')</label>
                                    <input class="form-control" name="name_ar" type="text" dir="rtl">
                                </div>
                                <div class="form-group">
                                    <label class="d-block text-end">@lang('Description (Arabic)')</label>
                                    <textarea name="description_ar" class="form-control" rows="3" dir="rtl"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Days Before Check-in')</label>
                            <input class="form-control" name="days_before_checkin" type="number" min="0" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Penalty Type')</label>
                            <select name="penalty_type" class="form-control" required>
                                <option value="percentage">@lang('Percentage (%)')</option>
                                <option value="fixed">@lang('Fixed Amount')</option>
                                <option value="nights">@lang('Number of Nights')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Penalty Value')</label>
                            <input class="form-control" name="penalty_value" type="number" step="any" min="0" required>
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

    <button class="btn btn-sm btn-outline--primary cuModalBtn" type="button" data-modal_title="@lang('Add Cancellation Policy')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
