@php
    $footerContent = getContent('footer.content', true);
    $policyPages = getContent('policy_pages.element', false, null, true);
@endphp

<footer class="footer {{ request()->routeIs('user.*') ? '' : 'bg_img dark--overlay-two' }}" style="{{ request()->routeIs('user.*') ? '' : 'background-image: url(' . frontendImage('footer', @$footerContent->data_values->background_image, '1920x960') . ');' }}">
    @if(!request()->routeIs('user.*'))
    <div class="footer__overview">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-8 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                    <h2 class="text-white text-md-start text-center">{{ __(@$footerContent->data_values->heading) }}</h2>
                </div>
                <div class="col-md-4 text-md-end text-center wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                    <a class="btn btn--base" href="{{ url(@$footerContent->data_values->button_link) }}">{{ __(@$footerContent->data_values->button_name) }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.7s">
                    <a class="footer-logo" href="{{ route('home') }}"><img src="{{ siteLogo() }}" alt="image"></a>
                    <p class="mt-4 text-white">{{ __(@$footerContent->data_values->content) }}</p>
                    <ul class="inlne-menu d-flex flex-wrap align-items-center justify-content-center mt-4">
                        <li><a href="{{ route('plans') }}">@lang('Tours')</a></li>
                        <li><a href="{{ route('seminars') }}">@lang('Day Trips')</a></li>
                        <li><a href="{{ route('hotels') }}">@lang('Hotels')</a></li>
                        @foreach ($policyPages as $policy)
                            <li>
                                <a href="{{ route('policy.pages', $policy->slug) }}">
                                    {{ __(@$policy->data_values->title) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="footer__bottom">
        <div class="container">
            <div class="row gy-2">
                <div class="col-md-6">
                    <p class="text-white text-md-start text-center">@lang('Copyright') &copy; {{ now()->year }} <a href="{{ route('home') }}"> <span class="text--base">{{ gs('site_name') }}</span></a>. All Right Reserved by <a href="https://dipencil.com/" target="_blank" class="text--base">Pencil Studio</a></p>
                </div>
                <div class="col-md-6 text-md-end text-center">
                    <img class="footer-card" src=" {{ frontendImage('footer', @$footerContent->data_values->payment_image, '385x51') }}" alt="image">
                </div>
            </div>
        </div>
    </div>
</footer>
