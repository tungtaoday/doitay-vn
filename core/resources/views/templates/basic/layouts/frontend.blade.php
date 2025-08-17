<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> {{ gs()->siteName(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    @stack('style-lib')

    @stack('style')

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">
    
    <!-- Include Modern Menu Styles -->
    @include($activeTemplate . 'partials.modern-menu')
</head>

@php echo loadExtension('google-analytics') @endphp

<!-- Google Analytics Enhanced Tracking -->
@include('partials.google-analytics-tracking')

<body>
    @stack('fbComment')

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
        overflow: hidden;
    " onclick="openZaloApp('{{ gs('zalo_phone') ?? '0901234567' }}')" title="Chat Zalo">
        
        @if(gs('zalo_avatar'))
            <img src="{{ gs('zalo_avatar') }}" 
                 alt="Zalo Avatar" 
                 style="
                     width: 100%;
                     height: 100%;
                     object-fit: cover;
                     border-radius: 50%;
                 "
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        @else
            <img src="{{ asset('assets/images/zalo-avatar.jpg') }}" 
                 alt="Zalo Avatar" 
                 style="
                     width: 100%;
                     height: 100%;
                     object-fit: cover;
                     border-radius: 50%;
                 "
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        @endif
        
        <span style="
            display: none;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        ">💬</span>
    </div>

    <!-- <div class="scroll-to-top">
        <span class="scroll-icon">
            <i class="las la-arrow-up"></i>
        </span>
    </div> -->

    @include($activeTemplate . 'partials.preloader')

    @include($activeTemplate . 'partials.header')

    <div class="main-wrapper">
        <!-- @if (!request()->routeIs('home') && !request()->routeIs('user.home') && !request()->routeIs('company.details'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif -->
        <!-- @yield('content') -->
    </div>

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie && $cookie->data_values && $cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card text-center">
            <div class="cookies-card__icon bg--base">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">{{ $cookie->data_values->short_desc }} <a
                    href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-4">
                <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
            </div>
        </div>
    @endif

    @include($activeTemplate . 'partials.footer')

    <script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/bootstrap-fileinput.js') }}"></script>

    @stack('script-lib')

    @php echo loadExtension('tawk-chat') @endphp

    @include('partials.notify')

    @if (gs('pn'))
        @include('partials.push_script')
    @endif

    @stack('script')

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
    
    // Kiểm tra và xử lý avatar
    document.addEventListener('DOMContentLoaded', function() {
        const widget = document.getElementById('zalo-chat-widget');
        const avatarImg = widget.querySelector('img');
        const emojiSpan = widget.querySelector('span');
        
        console.log('🎉 Zalo Widget đã được load!');
        console.log('📱 Số điện thoại:', '{{ gs('zalo_phone') ?? '0901234567' }}');
        console.log('👤 Avatar từ admin:', '{{ gs('zalo_avatar') ?? 'Không có' }}');
        console.log('🖼️ Avatar mặc định:', '{{ asset('assets/images/zalo-avatar.jpg') }}');
        
        if (avatarImg) {
            console.log('🔍 Tìm thấy avatar image element');
            console.log('📁 Avatar src:', avatarImg.src);
            
            // Xử lý lỗi load avatar
            avatarImg.onerror = function() {
                console.log('⚠️ Avatar không load được, chuyển sang emoji');
                this.style.display = 'none';
                emojiSpan.style.display = 'flex';
            };
            
            // Avatar load thành công
            avatarImg.onload = function() {
                console.log('✅ Avatar đã load thành công!');
                this.style.display = 'block';
                emojiSpan.style.display = 'none';
            };
            
            // Test avatar ngay lập tức
            if (avatarImg.complete) {
                console.log('✅ Avatar đã load xong');
            } else {
                console.log('⏳ Avatar đang load...');
            }
        } else {
            console.log('❌ Không tìm thấy avatar image element');
        }
        
        // Test tất cả đường dẫn avatar
        const testPaths = [
            '{{ asset('assets/images/zalo-avatar.jpg') }}',
            '{{ gs('zalo_avatar') ?? 'Không có' }}'
        ];
        
        console.log('🧪 Test các đường dẫn avatar:');
        testPaths.forEach((path, index) => {
            if (path && path !== 'Không có') {
                const testImg = new Image();
                testImg.onload = () => console.log(`✅ Path ${index + 1}: ${path} - LOAD THÀNH CÔNG`);
                testImg.onerror = () => console.log(`❌ Path ${index + 1}: ${path} - LOAD THẤT BẠI`);
                testImg.src = path;
            }
        });
    });
    </script>

    <!-- Include Tracking Scripts -->
    @include('templates.basic.partials.appointment-tracking')
    @include('templates.basic.partials.company-lead-tracking')
    @include('templates.basic.partials.user-tracking')

    <script>
        (function($) {
            "use strict";

            $(".langSel").on("click", function() {
                var value = $(this).data('value');
                window.location.href = "{{ route('home') }}/change/" + value;
            });

            $('.policy').on('click', function() {
                $.get('{{ route('cookie.accept') }}', function(response) {
                    $('.cookies-card').addClass('d-none');
                });
            });

            setTimeout(function() {
                $('.cookies-card').removeClass('hide')
            }, 2000);

            var inputElements = $('[type=text],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {
                var elementType = $(element);
                if (elementType.attr('type') != 'checkbox') {
                    if (element.hasAttribute('required')) {
                        $(element).closest('.form-group').find('label').addClass('required');
                    }
                }

            });

            $(".__add").on('click', function(e) {
                e.preventDefault()
                let id = $(this).data('id');
                let action = "{{ route('add.click', ':id') }}";

                $.ajax({
                    url: action.replace(':id', id),
                    type: "GET",
                    dataType: 'json',
                    cache: false,
                    success: function(resp) {
                        if (resp.data && resp.data.type == 'image' && resp.data.redirect_url != '#') {
                            window.open(resp.data.redirect_url)
                        }
                    }
                });
            })

            let disableSubmission = false;
            $('.disableSubmission').on('submit', function(e) {
                if (disableSubmission) {
                    e.preventDefault()
                } else {
                    disableSubmission = true;
                }
            });
        })(jQuery);
    </script>
</body>

</html>
