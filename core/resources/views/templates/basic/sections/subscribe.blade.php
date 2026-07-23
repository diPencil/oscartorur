@php
    $subscribeContent = getContent('subscribe.content', true);
@endphp
<section class="pt-100 pb-100 bg-white">
    <div class="container">
        <div class="subscribe-wrapper bg_img" style="background-image: url('{{ frontendImage('subscribe', @$subscribeContent->data_values->image, '1920x987') }}');">
            <div class="paper-plane">
                <img src="{{ asset($activeTemplateTrue . 'images/elements/paper-plane.png') }}" alt="image">
            </div>
            <div class="row gy-4 align-items-center">
                <div class="col-lg-5 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <h2 class="section-title text-white">{{ __(@$subscribeContent->data_values->heading) }}</h2>
                </div>
                <div class="col-lg-7 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    <form class="subscribe-form" method="POST">
                        <input class="form--control" name="email" type="email" autocomplete="off" placeholder="@lang('Enter email address')">
                        <button type="submit"><i class="lab la-telegram-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- subscribe section end -->

@push('script')
    <script>
        'use strict';

        $(function() {
            $('.subscribe-form').on('submit', function(event) {
                event.preventDefault();
                var email = $('.subscribe-form').find('[name="email"]').val();
                if (!email) {
                    notify('error', 'Email field is required');
                } else {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        },
                        url: "{{ route('subscribe') }}",
                        method: "POST",
                        data: {
                            email: email
                        },
                        success: function(response) {
                            if (response.success) {
                                notify('success', response.message);
                            } else {
                                notify('error', response.error);
                            }
                            $('.subscribe-form').find('[name="email"]').val('');
                        }
                    });
                }
            });

        })
    </script>
@endpush
