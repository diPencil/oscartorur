@extends('Template::layouts.master')
@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card custom--card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">@lang('Booking') #{{ $booking->booking_number }}</h4>
                <div>
                    @if($booking->booking_status != 'cancelled' && $booking->check_in > now()->format('Y-m-d'))
                        <form action="{{ route('user.hotel.booking.cancel', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('@lang('Are you sure you want to cancel this booking?')')">
                                <i class="las la-times-circle"></i> @lang('Cancel Booking')
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('user.hotel.booking') }}" class="btn btn-sm btn-dark"><i class="las la-undo"></i> @lang('Back')</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Hotel')
                                <span class="fw-bold">{{ $booking->hotel->name }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Check In')
                                <span class="fw-bold">{{ showDateTime($booking->check_in, 'd M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Check Out')
                                <span class="fw-bold">{{ showDateTime($booking->check_out, 'd M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Nights')
                                <span class="fw-bold">{{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 mb-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Status')
                                <span>
                                    @if($booking->booking_status == 'pending')
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($booking->booking_status == 'confirmed')
                                        <span class="badge badge--success">@lang('Confirmed')</span>
                                    @elseif($booking->booking_status == 'cancelled')
                                        <span class="badge badge--danger">@lang('Cancelled')</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Payment Status')
                                <span>
                                    @if($booking->payment_status == 'paid')
                                        <span class="badge badge--success">@lang('Paid')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Unpaid')</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Rooms')
                                <span class="fw-bold">{{ $booking->rooms_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Amount')
                                <span class="fw-bold text--base fs-5">{{ $general->cur_sym }}{{ showAmount($booking->total_price) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3 border-bottom pb-2">@lang('Rooms & Guests')</h5>
                @foreach($booking->rooms as $room)
                    <div class="card mb-3 border">
                        <div class="card-header bg-light">
                            <strong>{{ @$room->roomType->name ?? 'Room' }}</strong> - {{ $room->rate_plan_name_snapshot }}
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>@lang('Guests'):</strong></p>
                            <ul>
                                @foreach($booking->guests->where('booking_room_id', $room->id) as $guest)
                                    <li>{{ $guest->first_name }} {{ $guest->last_name }} {!! $guest->is_lead_guest ? '<span class="badge badge--primary">Lead</span>' : '' !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
