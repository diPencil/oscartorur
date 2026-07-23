@extends($activeTemplate . 'layouts.master')

@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <form>
                        <div class="mb-3 search-inner-form">
                            <div class="input-group">
                                <input class="form-control form--control" name="search" type="search" value="{{ request()->search }}" placeholder="@lang('Search by Trx')">
                                <button class="input-group-text bg--base text-white">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>
                                        @if (request()->routeIs('user.tour.log'))
                                            @lang('Tour Plan')
                                        @else
                                            @lang('Seminar Plan')
                                        @endif
                                    </th>
                                    <th>@lang('Seat')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Trx. ID')</th>
                                    @if (request()->routeIs('user.tour.log'))
                                        <th>@lang('Departure Time')</th>
                                        <th>@lang('Return Time')</th>
                                    @else
                                        <th>@lang('Start Time')</th>
                                        <th>@lang('End Time')</th>
                                    @endif
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>
                                            @if (request()->routeIs('user.tour.log'))
                                                <div class="table-tour-single  justify-content-lg-start justify-content-end">
                                                    <div class="thumb"><img src="{{ getImage(getFilePath('plan') . '/' . @$log->plan->images[0], getFileSize('plan')) }}" alt="img"></div>
                                                    <div class="content">
                                                        <h6 class="fs--16px"><a href="{{ route('plan.details', [$log->plan_id, slug($log->plan->name)]) }}">{{ __(@$log->plan->name) }}</a></h6>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="table-tour-single  justify-content-lg-start justify-content-end">
                                                    <div class="thumb"><img src="{{ getImage(getFilePath('seminar') . '/' . @$log->seminar->images[0], getFileSize('seminar')) }}" alt="img"></div>
                                                    <div class="content">
                                                        <h6 class="fs--16px"><a href="{{ route('seminar.details', [$log->plan_id, slug($log->seminar->name)]) }}">{{ __(@$log->seminar->name) }}</a></h6>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $log->seat }}</td>
                                        <td><strong>{{ __(showAmount($log->price)) }}</strong></td>
                                        <td>#{{ __($log->trx) }}</td>
                                        @if (request()->routeIs('user.tour.log'))
                                            <td>
                                                {{ showDateTime($log->plan->departure_time) }}<br>{{ diffForHumans($log->plan->departure_time) }}
                                            </td>
                                            <td>
                                                {{ showDateTime($log->plan->return_time) }}<br>{{ diffForHumans($log->plan->return_time) }}
                                            </td>
                                            <td>
                                                @if ($log->plan->departure_time < now())
                                                    <span class="badge badge--success">@lang('Completed')</span>
                                                @else
                                                    <span class="badge badge--warning">@lang('Running')</span>
                                                @endif
                                            </td>
                                        @else
                                            <td>
                                                {{ showDateTime($log->seminar->start_time) }}<br>{{ diffForHumans($log->seminar->start_time) }}
                                            </td>
                                            <td>
                                                {{ showDateTime($log->seminar->end_time) }}<br>{{ diffForHumans($log->seminar->end_time) }}
                                            </td>
                                            <td>
                                                @if ($log->seminar->start_time < now())
                                                    <span class="badge badge--success">@lang('Completed')</span>
                                                @else
                                                    <span class="badge badge--warning">@lang('Running')</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center not-found" colspan="100%">
                                            @include($activeTemplate . 'partials.empty', ['message' => 'Plan log not found!'])
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ paginateLinks($logs) }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
