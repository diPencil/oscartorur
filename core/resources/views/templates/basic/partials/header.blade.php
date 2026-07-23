@php
    $contactContent = getContent('contact_us.content', true);
    $socialIconElement = getContent('social_icon.element', orderById: true);
@endphp
<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-top-bar-area">
                <ul class="phone-number">
                    <li>
                        <a href="tel:{{ @$contactContent->data_values->contact_number }}"><i class="las la-phone"></i><span dir="ltr">{{ @$contactContent->data_values->contact_number }}</span></a>
                    </li>
                    <li>
                        <a href="mailto:{{ @$contactContent->data_values->email_address }}"><i class="las la-envelope"></i>{{ @$contactContent->data_values->email_address }}</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    @include($activeTemplate . 'partials.language')
                    <ul class="social-icons mb-0">
                        @foreach (@$socialIconElement as $social)
                            <li>
                                <a href="{{ $social->data_values->url }}" target="_blank">@php echo $social->data_values->social_icon @endphp</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="logo">
                </a>
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li><a class="{{ menuActive('home') }}" href="{{ route('home') }}">@lang('Home')</a></li>
                        @foreach ($pages as $k => $data)
                            <li>
                                <a class="{{ menuActive('pages', null, @$data->slug) }}" href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a>
                            </li>
                        @endforeach
                        <li><a class="{{ menuActive(['hotels', 'hotel.details']) }}" href="{{ route('hotels') ?? '#' }}">@lang('Hotels')</a></li>
                        <li><a class="{{ menuActive(['plans', 'plan.details']) }}" href="{{ route('plans') }}">@lang('Tour Package')</a></li>
                        <li><a class="{{ menuActive(['seminars', 'seminar.details']) }}" href="{{ route('seminars') }}">@lang('Day Trips')</a></li>
                        <li><a class="{{ menuActive(['blogs', 'blog*']) }}" href="{{ route('blogs') }}">@lang('Blog')</a></li>
                        <li><a class="{{ menuActive('contact') }}" href="{{ route('contact') }}">@lang('Contact')</a></li>
                    </ul>
                    <div class="nav-right">
                        @auth
                            <a class="btn btn-md btn--base d-flex align-items-center" href="{{ route('user.home') }}">
                                <i class="las la-tachometer-alt fs--18px me-2"></i> @lang('Dashboard')</a>
                        @else
                            <a class="btn btn-md btn--base d-flex align-items-center" href="{{ route('user.login') }}">
                                <i class="las la-user fs--18px me-2"></i> @lang('Login')</a>
                        @endauth
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
