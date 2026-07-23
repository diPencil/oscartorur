@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- single package section start -->
    <section class="pb-100">
        <div class="single-package-header bg_img" style="background-image: url('{{ frontendImage('seminar_breadcrumb', @$breadcrumb->data_values->image, '1920x1186') }}');">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="single-package-content">
                            <h2 class="title text-white">@lang($pageTitle)</h2>
                            <div class="ratings mt-2">
                                @php
                                    $rating = $seminar->ratings_avg_rating + 0;
                                @endphp

                                <span class="rating-stars">
                                    @php echo rating($rating); @endphp
                                </span>
                                <span class="text-white">({{ $seminar->ratings_count }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-8 pe-lg-5">
                    <ul class="nav nav-tabs custom--nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">@lang('Seminar Details')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="plan-tab" data-bs-toggle="tab" data-bs-target="#plan" type="button" role="tab" aria-controls="plan" aria-selected="false">@lang('Seminar Plan')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="true">@lang('Seminar Gallery')</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="review-tab" data-bs-toggle="tab" data-bs-target="#review" type="button" role="tab" aria-controls="review" aria-selected="false">@lang('Review')</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-5 package-tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <a href="{{ getImage(getFilePath('seminar') . '/' . @$seminar->images[0]) }}" data-rel="lightcase">
                                <img class="w-100 mb-4 tour-plan-img" src="{{ getImage(getFilePath('seminar') . '/' . @$seminar->images[0], getFileSize('seminar')) }}" alt="image">
                            </a>
                            <h3 class="mb-3">@lang('Seminar Details')</h3>
                            <p>
                                @php
                                    echo $seminar->details;
                                @endphp
                            </p>
                            <h4 class="action-widget__title no-icon mb-3 mt-5">@lang('Included')</h4>
                            <ul class="cmn-list">
                                @foreach ($seminar->included ?? [] as $incld)
                                    <li>@lang($incld)</li>
                                @endforeach
                            </ul>

                            <h4 class="action-widget__title no-icon mb-3 mt-5">@lang('Excluded')</h4>
                            <ul class="cmn-list cmn-list-excluded">
                                @foreach ($seminar->excluded ?? [] as $excld)
                                    <li>@lang($excld)</li>
                                @endforeach
                            </ul>
                            <h4 class="action-widget__title no-icon mb-3 mt-5">@lang('Touring Map')</h4>
                            <div class="tour-map-wrapper">
                                <iframe id="tour-map" src ="https://maps.google.com/maps?q={{ $seminar->map_latitude }},{{ $seminar->map_longitude }}&hl=es;z=14&amp;output=embed"></iframe>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                            <div class="row gy-4">
                                @forelse (@$seminar->images ?? [] as $image)
                                    <div class="col-lg-4">
                                        <div class="gallery-card">
                                            <img src=" {{ getImage(getFilePath('seminar') . '/' . @$image, getFileSize('seminar')) }}" alt="image">
                                            <a class="view-thumb" data-rel="lightcase:myCollection:slideshow" href="{{ getImage(getFilePath('seminar') . '/' . @$image) }}"><i class="las la-plus"></i></a>
                                        </div>
                                    </div>
                                @empty
                                    @include($activeTemplate . 'partials.empty', ['message' => 'Extra images not attached!'])
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="plan" role="tabpanel" aria-labelledby="plan-tab">

                            @foreach (@$seminar->seminar_plan ?? [] as $item)
                                <div class="tour-plan-block">
                                    <div class="tour-plan-block__header">
                                        <h3 class="title mb-3"><span>{{ __($item->title) }}</span> {{ __($item->subtitle) }}</h3>
                                    </div>
                                    <div class="tour-plan-block__content">
                                        <p>
                                            @php
                                                echo $item->content;
                                            @endphp
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                            <div class="course-details-review mb-5">
                                <div class="rating-area d-flex flex-wrap align-items-center justify-content-between mb-4">
                                    <div class="rating">{{ @$seminar->ratings_avg_rating + 0 }}</div>
                                    <div class="content">
                                        <div class="ratings d-flex align-items-center justify-content-end fs--18px">

                                            @php
                                                $rating = $seminar->ratings_avg_rating + 0;
                                            @endphp

                                            <span class="rating-stars">
                                                @php echo rating($rating) @endphp
                                            </span>

                                        </div>
                                        <span class="mt-1 text-muted fs--14px">@lang('Based on') {{ @$seminar->ratings_count }} @lang('ratings')</span>
                                    </div>
                                </div>
                                @foreach ([5, 4, 3, 2, 1] as $rating)
                                    @php
                                        $count = $seminar->ratings()->where('rating', $rating)->count();
                                        $percentage = $count ? ($count / $seminar->ratings_count) * 100 : 0;
                                    @endphp
                                    <x-single-review :rating="$rating" :percentage="$percentage" />
                                @endforeach
                            </div>

                            @auth
                                @php
                                    $userHasRated = $seminar->ratings->where('user_id', auth()->id())->isNotEmpty();
                                @endphp

                                @if ($userHasRated)
                                    <div class="course-details-review mb-5 text-center">
                                        <h4 class="text--warning">@lang('Already provided your valuable rating this seminar plan!')</h4>
                                    </div>
                                @else
                                    <div class="course-details-review mb-5">
                                        <form action="{{ route('user.rating', $seminar->id) }}" method="POST">
                                            @csrf

                                            <input name="type" type="hidden" value="seminar">
                                            <input id="rating" name="rating" type="hidden">

                                            <div class='rating-stars text-center mb-3'>
                                                <ul id='stars'>
                                                    @foreach (range(1, 5) as $value)
                                                        <li class='star' data-value='{{ $value }}' title='{{ ['Poor', 'Fair', 'Good', 'Excellent', 'WOW!!!'][$value - 1] }}'>
                                                            <i class='fa fa-star fa-fw'></i>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form--control" name="review" cols="30" rows="10" placeholder="@lang('Write your review')"></textarea>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button class="btn btn--base" type="submit">@lang('Submit')</button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @else
                                <div class="course-details-review mb-5 text-center">
                                    <h4 class="text--base">@lang('Please login to add rating!')</h4>
                                </div>
                            @endauth

                            @forelse ($seminar->ratings as $item)
                                <x-user-review :item="$item" />
                            @empty
                                @include($activeTemplate . 'partials.empty', ['message' => 'Review not found!'])
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="package-sidebar-widget">
                        <div class="thumb">
                            <a href="{{ getImage(getFilePath('seminar') . '/' . @$seminar->images[0]) }}" data-rel="lightcase">
                                <img src="{{ getImage(getFilePath('seminar') . '/' . @$seminar->images[0], getFileSize('seminar')) }}" alt="image">
                            </a>
                            <div class="price">{{ showAmount($seminar->price) }}</div>
                        </div>
                        <div class="content mt-5">
                            <ul class="package-sidebar-list">
                                <li>
                                    <i class="las la-clock"></i>
                                    <span>{{ $seminar->duration }} @lang('Days')</span>
                                </li>
                                <li>
                                    <i class="las la-calendar-alt"></i>
                                    <span>{{ showDateTime($seminar->start_time, 'd, F, Y') }}</span>
                                </li>
                                <li>
                                    <i class="las la-couch"></i>
                                    <span>{{ $seminar->capacity - $seminar->sold }} @lang('Availability')</span>
                                </li>
                            </ul>
                            <ul class="caption-list mt-5">
                                <li>
                                    <span class="caption text-white">@lang('Start Time')</span>
                                    <span class="value text-end text--base">{{ showDateTime($seminar->start_time) }}</span>
                                </li>
                                <li>
                                    <span class="caption text-white">@lang('Return')</span>
                                    <span class="value text-end text--base">{{ showDateTime($seminar->end_time) }}</span>
                                </li>
                                <li>
                                    <span class="caption text-white">@lang('Total Capacity')</span>
                                    <span class="value text-end text--base">{{ $seminar->capacity }} @lang('Persons')</span>
                                </li>
                            </ul>

                            @if ($seminar->start_time < now())
                                <button class="btn btn--base w-100 mt-5" type="button">@lang('Completed')</button>
                            @else
                                @if ($seminar->sold >= $seminar->capacity)
                                    <button class="btn btn--base w-100 mt-5" href="button">@lang('Seat Not Available')</button>
                                @else
                                    @auth
                                        <button class="btn btn--base w-100 mt-5" data-bs-toggle="modal" data-bs-target="#bookingModal" type="button">
                                            @lang('Book Now')
                                        </button>
                                    @else
                                        <a class="btn btn--base w-100 mt-5" href="{{ route('user.login') }}">
                                            @lang('Book Now')
                                        </a>
                                    @endauth
                                @endif
                            @endif
                        </div>
                        @php
                            $route = route('seminar.details', [$seminar->id, slug($seminar->name)]);
                        @endphp
                        <div class="blog-details-footer mt-4">
                            <span class="share-caption text-white">@lang('Share Seminar')</span>
                            <ul class="share-post-links">
                                <li><a class="twitter" href="http://twitter.com/share?url={{ urlencode($route) }}" target="_blank"><i class="lab la-twitter m-0"></i> </a></li>
                                <li><a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode($route) }}&amp;title={{ $seminar->name }}&amp;{{ $seminar->name }}" target="_blank"><i class="lab la-linkedin-in m-0"></i> </a></li>
                                <li><a class="facebook" href="http://www.facebook.com/sharer.php?u={{ urlencode($route) }}" target="_blank"><i class="lab la-facebook-f m-0"></i></a></li>
                                <li><a class="instagram" href="https://www.instagram.com/share?u={{ urlencode($route) }}" target="_blank"><i class="lab la-facebook-f m-0"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- single package section end -->

    <!-- Modal -->
    @if (auth()->check() && $seminar->start_time > now() && $seminar->sold <= $seminar->capacity)
        <div class="modal fade" id="bookingModal" aria-labelledby="exampleModalLabel" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('Booking')</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user.booking') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input name="plan_id" type="hidden" value="{{ $seminar->id }}">
                            <input name="type" type="hidden" value="seminar">
                            <div class="form-group">
                                <label for="seat">@lang('Number of Seat')</label>
                                <div class="input-group">
                                    <input class="form--control" id="seat" name="seat" type="number" min="1" max="{{ $seminar->capacity - $seminar->sold }}" placeholder="@lang('Enter Number of Seat')">
                                    <span class="input-group-text bg--base text-white"><i class="las la-user"></i></span>
                                </div>
                                <span class="text--danger total"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn--base btn-sm" type="submit">@lang('Book Now')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('a[data-rel^=lightcase]').lightcase();

            $(document).on('input', '#seat', function() {
                var seat = $(this).val();
                var price = parseFloat('{{ getAmount($seminar->price) }}');
                if (seat) {
                    $('.total').text('Total price: {{ gs('cur_sym') }}' + (seat * price).toFixed(2));
                } else {
                    $('.total').text('');
                }
            });


            $('#stars li').on('mouseover', function() {
                var onStar = parseInt($(this).data('value'), 10);

                $(this).parent().children('li.star').each(function(e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function() {
                $(this).parent().children('li.star').each(function(e) {
                    $(this).removeClass('hover');
                });
            });

            $('#stars li').on('click', function() {
                var onStar = parseInt($(this).data('value'), 10);
                var stars = $(this).parent().children('li.star');

                for (var i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('selected');
                }

                for (var i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                }

                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                if (ratingValue >= 1) {
                    $('#rating').val(ratingValue);
                }
            });

        })(jQuery);
    </script>
@endpush
