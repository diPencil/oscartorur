@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Booking Number')</th>
                                <th>@lang('User')</th>
                                <th>@lang('Hotel')</th>
                                <th>@lang('Check In/Out')</th>
                                <th>@lang('Rooms')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $booking->booking_number }}</span><br>
                                        <small>{{ showDateTime($booking->created_at, 'd M, Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $booking->user ? $booking->user->fullname : 'Guest' }}</span>
                                        @if($booking->user)
                                            <br>
                                            <a href="{{ route('admin.users.detail', $booking->user->id) }}"><span>@</span>{{ $booking->user->username }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $booking->hotel?->name ?? 'Deleted Hotel' }}</span>
                                    </td>
                                    <td>
                                        {{ showDateTime($booking->check_in, 'd M, Y') }} <br>
                                        @lang('to') {{ showDateTime($booking->check_out, 'd M, Y') }}
                                    </td>
                                    <td>{{ $booking->rooms_count }}</td>
                                    <td>
                                        <span class="fw-bold">{{ showAmount($booking->total_price) }} {{ gs()->cur_text }}</span>
                                    </td>
                                    <td>
                                        @if($booking->booking_status == 'pending')
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                        @elseif($booking->booking_status == 'confirmed')
                                            <span class="badge badge--success">@lang('Confirmed')</span>
                                        @elseif($booking->booking_status == 'cancelled')
                                            <span class="badge badge--danger">@lang('Cancelled')</span>
                                        @elseif($booking->booking_status == 'completed')
                                            <span class="badge badge--primary">@lang('Completed')</span>
                                        @endif
                                        <br>
                                        @if($booking->payment_status == 'paid')
                                            <span class="badge badge--success">@lang('Paid')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Unpaid')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.hotel.booking.details', $booking->id) }}"
                                           class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> @lang('Details')
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No bookings found') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($bookings->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bookings) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
