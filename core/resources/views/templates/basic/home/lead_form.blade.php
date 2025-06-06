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
                            @php
                                // Get testimonials from frontend content management
                                $testimonials = getContent('testimonial.element', false, null, true);
                            @endphp
                            
                            @if(count($testimonials) > 0)
                                @foreach($testimonials as $index => $testimonial)
                                    <div class="review-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="review-stars">
                                            ⭐⭐⭐⭐⭐
                                        </div>
                                        <p class="review-text">"{{ __(@$testimonial->data_values->quote) }}"</p>
                                        <div class="reviewer">
                                            <div class="reviewer-info">
                                                @if(@$testimonial->data_values->image)
                                                    <div class="reviewer-avatar">
                                                        <img src="{{ frontendImage('testimonial', @$testimonial->data_values->image, '60x60') }}" 
                                                             alt="{{ __(@$testimonial->data_values->name) }}" 
                                                             class="rounded-circle me-2" 
                                                             width="40" height="40">
                                                    </div>
                                                @endif
                                                <div class="reviewer-details">
                                                    <strong>{{ __(@$testimonial->data_values->name) }}</strong>
                                                    @if(@$testimonial->data_values->address)
                                                        <br><small class="text-muted">{{ __(@$testimonial->data_values->address) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                <!-- Navigation Dots for Slider -->
                                @if(count($testimonials) > 1)
                                    <div class="review-navigation text-center mt-3">
                                        @foreach($testimonials as $index => $testimonial)
                                            <button class="review-dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <!-- Fallback static reviews if no testimonials -->
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
                            @endif
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

@push('style')
<link rel="stylesheet" href="{{ asset('public/css/home.css') }}">
@endpush

@push('script')
<script src="{{ asset('public/js/home.js') }}"></script>
@endpush