@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xl-4 col-md-6">
            <div class="card b-radius--10 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Booking Information')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Booking Number')
                            <span class="fw-bold">{{ $booking->booking_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Hotel')
                            <span class="fw-bold">{{ $booking->hotel->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Check In')
                            <span class="fw-bold">{{ showDateTime($booking->check_in, 'd M, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Check Out')
                            <span class="fw-bold">{{ showDateTime($booking->check_out, 'd M, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Rooms')
                            <span class="fw-bold">{{ $booking->rooms_count }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="fw-bold text--base">{{ showAmount($booking->total_price) }} {{ gs()->cur_text }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card b-radius--10 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('User Information')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($booking->user)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Name')
                                <span class="fw-bold">{{ $booking->user->fullname }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Username')
                                <span class="fw-bold"><a href="{{ route('admin.users.detail', $booking->user->id) }}"><span>@</span>{{ $booking->user->username }}</a></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Email')
                                <span class="fw-bold">{{ $booking->user->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Mobile')
                                <span class="fw-bold">{{ $booking->user->mobile }}</span>
                            </li>
                        @else
                            <li class="list-group-item d-flex justify-content-center align-items-center">
                                <span class="fw-bold text-muted">@lang('Guest User')</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div class="card b-radius--10 h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Update Status')</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hotel.booking.status', $booking->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Booking Status')</label>
                            <select name="booking_status" class="form-control" required>
                                <option value="pending" @selected($booking->booking_status == 'pending')>@lang('Pending')</option>
                                <option value="confirmed" @selected($booking->booking_status == 'confirmed')>@lang('Confirmed')</option>
                                <option value="cancelled" @selected($booking->booking_status == 'cancelled')>@lang('Cancelled')</option>
                                <option value="completed" @selected($booking->booking_status == 'completed')>@lang('Completed')</option>
                                <option value="no_show" @selected($booking->booking_status == 'no_show')>@lang('No Show')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Payment Status')</label>
                            <select name="payment_status" class="form-control" required>
                                <option value="unpaid" @selected($booking->payment_status == 'unpaid')>@lang('Unpaid')</option>
                                <option value="paid" @selected($booking->payment_status == 'paid')>@lang('Paid')</option>
                                <option value="refunded" @selected($booking->payment_status == 'refunded')>@lang('Refunded')</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Rooms & Guests')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Room Type')</th>
                                    <th>@lang('Rate Plan')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Guests')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($booking->rooms as $room)
                                    <tr>
                                        <td>{{ @$room->roomType->name ?? 'Room' }}</td>
                                        <td>{{ $room->rate_plan_name_snapshot }}</td>
                                        <td>{{ showAmount($room->price) }} {{ gs()->cur_text }}</td>
                                        <td>
                                            @foreach($booking->guests->where('booking_room_id', $room->id) as $guest)
                                                <span class="d-block">
                                                    - {{ $guest->first_name }} {{ $guest->last_name }} 
                                                    @if($guest->is_lead_guest)
                                                        <span class="badge badge--primary">@lang('Lead')</span>
                                                    @endif
                                                </span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.hotel.booking.index') }}" class="btn btn-sm btn-outline--primary"><i class="las la-undo"></i> @lang('Back')</a>
@endpush
