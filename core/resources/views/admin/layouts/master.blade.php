<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" dir="{{ session('lang') == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName($pageTitle ?? '') }}</title>

    <link rel="shortcut icon" type="image/png" href="{{siteFavicon()}}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if(session('lang') == 'ar')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.rtl.min.css') }}">
    <style>
        /* Widget two RTL fixes */
        .widget-two__content { padding-left: 0 !important; padding-right: 20px !important; }
        .widget-two__content h3, .widget-two__content p { text-align: right !important; }
        
        /* Dashboard-w1 (Overview Cards) RTL fixes */
        .dashboard-w1 .icon { left: auto !important; right: 0 !important; }
        .dashboard-w1 .icon i { margin-left: 0 !important; margin-right: -15px !important; }
        .dashboard-w1 .details { text-align: left !important; z-index: 2; position: relative; }
        
        /* Table RTL fixes */
        table thead th:first-child { border-radius: 0 5px 0 0 !important; text-align: right !important; }
        table thead th:last-child { border-radius: 5px 0 0 0 !important; text-align: left !important; }
        table tbody td:first-child { text-align: right !important; }
        table tbody td:last-child { text-align: left !important; }
        
        /* General Icon spacing for RTL */
        .nav-tabs--style1 .nav-link i, 
        .text-muted i, 
        .card-title i, 
        .badge i, 
        .btn i { margin-left: 5px; margin-right: 0 !important; }
    </style>
    @else
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    @endif

    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/bootstrap-toggle.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">

    @stack('style-lib')

    <link rel="stylesheet" href="{{asset('assets/global/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/app.css')}}">


    @stack('style')
    <style>
        [dir="rtl"] .sidebar {
            left: auto;
            right: 0;
            border-right: none;
            border-left: 1px solid #66666675;
        }
        [dir="rtl"] .sidebar__menu .sidebar-menu-item > a {
            position: relative !important;
            padding-right: 65px !important;
        }
        [dir="rtl"] .sidebar__menu .menu-title {
            flex-grow: 1 !important;
            text-align: right !important;
            margin-right: 0 !important;
        }
        [dir="rtl"] .sidebar__menu .menu-icon {
            position: absolute !important;
            right: 25px !important;
            margin: 0 !important;
            width: 25px !important;
            text-align: center !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        [dir="rtl"] .sidebar__menu .sidebar-submenu .sidebar-menu-item a {
            padding: 10px 75px 10px 20px !important; 
        }
        [dir="rtl"] .sidebar__menu .sidebar-submenu .menu-icon {
            right: 35px !important;
        }
        [dir="rtl"] .sidebar__menu .sidebar-dropdown > a {
            padding-right: 65px !important;
            padding-left: 40px !important;
        }
        [dir="rtl"] .sidebar__menu .sidebar-dropdown > a::before {
            right: auto !important;
            left: 20px !important;
            transform: rotate(0deg) !important;
        }
        [dir="rtl"] .sidebar__menu .sidebar-dropdown > a.side-menu--open::before {
            transform: rotate(180deg) !important;
            left: 20px !important;
            right: auto !important;
        }
        [dir="rtl"] .navbar__action-list li {
            margin-right: 0 !important;
            margin-left: 10px !important;
        }
        [dir="rtl"] .navbar__right {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
        [dir="rtl"] .body-wrapper {
            margin-left: 0 !important;
            margin-right: 250px !important;
        }
        [dir="rtl"] .navbar-wrapper {
            margin-left: 0 !important;
            margin-right: 250px !important;
        }
        [dir="rtl"] .body-wrapper.active {
            margin-left: 0 !important;
            margin-right: 80px !important;
        }
        [dir="rtl"] .widget-two__content {
            padding-right: 20px !important;
            padding-left: 0 !important;
        }
        [dir="rtl"] .widget-two .overlay-icon {
            left: -15px !important;
            right: auto !important;
        }
        @media (max-width: 991px) {
            [dir="rtl"] .sidebar {
                right: -285px;
                left: auto;
            }
            [dir="rtl"] .sidebar.open {
                right: 0;
                left: auto;
            }
            [dir="rtl"] .body-wrapper, [dir="rtl"] .navbar-wrapper {
                margin-right: 0 !important;
            }
        }
    </style>
</head>
<body>
@yield('content')




<script src="{{asset('assets/global/js/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/global/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/js/vendor/bootstrap-toggle.min.js')}}"></script>


@include('partials.notify')
@stack('script-lib')
<script src="{{ asset('assets/admin/js/cu-modal.js') }}"></script>
<script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>

<script src="{{asset('assets/global/js/select2.min.js')}}"></script>
<script src="{{asset('assets/admin/js/app.js')}}"></script>

{{-- LOAD NIC EDIT --}}
<script>
    "use strict";
    bkLib.onDomLoaded(function() {
        $( ".nicEdit" ).each(function( index ) {
            $(this).attr("id","nicEditor"+index);
            new nicEditor({fullPanel : true}).panelInstance('nicEditor'+index,{hasPanel : true});
        });
    });
    (function($){
        $( document ).on('mouseover ', '.nicEdit-main,.nicEdit-panelContain',function(){
            $('.nicEdit-main').focus();
        });

        $('.breadcrumb-nav-open').on('click', function() {
            $(this).toggleClass('active');
            $('.breadcrumb-nav').toggleClass('active');
        });

        $('.breadcrumb-nav-close').on('click', function() {
            $('.breadcrumb-nav').removeClass('active');
        });

        if($('.topTap').length){
            $('.breadcrumb-nav-open').removeClass('d-none');
        }
    })(jQuery);
</script>

@stack('script')


</body>
</html>
