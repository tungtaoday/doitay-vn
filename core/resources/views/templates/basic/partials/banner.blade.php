@php
    $bannerContent = getContent('banner.content', true);
    $mobileBannerContent = getContent('mobile_banner.content', true);
    $desktopImage = frontendImage('banner', @$bannerContent->data_values->image, '1920x840');
    $mobileImage = frontendImage('mobile_banner', @$mobileBannerContent->data_values->image, '768x500');
    // Debug info
    echo "<!-- Debug Info: -->";
    echo "<!-- Mobile Image Value: " . @$mobileBannerContent->data_values->image . " -->";
    echo "<!-- Mobile Image Path: " . $mobileImage . " -->";
    // Nếu không có hình ảnh mobile, sử dụng hình ảnh desktop
    if (strpos($mobileImage, 'placeholder-image') !== false) {
        $mobileImage = $desktopImage;
    }
@endphp

<!-- Preload fonts -->
<link rel="preload" href="{{ asset('assets/global/fonts/la-solid-900.woff2') }}" as="font" type="font/woff2" crossorigin>

<header class="header">
    <!-- ...header content... -->
</header>
<section class="hero bg_img" data-mobile-image="{{ $mobileImage }}" style="background-image: url('{{ $desktopImage }}');">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-8 text-md-start text-left">
                <div class="banner">
                    <h1>  <br> </h1>
                    <h1> Ngôi nhà của bạn </h1>
                    <h1> được <span class="changing-text-wrapper"> <span class="changing-text">làm đẹp</span>
                    </span> </h1>
                    <h1> thật dễ dàng </h1> 
                </div>
            </div>
            <div class="row mt-lg-5 mt-4">
                <div class="col-lg-6 col-md-8">
                    <form action="{{ route('company.search') }}" class="hero__search-form wow fadeInUp disableSubmission"
                        data-wow-duration="0.5" data-wow-delay="0.7s">
                        <input type="text" name="search" class="form--control" placeholder="@lang('Tìm kiếm tại đây...')" required>
                        <button type="submit" class="btn btn--base">@lang('Tìm')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .banner h1 {
        color: #ffffff !important;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    
    .banner .changing-text-wrapper,
    .banner .changing-text {
        color: rgb(12, 150, 209) !important;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    .header__bottom {
        background: #fff !important;
        position: relative;
        z-index: 2;
    }
    .hero.bg_img {
        width: 100%;
        min-height: 500px;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        position: relative;
        z-index: 1;
        padding-top: 120px;
        margin-top: 0 !important;
    }
    .header {
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
    }

    /* Mobile styles */
    @media (max-width: 767px) {
        .hero.bg_img {
            min-height: 400px;
            padding-top: 80px;
            background-size: cover !important;
            background-position: center !important;
        }
    }

    /* Điều chỉnh alignment cho banner text trên mobile */
    @media (max-width: 767px) {
        /* Reset và ghi đè tất cả các style trước đó */
        .banner h1 {
            all: unset;
            display: block !important;
            color: #ffffff !important;
            font-family: inherit !important;
            font-size: 32px !important; /* Tăng từ 24px lên 32px */
            line-height: 1.4 !important;
            margin-bottom: 10px !important; /* Tăng margin để tạo khoảng cách giữa các dòng */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5) !important;
            font-weight: 600 !important;
        }

        .banner .changing-text-wrapper,
        .banner .changing-text {
            all: unset;
            color: rgb(12, 150, 209) !important;
            font-size: 32px !important; /* Tăng từ 24px lên 32px */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5) !important;
            font-weight: 600 !important;
        }

        /* Thêm important cho container */
        .banner {
            display: block !important;
            text-align: left !important;
            padding-left: 15px !important;
            max-width: 100% !important;
            margin-top: 20px !important;
        }
    }
</style>

@push('script')
    <script>
        "use strict";
        // Xử lý banner image cho mobile
        document.addEventListener('DOMContentLoaded', function() {
            const heroSection = document.querySelector('.hero.bg_img');
            const mobileImage = heroSection.getAttribute('data-mobile-image');
            const desktopImage = heroSection.style.backgroundImage;
            
            // Debug logs
            console.log('Mobile Image URL:', mobileImage);
            console.log('Desktop Image:', desktopImage);
            console.log('Window Width:', window.innerWidth);
            
            function updateBackgroundImage() {
                if (window.innerWidth <= 767) {
                    console.log('Switching to mobile image');
                    if (mobileImage && mobileImage !== '') {
                        heroSection.style.setProperty('background-image', `url('${mobileImage}')`, 'important');
                    }
                } else {
                    console.log('Switching to desktop image');
                    heroSection.style.setProperty('background-image', desktopImage, 'important');
                }
            }

            // Chạy lần đầu khi trang load
            updateBackgroundImage();

            // Thêm event listener cho resize
            window.addEventListener('resize', updateBackgroundImage);
        });

        // Code cho changing text effect
        const phrases = ["làm đẹp", "sửa chữa", "vệ sinh"];
        const changingText = document.querySelector(".changing-text");

        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        const typingSpeed = 50;
        const deletingSpeed = 50;
        const delayBetweenWords = 1500;

        function typeEffect() {
            const currentPhrase = phrases[phraseIndex];
            if (isDeleting) {
                changingText.textContent = currentPhrase.substring(0, charIndex--);
            } else {
                changingText.textContent = currentPhrase.substring(0, charIndex++);
            }

            if (!isDeleting && charIndex === currentPhrase.length + 1) {
                isDeleting = true;
                setTimeout(typeEffect, delayBetweenWords);
                return;
            }

            if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
            }

            const delay = isDeleting ? deletingSpeed : typingSpeed;
            setTimeout(typeEffect, delay);
        }

        // Khởi động hiệu ứng
        typeEffect();
    </script>
@endpush
