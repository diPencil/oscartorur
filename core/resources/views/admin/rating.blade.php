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
                                    <th>@lang('User')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Rating')</th>
                                    <th>@lang('Review')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ratings as $rating)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ __(@$rating->user->fullname) }}</span>
                                            <br>
                                            <small> <a href="{{ route('admin.users.detail', $rating->user_id) }}"><span>@</span>{{ @$rating->user->username }}</a> </small>
                                        </td>
                                        <td>
                                            @if ($rating->type == 'tour')
                                                <span class="badge badge--success">@lang('Tour')</span><br>
                                            @else
                                                <span class="badge badge--primary">@lang('Seminar')</span><br>
                                            @endif
                                        </td>
                                        <td>
                                            {{ __(@$rating->plan->name) }}
                                        </td>
                                        <td>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($rating->rating >= $i)
                                                    <i class="las la-star"></i>
                                                @else
                                                    <i class="lar la-star"></i>
                                                @endif
                                            @endfor
                                        </td>

                                        <td>
                                            @if ($rating->review)
                                                <button class="icon-btn commentBtn" data-original-title="@lang('Review')" data-toggle="tooltip" data-comment="@php echo $rating->review @endphp" type="button">
                                                    <i class="la la-eye"></i>
                                                </button>
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>

                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{ route('admin.rating.delete', $rating->id) }}" data-question="@lang('Are you sure to delete this rating & review')?" type="button">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                                </button>
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
                @if ($ratings->hasPages())
                    <div class="card-footer">
                        {{ paginateLinks($ratings) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
    {{-- Comment MODAL --}}
    <div class="modal fade" id="commentModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('Review')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body ratingComment">

                </div>
                <div class="modal-footer">
                    <button class="btn btn--dark" data-bs-dismiss="modal" type="button">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            //Comment
            $('.commentBtn').on('click', function() {
                var modal = $('#commentModal');
                var comment = $(this).data('comment');
                $('.ratingComment').html(comment);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
