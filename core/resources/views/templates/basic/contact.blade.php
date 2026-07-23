@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contactContent = getContent('contact_us.content', true);
    @endphp
    <section class="pt-100 pb-100">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-7">
                    <div class="contact-thumb">
                        <img src="{{ frontendImage('contact_us', @$contactContent->data_values->image, '992x662') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-5">
                    <h2 class="section-title">{{ __(@$contactContent->data_values->title) }}</h2>
                    <p class="mt-3">{{ __(@$contactContent->data_values->short_details) }}</p>
                    <form class="contact-form mt-5" class="verify-gcaptcha" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input class="form--control" name="name" type="text" value="{{ old('name', @$user->fullname) }}" @if ($user && $user->profile_complete) readonly @endif required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Email')</label>
                            <input class="form--control" name="email" type="email" value="{{ old('email', @$user->email) }}" @if ($user) readonly @endif required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Subject')</label>
                            <input class="form--control" name="subject" type="text" value="{{ old('subject') }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Message')</label>
                            <textarea class="form--control" name="message" wrap="off" required>{{ old('message') }}</textarea>
                        </div>
                        <x-captcha />
                        <button class="btn btn--base w-100" type="submit">@lang('Submit Now')</button>
                    </form>
                </div>
            </div>
            <div class="row g-4 pt-100 justify-content-center">
                <div class="col-sm-10 col-md-6 col-xl-4">
                    <div class="contact-item">
                        <div class="icon">
                            <i class="las la-map-marked-alt"></i>
                        </div>
                        <div class="cont">
                            <h6 class="mb-2">@lang('Address')</h6>
                            <p>@php echo @$contactContent->data_values->contact_details @endphp</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-6 col-xl-4">
                    <div class="contact-item">
                        <div class="icon">
                            <i class="las la-phone"></i>
                        </div>
                        <div class="cont">
                            <h6 class="mb-2">@lang('Phone')</h6>
                            <a href="tel:{{ @$contactContent->data_values->contact_number }}"><span dir="ltr">{{ @$contactContent->data_values->contact_number }}</span></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-6 col-xl-4">
                    <div class="contact-item">
                        <div class="icon">
                            <i class="las la-envelope"></i>
                        </div>
                        <div class="cont">
                            <h6 class="mb-2">@lang('Email Address')</h6>
                            <a href="mailto:{{ @$contactContent->data_values->email_address }}">{{ $contactContent->data_values->email_address }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- map area start -->
    <div class="map-area">
        <iframe src = "https://maps.google.com/maps?q={{ @$contactContent->data_values->latitude }},{{ @$contactContent->data_values->longitude }}&hl=es;z=14&amp;output=embed"></iframe>
    </div>
    <!-- map area end -->

    @if (@$sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection
