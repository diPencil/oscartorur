@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Code')</th>
                                    <th>@lang('Credit Limit')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agencies as $agency)
                                    <tr>
                                        <td>{{ $agencies->firstItem() + $loop->index }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $agency->name }}</span>
                                        </td>
                                        <td>{{ $agency->code }}</td>
                                        <td>{{ showAmount($agency->credit_limit) }} {{ gs('cur_text') }}</td>
                                        <td>
                                            @php echo $agency->statusBadge; @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary cuModalBtn"
                                                data-resource="{{ $agency }}" data-modal_title="@lang('Update Agency')">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
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
                @if ($agencies->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($agencies) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Create / Update Modal --}}
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.agencies.store') }}" method="POST">
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
                                    <label>@lang('Agency Name')</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="lang-ar" role="tabpanel" dir="rtl">
                                <div class="form-group">
                                    <label class="d-block text-end">@lang('Agency Name (Arabic)')</label>
                                    <input class="form-control" name="name_ar" type="text" dir="rtl">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Agency Code')</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Credit Limit')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="credit_limit" class="form-control" value="0">
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group statusGroup">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success"
                                data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Active')"
                                data-off="@lang('Inactive')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add New Agency')">
        <i class="las la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.cuModalBtn').on('click', function() {
                let modal = $('#cuModal');
                let resource = $(this).data('resource');
                if(resource) {
                    modal.find('form').attr('action', `{{ route('admin.agencies.store') }}`.replace('store', `update/${resource.id}`));
                    modal.find('[name=name]').val(resource.name);
                    modal.find('[name=name_ar]').val(resource.name_ar);
                    modal.find('[name=code]').val(resource.code);
                    modal.find('[name=credit_limit]').val(parseFloat(resource.credit_limit).toFixed(2));
                    
                    if(resource.status == 1) {
                        modal.find('[name=status]').bootstrapToggle('on');
                    } else {
                        modal.find('[name=status]').bootstrapToggle('off');
                    }
                    modal.find('.statusGroup').show();
                } else {
                    modal.find('form').attr('action', `{{ route('admin.agencies.store') }}`);
                    modal.find('.statusGroup').hide();
                    modal.find('form')[0].reset();
                }
            });
        })(jQuery);
    </script>
@endpush
