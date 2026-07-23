@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="notice mb-3"></div>
            <div class="row gy-4">
                <div class="col-lg-3 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="las la-globe "></i>
                            <a class="view-all" href="{{ route('user.tour.log') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number ">{{ $widget['total_tours'] }}</h3>
                            <p class="caption ">@lang('Total Tours')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="lab la-telegram-plane "></i>
                            <a class="view-all" href="{{ route('user.tour.log') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number ">{{ $widget['upcoming_tours'] }}</h3>
                            <p class="caption ">@lang('Upcoming Tours')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="las la-calendar "></i>
                            <a class="view-all" href="{{ route('user.seminar.log') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number ">{{ $widget['total_seminars'] }}</h3>
                            <p class="caption ">@lang('Total Day Trips')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="las la-calendar-day"></i>
                            <a class="view-all" href="{{ route('user.seminar.log') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number">{{ $widget['upcoming_seminars'] }}</h3>
                            <p class="caption ">@lang('Upcoming Day Trips')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="las la-hotel"></i>
                            <a class="view-all" href="{{ route('user.hotel.booking') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number ">{{ $widget['total_hotel_bookings'] }}</h3>
                            <p class="caption ">@lang('Total Hotel Bookings')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
                <div class="col-lg-6 col-sm-6">
                    <div class="d-widget">
                        <div class="d-widget__icon">
                            <i class="las la-calendar-check"></i>
                            <a class="view-all" href="{{ route('user.hotel.booking') }}">@lang('View All')</a>
                        </div>
                        <div class="d-widget__content">
                            <h3 class="d-widget__number">{{ $widget['upcoming_hotel_bookings'] }}</h3>
                            <p class="caption ">@lang('Upcoming Hotel Stays')</p>
                        </div>
                    </div><!-- d-widget end -->
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-12">
                    @include($activeTemplate . 'partials.payment')
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-12">
                    <h5 class="mb-3">@lang('Latest Hotel Bookings')</h5>
                    <div class="table-responsive">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Booking Number')</th>
                                    <th>@lang('Hotel')</th>
                                    <th>@lang('Check In') - @lang('Check Out')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($hotelBookings as $booking)
                                    <tr>
                                        <td><span class="fw-bold">{{ $booking->booking_number }}</span></td>
                                        <td>{{ $booking->hotel->name ?? 'N/A' }}</td>
                                        <td>
                                            <div>{{ showDateTime($booking->check_in, 'd M, Y') }}</div>
                                            <div>{{ showDateTime($booking->check_out, 'd M, Y') }}</div>
                                        </td>
                                        <td>{{ showAmount($booking->total_price) }}</td>
                                        <td>
                                            @if($booking->booking_status == 'confirmed')
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @elseif($booking->booking_status == 'cancelled')
                                                <span class="badge badge--danger">@lang('Cancelled')</span>
                                            @elseif($booking->booking_status == 'completed')
                                                <span class="badge badge--primary">@lang('Completed')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">
                                            @lang('No hotel bookings found')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
