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
                                    <th>@lang('Contract Room')</th>
                                    <th>@lang('Cancel Policy')</th>
                                    <th>@lang('Payment')</th>
                                    <th>@lang('Refundable')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ratePlans as $ratePlan)
                                    <tr>
                                        <td>{{ $loop->index + $ratePlans->firstItem() }}</td>
                                        <td>{{ __($ratePlan->name) }}</td>
                                        <td>
                                            @if($ratePlan->contractRoomType && $ratePlan->contractRoomType->roomType && $ratePlan->contractRoomType->contract)
                                                {{ $ratePlan->contractRoomType->roomType->name }} <br>
                                                <small class="text-muted">{{ $ratePlan->contractRoomType->contract->contract_name }}</small>
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>
                                        <td>{{ optional($ratePlan->cancellationPolicy)->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $ratePlan->payment_type)) }}</td>
                                        <td>
                                            @if($ratePlan->refundable)
                                                <span class="badge badge--success">@lang('Refundable')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Non-Refundable')</span>
                                            @endif
                                        </td>
                                        <td> @php echo $ratePlan->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $ratePlan }}" data-modal_title="@lang('Edit Rate Plan')" data-has_status="1" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($ratePlan->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.rate.plan.status', $ratePlan->id) }}" data-question="@lang('Are you sure to enable this rate plan')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.rate.plan.status', $ratePlan->id) }}" data-question="@lang('Are you sure to disable this rate plan')?" type="button">
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
                @if ($ratePlans->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($ratePlans) }}
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
                <form action="{{ route('admin.rate.plan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Contract Room Type')</label>
                            <select name="contract_room_type_id" class="form-control" required>
                                <option value="">@lang('Select Room')</option>
                                @foreach($contractRoomTypes as $crt)
                                    @if($crt->roomType && $crt->contract)
                                    <option value="{{ $crt->id }}" @selected(isset($contract_room_type_id) && $contract_room_type_id == $crt->id)>
                                        {{ $crt->roomType->name }} - {{ $crt->contract->contract_name }}
                                    </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
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
                                    <input class="form-control" name="name" type="text" placeholder="@lang('e.g. Standard Rate / Bed & Breakfast')" required>
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
                            <label>@lang('Cancellation Policy')</label>
                            <select name="cancellation_policy_id" class="form-control">
                                <option value="">@lang('Select Policy (Optional)')</option>
                                @foreach($cancellationPolicies as $cp)
                                    <option value="{{ $cp->id }}">{{ $cp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Payment Type')</label>
                            <select name="payment_type" class="form-control">
                                <option value="">@lang('Select Type')</option>
                                <option value="prepaid">@lang('Prepaid')</option>
                                <option value="post_paid">@lang('Post Paid (Pay at Hotel)')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Refundable Status')</label>
                            <select name="refundable" class="form-control" required>
                                <option value="1">@lang('Refundable')</option>
                                <option value="0">@lang('Non-Refundable')</option>
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

    <button class="btn btn-sm btn-outline--primary cuModalBtn" type="button" data-modal_title="@lang('Add Rate Plan')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
