@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $authContent = getContent('auth.content', true);
    @endphp
    <section class="account-section bg_img" style="background-image: url('{{ frontendImage('auth', @$authContent->data_values->image, '1920x1080') }}');">
        <div class="left">
            <div class="account-form-area">
                <div class="text-center my-3">
                    <a class="account-logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="logo"></a>
                </div>

                @include($activeTemplate . 'partials.social_login')

                <form class="account-form verify-gcaptcha" method="POST" action="{{ route('user.login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="email">@lang('Username or Email')</label>
                        <input class="form--control" name="username" type="text" value="{{ old('username') }}" required>
                    </div>

                    <div class="form-group">
                        <div class="d-flex flex-wrap justify-content-between mb-2">
                            <label class="form-label mb-0" for="password">@lang('Password')</label>
                            <a class="fw-bold forgot-pass" href="{{ route('user.password.request') }}">
                                @lang('Forgot your password?')
                            </a>
                        </div>
                        <input class="form--control" id="password" name="password" type="password" required>
                    </div>

                    <x-captcha />

                    <div class="form-group form-check">
                        <input class="form-check-input" id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            @lang('Remember Me')
                        </label>
                    </div>

                    <div class="form-group">
                        <button class="btn btn--base w-100" id="recaptcha" type="submit">
                            @lang('Login')
                        </button>
                    </div>
                    <p class="text-white">@lang('Don\'t have any account?') <a class="text--base" href="{{ route('user.register') }}">@lang('Register')</a></p>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .account-form .form--control {
            backdrop-filter: blur(5px) !important;
            background-color: rgb(255 255 255 / 10%) !important;
        }
    </style>
@endpush