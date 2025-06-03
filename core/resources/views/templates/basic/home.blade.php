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
<section id="quick-lead-form" class="py-5 bg-light">
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
                        <!-- Login/Register Tabs -->
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

                        <!-- Guest Form (Default) -->
                        <div class="tab-content active" id="guestTab">
                            <form class="lead-form" id="guestLeadForm">
                                @csrf
                                <div class="form-step" id="step1">
                                    <h5 class="step-title">📋 Thông tin công việc</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Loại công việc *</label>
                                            <select name="category_id" class="form-select modern-select" required>
                                                <option value="">Chọn loại công việc</option>
                                                @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Khu vực *</label>
                                            <select name="district" class="form-select modern-select" required>
                                                <option value="">Chọn quận/huyện</option>
                                                <option value="Quận 1">Quận 1</option>
                                                <option value="Quận 2">Quận 2</option>
                                                <option value="Quận 3">Quận 3</option>
                                                <option value="Quận 4">Quận 4</option>
                                                <option value="Quận 5">Quận 5</option>
                                                <option value="Quận 6">Quận 6</option>
                                                <option value="Quận 7">Quận 7</option>
                                                <option value="Quận 8">Quận 8</option>
                                                <option value="Quận 9">Quận 9</option>
                                                <option value="Quận 10">Quận 10</option>
                                                <option value="Quận 11">Quận 11</option>
                                                <option value="Quận 12">Quận 12</option>
                                                <option value="Quận Bình Thạnh">Quận Bình Thạnh</option>
                                                <option value="Quận Gò Vấp">Quận Gò Vấp</option>
                                                <option value="Quận Phú Nhuận">Quận Phú Nhuận</option>
                                                <option value="Quận Tân Bình">Quận Tân Bình</option>
                                                <option value="Quận Tân Phú">Quận Tân Phú</option>
                                                <option value="Quận Thủ Đức">Quận Thủ Đức</option>
                                                <option value="Huyện Bình Chánh">Huyện Bình Chánh</option>
                                                <option value="Huyện Cần Giờ">Huyện Cần Giờ</option>
                                                <option value="Huyện Củ Chi">Huyện Củ Chi</option>
                                                <option value="Huyện Hóc Môn">Huyện Hóc Môn</option>
                                                <option value="Huyện Nhà Bè">Huyện Nhà Bè</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Tiêu đề công việc *</label>
                                        <input type="text" name="title" class="form-control modern-input" 
                                               placeholder="VD: Sửa chữa điện nước tại nhà" required>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Mô tả chi tiết *</label>
                                        <textarea name="description" class="form-control modern-textarea" rows="3" 
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
                                            <input type="text" name="fullname" class="form-control modern-input" 
                                                   placeholder="Nhập họ tên" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Số điện thoại *</label>
                                            <input type="tel" name="mobile" class="form-control modern-input" 
                                                   placeholder="0123456789" required>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control modern-input" 
                                               placeholder="email@domain.com">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Địa chỉ cụ thể *</label>
                                        <input type="text" name="address" class="form-control modern-input" 
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
                                            <input type="number" name="budget_min" class="form-control modern-input" 
                                                   placeholder="200,000">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Ngân sách đến</label>
                                            <input type="number" name="budget_max" class="form-control modern-input" 
                                                   placeholder="500,000">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Mức độ ưu tiên</label>
                                            <select name="urgency" class="form-select modern-select">
                                                <option value="medium">Bình thường</option>
                                                <option value="high">Khẩn cấp</option>
                                                <option value="low">Không gấp</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Cần hoàn thành trước</label>
                                            <input type="date" name="needed_by" class="form-control modern-input" 
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

                        <!-- Login Tab -->
                        <div class="tab-content" id="loginTab">
                            <form class="auth-form" id="loginForm">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email hoặc Số điện thoại</label>
                                    <input type="text" name="username" class="form-control modern-input" 
                                           placeholder="email@domain.com hoặc 0123456789" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" name="password" class="form-control modern-input" 
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
                                        <input type="text" name="firstname" class="form-control modern-input" 
                                               placeholder="Họ" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tên *</label>
                                        <input type="text" name="lastname" class="form-control modern-input" 
                                               placeholder="Tên" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control modern-input" 
                                           placeholder="email@domain.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="tel" name="mobile" class="form-control modern-input" 
                                           placeholder="0123456789" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mật khẩu *</label>
                                        <input type="password" name="password" class="form-control modern-input" 
                                               placeholder="Tối thiểu 6 ký tự" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Xác nhận mật khẩu *</label>
                                        <input type="password" name="password_confirmation" class="form-control modern-input" 
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
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.form-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
}

.form-subtitle {
    color: #6c757d;
    font-size: 1rem;
    font-weight: 400;
}

.modern-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
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
}

.tab-btn.active {
    background: white;
    color: #0b92d4;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.tab-btn:hover {
    color: #0b92d4;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@media (max-width: 768px) {
    .tab-btn {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .tab-btn i {
        font-size: 1rem;
    }
}

/* Form Steps */
.form-step {
    animation: slideIn 0.4s ease;
}

.step-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
    font-size: 1.125rem;
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
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    font-family: 'Inter', sans-serif;
}

.modern-input:focus,
.modern-select:focus,
.modern-textarea:focus {
    border-color: #0b92d4;
    box-shadow: 0 0 0 0.2rem rgba(11, 146, 212, 0.1);
    background: white;
}

.form-label {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.terms-checkbox {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.terms-checkbox .form-check-label {
    font-size: 0.9rem;
    color: #6c757d;
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
});

function initQuickLeadForm() {
    // Tab switching
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            document.getElementById(targetTab + 'Tab').classList.add('active');
        });
    });

    // Form steps navigation
    let currentStep = 1;
    const totalSteps = 3;

    // Next step buttons
    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            if (validateCurrentStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });
    });

    // Previous step buttons
    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            goToStep(currentStep - 1);
        });
    });

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        
        // Hide current step
        document.getElementById('step' + currentStep).style.display = 'none';
        
        // Show target step
        currentStep = step;
        document.getElementById('step' + currentStep).style.display = 'block';
    }

    function validateCurrentStep(step) {
        const currentStepEl = document.getElementById('step' + step);
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

    // Guest Lead Form Submission
    document.getElementById('guestLeadForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (!validateCurrentStep(3)) return;

        const formData = new FormData(e.target);
        const submitBtn = e.target.querySelector('.submit-lead');
        
        // Show loading state
        submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("user.customer.leads.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification('🎉 Lead đã được tạo thành công! Bạn sẽ nhận được liên hệ sớm.', 'success');
                e.target.reset();
                goToStep(1);
            } else {
                showNotification(result.message || 'Có lỗi xảy ra, vui lòng thử lại', 'error');
            }
        } catch (error) {
            showNotification('Có lỗi kết nối, vui lòng thử lại', 'error');
        } finally {
            submitBtn.innerHTML = '<i class="las la-rocket me-2"></i>Tạo Lead & Tự Động Đăng Ký';
            submitBtn.disabled = false;
        }
    });

    // Login Form
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const submitBtn = e.target.querySelector('button[type="submit"]');
        
        submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đăng nhập...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("user.login") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification('✅ Đăng nhập thành công!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(result.message || 'Thông tin đăng nhập không chính xác', 'error');
            }
        } catch (error) {
            showNotification('Có lỗi kết nối, vui lòng thử lại', 'error');
        } finally {
            submitBtn.innerHTML = '<i class="las la-sign-in-alt me-2"></i>Đăng Nhập';
            submitBtn.disabled = false;
        }
    });

    // Register Form
    document.getElementById('registerForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification('🎉 Đăng ký thành công! Vui lòng kiểm tra email để xác thực.', 'success');
                e.target.reset();
                // Switch to login tab
                document.querySelector('[data-tab="login"]').click();
            } else {
                showNotification(result.message || 'Có lỗi xảy ra khi đăng ký', 'error');
            }
        } catch (error) {
            showNotification('Có lỗi kết nối, vui lòng thử lại', 'error');
        } finally {
            submitBtn.innerHTML = '<i class="las la-user-plus me-2"></i>Đăng Ký Miễn Phí';
            submitBtn.disabled = false;
        }
    });

    // Reviews slider
    initReviewsSlider();
    
    // Stats counter animation
    initStatsCounter();
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
</script>
@endpush

@endsection
