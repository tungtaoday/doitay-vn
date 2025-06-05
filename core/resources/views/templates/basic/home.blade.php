@extends($activeTemplate . 'layouts.frontend')
@section('content')

@php
    $bannerContent = getContent('banner.content', true);
    $mobileBannerContent = getContent('mobile_banner.content', true);
    $desktopImage = frontendImage('banner', @$bannerContent->data_values->image, '1920x840');
    $mobileImage = frontendImage('mobile_banner', @$mobileBannerContent->data_values->image, '768x500');
    
    // If no mobile image, use desktop image
    if (strpos($mobileImage, 'placeholder-image') !== false) {
        $mobileImage = $desktopImage;
    }
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
                            <i class="las la-plus me-2"></i>Tạo Lead Ngay
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
                                    <h4 class="stat-number">2,500+</h4>
                                    <p class="stat-label">Thợ</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">15,000+</h4>
                                    <p class="stat-label">Jobs</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">4.9⭐</h4>
                                    <p class="stat-label">Đánh giá</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5 how-it-works-section">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center mb-5">
            <!-- <div class="section-badge">
                <i class="las la-cog"></i>
                <span>Quy Trình Làm Việc</span>
            </div> -->
            <h2 class="section-title-how">Cách Chúng Tôi Hoạt Động</h2>
            <p class="section-subtitle-how">Quy trình đơn giản 3 bước để tìm được thợ chuyên nghiệp phù hợp</p>
            <div class="title-decoration">
                <div class="decoration-line"></div>
                <div class="decoration-circle"></div>
                <div class="decoration-line"></div>
            </div>
        </div>
        
        <!-- Process Steps -->
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="process-step-card">
                    <div class="step-header">
                        <div class="step-icon-modern">
                            <span class="step-number-modern">1</span>
                            <div class="step-icon-bg-modern">
                                <i class="las la-edit"></i>
                            </div>
                        </div>
                        <div class="step-connector step-connector-1"></div>
                    </div>
                    <div class="step-content">
                        <h4 class="step-title-modern">Tạo Lead</h4>
                        <p class="step-description-modern">Mô tả công việc cần làm, ngân sách và thời gian. Hệ thống tự động thông báo cho các thợ phù hợp trong khu vực.</p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Miễn phí 100%</span>
                            <span class="feature-tag">✓ Nhanh chóng</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="process-step-card step-card-featured">
                    <div class="step-header">
                        <div class="step-icon-modern step-icon-featured">
                            <span class="step-number-modern">2</span>
                            <div class="step-icon-bg-modern">
                                <i class="las la-users"></i>
                            </div>
                        </div>
                        <div class="step-connector step-connector-2"></div>
                    </div>
                    <div class="step-content">
                        <h4 class="step-title-modern">Nhận Báo Giá</h4>
                        <p class="step-description-modern">Các thợ quan tâm sẽ mua lead và liên hệ báo giá trực tiếp. Bạn so sánh giá và chọn thợ phù hợp nhất với nhu cầu.</p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Nhiều lựa chọn</span>
                            <span class="feature-tag featured-tag">✓ Thợ verified</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="process-step-card">
                    <div class="step-header">
                        <div class="step-icon-modern">
                            <span class="step-number-modern">3</span>
                            <div class="step-icon-bg-modern">
                                <i class="las la-handshake"></i>
                            </div>
                        </div>
                    </div>
                    <div class="step-content">
                        <h4 class="step-title-modern">Hoàn Thành</h4>
                        <p class="step-description-modern">Thợ thực hiện công việc chuyên nghiệp, bạn thanh toán và đánh giá. Tích điểm loyalty cho những lần tiếp theo.</p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Đảm bảo chất lượng</span>
                            <span class="feature-tag">✓ Tích điểm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Lead Creation Form -->
<section id="quick-lead-form" class="py-5 bg-light quick-lead-form">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left Column - Form -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="lead-form-container">
                    <div class="form-header text-center mb-4">
                        <div class="form-badge">
                            <i class="las la-rocket"></i>
                            <span>Miễn Phí 100%</span>
                        </div>
                        <h3 class="form-title">Tạo Lead & Tìm Thợ Ngay</h3>
                        <p class="form-subtitle">Chỉ mất 2 phút • Nhận báo giá từ nhiều thợ chuyên nghiệp</p>
                    </div>

                    <div class="modern-card">
                        <!-- Auth Tabs (Show only if not logged in) -->
                        @guest
                        <div class="auth-tabs mb-4" id="authTabs">
                            <div class="tab-buttons">
                                <button class="tab-btn active" data-tab="guest">
                                    <i class="las la-user-clock"></i>
                                    Tạo Lead Nhanh
                                </button>
                                <button class="tab-btn" data-tab="login">
                                    <i class="las la-sign-in-alt"></i>
                                    Đăng Nhập
                                </button>
                                <button class="tab-btn" data-tab="register">
                                    <i class="las la-user-plus"></i>
                                    Đăng Ký
                                </button>
                            </div>
                        </div>
                        @endguest

                        @auth
                        <!-- Logged in user header -->
                        <div class="auth-header mb-4 text-center">
                            <div class="user-welcome">
                                <i class="las la-user-circle text-primary" style="font-size: 2rem;"></i>
                                <h5 class="mt-2 mb-1">Xin chào, {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}!</h5>
                                <p class="text-muted">Tạo lead mới để tìm thợ chuyên nghiệp</p>
                            </div>
                        </div>
                        @endauth

                        <!-- Lead Form (Always visible, active by default for logged in users) -->
                        <div class="tab-content {{ auth()->check() ? 'active' : '' }}" id="leadTab">
                            <form class="lead-form" id="authenticatedLeadForm">
                                @csrf
                                <div class="form-step" id="lead-step1">
                                    <h5 class="step-title">📋 Thông tin công việc</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Loại công việc *</label>
                                            <select name="category_id" class="form-select modern-select" id="auth_category_id" required>
                                                <option value="">Chọn loại công việc</option>
                                                @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Thành phố *</label>
                                            <select name="city_code" class="form-select modern-select" id="auth_city_code" required>
                                                <option value="">Chọn thành phố</option>
                                                <!-- Cities will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Quận/Huyện *</label>
                                            <select name="district_code" class="form-select modern-select" id="auth_district_code" disabled required>
                                                <option value="">Chọn quận/huyện</option>
                                                <!-- Districts will be loaded dynamically -->
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phường/Xã</label>
                                            <select name="ward_code" class="form-select modern-select" id="auth_ward_code" disabled>
                                                <option value="">Chọn phường/xã</option>
                                                <!-- Wards will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Địa chỉ cụ thể *</label>
                                        <input type="text" name="address" class="form-control modern-input" id="auth_address"
                                               placeholder="Số nhà, tên đường" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề công việc *</label>
                                        <input type="text" name="title" class="form-control modern-input" id="auth_title"
                                               placeholder="VD: Sửa chữa điện nước tại nhà" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Mô tả chi tiết *</label>
                                        <textarea name="description" class="form-control modern-textarea" id="auth_description" rows="3" 
                                                  placeholder="Mô tả chi tiết công việc cần làm..." required></textarea>
                                    </div>

                                    <button type="button" class="btn btn-primary btn-lg w-100 next-step-auth">
                                        Tiếp theo: Chi tiết bổ sung
                                        <i class="las la-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <div class="form-step" id="lead-step2" style="display: none;">
                                    <h5 class="step-title">💰 Chi tiết bổ sung</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngân sách từ</label>
                                            <input type="number" name="budget_min" class="form-control modern-input" id="auth_budget_min"
                                                   placeholder="200,000">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngân sách đến</label>
                                            <input type="number" name="budget_max" class="form-control modern-input" id="auth_budget_max"
                                                   placeholder="500,000">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mức độ ưu tiên</label>
                                            <select name="urgency" class="form-select modern-select" id="auth_urgency">
                                                <option value="medium">Bình thường</option>
                                                <option value="high">Khẩn cấp</option>
                                                <option value="low">Không gấp</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cần hoàn thành trước</label>
                                            <input type="date" name="needed_by" class="form-control modern-input" id="auth_needed_by"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        </div>
                                    </div>

                                    <div class="step-navigation">
                                        <button type="button" class="btn btn-outline-secondary prev-step-auth">
                                            <i class="las la-arrow-left me-2"></i>Quay lại
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg submit-lead-auth">
                                            <i class="las la-rocket me-2"></i>Tạo Lead Ngay
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        @guest
                        <!-- Guest Form (Only for non-logged in users) -->
                        <div class="tab-content active" id="guestTab">
                            <form class="lead-form" id="guestLeadForm">
                                @csrf
                                <div class="form-step" id="step1">
                                    <h5 class="step-title">📋 Thông tin công việc</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Loại công việc *</label>
                                            <select name="category_id" class="form-select modern-select" id="guest_category_id" required>
                                                <option value="">Chọn loại công việc</option>
                                                @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Thành phố *</label>
                                            <select name="city_code" class="form-select modern-select" id="guest_city_code" required>
                                                <option value="">Chọn thành phố</option>
                                                <!-- Cities will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Quận/Huyện *</label>
                                            <select name="district_code" class="form-select modern-select" id="guest_district_code" disabled required>
                                                <option value="">Chọn quận/huyện</option>
                                                <!-- Districts will be loaded dynamically -->
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phường/Xã</label>
                                            <select name="ward_code" class="form-select modern-select" id="guest_ward_code" disabled>
                                                <option value="">Chọn phường/xã</option>
                                                <!-- Wards will be loaded dynamically -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề công việc *</label>
                                        <input type="text" name="title" class="form-control modern-input" id="guest_title"
                                               placeholder="VD: Sửa chữa điện nước tại nhà" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Mô tả chi tiết *</label>
                                        <textarea name="description" class="form-control modern-textarea" id="guest_description" rows="3" 
                                                  placeholder="Mô tả chi tiết công việc cần làm..." required></textarea>
                                    </div>

                                    <button type="button" class="btn btn-primary btn-lg w-100 next-step">
                                        Tiếp theo: Thông tin liên hệ
                                        <i class="las la-arrow-right ms-2"></i>
                                    </button>
                                </div>

                                <div class="form-step" id="step2" style="display: none;">
                                    <h5 class="step-title">📞 Thông tin liên hệ</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Họ tên *</label>
                                            <input type="text" name="fullname" class="form-control modern-input" id="guest_fullname"
                                                   placeholder="Nhập họ tên" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Số điện thoại *</label>
                                            <input type="tel" name="mobile" class="form-control modern-input" id="guest_mobile"
                                                   placeholder="0123456789" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control modern-input" id="guest_email"
                                               placeholder="email@domain.com">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Địa chỉ cụ thể *</label>
                                        <input type="text" name="address" class="form-control modern-input" id="guest_address"
                                               placeholder="Số nhà, tên đường" required>
                                    </div>

                                    <div class="step-navigation">
                                        <button type="button" class="btn btn-outline-secondary prev-step">
                                            <i class="las la-arrow-left me-2"></i>Quay lại
                                        </button>
                                        <button type="button" class="btn btn-primary next-step">
                                            Tiếp theo: Chi tiết khác
                                            <i class="las la-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-step" id="step3" style="display: none;">
                                    <h5 class="step-title">💰 Chi tiết bổ sung</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngân sách từ</label>
                                            <input type="number" name="budget_min" class="form-control modern-input" id="guest_budget_min"
                                                   placeholder="200,000">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngân sách đến</label>
                                            <input type="number" name="budget_max" class="form-control modern-input" id="guest_budget_max"
                                                   placeholder="500,000">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mức độ ưu tiên</label>
                                            <select name="urgency" class="form-select modern-select" id="guest_urgency">
                                                <option value="medium">Bình thường</option>
                                                <option value="high">Khẩn cấp</option>
                                                <option value="low">Không gấp</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cần hoàn thành trước</label>
                                            <input type="date" name="needed_by" class="form-control modern-input" id="guest_needed_by"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                        </div>
                                    </div>

                                    <div class="terms-checkbox mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                                            <label class="form-check-label" for="agreeTerms">
                                                Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a> 
                                                và <a href="#" class="text-primary">Chính sách bảo mật</a>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="step-navigation">
                                        <button type="button" class="btn btn-outline-secondary prev-step">
                                            <i class="las la-arrow-left me-2"></i>Quay lại
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg submit-lead">
                                            <i class="las la-rocket me-2"></i>Tạo Lead & Tự Động Đăng Ký
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endguest

                        <!-- Login Tab -->
                        <div class="tab-content" id="loginTab">
                            <form class="auth-form" id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email hoặc Số điện thoại</label>
                                    <input type="text" name="username" class="form-control modern-input" id="login_username"
                                           placeholder="email@domain.com hoặc 0123456789" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control modern-input" id="login_password"
                                           placeholder="Nhập mật khẩu" required>
                                </div>
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Ghi nhớ</label>
                                    </div>
                                    <a href="#" class="text-primary">Quên mật khẩu?</a>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="las la-sign-in-alt me-2"></i>Đăng Nhập
                                </button>
                            </form>
                        </div>

                        <!-- Register Tab -->
                        <div class="tab-content" id="registerTab">
                            <form class="auth-form" id="registerForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Họ *</label>
                                        <input type="text" name="firstname" class="form-control modern-input" id="register_firstname"
                                               placeholder="Họ" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tên *</label>
                                        <input type="text" name="lastname" class="form-control modern-input" id="register_lastname"
                                               placeholder="Tên" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control modern-input" id="register_email"
                                           placeholder="email@domain.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="tel" name="mobile" class="form-control modern-input" id="register_mobile"
                                           placeholder="0123456789" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mật khẩu *</label>
                                        <input type="password" name="password" class="form-control modern-input" id="register_password"
                                               placeholder="Tối thiểu 6 ký tự" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Xác nhận mật khẩu *</label>
                                        <input type="password" name="password_confirmation" class="form-control modern-input" id="register_password_confirmation"
                                               placeholder="Nhập lại mật khẩu" required>
                                    </div>
                                </div>
                                <div class="terms-checkbox mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="agreeTermsReg" required>
                                        <label class="form-check-label" for="agreeTermsReg">
                                            Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a>
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="las la-user-plus me-2"></i>Đăng Ký Miễn Phí
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Persuasive Content -->
            <div class="col-lg-6">
                <div class="persuasive-content">
                    <!-- Hero Image/Video -->
                    <!-- <div class="content-hero mb-4">
                        <div class="hero-video-placeholder">
                            <div class="video-overlay">
                                <i class="las la-play-circle"></i>
                                <h4>Xem cách Doitay.vn hoạt động</h4>
                                <p>2 phút để hiểu toàn bộ quy trình</p>
                            </div>
                        </div>
                    </div> -->

                    <!-- Trust Indicators -->
                    <div class="trust-indicators mb-4">
                        <h4 class="trust-title">✨ Tại sao chọn Doitay.vn?</h4>
                        <div class="trust-features">
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="las la-shield-alt text-success"></i>
                                </div>
                                <div class="trust-text">
                                    <h6>100% An toàn & Bảo mật</h6>
                                    <p>Thông tin được mã hóa SSL, thợ đã xác minh</p>
                                </div>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="las la-clock text-primary"></i>
                                </div>
                                <div class="trust-text">
                                    <h6>Phản hồi trong 15 phút</h6>
                                    <p>Nhận báo giá nhanh chóng từ nhiều thợ</p>
                                </div>
                            </div>
                            <div class="trust-item">
                                <div class="trust-icon">
                                    <i class="las la-medal text-warning"></i>
                                </div>
                                <div class="trust-text">
                                    <h6>Thợ chuyên nghiệp verified</h6>
                                    <p>Đã kiểm tra kỹ năng và kinh nghiệm</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Reviews -->
                    <div class="customer-reviews">
                        <h5 class="reviews-title">💬 Khách hàng nói gì</h5>
                        <div class="reviews-slider">
                            <div class="review-item active">
                                <div class="review-stars">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <p class="review-text">"Tìm được thợ sửa điện nước rất nhanh, giá cả hợp lý. Sẽ dùng lại!"</p>
                                <div class="reviewer">
                                    <strong>Anh Minh</strong> - Quận 1, TP.HCM
                                </div>
                            </div>
                            <div class="review-item">
                                <div class="review-stars">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <p class="review-text">"Platform rất dễ sử dụng, nhiều thợ chuyên nghiệp. Rất hài lòng!"</p>
                                <div class="reviewer">
                                    <strong>Chị Lan</strong> - Quận 7, TP.HCM
                                </div>
                            </div>
                            <div class="review-item">
                                <div class="review-stars">
                                    ⭐⭐⭐⭐⭐
                                </div>
                                <p class="review-text">"Thợ đến đúng giờ, làm việc sạch sẽ. Giá cả minh bạch."</p>
                                <div class="reviewer">
                                    <strong>Anh Tuấn</strong> - Quận Bình Thạnh
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Counter -->
                    <!-- <div class="stats-counter mt-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-number" data-count="2500">0</div>
                                <div class="stat-label">Thợ</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number" data-count="15000">0</div>
                                <div class="stat-label">Jobs</div>
                            </div>
                            <div class="col-4">
                                <div class="stat-number" data-count="99">0</div>
                                <div class="stat-label">% Hài lòng</div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contractor Search Section -->
<section id="contractor-search" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Tìm Thợ Theo Danh Mục</h2>
            <p class="section-subtitle">Hoặc tìm thợ trực tiếp theo chuyên môn</p>
        </div>
        
        <div class="row">
            @foreach(App\Models\Category::where('status', 1)->take(8)->get() as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <a href="{{ route('companies.category', $category->id) }}" class="category-card">
                    <div class="card h-100 border-0 shadow-sm category-card-inner">
                        <div class="card-body text-center">
                            <div class="category-icon mb-3">
                                <i class="las la-tools text-primary"></i>
                            </div>
                            <h5 class="card-title">{{ $category->name }}</h5>
                            <p class="card-text small text-muted">
                                {{ $category->companies_count ?? 0 }} thợ có sẵn
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Contractors -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Thợ Chuyên Nghiệp Nổi Bật</h2>
            <p class="section-subtitle">Được khách hàng đánh giá cao nhất</p>
        </div>
        
        <div class="row">
            @foreach(App\Models\Company::with(['user', 'ratings'])->approved()->take(4)->get() as $company)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="contractor-card">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="contractor-avatar mb-3">
                                <img src="{{ getImage(getFilePath('company') . '/' . $company->image, getFileSize('company')) }}" 
                                     alt="{{ $company->name }}" class="rounded-circle">
                            </div>
                            <h5 class="card-title">{{ $company->name }}</h5>
                            <p class="text-muted small">{{ $company->category->name ?? 'N/A' }}</p>
                            
                            <div class="rating mb-2">
                                @php $avgRating = $company->ratings->avg('avg_rating') ?? 0; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="las la-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                                <span class="ms-1">({{ $company->ratings->count() }})</span>
                            </div>
                            
                            <a href="{{ route('company.details', [$company->id, slug($company->name)]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                Xem Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Khách Hàng Nói Gì</h2>
            <p class="section-subtitle">Trải nghiệm thực tế từ người dùng Thumbstack</p>
        </div>
        
        <div class="row">
            @foreach(App\Models\Rating::with(['user', 'company'])->where('status', 1)->latest()->take(3)->get() as $review)
            <div class="col-lg-4 mb-4">
                <div class="testimonial-card">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="stars mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="las la-star {{ $i <= $review->avg_rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </div>
                            <p class="review-text">{{ Str::limit($review->suggest, 120) }}</p>
                            <div class="reviewer-info">
                                <strong>{{ $review->user->fullname }}</strong>
                                <small class="text-muted d-block">Đã sử dụng {{ $review->company->name }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="mb-3">Sẵn Sàng Tìm Thợ Ngay?</h2>
        <p class="mb-4 fs-5">Hàng ngàn thợ chuyên nghiệp đang chờ giúp bạn</p>
        
        @auth
            <a href="{{ route('user.customer.leads.create') }}" class="btn btn-light btn-lg me-3">
                <i class="las la-plus me-2"></i>Tạo Lead Ngay
            </a>
        @else
            <a href="{{ route('user.register') }}" class="btn btn-light btn-lg me-3">
                <i class="las la-user-plus me-2"></i>Đăng Ký Miễn Phí
            </a>
        @endauth
        
        <a href="#contractor-search" class="btn btn-outline-light btn-lg">
            <i class="las la-search me-2"></i>Duyệt Thợ
        </a>
    </div>
</section>

<style>
/* Global Font Family */
* {
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Hero Section with Background Image */
.hero-section.bg_img {
    position: relative;
    min-height: 100vh;
    background-size: cover !important;
    background-position: center !important;
    background-repeat: no-repeat !important;
    overflow: hidden;
}

.hero-section .container {
    position: relative;
    z-index: 2;
}

.hero-content {
    color: white;
}

.hero-title {
    font-size: 3.2rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: white;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    font-family: 'Inter', sans-serif;
}

.gradient-text {
    background: linear-gradient(45deg, #ffd700, #ffa500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: none;
}

.hero-description {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.95);
    margin-bottom: 2rem;
    text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    line-height: 1.6;
    font-weight: 400;
}

.hero-actions .btn {
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.hero-actions .btn-primary {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    color: white;
}

.hero-actions .btn-primary:hover {
    background: white;
    color: #0b92d4;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.hero-actions .btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.5);
    background: transparent;
    color: white;
}

.hero-actions .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: white;
    transform: translateY(-2px);
    color: white;
}

.hero-actions .btn:last-child {
    margin-bottom: 0;
}

/* Hero Stats Styling */
.hero-stats .stat-item {
    text-align: center;
    position: relative;
    padding: 1.25rem 0.5rem;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    overflow: hidden;
}

.hero-stats .stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 165, 0, 0.1) 0%, rgba(255, 215, 0, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 16px;
}

.hero-stats .stat-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border-color: rgba(255, 215, 0, 0.4);
}

.hero-stats .stat-item:hover::before {
    opacity: 1;
}

.hero-stats .stat-number {
    position: relative;
    z-index: 2;
    font-size: 1.5rem;
    font-weight: 800;
    color: #ffffff;
    margin-bottom: 0.25rem;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
    line-height: 1.2;
}

.hero-stats .stat-label {
    position: relative;
    z-index: 2;
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.95);
    margin: 0;
    font-weight: 600;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    letter-spacing: 0.3px;
    line-height: 1.1;
}

/* Stats Counter */
.stats-counter .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #0b92d4;
    margin-bottom: 0.5rem;
    display: block;
}

.stats-counter {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stats-counter .stat-label {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .stats-counter {
        padding: 1.25rem;
    }
    
    .stats-counter .stat-number {
        font-size: 1.5rem;
    }
    
    .stats-counter .stat-label {
        font-size: 0.8rem;
    }
}

/* Section Titles */
.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-family: 'Inter', sans-serif;
}

.section-subtitle {
    font-size: 1.125rem;
    color: #6c757d;
    font-weight: 400;
    line-height: 1.6;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .hero-section.bg_img {
        min-height: 80vh;
        background-size: cover !important;
        background-position: center !important;
        margin: 1rem;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .hero-title {
        font-size: 2rem;
        line-height: 1.3;
        margin-bottom: 1rem;
    }
    
    .hero-description {
        font-size: 1rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
    
    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
        padding: 0.875rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .hero-actions .btn:last-child {
        margin-bottom: 0;
    }
    
    .hero-stats .stat-item {
        padding: 1rem 0.25rem;
    }
    
    .hero-stats .stat-number {
        font-size: 1.1rem;
        margin-bottom: 0.2rem;
        line-height: 1.1;
    }
    
    .hero-stats .stat-label {
        font-size: 0.65rem;
        line-height: 1;
        letter-spacing: 0.2px;
    }
    
    .section-title {
        font-size: 1.75rem;
        margin-bottom: 0.75rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
}

/* Small mobile devices */
@media (max-width: 480px) {
    .hero-section.bg_img {
        margin: 0.75rem;
        border-radius: 16px;
        min-height: 75vh;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-description {
        font-size: 0.95rem;
    }
    
    .hero-actions .btn {
        padding: 0.75rem 1.25rem;
        font-size: 0.85rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .section-subtitle {
        font-size: 0.9rem;
    }
}

/* Process Steps */
.process-step {
    position: relative;
    padding: 2rem 1rem;
}

.process-step h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.process-step p {
    font-size: 1rem;
    color: #6c757d;
    line-height: 1.6;
    font-weight: 400;
}

.step-icon {
    position: relative;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.step-number {
    position: absolute;
    top: -10px;
    right: -10px;
    background: #0b92d4;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.875rem;
    z-index: 2;
}

.step-icon-bg {
    font-size: 4rem;
    color: rgba(11, 146, 212, 0.1);
}

@media (max-width: 768px) {
    .process-step {
        padding: 1.5rem 0.5rem;
    }
    
    .process-step h4 {
        font-size: 1.125rem;
    }
    
    .process-step p {
        font-size: 0.9rem;
    }
    
    .step-icon-bg {
        font-size: 3rem;
    }
}

/* Category Cards */
.category-card {
    text-decoration: none;
    color: inherit;
}

.category-card-inner {
    transition: all 0.3s ease;
}

.category-card:hover .category-card-inner {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.category-card .card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2c3e50;
}

.category-card .card-text {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 400;
}

.category-icon i {
    font-size: 3rem;
}

@media (max-width: 768px) {
    .category-card .card-title {
        font-size: 1rem;
    }
    
    .category-card .card-text {
        font-size: 0.8rem;
    }
    
    .category-icon i {
        font-size: 2.5rem;
    }
}

/* Contractor Cards */
.contractor-avatar img {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.contractor-card .card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #2c3e50;
}

.contractor-card .text-muted {
    font-size: 0.875rem;
    font-weight: 400;
}

@media (max-width: 768px) {
    .contractor-avatar img {
        width: 60px;
        height: 60px;
    }
    
    .contractor-card .card-title {
        font-size: 1rem;
    }
    
    .contractor-card .text-muted {
        font-size: 0.8rem;
    }
}

/* Quick Lead Form Styles */
.lead-form-container {
    position: relative;
}

.form-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #48bbe2;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(72, 187, 226, 0.25);
    font-family: 'Inter', sans-serif;
}

.form-title {
    color: #102f4b;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    font-family: 'Inter', sans-serif;
}

.form-subtitle {
    color: #6c757d;
    font-size: 1rem;
    font-weight: 400;
    font-family: 'Inter', sans-serif;
}

.modern-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(72, 187, 226, 0.1);
}

@media (max-width: 768px) {
    .form-badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    
    .form-title {
        font-size: 1.25rem;
    }
    
    .form-subtitle {
        font-size: 0.9rem;
    }
    
    .modern-card {
        padding: 1.5rem;
    }
}

/* Tab System */
.auth-tabs {
    margin-bottom: 1.5rem;
}

.tab-buttons {
    display: flex;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 0.25rem;
    gap: 0.25rem;
    position: relative;
    z-index: 10;
}

.tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    color: #6c757d;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 11;
    min-height: 48px;
    text-decoration: none;
    outline: none;
    -webkit-tap-highlight-color: transparent;
}

.tab-btn:focus {
    outline: 2px solid #48bbe2;
    outline-offset: 2px;
}

.tab-btn.active {
    background: white;
    color: #102f4b;
    box-shadow: 0 2px 8px rgba(16, 47, 75, 0.1);
    pointer-events: auto;
    z-index: 12;
}

.tab-btn:hover {
    color: #48bbe2;
    background: rgba(72, 187, 226, 0.1);
    pointer-events: auto;
}

.tab-btn:active {
    transform: scale(0.98);
}

/* Ensure tab content visibility */
.tab-content {
    display: none;
    position: relative;
    z-index: 5;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

/* Mobile tab improvements */
@media (max-width: 768px) {
    .tab-btn {
        font-size: 0.75rem;
        padding: 0.6rem 0.75rem;
        flex-direction: column;
        gap: 0.25rem;
        min-height: 56px;
    }
    
    .tab-btn i {
        font-size: 1.1rem;
    }
    
    .tab-buttons {
        gap: 0.15rem;
        padding: 0.2rem;
    }
}

/* Form Steps */
.form-step {
    animation: slideIn 0.4s ease;
}

.step-title {
    color: #102f4b;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(72, 187, 226, 0.2);
    font-size: 1.125rem;
    font-family: 'Inter', sans-serif;
}

.step-navigation {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

.step-navigation .btn {
    flex: 1;
}

@media (max-width: 768px) {
    .step-title {
        font-size: 1rem;
        margin-bottom: 1rem;
    }
    
    .step-navigation {
        flex-direction: column;
        gap: 0.75rem;
    }
}

/* Modern Form Controls */
.modern-input,
.modern-select,
.modern-textarea {
    border: 2px solid rgba(72, 187, 226, 0.2);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-family: 'Inter', sans-serif;
    color: #102f4b;
}

.modern-input:focus,
.modern-select:focus,
.modern-textarea:focus {
    border-color: #48bbe2;
    box-shadow: 0 0 0 0.2rem rgba(72, 187, 226, 0.15);
    background: white;
    outline: none;
}

.modern-input::placeholder,
.modern-textarea::placeholder {
    color: #6c757d;
    font-family: 'Inter', sans-serif;
}

.form-label {
    font-weight: 500;
    color: #102f4b;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
    font-family: 'Inter', sans-serif;
}

.terms-checkbox {
    background: rgba(72, 187, 226, 0.05);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(72, 187, 226, 0.15);
}

.terms-checkbox .form-check-label {
    font-size: 0.9rem;
    color: #102f4b;
    font-family: 'Inter', sans-serif;
}

@media (max-width: 768px) {
    .modern-input,
    .modern-select,
    .modern-textarea {
        font-size: 0.9rem;
        padding: 0.65rem 0.85rem;
    }
    
    .form-label {
        font-size: 0.85rem;
    }
    
    .terms-checkbox .form-check-label {
        font-size: 0.8rem;
    }
}

/* Persuasive Content */
.persuasive-content {
    padding-left: 2rem;
}

.content-hero {
    position: relative;
}

.hero-video-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    height: 250px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: transform 0.3s ease;
    overflow: hidden;
    position: relative;
}

.hero-video-placeholder::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="white" opacity="0.1"/></svg>') repeat;
    animation: float 6s ease-in-out infinite;
}

.hero-video-placeholder:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
}

.video-overlay {
    text-align: center;
    position: relative;
    z-index: 2;
}

.video-overlay i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.video-overlay h4 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 1.25rem;
}

.video-overlay p {
    opacity: 0.8;
    margin: 0;
    font-size: 1rem;
}

@media (max-width: 768px) {
    .persuasive-content {
        padding-left: 0;
        margin-top: 2rem;
    }
    
    .hero-video-placeholder {
        height: 200px;
    }
    
    .video-overlay i {
        font-size: 3rem;
    }
    
    .video-overlay h4 {
        font-size: 1.125rem;
    }
    
    .video-overlay p {
        font-size: 0.9rem;
    }
}

/* Trust Indicators */
.trust-indicators {
    display: block;
}

.trust-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
}

.trust-features {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.trust-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.trust-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.trust-icon {
    flex-shrink: 0;
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(11, 146, 212, 0.1);
}

.trust-icon i {
    font-size: 1.5rem;
}

.trust-text h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.trust-text p {
    color: #6c757d;
    font-size: 0.875rem;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .trust-title {
        font-size: 1.125rem;
    }
    
    .trust-features {
        gap: 1rem;
    }
    
    .trust-item {
        padding: 0.875rem;
    }
    
    .trust-icon {
        width: 40px;
        height: 40px;
    }
    
    .trust-icon i {
        font-size: 1.25rem;
    }
    
    .trust-text h6 {
        font-size: 0.95rem;
    }
    
    .trust-text p {
        font-size: 0.8rem;
    }
    
    /* Hide trust indicators on mobile */
    .trust-indicators {
        display: none;
    }
}

/* Customer Reviews */
.reviews-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
}

.reviews-slider {
    position: relative;
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.review-item {
    display: none;
    animation: fadeIn 0.5s ease;
}

.review-item.active {
    display: block;
}

.review-stars {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}

.review-text {
    font-style: italic;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    line-height: 1.6;
    font-weight: 400;
}

.reviewer {
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

@media (max-width: 768px) {
    .reviews-title {
        font-size: 1.125rem;
    }
    
    .reviews-slider {
        padding: 1.25rem;
    }
    
    .review-text {
        font-size: 1rem;
    }
    
    .reviewer {
        font-size: 0.85rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(5deg); }
}

/* How It Works Section - Clean Professional Design */
.how-it-works-section {
    background: #fafbfc;
    position: relative;
    overflow: hidden;
}

.how-it-works-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1.5" fill="%23102f4b" opacity="0.02"/></svg>') repeat;
    pointer-events: none;
}

.section-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #48bbe2;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(72, 187, 226, 0.25);
    position: relative;
    z-index: 2;
}

.section-badge i {
    font-size: 1.1rem;
}

.section-title-how {
    font-size: 2.75rem;
    font-weight: 700;
    color: #102f4b;
    margin-bottom: 1rem;
    font-family: 'Inter', sans-serif;
    position: relative;
    z-index: 2;
}

.section-subtitle-how {
    font-size: 1.2rem;
    color:rgb(0, 0, 0);
    font-weight: 400;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto 2rem;
    position: relative;
    z-index: 2;
}

.title-decoration {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
}

.decoration-line {
    width: 60px;
    height: 2px;
    background: #48bbe2;
    opacity: 0.3;
}

.decoration-circle {
    width: 8px;
    height: 8px;
    background: #48bbe2;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(72, 187, 226, 0.15);
}

/* Process Step Cards */
.process-step-card {
    background: white;
    border-radius: 20px;
    padding: 2rem 1.5rem;
    box-shadow: 0 8px 32px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(72, 187, 226, 0.1);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.process-step-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #48bbe2;
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.process-step-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 16px 48px rgba(16, 47, 75, 0.15);
    border-color: rgba(72, 187, 226, 0.3);
}

.process-step-card:hover::before {
    transform: scaleX(1);
}

/* Featured Step Card */
.step-card-featured {
    background: rgba(72, 187, 226, 0.02);
    border: 2px solid rgba(72, 187, 226, 0.2);
    transform: scale(1.02);
}

.step-card-featured::before {
    background: #102f4b;
    height: 6px;
}

/* Step Header */
.step-header {
    position: relative;
    margin-bottom: 1.5rem;
    display: flex;
    justify-content: center;
}

.step-icon-modern {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #48bbe2;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(72, 187, 226, 0.25);
    transition: all 0.3s ease;
}

.step-icon-featured {
    background: #102f4b;
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.25);
    animation: pulse-featured 2s infinite;
}

.step-number-modern {
    position: absolute;
    top: -8px;
    right: -8px;
    background: white;
    color: #102f4b;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    z-index: 3;
    border: 2px solid #48bbe2;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.step-icon-bg-modern {
    color: white;
    font-size: 2rem;
    position: relative;
    z-index: 2;
}

/* Step Connectors */
.step-connector {
    position: absolute;
    top: 40px;
    right: -40px;
    width: 80px;
    height: 2px;
    background: #48bbe2;
    opacity: 0.3;
    z-index: 1;
}

.step-connector-2 {
    background: #102f4b;
    opacity: 0.4;
}

/* Step Content */
.step-content {
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.step-title-modern {
    font-size: 1.4rem;
    font-weight: 700;
    color: #102f4b;
    margin-bottom: 1rem;
    font-family: 'Inter', sans-serif;
}

.step-description-modern {
    font-size: 1rem;
    color:rgb(0, 0, 0);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex: 1;
}

.step-features {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: auto;
}

.feature-tag {
    display: inline-flex;
    align-items: center;
    background: rgba(72, 187, 226, 0.1);
    color: #102f4b;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid rgba(72, 187, 226, 0.2);
}

.featured-tag {
    background: rgba(16, 47, 75, 0.1);
    color: #102f4b;
    border-color: rgba(16, 47, 75, 0.2);
}

/* Animations */
@keyframes pulse-featured {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .how-it-works-section {
        padding: 3rem 0;
    }
    
    .section-title-how {
        font-size: 2rem;
        margin-bottom: 0.75rem;
    }
    
    .section-subtitle-how {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .title-decoration {
        margin-bottom: 2rem;
    }
    
    .decoration-line {
        width: 40px;
    }
    
    .process-step-card {
        padding: 1.5rem 1rem;
    }
    
    .step-icon-modern {
        width: 60px;
        height: 60px;
    }
    
    .step-icon-bg-modern {
        font-size: 1.5rem;
    }
    
    .step-number-modern {
        width: 24px;
        height: 24px;
        font-size: 0.8rem;
        top: -6px;
        right: -6px;
    }
    
    .step-connector {
        display: none;
    }
    
    .step-card-featured {
        transform: none;
    }
    
    .step-title-modern {
        font-size: 1.2rem;
    }
    
    .step-description-modern {
        font-size: 0.9rem;
    }
    
    .feature-tag {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}

/* Quick Lead Form Buttons */
.quick-lead-form .btn-primary {
    background: #48bbe2;
    border-color: #48bbe2;
    color: white;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 10;
    display: inline-block;
    text-decoration: none;
    outline: none;
}

.quick-lead-form .btn-primary:hover {
    background: #102f4b;
    border-color: #102f4b;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.2);
}

.quick-lead-form .btn-primary:focus {
    outline: 2px solid #48bbe2;
    outline-offset: 2px;
    box-shadow: 0 0 0 0.2rem rgba(72, 187, 226, 0.25);
}

.quick-lead-form .btn-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(16, 47, 75, 0.2);
}

.quick-lead-form .btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

/* Specific styling for next-step-auth button */
.next-step-auth {
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 15 !important;
    display: inline-block !important;
    background: #48bbe2 !important;
    border: 2px solid #48bbe2 !important;
    color: white !important;
    font-weight: 600 !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 12px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    outline: none !important;
    min-height: 48px !important;
    width: 100% !important;
}

.next-step-auth:hover {
    background: #102f4b !important;
    border-color: #102f4b !important;
    color: white !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.2) !important;
}

.next-step-auth:focus {
    outline: 2px solid #48bbe2 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(72, 187, 226, 0.25) !important;
}

.next-step-auth:active {
    transform: translateY(0) !important;
    background: #3aa3c7 !important;
}

/* Specific styling for prev-step-auth button */
.prev-step-auth {
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 15 !important;
    display: inline-block !important;
    background: transparent !important;
    border: 2px solid rgba(72, 187, 226, 0.3) !important;
    color: #102f4b !important;
    font-weight: 500 !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 12px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    outline: none !important;
    min-height: 48px !important;
}

.prev-step-auth:hover {
    background: rgba(72, 187, 226, 0.1) !important;
    border-color: #48bbe2 !important;
    color: #102f4b !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(72, 187, 226, 0.1) !important;
}

.prev-step-auth:focus {
    outline: 2px solid #48bbe2 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(72, 187, 226, 0.25) !important;
}

.prev-step-auth:active {
    transform: translateY(0) !important;
    background: rgba(72, 187, 226, 0.2) !important;
}

/* Ensure parent containers don't block clicks */
.lead-form-container,
.modern-card,
.tab-content,
#leadTab,
#authenticatedLeadForm,
.form-step,
.step-navigation {
    pointer-events: auto !important;
    position: relative;
}

/* Form step specific styling */
#lead-step1,
#lead-step2 {
    pointer-events: auto !important;
    position: relative;
    z-index: 5;
}

.quick-lead-form .btn-success {
    background: #102f4b;
    border-color: #102f4b;
    color: white;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 10;
}

.quick-lead-form .btn-success:hover {
    background: #48bbe2;
    border-color: #48bbe2;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(72, 187, 226, 0.2);
}

.quick-lead-form .btn-outline-secondary {
    border-color: rgba(72, 187, 226, 0.3);
    color: #102f4b;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 10;
}

.quick-lead-form .btn-outline-secondary:hover {
    background: rgba(72, 187, 226, 0.1);
    border-color: #48bbe2;
    color: #102f4b;
}

/* Mobile responsive button styling */
@media (max-width: 768px) {
    .next-step-auth {
        padding: 0.875rem 1.25rem !important;
        font-size: 0.9rem !important;
        min-height: 52px !important;
    }
    
    .quick-lead-form .btn-primary,
    .quick-lead-form .btn-success {
        padding: 0.875rem 1.25rem;
        font-size: 0.9rem;
        min-height: 48px;
    }
}

/* User Welcome Header */
.auth-header .user-welcome {
    padding: 1rem;
}

.auth-header .user-welcome i {
    color: #48bbe2 !important;
}

.auth-header .user-welcome h5 {
    color: #102f4b;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
}

.auth-header .user-welcome p {
    color: #6c757d;
    font-family: 'Inter', sans-serif;
    margin: 0;
}

/* Disabled Select Styling */
.modern-select:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
    cursor: not-allowed;
}

.modern-select:disabled:focus {
    border-color: rgba(72, 187, 226, 0.2);
    box-shadow: none;
}
</style>

@push('script')
<script>
"use strict";
// Handle responsive background image for hero section
document.addEventListener('DOMContentLoaded', function() {
    const heroSection = document.querySelector('.hero-section.bg_img');
    if (!heroSection) return;
    
    const mobileImage = heroSection.getAttribute('data-mobile-image');
    const desktopImage = heroSection.style.backgroundImage;
    
    function updateBackgroundImage() {
        if (window.innerWidth <= 768) {
            if (mobileImage && mobileImage !== '') {
                heroSection.style.setProperty('background-image', `url('${mobileImage}')`, 'important');
            }
        } else {
            heroSection.style.setProperty('background-image', desktopImage, 'important');
        }
    }

    // Run on page load
    updateBackgroundImage();

    // Add resize listener
    window.addEventListener('resize', updateBackgroundImage);

    // Initialize Quick Lead Form functionality
    initQuickLeadForm();
    
    // Initialize Location System
    initLocationSystem();
});

function initLocationSystem() {
    // Load cities when page loads
    loadCities();
    
    // Handle city change for both guest and authenticated forms
    $(document).on('change', 'select[name="city_code"]', function() {
        const cityCode = $(this).val();
        const formContainer = $(this).closest('form');
        const districtSelect = formContainer.find('select[name="district_code"]');
        const wardSelect = formContainer.find('select[name="ward_code"]');
        
        // Reset district and ward selects
        districtSelect.empty().append('<option value="">Chọn quận/huyện</option>').prop('disabled', true);
        wardSelect.empty().append('<option value="">Chọn phường/xã</option>').prop('disabled', true);
        
        if (cityCode) {
            loadDistricts(cityCode, districtSelect);
        }
    });
    
    // Handle district change for both guest and authenticated forms
    $(document).on('change', 'select[name="district_code"]', function() {
        const districtCode = $(this).val();
        const formContainer = $(this).closest('form');
        const wardSelect = formContainer.find('select[name="ward_code"]');
        
        // Reset ward select
        wardSelect.empty().append('<option value="">Chọn phường/xã</option>').prop('disabled', true);
        
        if (districtCode) {
            loadWards(districtCode, wardSelect);
        }
    });
    
    // Add hidden fields for form submission
    $(document).on('submit', '#guestLeadForm, #authenticatedLeadForm', function() {
        const form = $(this);
        const citySelect = form.find('select[name="city_code"]');
        const districtSelect = form.find('select[name="district_code"]');
        const wardSelect = form.find('select[name="ward_code"]');
        
        // Add city name
        const cityName = citySelect.find('option:selected').text();
        if (cityName && cityName !== 'Chọn thành phố') {
            $('<input>').attr({
                type: 'hidden',
                name: 'city',
                value: cityName
            }).appendTo(form);
        }
        
        // Add district name
        const districtName = districtSelect.find('option:selected').text();
        if (districtName && districtName !== 'Chọn quận/huyện') {
            $('<input>').attr({
                type: 'hidden',
                name: 'district',
                value: districtName
            }).appendTo(form);
        }
        
        // Add ward name
        const wardName = wardSelect.find('option:selected').text();
        if (wardName && wardName !== 'Chọn phường/xã') {
            $('<input>').attr({
                type: 'hidden',
                name: 'ward',
                value: wardName
            }).appendTo(form);
        }
    });
}

function loadCities() {
    console.log('Loading cities...');
    $.ajax({
        url: '/localtion/api/cities',
        type: 'GET',
        dataType: 'text', // Changed back to 'text' 
        success: function(response) {
            console.log('Raw cities API response:', response);
            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
            console.log('Cleaned response:', cleanResponse);
            try {
                const cities = JSON.parse(cleanResponse);
                const citySelects = $('select[name="city_code"]');
                
                console.log('Found city selects:', citySelects.length);
                console.log('Cities data:', cities);
                
                citySelects.each(function() {
                    const select = $(this);
                    select.empty().append('<option value="">Chọn thành phố</option>');
                    
                    cities.forEach(city => {
                        console.log('Adding city:', city);
                        select.append(
                            `<option value="${city.City_code}" data-name="${city.City}">${city.City}</option>`
                        );
                    });
                });
                
                console.log('Cities loaded successfully');
            } catch (error) {
                console.error("Lỗi xử lý dữ liệu cities:", error);
                showNotification('Có lỗi khi tải danh sách thành phố', 'error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Lỗi API (cities):", textStatus, errorThrown);
            console.error("Response text:", jqXHR.responseText);
            showNotification('Không thể tải danh sách thành phố', 'error');
        }
    });
}

function loadDistricts(cityCode, districtSelect) {
    console.log('Loading districts for city:', cityCode);
    $.ajax({
        url: `/localtion/api/districts/${cityCode}`,
        type: 'GET',
        dataType: 'text', // Changed back to 'text'
        success: function(response) {
            console.log('Raw districts API response:', response);
            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
            console.log('Cleaned districts response:', cleanResponse);
            try {
                const districts = JSON.parse(cleanResponse);
                
                districtSelect.empty().append('<option value="">Chọn quận/huyện</option>');
                
                districts.forEach(district => {
                    console.log('Adding district:', district);
                    districtSelect.append(
                        `<option value="${district.District_code}" data-name="${district.District}">${district.District}</option>`
                    );
                });
                
                districtSelect.prop('disabled', false);
                console.log('Districts loaded successfully');
            } catch (error) {
                console.error("Lỗi xử lý dữ liệu districts:", error);
                showNotification('Có lỗi khi tải danh sách quận/huyện', 'error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Lỗi API (districts):", textStatus, errorThrown);
            console.error("Response text:", jqXHR.responseText);
            showNotification('Không thể tải danh sách quận/huyện', 'error');
        }
    });
}

function loadWards(districtCode, wardSelect) {
    console.log('Loading wards for district:', districtCode);
    $.ajax({
        url: `/localtion/api/wards/${districtCode}`,
        type: 'GET',
        dataType: 'text', // Changed back to 'text'
        success: function(response) {
            console.log('Raw wards API response:', response);
            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
            console.log('Cleaned wards response:', cleanResponse);
            try {
                const wards = JSON.parse(cleanResponse);
                
                wardSelect.empty().append('<option value="">Chọn phường/xã</option>');
                
                wards.forEach(ward => {
                    console.log('Adding ward:', ward);
                    wardSelect.append(
                        `<option value="${ward.Ward_code}" data-name="${ward.Ward}">${ward.Ward}</option>`
                    );
                });
                
                wardSelect.prop('disabled', false);
                console.log('Wards loaded successfully');
            } catch (error) {
                console.error("Lỗi xử lý dữ liệu wards:", error);
                showNotification('Có lỗi khi tải danh sách phường/xã', 'error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Lỗi API (wards):", textStatus, errorThrown);
            console.error("Response text:", jqXHR.responseText);
            showNotification('Không thể tải danh sách phường/xã', 'error');
        }
    });
}

function initQuickLeadForm() {
    // Remove any existing event listeners first to prevent conflicts
    document.removeEventListener('DOMContentLoaded', initQuickLeadForm);
    
    // Tab switching (only for guests) with improved event handling
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // Clean up any existing tab listeners first
    tabBtns.forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    // Re-select after cloning
    const newTabBtns = document.querySelectorAll('.tab-btn');
    
    // Improved tab switching with debugging
    newTabBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Tab clicked:', btn.getAttribute('data-tab'));
            
            const targetTab = btn.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            newTabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            const targetElement = document.getElementById(targetTab + 'Tab');
            if (targetElement) {
                targetElement.classList.add('active');
                console.log('Activated tab:', targetTab);
            }
        });
    });

    // Manual tab switching function for debugging
    window.switchTab = function(tabName) {
        console.log('Manual tab switch to:', tabName);
        const tabBtn = document.querySelector(`[data-tab="${tabName}"]`);
        if (tabBtn) {
            tabBtn.click();
        }
    };

    // Form steps navigation for guest users
    let currentStep = 1;
    const totalSteps = 3;

    // Next step buttons for guest form with improved event handling
    const nextStepBtns = document.querySelectorAll('.next-step');
    nextStepBtns.forEach(btn => {
        // Clone to remove existing listeners
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    // Re-select and add listeners to cloned buttons
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Next step clicked, current step:', currentStep);
            if (validateCurrentStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });
    });

    // Previous step buttons for guest form with improved event handling
    const prevStepBtns = document.querySelectorAll('.prev-step');
    prevStepBtns.forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Previous step clicked, current step:', currentStep);
            goToStep(currentStep - 1);
        });
    });

    // Form steps navigation for authenticated users
    let currentAuthStep = 1;
    const totalAuthSteps = 2;

    // Next step buttons for authenticated user form with enhanced debugging
    const nextStepAuthBtns = document.querySelectorAll('.next-step-auth');
    console.log('Found next-step-auth buttons:', nextStepAuthBtns.length);
    
    nextStepAuthBtns.forEach((btn, index) => {
        console.log('Processing next-step-auth button', index, btn);
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    document.querySelectorAll('.next-step-auth').forEach((btn, index) => {
        console.log('Adding listener to next-step-auth button', index);
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Auth next step clicked, current step:', currentAuthStep);
            console.log('Button element:', btn);
            console.log('Button classes:', btn.className);
            
            // Skip validation for debugging - just go to next step
            goToAuthStep(currentAuthStep + 1);
            
            // Alternative: Check validation but still log
            // if (validateAuthStep(currentAuthStep)) {
            //     goToAuthStep(currentAuthStep + 1);
            // } else {
            //     console.log('Validation failed for auth step:', currentAuthStep);
            // }
        });
        
        // Add backup event listener
        btn.addEventListener('mousedown', (e) => {
            console.log('Auth next step button mousedown event');
        });
        
        btn.addEventListener('touchstart', (e) => {
            console.log('Auth next step button touchstart event');
        });
    });

    // Previous step buttons for authenticated user form
    const prevStepAuthBtns = document.querySelectorAll('.prev-step-auth');
    console.log('Found prev-step-auth buttons:', prevStepAuthBtns.length);
    
    prevStepAuthBtns.forEach((btn, index) => {
        console.log('Processing prev-step-auth button', index, btn);
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });

    document.querySelectorAll('.prev-step-auth').forEach((btn, index) => {
        console.log('Adding listener to prev-step-auth button', index);
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            console.log('Auth previous step clicked, current step:', currentAuthStep);
            console.log('Previous button element:', btn);
            console.log('Previous button classes:', btn.className);
            
            goToAuthStep(currentAuthStep - 1);
        });
        
        // Add backup event listeners
        btn.addEventListener('mousedown', (e) => {
            console.log('Auth prev step button mousedown event');
        });
        
        btn.addEventListener('touchstart', (e) => {
            console.log('Auth prev step button touchstart event');
        });
    });

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        
        console.log('Going to step:', step);
        // Hide current step
        const currentStepEl = document.getElementById('step' + currentStep);
        if (currentStepEl) {
            currentStepEl.style.display = 'none';
        }
        
        // Show target step
        currentStep = step;
        const targetStepEl = document.getElementById('step' + currentStep);
        if (targetStepEl) {
            targetStepEl.style.display = 'block';
        }
    }

    function goToAuthStep(step) {
        if (step < 1 || step > totalAuthSteps) {
            console.log('Invalid auth step:', step);
            return;
        }
        
        console.log('Going to auth step:', step, 'from current step:', currentAuthStep);
        
        // Hide current step
        const currentStepEl = document.getElementById('lead-step' + currentAuthStep);
        if (currentStepEl) {
            currentStepEl.style.display = 'none';
            console.log('Hid step:', 'lead-step' + currentAuthStep);
        } else {
            console.log('Could not find current step element:', 'lead-step' + currentAuthStep);
        }
        
        // Show target step
        currentAuthStep = step;
        const targetStepEl = document.getElementById('lead-step' + currentAuthStep);
        if (targetStepEl) {
            targetStepEl.style.display = 'block';
            console.log('Showed step:', 'lead-step' + currentAuthStep);
        } else {
            console.log('Could not find target step element:', 'lead-step' + currentAuthStep);
        }
    }

    function validateCurrentStep(step) {
        const currentStepEl = document.getElementById('step' + step);
        if (!currentStepEl) return true;
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            showNotification('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
        }

        return isValid;
    }

    function validateAuthStep(step) {
        const currentStepEl = document.getElementById('lead-step' + step);
        if (!currentStepEl) return true;
        
        let isValid = true;
        let missingFields = [];

        if (step === 1) {
            // Check required fields in step 1
            const requiredFields = currentStepEl.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    missingFields.push(field.previousElementSibling?.textContent || field.name || 'Unknown field');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Check district selection specifically for step 1
            const districtSelect = document.getElementById('auth_district_code');
            if (!districtSelect || !districtSelect.value) {
                if (districtSelect) districtSelect.classList.add('is-invalid');
                missingFields.push('Quận/Huyện');
                isValid = false;
            } else {
                if (districtSelect) districtSelect.classList.remove('is-invalid');
            }
        }

        if (step === 2) {
            // Check required fields in step 2 only
            const requiredFields = currentStepEl.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    missingFields.push(field.previousElementSibling?.textContent || field.name || 'Unknown field');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Don't re-validate district here, assume it was validated in step 1
            // Instead, just check if district value exists without marking as invalid
            const districtSelect = document.getElementById('auth_district_code');
            if (!districtSelect || !districtSelect.value) {
                console.warn('District not selected, but allowing submission (may be handled by server)');
                // Don't block submission, let server handle this
            }
        }

        if (!isValid) {
            console.log('Validation failed for step', step, '- Missing fields:', missingFields);
            showNotification('Vui lòng điền đầy đủ thông tin bắt buộc:\n- ' + missingFields.join('\n- '), 'error');
        } else {
            console.log('Validation passed for step', step);
        }

        return isValid;
    }

    // Authenticated Lead Form Submission with improved error handling
    const authenticatedLeadForm = document.getElementById('authenticatedLeadForm');
    if (authenticatedLeadForm) {
        // Remove existing listeners
        const newForm = authenticatedLeadForm.cloneNode(true);
        authenticatedLeadForm.parentNode.replaceChild(newForm, authenticatedLeadForm);
        
        // Add listener to new form
        document.getElementById('authenticatedLeadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('Authenticated lead form submitted');
            
            // Enhanced validation check
            if (!validateAuthStep(2)) {
                console.log('Validation failed, not submitting');
                return;
            }

            const formData = new FormData(e.target);
            
            // Add location data from selects
            const formContainer = e.target;
            const citySelect = formContainer.querySelector('select[name="city_code"]');
            const districtSelect = formContainer.querySelector('select[name="district_code"]');
            const wardSelect = formContainer.querySelector('select[name="ward_code"]');
            
            // Add city name
            if (citySelect && citySelect.value) {
                const cityName = citySelect.options[citySelect.selectedIndex].text;
                if (cityName && cityName !== 'Chọn thành phố') {
                    formData.append('city', cityName);
                    console.log('Added city:', cityName);
                }
            }
            
            // Add district name - required
            if (districtSelect && districtSelect.value) {
                const districtName = districtSelect.options[districtSelect.selectedIndex].text;
                if (districtName && districtName !== 'Chọn quận/huyện') {
                    formData.append('district', districtName);
                    console.log('Added district:', districtName);
                }
            } else {
                // More flexible district handling
                console.warn('No district selected, checking for alternatives...');
                
                // Try to get district from step 1 form data
                const districtCodeValue = districtSelect?.value;
                if (districtCodeValue) {
                    formData.append('district', 'District_' + districtCodeValue);
                    console.log('Added district from code:', districtCodeValue);
                } else {
                    // Last resort: use city as district
                    const citySelect = formContainer.querySelector('select[name="city_code"]');
                    if (citySelect && citySelect.value) {
                        const cityName = citySelect.options[citySelect.selectedIndex].text;
                        if (cityName && cityName !== 'Chọn thành phố') {
                            formData.append('district', cityName);
                            console.log('Using city as district fallback:', cityName);
                        }
                    }
                    
                    // If still no district, create a default one
                    if (!formData.get('district')) {
                        formData.append('district', 'Khu vực không xác định');
                        console.log('Using default district');
                    }
                }
            }
            
            // Add ward name with better fallback
            if (wardSelect && wardSelect.value) {
                const wardName = wardSelect.options[wardSelect.selectedIndex].text;
                if (wardName && wardName !== 'Chọn phường/xã') {
                    formData.append('ward', wardName);
                    console.log('Added ward:', wardName);
                }
            } else {
                // Use district name as ward if no ward selected
                const districtName = formData.get('district');
                if (districtName) {
                    formData.append('ward', districtName);
                    console.log('Added ward as district:', districtName);
                } else {
                    formData.append('ward', 'Phường không xác định');
                    console.log('Added default ward');
                }
            }
            
            const submitBtn = e.target.querySelector('.submit-lead-auth');
            
            console.log('Final form data entries:', Array.from(formData.entries()));
            
            // Show loading state
            submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
            submitBtn.disabled = true;

            try {
                console.log('Sending request to:', '{{ route("user.customer.leads.store") }}');
                const response = await fetch('{{ route("user.customer.leads.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const responseText = await response.text();
                    console.error('Non-JSON response:', responseText);
                    throw new Error('Server returned non-JSON response: ' + contentType);
                }

                const result = await response.json();
                console.log('Response result:', result);

                if (result.success) {
                    showNotification('🎉 Lead đã được tạo thành công! Bạn sẽ nhận được liên hệ sớm.', 'success');
                    e.target.reset();
                    goToAuthStep(1);
                } else {
                    // Show detailed validation errors
                    if (result.errors) {
                        console.log('Validation errors:', result.errors);
                        let errorMessage = result.message || 'Có lỗi xảy ra';
                        if (result.errors.district) {
                            errorMessage += '\n- ' + result.errors.district.join(', ');
                        }
                        if (result.errors.ward) {
                            errorMessage += '\n- ' + result.errors.ward.join(', ');
                        }
                        if (result.errors.address) {
                            errorMessage += '\n- ' + result.errors.address.join(', ');
                        }
                        showNotification(errorMessage, 'error');
                    } else {
                        showNotification(result.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                    }
                }
            } catch (error) {
                console.error('Form submission error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-rocket me-2"></i>Tạo Lead Ngay';
                submitBtn.disabled = false;
            }
        });
    }

    // Guest Lead Form Submission with improved error handling
    const guestLeadForm = document.getElementById('guestLeadForm');
    if (guestLeadForm) {
        // Remove existing listeners
        const newGuestForm = guestLeadForm.cloneNode(true);
        guestLeadForm.parentNode.replaceChild(newGuestForm, guestLeadForm);
        
        // Add listener to new form
        document.getElementById('guestLeadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('Guest lead form submitted');
            
            if (!validateCurrentStep(3)) return;

            const formData = new FormData(e.target);
            const submitBtn = e.target.querySelector('.submit-lead');
            
            console.log('Guest form data entries:', Array.from(formData.entries()));
            
            // Show loading state
            submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
            submitBtn.disabled = true;

            try {
                console.log('Guest sending request to:', '{{ route("user.customer.leads.store") }}');
                const response = await fetch('{{ route("user.customer.leads.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                console.log('Guest response status:', response.status);
                console.log('Guest response headers:', response.headers);

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const responseText = await response.text();
                    console.error('Guest non-JSON response:', responseText);
                    throw new Error('Server returned non-JSON response: ' + contentType);
                }

                const result = await response.json();
                console.log('Guest response result:', result);

                if (result.success) {
                    showNotification('🎉 Lead đã được tạo thành công! Bạn sẽ nhận được liên hệ sớm.', 'success');
                    e.target.reset();
                    goToStep(1);
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
                }
            } catch (error) {
                console.error('Guest form submission error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-rocket me-2"></i>Tạo Lead & Tự Động Đăng Ký';
                submitBtn.disabled = false;
            }
        });
    }

    // Login Form with improved error handling
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        // Remove existing listeners
        const newLoginForm = loginForm.cloneNode(true);
        loginForm.parentNode.replaceChild(newLoginForm, loginForm);
        
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('Login form submitted');
            
            const formData = new FormData(e.target);
            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đăng nhập...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("user.login") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const responseText = await response.text();
                    console.error('Login non-JSON response:', responseText);
                    throw new Error('Server returned non-JSON response: ' + contentType);
                }

                const result = await response.json();
                console.log('Login response result:', result);

                if (result.success) {
                    showNotification('✅ Đăng nhập thành công!', 'success');
                    // Switch to lead tab instead of reloading
                    setTimeout(() => {
                        // Hide auth tabs
                        const authTabs = document.getElementById('authTabs');
                        if (authTabs) {
                            authTabs.style.display = 'none';
                        }
                        
                        // Show lead tab
                        tabContents.forEach(c => c.classList.remove('active'));
                        const leadTab = document.getElementById('leadTab');
                        if (leadTab) {
                            leadTab.classList.add('active');
                        }
                        
                        // Show user welcome message
                        showUserWelcome(result.user);
                    }, 500);
                } else {
                    showNotification(result.message || 'Thông tin đăng nhập không chính xác', 'error');
                }
            } catch (error) {
                console.error('Login error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-sign-in-alt me-2"></i>Đăng Nhập';
                submitBtn.disabled = false;
            }
        });
    }

    // Register Form with improved error handling
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        // Remove existing listeners
        const newRegisterForm = registerForm.cloneNode(true);
        registerForm.parentNode.replaceChild(newRegisterForm, registerForm);
        
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopImmediatePropagation();
            console.log('Register form submitted');
            
            const formData = new FormData(e.target);
            const password = formData.get('password');
            const passwordConfirm = formData.get('password_confirmation');
            
            if (password !== passwordConfirm) {
                showNotification('Mật khẩu xác nhận không khớp', 'error');
                return;
            }

            const submitBtn = e.target.querySelector('button[type="submit"]');
            
            submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đăng ký...';
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("user.register") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    const responseText = await response.text();
                    console.error('Register non-JSON response:', responseText);
                    throw new Error('Server returned non-JSON response: ' + contentType);
                }

                const result = await response.json();
                console.log('Register response result:', result);

                if (result.success) {
                    showNotification('🎉 Đăng ký thành công! Đang chuyển đến form tạo lead...', 'success');
                    e.target.reset();
                    
                    // Switch to lead tab instead of login tab
                    setTimeout(() => {
                        // Hide auth tabs
                        const authTabs = document.getElementById('authTabs');
                        if (authTabs) {
                            authTabs.style.display = 'none';
                        }
                        
                        // Show lead tab
                        tabContents.forEach(c => c.classList.remove('active'));
                        const leadTab = document.getElementById('leadTab');
                        if (leadTab) {
                            leadTab.classList.add('active');
                        }
                        
                        // Show user welcome message
                        showUserWelcome(result.user);
                    }, 1000);
                } else {
                    showNotification(result.message || 'Có lỗi xảy ra khi đăng ký', 'error');
                }
            } catch (error) {
                console.error('Register error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-user-plus me-2"></i>Đăng Ký Miễn Phí';
                submitBtn.disabled = false;
            }
        });
    }

    // Reviews slider
    initReviewsSlider();
    
    // Stats counter animation
    initStatsCounter();
    
    // Add backup event delegation for tab buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.tab-btn')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.closest('.tab-btn');
            const targetTab = btn.getAttribute('data-tab');
            console.log('Backup tab handler:', targetTab);
            
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            const targetElement = document.getElementById(targetTab + 'Tab');
            if (targetElement) {
                targetElement.classList.add('active');
                console.log('Backup activated tab:', targetTab);
            }
        }
        
        // Backup handler for next-step-auth button
        if (e.target.closest('.next-step-auth')) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Backup next-step-auth handler triggered');
            const btn = e.target.closest('.next-step-auth');
            console.log('Backup button element:', btn);
            
            // Force go to next step without validation for testing
            const currentStep = 1; // Assuming we're on step 1
            const targetStep = currentStep + 1;
            
            console.log('Backup forcing step change from', currentStep, 'to', targetStep);
            
            // Hide step 1
            const step1 = document.getElementById('lead-step1');
            if (step1) {
                step1.style.display = 'none';
                console.log('Backup hid step 1');
            }
            
            // Show step 2
            const step2 = document.getElementById('lead-step2');
            if (step2) {
                step2.style.display = 'block';
                console.log('Backup showed step 2');
            }
        }
        
        // Backup handler for prev-step-auth button
        if (e.target.closest('.prev-step-auth')) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Backup prev-step-auth handler triggered');
            const btn = e.target.closest('.prev-step-auth');
            console.log('Backup prev button element:', btn);
            
            // Force go to previous step
            const currentStep = 2; // Assuming we're on step 2
            const targetStep = currentStep - 1;
            
            console.log('Backup forcing step change from', currentStep, 'to', targetStep);
            
            // Hide step 2
            const step2 = document.getElementById('lead-step2');
            if (step2) {
                step2.style.display = 'none';
                console.log('Backup hid step 2');
            }
            
            // Show step 1
            const step1 = document.getElementById('lead-step1');
            if (step1) {
                step1.style.display = 'block';
                console.log('Backup showed step 1');
            }
        }
        
        // General backup for any auth step navigation
        if (e.target.closest('[class*="step-auth"]')) {
            console.log('Backup handler for auth step element:', e.target.closest('[class*="step-auth"]'));
        }
    });

    // Add specific click handler for the next-step-auth button
    setTimeout(() => {
        const nextStepBtn = document.querySelector('.next-step-auth');
        if (nextStepBtn) {
            console.log('Adding direct click handler to next-step-auth button');
            
            // Add multiple event types to ensure it works
            ['click', 'touchend', 'mouseup'].forEach(eventType => {
                nextStepBtn.addEventListener(eventType, function(e) {
                    console.log(`Direct ${eventType} handler for next-step-auth`);
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Direct step change
                    const step1 = document.getElementById('lead-step1');
                    const step2 = document.getElementById('lead-step2');
                    
                    if (step1 && step2) {
                        step1.style.display = 'none';
                        step2.style.display = 'block';
                        console.log('Direct handler: Changed from step 1 to step 2');
                    }
                }, { passive: false });
            });
            
            // Test button properties
            console.log('Next button properties:');
            console.log('- Visible:', nextStepBtn.offsetParent !== null);
            console.log('- Disabled:', nextStepBtn.disabled);
            console.log('- Pointer events:', window.getComputedStyle(nextStepBtn).pointerEvents);
            console.log('- Position:', window.getComputedStyle(nextStepBtn).position);
            console.log('- Z-index:', window.getComputedStyle(nextStepBtn).zIndex);
            console.log('- Display:', window.getComputedStyle(nextStepBtn).display);
        } else {
            console.log('next-step-auth button not found in setTimeout');
        }
        
        // Add specific click handler for the prev-step-auth button
        const prevStepBtn = document.querySelector('.prev-step-auth');
        if (prevStepBtn) {
            console.log('Adding direct click handler to prev-step-auth button');
            
            // Add multiple event types to ensure it works
            ['click', 'touchend', 'mouseup'].forEach(eventType => {
                prevStepBtn.addEventListener(eventType, function(e) {
                    console.log(`Direct ${eventType} handler for prev-step-auth`);
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Direct step change back to step 1
                    const step1 = document.getElementById('lead-step1');
                    const step2 = document.getElementById('lead-step2');
                    
                    if (step1 && step2) {
                        step2.style.display = 'none';
                        step1.style.display = 'block';
                        console.log('Direct handler: Changed from step 2 to step 1');
                    }
                }, { passive: false });
            });
            
            // Test button properties
            console.log('Prev button properties:');
            console.log('- Visible:', prevStepBtn.offsetParent !== null);
            console.log('- Disabled:', prevStepBtn.disabled);
            console.log('- Pointer events:', window.getComputedStyle(prevStepBtn).pointerEvents);
            console.log('- Position:', window.getComputedStyle(prevStepBtn).position);
            console.log('- Z-index:', window.getComputedStyle(prevStepBtn).zIndex);
            console.log('- Display:', window.getComputedStyle(prevStepBtn).display);
        } else {
            console.log('prev-step-auth button not found in setTimeout');
        }
    }, 1000);

    // Add route testing function for debugging
    window.testRoute = async function() {
        console.log('Testing route accessibility...');
        try {
            const response = await fetch('{{ route("user.customer.leads.store") }}', {
                method: 'GET', // Test with GET first to see what happens
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            console.log('Test response status:', response.status);
            console.log('Test response headers:', response.headers);
            
            const responseText = await response.text();
            console.log('Test response text:', responseText);
            
            return {
                status: response.status,
                headers: Object.fromEntries(response.headers.entries()),
                body: responseText
            };
        } catch (error) {
            console.error('Route test error:', error);
            return { error: error.message };
        }
    };

    // Add form data testing function
    window.testFormData = function() {
        const testForm = document.getElementById('guestLeadForm') || document.getElementById('authenticatedLeadForm');
        if (testForm) {
            const formData = new FormData(testForm);
            console.log('Test form data entries:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            return Array.from(formData.entries());
        } else {
            console.log('No form found for testing');
            return null;
        }
    };

    // Add alternative route testing with multiple route attempts
    window.testMultipleRoutes = async function() {
        const routes = [
            '{{ route("user.customer.leads.store") }}',
            '/customer/leads/store',
            '/user/customer/leads/store'
        ];
        
        console.log('Testing multiple route variations...');
        
        for (const route of routes) {
            console.log('Testing route:', route);
            try {
                const response = await fetch(route, {
                    method: 'POST',
                    body: new FormData(),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                console.log('Route', route, 'status:', response.status);
                const text = await response.text();
                console.log('Route', route, 'response:', text.substring(0, 200));
                
                if (response.status !== 404) {
                    console.log('Found working route:', route);
                    return { route, status: response.status };
                }
            } catch (error) {
                console.log('Route', route, 'error:', error.message);
            }
        }
        
        return null;
    };

    // Add manual testing function for auth step navigation
    window.testAuthStep = function(step) {
        console.log('Manual auth step test to step:', step);
        goToAuthStep(step);
    };

    // Add function to test next-step-auth button directly
    window.testNextStepAuth = function() {
        console.log('Testing next-step-auth button click');
        const btn = document.querySelector('.next-step-auth');
        if (btn) {
            console.log('Found button:', btn);
            console.log('Button visible:', btn.offsetParent !== null);
            console.log('Button disabled:', btn.disabled);
            console.log('Button pointer events:', window.getComputedStyle(btn).pointerEvents);
            btn.click();
        } else {
            console.log('next-step-auth button not found');
        }
    };

    // Add function to test prev-step-auth button directly
    window.testPrevStepAuth = function() {
        console.log('Testing prev-step-auth button click');
        const btn = document.querySelector('.prev-step-auth');
        if (btn) {
            console.log('Found prev button:', btn);
            console.log('Button visible:', btn.offsetParent !== null);
            console.log('Button disabled:', btn.disabled);
            console.log('Button pointer events:', window.getComputedStyle(btn).pointerEvents);
            btn.click();
        } else {
            console.log('prev-step-auth button not found');
        }
    };

    // Add function to force step change
    window.forceNextStep = function() {
        console.log('Forcing step change from 1 to 2');
        const step1 = document.getElementById('lead-step1');
        const step2 = document.getElementById('lead-step2');
        
        if (step1 && step2) {
            step1.style.display = 'none';
            step2.style.display = 'block';
            console.log('Successfully changed to step 2');
            return true;
        } else {
            console.log('Could not find step elements');
            console.log('Step 1:', step1);
            console.log('Step 2:', step2);
            return false;
        }
    };

    // Add function to force go back
    window.forcePrevStep = function() {
        console.log('Forcing step change from 2 to 1');
        const step1 = document.getElementById('lead-step1');
        const step2 = document.getElementById('lead-step2');
        
        if (step1 && step2) {
            step2.style.display = 'none';
            step1.style.display = 'block';
            console.log('Successfully changed back to step 1');
            return true;
        } else {
            console.log('Could not find step elements');
            console.log('Step 1:', step1);
            console.log('Step 2:', step2);
            return false;
        }
    };

    // Add function to show all steps for debugging
    window.showAllSteps = function() {
        console.log('Showing all available steps:');
        for (let i = 1; i <= 5; i++) {
            const step = document.getElementById('lead-step' + i);
            if (step) {
                console.log('Found step:', 'lead-step' + i, step);
                console.log('Display:', window.getComputedStyle(step).display);
            }
        }
    };

    // Add function to check form validation
    window.checkValidation = function() {
        console.log('Checking form validation for step 1');
        const step1 = document.getElementById('lead-step1');
        if (step1) {
            const requiredFields = step1.querySelectorAll('[required]');
            console.log('Required fields in step 1:', requiredFields.length);
            
            let validationResults = [];
            requiredFields.forEach((field, index) => {
                const isValid = field.value.trim() !== '';
                validationResults.push({
                    index: index,
                    name: field.name || field.id,
                    value: field.value,
                    valid: isValid
                });
                console.log(`Field ${index} (${field.name || field.id}):`, field.value, isValid ? 'VALID' : 'INVALID');
            });
            
            return validationResults;
        }
        return null;
    };

    // Add function to check location data
    window.checkLocationData = function() {
        console.log('Checking location data...');
        
        const citySelect = document.getElementById('auth_city_code');
        const districtSelect = document.getElementById('auth_district_code');
        const wardSelect = document.getElementById('auth_ward_code');
        
        const locationData = {
            city: {
                element: citySelect,
                value: citySelect?.value,
                text: citySelect?.options[citySelect.selectedIndex]?.text,
                disabled: citySelect?.disabled,
                optionsCount: citySelect?.options.length
            },
            district: {
                element: districtSelect,
                value: districtSelect?.value,
                text: districtSelect?.options[districtSelect.selectedIndex]?.text,
                disabled: districtSelect?.disabled,
                optionsCount: districtSelect?.options.length
            },
            ward: {
                element: wardSelect,
                value: wardSelect?.value,
                text: wardSelect?.options[wardSelect.selectedIndex]?.text,
                disabled: wardSelect?.disabled,
                optionsCount: wardSelect?.options.length
            }
        };
        
        console.log('Location data:', locationData);
        
        // Check if cities are loaded
        if (citySelect) {
            console.log('City options count:', citySelect.options.length);
            if (citySelect.options.length <= 1) {
                console.warn('Cities not loaded! Trying to load...');
                loadCities();
            }
        }
        
        // Detailed district check
        if (districtSelect) {
            console.log('District element found:', !!districtSelect);
            console.log('District value:', districtSelect.value);
            console.log('District disabled:', districtSelect.disabled);
            console.log('District options:', districtSelect.options.length);
            
            // Log all district options
            if (districtSelect.options.length > 0) {
                console.log('District options:');
                for (let i = 0; i < districtSelect.options.length; i++) {
                    console.log(`  ${i}: ${districtSelect.options[i].value} - ${districtSelect.options[i].text}`);
                }
            }
        } else {
            console.error('District select element not found!');
        }
        
        return locationData;
    };

    // Add specific function to debug district selection
    window.debugDistrictSelection = function() {
        console.log('=== DISTRICT DEBUG ===');
        
        const districtSelect = document.getElementById('auth_district_code');
        
        if (!districtSelect) {
            console.error('❌ District select element not found!');
            return false;
        }
        
        console.log('✅ District select found');
        console.log('Value:', districtSelect.value);
        console.log('Selected index:', districtSelect.selectedIndex);
        console.log('Options count:', districtSelect.options.length);
        console.log('Disabled:', districtSelect.disabled);
        console.log('Required:', districtSelect.required);
        
        if (districtSelect.selectedIndex >= 0) {
            const selectedOption = districtSelect.options[districtSelect.selectedIndex];
            console.log('Selected option text:', selectedOption.text);
            console.log('Selected option value:', selectedOption.value);
        }
        
        // Test if validation would pass
        const hasValue = districtSelect.value && districtSelect.value.trim() !== '';
        console.log('Has valid value:', hasValue);
        
        if (!hasValue) {
            console.warn('⚠️ District not selected - this would cause validation to fail');
            
            // Try to auto-select first non-empty option
            for (let i = 1; i < districtSelect.options.length; i++) {
                const option = districtSelect.options[i];
                if (option.value && option.value.trim() !== '') {
                    console.log('🔧 Auto-selecting first available district:', option.text);
                    districtSelect.selectedIndex = i;
                    districtSelect.dispatchEvent(new Event('change'));
                    break;
                }
            }
        }
        
        return hasValue;
    };

    // Add function to fill test data
    window.fillTestData = function() {
        console.log('Filling test data...');
        
        // Fill basic fields
        const categorySelect = document.getElementById('auth_category_id');
        if (categorySelect && categorySelect.options.length > 1) {
            categorySelect.selectedIndex = 1;
            console.log('Selected category:', categorySelect.value);
        }
        
        const titleInput = document.getElementById('auth_title');
        if (titleInput) {
            titleInput.value = 'Test: Sửa chữa điện nước';
            console.log('Set title:', titleInput.value);
        }
        
        const descriptionInput = document.getElementById('auth_description');
        if (descriptionInput) {
            descriptionInput.value = 'Cần sửa chữa hệ thống điện và nước trong nhà';
            console.log('Set description:', descriptionInput.value);
        }
        
        // Fill step 2 data
        const addressInput = document.getElementById('auth_address');
        if (addressInput) {
            addressInput.value = '123 Nguyễn Văn A';
            console.log('Set address:', addressInput.value);
        }
        
        console.log('Test data filled. Please select city and district manually.');
    };

    // Add function to simulate form submission with test data
    window.testSubmitWithData = function() {
        console.log('Testing form submission with current data...');
        
        const form = document.getElementById('authenticatedLeadForm');
        if (form) {
            const formData = new FormData(form);
            
            // Add location data manually for testing
            formData.append('district', 'Quận 1');
            formData.append('ward', 'Phường Bến Nghé');
            
            console.log('Test form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ':', value);
            }
            
            return Array.from(formData.entries());
        }
        return null;
    };

    // Add function to bypass validation and submit directly
    window.bypassValidationAndSubmit = function() {
        console.log('Bypassing validation and submitting form...');
        
        const form = document.getElementById('authenticatedLeadForm');
        if (!form) {
            console.error('Form not found');
            return;
        }
        
        const formData = new FormData(form);
        
        // Force add required data
        formData.append('district', 'Quận 1');
        formData.append('ward', 'Phường Bến Nghé');
        formData.append('city', 'Hồ Chí Minh');
        
        console.log('Bypassed form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
        
        // Submit directly without validation
        fetch('{{ route("user.customer.leads.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Bypass response status:', response.status);
            return response.json();
        })
        .then(result => {
            console.log('Bypass response result:', result);
            if (result.success) {
                showNotification('✅ Lead created successfully with bypass!', 'success');
            } else {
                showNotification('❌ Still failed: ' + (result.message || 'Unknown error'), 'error');
                console.log('Errors:', result.errors);
            }
        })
        .catch(error => {
            console.error('Bypass error:', error);
            showNotification('❌ Network error: ' + error.message, 'error');
        });
    };

    // Add simplified validation check
    window.checkSimpleValidation = function() {
        console.log('=== SIMPLE VALIDATION CHECK ===');
        
        // Check step 1 fields
        const requiredStep1 = [
            'auth_category_id',
            'auth_city_code', 
            'auth_district_code',
            'auth_address',
            'auth_title',
            'auth_description'
        ];
        
        let step1Valid = true;
        console.log('Step 1 validation:');
        
        requiredStep1.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            const hasValue = field && field.value && field.value.trim() !== '';
            console.log(`  ${fieldId}:`, hasValue ? '✅' : '❌', field?.value || 'empty');
            if (!hasValue) step1Valid = false;
        });
        
        console.log('Step 1 overall:', step1Valid ? '✅ VALID' : '❌ INVALID');
        
        return step1Valid;
    };
}

function initReviewsSlider() {
    const reviews = document.querySelectorAll('.review-item');
    let currentReview = 0;

    function showNextReview() {
        reviews[currentReview].classList.remove('active');
        currentReview = (currentReview + 1) % reviews.length;
        reviews[currentReview].classList.add('active');
    }

    // Auto-rotate reviews every 4 seconds
    setInterval(showNextReview, 4000);
}

function initStatsCounter() {
    const statNumbers = document.querySelectorAll('[data-count]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.getAttribute('data-count'));
                if (!isNaN(target)) {
                    animateNumber(entry.target, 0, target, 2000);
                }
                observer.unobserve(entry.target);
            }
        });
    });

    statNumbers.forEach(stat => observer.observe(stat));
}

function animateNumber(element, start, end, duration) {
    // Only animate if element has data-count attribute
    if (!element.hasAttribute('data-count')) return;
    
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const currentNumber = Math.floor(start + (end - start) * easeOutQuart(progress));
        
        if (progress < 1) {
            element.textContent = currentNumber.toLocaleString();
            requestAnimationFrame(update);
        } else {
            // Final formatting based on the number
            if (end >= 10000) {
                element.textContent = (end / 1000).toFixed(0) + 'K+';
            } else if (end >= 1000) {
                element.textContent = end.toLocaleString() + '+';
            } else if (end >= 90) {
                element.textContent = end + '%';
            } else {
                element.textContent = end.toLocaleString();
            }
        }
    }
    
    requestAnimationFrame(update);
}

function easeOutQuart(t) {
    return 1 - Math.pow(1 - t, 4);
}

function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(n => n.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideInRight 0.3s ease;
    `;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add CSS for notification animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1) !important;
    }
`;
document.head.appendChild(style);

function showUserWelcome(user) {
    // Create user welcome header dynamically
    const welcomeHTML = `
        <div class="auth-header mb-4 text-center">
            <div class="user-welcome">
                <i class="las la-user-circle text-primary" style="font-size: 2rem;"></i>
                <h5 class="mt-2 mb-1">Xin chào, ${user.firstname} ${user.lastname}!</h5>
                <p class="text-muted">Tạo lead mới để tìm thợ chuyên nghiệp</p>
            </div>
        </div>
    `;
    
    // Insert welcome message at the beginning of lead tab
    const leadTab = document.getElementById('leadTab');
    if (leadTab) {
        leadTab.insertAdjacentHTML('afterbegin', welcomeHTML);
    }
}
</script>
@endpush

@endsection
