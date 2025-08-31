@php
    $bannerContent = getContent('banner.content', true);
    $mobileBannerContent = getContent('mobile_banner.content', true);
    $desktopImage = frontendImage('banner', @$bannerContent->data_values->image, '1920x840');
    $mobileImage = frontendImage('mobile_banner', @$mobileBannerContent->data_values->image, '600x800');
    
    // If no mobile image, use desktop image
    if (strpos($mobileImage, 'placeholder-image') !== false) {
        $mobileImage = $desktopImage;
    }
    
    // Lấy thống kê thực tế từ database
    $totalCompanies = \App\Models\Company::where('status', 1)->count();
    $totalAppointments = \App\Models\Appointment::count();
    $avgRating = \App\Models\Company::where('status', 1)->avg('avg_rating') ?? 0;
    
    // Format số liệu
    $formattedCompanies = $totalCompanies >= 1000 ? number_format($totalCompanies / 1000, 1) . 'K+' : $totalCompanies . '+';
    $formattedAppointments = $totalAppointments >= 1000 ? number_format($totalAppointments / 1000, 1) . 'K+' : $totalAppointments . '+';
    $formattedRating = number_format($avgRating, 1);
@endphp

<!-- Hero Section with Admin Managed Background Image -->
<section class="hero-section bg_img" 
         data-mobile-image="{{ $mobileImage }}" 
         style="background-image: url('{{ $desktopImage }}');">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Tìm Thợ Chuyên Nghiệp <br>
                        <span class="gradient-text">Nhanh & Tin Cậy</span>
                    </h1>
                    <p class="hero-description">
                        Kết nối bạn với hàng ngàn thợ chuyên nghiệp. Từ điện nước, sửa chữa đến thi công - 
                        tất cả trong một nền tảng tin cậy.
                    </p>
                    
                    <!-- Quick Action Buttons -->
                    <div class="hero-actions">
                        <a href="#quick-lead-form" class="btn btn-primary btn-lg me-3">
                            <i class="las la-plus me-2"></i>Tạo Nhu cầu Ngay
                        </a>
                        <a href="#contractor-search" class="btn btn-outline-light btn-lg">
                            <i class="las la-search me-2"></i>Tìm Thợ
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="hero-stats mt-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">{{ $formattedCompanies }}</h4>
                                    <p class="stat-label">Thợ</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">{{ $formattedAppointments }}</h4>
                                    <p class="stat-label">Jobs</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">{{ $formattedRating }}⭐</h4>
                                    <p class="stat-label">Đánh giá</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="hero-visual">
                    <!-- Hero visual content can be added here -->
                </div>
            </div>
        </div>
    </div>
</section> 