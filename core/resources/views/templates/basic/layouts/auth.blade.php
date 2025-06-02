<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ gs('base_color') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}" rel="stylesheet">
</head>
@php echo loadExtension('google-analytics') @endphp

<body>
    <div class="scroll-to-top">
        <span class="scroll-icon">
            <i class="las la-arrow-up"></i>
        </span>
    </div>

    @include($activeTemplate.'partials.preloader')

    @include($activeTemplate.'partials.header')

    @php
        $content = getContent('breadcrumb.content', true);
    @endphp

    <div class="main-wrapper">
        <div class="profile-bg bg_img"
            style="background-image: url('{{ frontendImage('breadcrumb', @$content->data_values->image, '1920x840') }}');">
        </div>

        <div class="pt-50 pb-50 section--bg">
            <div class="container">
                <div class="row justify-content-between flex-row-reverse">
                    <div class="col-xl-3 col-lg-4 order-1">
                        @include($activeTemplate . 'partials.user_left_nav')
                    </div>
                    <div class="col-xl-9 col-lg-8 order-0">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include($activeTemplate . 'partials.footer')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @stack('script')

    <script>
        (function($) {
            "use strict"

            $(".langSel").on("click", function() {
                var value = $(this).data('value');
                window.location.href = "{{ route('home') }}/change/" + value;
            });

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });

            // Mobile menu toggle
            $('.profile-nav__toggle').on('click', function() {
                $('.profile-menu').toggleClass('active');
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.profile-nav').length) {
                    $('.profile-menu').removeClass('active');
                }
            });
        })(jQuery)
    </script>
</body>

</html>
