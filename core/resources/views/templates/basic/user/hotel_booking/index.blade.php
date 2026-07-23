@extends('Template::layouts.master')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card custom--card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table custom--table">
                        <thead>
                            <tr>
                                <th>@lang('Booking Number')</th>
                                <th>@lang('Hotel')</th>
                                <th>@lang('Check In - Out')</th>
                                <th>@lang('Rooms')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td><span class="fw-bold">{{ $booking->booking_number }}</span></td>
                                    <td>{{ $booking->hotel->name }}</td>
                                    <td>{{ showDateTime($booking->check_in, 'd M Y') }} - {{ showDateTime($booking->check_out, 'd M Y') }}</td>
                                    <td>{{ $booking->rooms_count }}</td>
                                    <td>{{ gs('cur_sym') }}{{ showAmount($booking->total_price) }}</td>
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
                                    </td>
                                    <td>
                                        <a href="{{ route('user.hotel.booking.details', $booking->id) }}" class="btn btn-sm btn--base">
                                            <i class="las la-desktop"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center">{{ __($emptyMessage ?? 'No bookings found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($bookings->hasPages())
                <div class="card-footer">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
