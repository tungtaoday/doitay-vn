<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')
    
    @stack('meta')

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">
</head>
@php echo loadExtension('google-analytics') @endphp

<body>

<!-- Zalo Chat Widget - Simple Button -->
<div id="zalo-chat-widget" style="
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0, 166, 255, 0.3);
    transition: all 0.3s ease;
    color: white;
    font-size: 24px;
    animation: zalo-pulse 2s infinite;
" onclick="openZaloApp('0901234567')" title="Chat Zalo">
    💬
</div>

@include($activeTemplate . 'partials.preloader')

    @yield('content')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/select2.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/bootstrap-fileinput.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>

    @stack('script-lib')

    @include('partials.notify')

    @php echo loadExtension('tawk-chat') @endphp

    <!-- Zalo Chat Widget CSS -->
    <style>
    @keyframes zalo-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    #zalo-chat-widget:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(0, 166, 255, 0.4);
    }
    </style>

    <!-- Zalo Chat Widget JavaScript -->
    <script>
    function openZaloApp(phone) {
        console.log('🎯 Mở Zalo app với số:', phone);
        const zaloUrl = `zalo://chat?phone=${phone}`;
        window.location.href = zaloUrl;
        
        setTimeout(() => {
            const webUrl = `https://zalo.me/${phone}`;
            window.open(webUrl, '_blank');
        }, 1000);
    }
    
    console.log('🎉 Zalo Widget đã được load!');
    </script>

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')
</body>

</html>
