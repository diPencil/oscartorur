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
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Target (Hotel / Supplier)')</th>
                                    <th>@lang('Customer & Market')</th>
                                    <th>@lang('Markup')</th>
                                    <th>@lang('Validity')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rules as $rule)
                                    <tr>
                                        <td><span class="badge badge--primary">{{ $rule->priority }}</span></td>
                                        <td>
                                            @if($rule->hotel_id)
                                                <span class="d-block text--info"><i class="las la-building"></i> {{ optional($rule->hotel)->name }}</span>
                                            @endif
                                            @if($rule->supplier_id)
                                                <span class="d-block text--warning"><i class="las la-truck"></i> {{ optional($rule->supplier)->name }}</span>
                                            @endif
                                            @if(!$rule->hotel_id && !$rule->supplier_id)
                                                <span class="badge badge--dark">@lang('Global / All')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="d-block">@lang('Cust:'): {{ $rule->customer_type ? strtoupper($rule->customer_type) : trans('All') }}</span>
                                            <span class="d-block text--small">@lang('Market:'): {{ $rule->market ?? trans('All') }}</span>
                                        </td>
                                        <td>
                                            @if($rule->markup_type == 'percentage')
                                                {{ $rule->markup_value }}%
                                            @elseif($rule->markup_type == 'fixed_amount')
                                                {{ $general->cur_sym }}{{ showAmount($rule->markup_value) }} @lang('Fixed')
                                            @elseif($rule->markup_type == 'per_night')
                                                {{ $general->cur_sym }}{{ showAmount($rule->markup_value) }} / @lang('Night')
                                            @else
                                                {{ $general->cur_sym }}{{ showAmount($rule->markup_value) }} / @lang('Booking')
                                            @endif
                                        </td>
                                        <td>
                                            @if($rule->start_date && $rule->end_date)
                                                {{ showDateTime($rule->start_date, 'd M Y') }} - {{ showDateTime($rule->end_date, 'd M Y') }}
                                            @else
                                                <span class="badge badge--success">@lang('Always Valid')</span>
                                            @endif
                                        </td>
                                        <td> @php echo $rule->statusBadge;  @endphp </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editBtn cuModalBtn" data-resource="{{ $rule }}" data-modal_title="@lang('Edit Markup Rule')" data-has_status="1" type="button">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($rule->status == Status::DISABLE)
                                                    <button class="btn btn-sm btn-outline--success ms-1 confirmationBtn" data-action="{{ route('admin.markup.rule.status', $rule->id) }}" data-question="@lang('Are you sure to enable this rule')?" type="button">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.markup.rule.status', $rule->id) }}" data-question="@lang('Are you sure to disable this rule')?" type="button">
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
                @if ($rules->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($rules) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div class="modal fade" id="cuModal" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button class="close" data-bs-dismiss="modal" type="button" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.markup.rule.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>@lang('Specific Hotel') <small class="text-muted">(@lang('Optional'))</small></label>
                                <select name="hotel_id" class="form-control">
                                    <option value="">@lang('All Hotels')</option>
                                    @foreach($hotels as $h)
                                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Specific Supplier') <small class="text-muted">(@lang('Optional'))</small></label>
                                <select name="supplier_id" class="form-control">
                                    <option value="">@lang('All Suppliers')</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Customer Type')</label>
                                <select name="customer_type" class="form-control">
                                    <option value="">@lang('All Types')</option>
                                    <option value="b2c">@lang('B2C (Direct Users)')</option>
                                    <option value="b2b">@lang('B2B (Agencies)')</option>
                                    <option value="corporate">@lang('Corporate')</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Market')</label>
                                <input class="form-control" name="market" type="text" placeholder="@lang('e.g. GCC, Europe (Optional)')">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Markup Type')</label>
                                <select name="markup_type" class="form-control" required>
                                    <option value="percentage">@lang('Percentage (%)')</option>
                                    <option value="fixed_amount">@lang('Fixed Amount (Total)')</option>
                                    <option value="per_night">@lang('Fixed per Night')</option>
                                    <option value="per_booking">@lang('Fixed per Booking')</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>@lang('Markup Value')</label>
                                <input class="form-control" name="markup_value" type="number" step="any" min="0" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Start Date')</label>
                                <input class="form-control" name="start_date" type="date">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('End Date')</label>
                                <input class="form-control" name="end_date" type="date">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>@lang('Priority')</label>
                                <input class="form-control" name="priority" type="number" min="0" value="0" required>
                                <small class="text-muted">@lang('Higher number = Higher priority')</small>
                            </div>
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
    <button class="btn btn-sm btn-outline--primary cuModalBtn" type="button" data-modal_title="@lang('Add Markup Rule')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush
