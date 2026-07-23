<header class="header">
    <div class="header__bottom" style="{{ request()->routeIs('user.*') ? 'background-color: #14233c;' : '' }}">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ siteLogo() }}" alt="logo"></a>
                <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu ms-auto">
                        <li><a class="{{ menuActive('user.home') }}" href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
                        <li class="menu_has_children"><a class="{{ menuActive(['plans', 'user.tour.log']) }}" href="javascript:void(0)">@lang('Tour Plan')</a>
                            <ul class="sub-menu">
                                <li><a class="{{ menuActive('plans') }}" href="{{ route('plans') }}">@lang('Plans')</a></li>
                                <li><a class="{{ menuActive('user.tour.log') }}" href="{{ route('user.tour.log') }}">@lang('Tour Log')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children"><a class="{{ menuActive(['seminars', 'user.seminar.log']) }}" href="javascript:void(0)">@lang('Day Trips')</a>
                            <ul class="sub-menu">
                                <li><a class="{{ menuActive('seminars') }}" href="{{ route('seminars') }}">@lang('Day Trips')</a></li>
                                <li><a class="{{ menuActive('user.seminar.log') }}" href="{{ route('user.seminar.log') }}">@lang('Day Trip Bookings')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children"><a class="{{ menuActive(['hotel.search', 'user.hotel.booking']) }}" href="javascript:void(0)">@lang('Hotels')</a>
                            <ul class="sub-menu">
                                <li><a class="{{ menuActive('hotel.search') }}" href="{{ route('hotel.search') }}">@lang('Search')</a></li>
                                <li><a class="{{ menuActive('user.hotel.booking') }}" href="{{ route('user.hotel.booking') }}">@lang('My Bookings')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children"><a class="{{ menuActive(['ticket.open', 'ticket.index']) }}" href="javascript:void(0)">@lang('Support')</a>
                            <ul class="sub-menu">
                                <li><a class="{{ menuActive('ticket.open') }}" href="{{ route('ticket.open') }}">@lang('Open New Ticket')</a></li>
                                <li><a class="{{ menuActive('ticket.index') }}" href="{{ route('ticket.index') }}">@lang('My Tickets')</a></li>
                            </ul>
                        </li>
                        <li class="menu_has_children"><a class="{{ menuActive(['user.change.password', 'user.deposit.history', 'user.profile.setting', 'user.twofactor']) }}" href="javascript:void(0)">@lang('Account')</a>
                            <ul class="sub-menu">
                                <li><a class="{{ menuActive('user.deposit.history') }}" href="{{ route('user.deposit.history') }}">@lang('Payment History')</a></li>
                                <li><a class="{{ menuActive('user.change.password') }}" href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                <li><a class="{{ menuActive('user.profile.setting') }}" href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                                <li><a href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                            </ul>
                        </li>

                    </ul>
                    <div class="nav-right">
                        @include($activeTemplate . 'partials.language')
                        <a class="btn btn-md btn--base d-flex align-items-center" href="{{ route('user.logout') }}"><i
                               class="las la-sign-out-alt fs--18px me-2"></i> @lang('Logout')</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>
