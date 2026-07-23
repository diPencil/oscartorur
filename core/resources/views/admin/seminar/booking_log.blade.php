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
                                    <th>@lang('TRX')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Seat')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Start Time')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($planLogs as $log)
                                    <tr>
                                        <td>
                                            {{ $log->trx }}
                                        </td>

                                        <td>
                                            {{ $log->user->fullname }}
                                            <br>
                                            <small> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a> </small>
                                        </td>

                                        <td>{{ __($log->seminar->name) }}</td>

                                        <td>{{ $log->seat }}</td>
                                        <td>{{ showAmount($log->price) }}</td>
                                        <td> {{ showDateTime($log->seminar->start_time) }}<br>{{ diffForHumans($log->seminar->start_time) }}</td>

                                        <td>
                                            @if ($log->seminar->start_time < now())
                                                <span class="badge badge--success">@lang('Completed')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Running')</span>
                                            @endif
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
                @if ($planLogs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($planLogs) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Plan|trx" />
@endpush
