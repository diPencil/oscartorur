@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.auth_header')

    @if (!request()->routeIs('home') && !request()->routeIs('plan.details') && !request()->routeIs('seminar.details') && !request()->routeIs('user.*'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif

    @yield('content')

    @include($activeTemplate . 'partials.footer')
@endsection
