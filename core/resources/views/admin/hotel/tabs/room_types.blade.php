<div class="row">
    <div class="col-md-12">
        <div class="card border--dark">
            <div class="card-header bg--dark d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0">@lang('Room Types')</h5>
                <a href="{{ route('admin.room.type.index', $hotel->id) }}" class="btn btn-sm btn-outline-light">
                    <i class="las la-cog"></i> @lang('Manage Room Types')
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Room Name')</th>
                                <th>@lang('Max Adults')</th>
                                <th>@lang('Max Children')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Cover Image')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hotel->roomTypes as $room)
                                <tr>
                                    <td><strong>{{ $room->name }}</strong></td>
                                    <td>{{ $room->max_adult }}</td>
                                    <td>{{ $room->max_child }}</td>
                                    <td>
                                        @if($room->status == 1)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($room->images()->where('is_cover', 1)->exists())
                                            <span class="badge badge--success"><i class="las la-check"></i> @lang('Yes')</span>
                                        @else
                                            <span class="badge badge--danger"><i class="las la-times"></i> @lang('No')</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center text-muted">@lang('No room types added yet. Please manage room types to add one.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
