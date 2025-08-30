@extends($activeTemplate . 'layouts.frontend')
@section('content')

<!-- Hero Section -->
<section class="contractor-hero">
    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <i class="las la-tools"></i>
                        <span>Kiếm tiền từ kỹ năng</span>
                    </div>
                    <h1 class="hero-title">
                        Trở thành <span class="gradient-text">Thợ chuyên nghiệp</span><br>
                        Kiếm tiền từ những gì bạn giỏi nhất
                    </h1>
                    <p class="hero-description">
                        Tham gia mạng lưới thợ chuyên nghiệp hàng đầu Việt Nam. 
                        Kết nối với hàng ngàn khách hàng, tự do về thời gian, thu nhập hấp dẫn.
                    </p>
                    
                    <div class="hero-stats">
                        <div class="stat-item">
                            <h3>{{ number_format($totalJobs) }}+</h3>
                            <p>Công việc đã hoàn thành</p>
                        </div>
                        <div class="stat-item">
                            <h3>{{ number_format($activeContractors) }}+</h3>
                            <p>Thợ đang hoạt động</p>
                        </div>
                        <div class="stat-item">
                            <h3>{{ number_format($averageEarning/1000000, 1) }}M+</h3>
                            <p>Thu nhập TB/tháng</p>
                        </div>
                    </div>

                    <div class="hero-actions">
                        <a href="#registration-form" class="btn btn-primary btn-lg scroll-to-form me-3" onclick="scrollToForm(event); return false;">
                            <i class="las la-rocket me-2"></i>Đăng ký ngay
                        </a>
                        <a href="https://zalo.me/{{ gs('zalo_phone') ?? '0972585990' }}" 
                           class="btn btn-success btn-lg zalo-chat-btn me-3"
                           target="_blank">
                            <i class="las la-comments me-2"></i>Hỗ trợ đăng ký bằng chat Zalo
                        </a>
                        <!-- <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg" onclick="goToHome(event); return false;">
                            <i class="las la-play-circle me-2"></i>Tìm hiểu thêm
                        </a> -->
                        <!-- Debug button -->
                        <!-- <button type="button" class="btn btn-warning btn-sm" onclick="alert('Button works!'); console.log('Debug button clicked');">
                            Test Click
                        </button> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <div class="visual-card earnings">
                        <h4>💰 Thu nhập hàng tháng</h4>
                        <div class="earning-chart">
                            <div class="bar" style="height: 60%"></div>
                            <div class="bar" style="height: 80%"></div>
                            <div class="bar" style="height: 100%"></div>
                            <div class="bar" style="height: 75%"></div>
                        </div>
                        <p>+{{ number_format($averageEarning) }} VNĐ/tháng</p>
                    </div>
                    <div class="visual-card jobs">
                        <h4>📋 Công việc mới</h4>
                        <div class="job-notifications">
                            <div class="notification">Sửa điện - 2M VNĐ</div>
                            <div class="notification">Thi công nhà - 15M VNĐ</div>
                            <div class="notification">Lắp đặt - 5M VNĐ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section id="why-choose-us" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Tại sao nên chọn Doitay.vn?</h2>
            <p class="section-subtitle">Những lợi ích vượt trội khi trở thành thợ chuyên nghiệp</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="las la-users" style="color: #48bbe2;"></i>
                    </div>
                    <h4>Khách hàng chất lượng</h4>
                    <p>Kết nối với hàng ngàn khách hàng đã được xác minh, có nhu cầu thực sự và ngân sách rõ ràng.</p>
                    <ul>
                        <li>✓ Khách hàng đã xác minh thông tin</li>
                        <li>✓ Ngân sách minh bạch từ đầu</li>
                        <li>✓ Đánh giá uy tín công khai</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="las la-clock" style="color: #48bbe2;"></i>
                    </div>
                    <h4>Tự do thời gian</h4>
                    <p>Làm việc theo lịch trình của bạn, chọn công việc phù hợp với khả năng và thời gian rảnh.</p>
                    <ul>
                        <li>✓ Tự chọn công việc phù hợp</li>
                        <li>✓ Linh hoạt về thời gian</li>
                        <li>✓ Cân bằng công việc - cuộc sống</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="las la-dollar-sign" style="color: #ffa500;"></i>
                    </div>
                    <h4>Thu nhập cao</h4>
                    <p>Mức giá cạnh tranh, thanh toán nhanh chóng và cơ hội kiếm thu nhập cao từ kỹ năng của bạn.</p>
                    <ul>
                        <li>✓ Mức giá thị trường tốt nhất</li>
                        <li>✓ Thanh toán an toàn & nhanh</li>
                        <li>✓ Thưởng cho thợ xuất sắc</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comparison Infographic -->
<section class="comparison-section py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h3 class="text-center mb-5">Thợ tìm khách hàng: Truyền thống có khó? Doitay.vn dễ hơn thế nào?</h3>
                
                <div class="comparison-container">
                    <!-- Cách truyền thống -->
                    <div class="comparison-card traditional">
                        <div class="card-header">
                            <div class="icon-wrapper">
                                <i class="las la-times-circle"></i>
                            </div>
                            <h4>Cách truyền thống</h4>
                            <p class="card-subtitle">Khó khăn & Rủi ro cao</p>
                        </div>
                        <div class="card-body">
                            <ul class="feature-list">
                                <li class="negative">
                                    <i class="las la-times"></i>
                                    <span><strong>Tìm khách hàng khó khăn</strong><br><small>Chỉ 20-30% thời gian có việc và không được nhận đánh giá</small></span>
                                </li>
                                <li class="negative">
                                    <i class="las la-times"></i>
                                    <span><strong>Khó quyết định được giá</strong><br><small>Dễ bị chủ thầu hoặc công ty ép giá 15-25%</small></span>
                                </li>
                                <li class="negative">
                                    <i class="las la-times"></i>
                                    <span><strong>Khó xây dựng uy tín</strong><br><small>Không nhận được đánh giá, chủ yếu uy tín trong một nhóm nhỏ người quen</small></span>
                                </li>
                                <li class="negative">
                                    <i class="las la-times"></i>
                                    <span><strong>Không chủ động thời gian</strong><br><small>Thời gian phụ thuộc vào chủ thầu, hoặc công ty đưa ra, không tự chủ động thời gian với khách hàng</small></span>
                                </li>
                                <li class="negative">
                                    <i class="las la-times"></i>
                                    <span><strong>Thu nhập thấp</strong><br><small>Chỉ 8-12 triệu/tháng</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- VS -->
                    <div class="vs-badge">
                        <span>VS</span>
                    </div>

                    <!-- Doitay.vn -->
                    <div class="comparison-card doitay">
                        <div class="card-header">
                            <div class="icon-wrapper">
                                <i class="las la-check-circle"></i>
                            </div>
                            <h4>Doitay.vn</h4>
                            <p class="card-subtitle">Dễ dàng & Đảm bảo</p>
                        </div>
                        <div class="card-body">
                            <ul class="feature-list">
                                <li class="positive">
                                    <i class="las la-check"></i>
                                    <span><strong>Khách hàng chất lượng</strong><br><small>90% thời gian có việc ổn định nếu được đánh giá tốt</small></span>
                                </li>
                                <li class="positive">
                                    <i class="las la-check"></i>
                                    <span><strong>Giá cả do mình quyết định</strong><br><small>Bạn được quyền tự thương lượng về giá với khách hàng</small></span>
                                </li>
                                <li class="positive">
                                    <i class="las la-check"></i>
                                    <span><strong>Xây dựng uy tín</strong><br><small>Tạo trang riêng cho bạn, uy tín lan tỏa không giới hạn số lượng khách hàng</small></span>
                                </li>
                                <li class="positive">
                                    <i class="las la-check"></i>
                                    <span><strong>Chủ động thời gian</strong><br><small>Bạn được chọn các công việc phù hợp với thời gian rảnh của bạn</small></span>
                                </li>
                                <li class="positive">
                                    <i class="las la-check"></i>
                                    <span><strong>Thu nhập cao</strong><br><small>15-35 triệu/tháng (+30% so với truyền thống)</small></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Nút Zalo Chat sau infographic -->
                <div class="text-center mt-5">
                    <h4 class="mb-3">Bạn cần hỗ trợ thêm?</h4>
                    <p class="mb-4 text-muted">Chúng tôi sẽ hỗ trợ mở tài khoản và tạo trang cá nhân của bạn</p>
                    <a href="https://zalo.me/{{ gs('zalo_phone') ?? '0972585990' }}" 
                       class="btn btn-success btn-lg zalo-chat-btn"
                       target="_blank">
                        <i class="las la-comments"></i>
                        Zalo Chat - Chúng tôi sẽ hỗ trợ mở tài khoản và tạo trang cá nhân của bạn
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Cách thức hoạt động</h2>
            <p class="section-subtitle">4 bước đơn giản để bắt đầu kiếm tiền</p>
        </div>
        
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-card">
                    <div class="step-number">1</div>
                    <div class="step-icon">
                        <i class="las la-user-plus"></i>
                    </div>
                    <h5>Đăng ký tài khoản</h5>
                    <p>Tạo hồ sơ thợ chuyên nghiệp với thông tin kỹ năng và kinh nghiệm của bạn.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-card">
                    <div class="step-number">2</div>
                    <div class="step-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                    <h5>Xác minh hồ sơ</h5>
                    <p>Đội ngũ chúng tôi sẽ xem xét và phê duyệt hồ sơ trong vòng 24 giờ.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-card">
                    <div class="step-number">3</div>
                    <div class="step-icon">
                        <i class="las la-search"></i>
                    </div>
                    <h5>Tìm công việc</h5>
                    <p>Duyệt và chọn những công việc phù hợp với kỹ năng và thời gian của bạn.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="process-card">
                    <div class="step-number">4</div>
                    <div class="step-icon">
                        <i class="las la-handshake"></i>
                    </div>
                    <h5>Hoàn thành & Thanh toán</h5>
                    <p>Thực hiện công việc chất lượng và nhận thanh toán ngay sau khi hoàn thành.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Registration Form -->
<section id="registration-form" class="py-5 text-white" style="background: linear-gradient(135deg, #102f4b 0%, #1a4568 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    @if($isAuthenticated)
                        @if($hasCompany)
                            <h2 class="text-white">Bạn đã có hồ sơ thợ!</h2>
                            <p class="text-white-50">Hồ sơ thợ của bạn đang được xem xét hoặc đã được phê duyệt</p>
                        @else
                            <h2 class="text-white">Tạo hồ sơ thợ chuyên nghiệp</h2>
                            <p class="text-white-50">Hoàn thành thông tin để trở thành thợ cung cấp dịch vụ</p>
                        @endif
                    @else
                        <h2 class="text-white">Sẵn sàng bắt đầu?</h2>
                        <p class="text-white-50">Đăng ký ngay để tham gia mạng lưới thợ chuyên nghiệp</p>
                    @endif
                </div>
                
                @if($isAuthenticated)
                    @if($hasCompany)
                        <!-- User already has company -->
                        <div class="registration-container text-center">
                            <div class="alert alert-success bg-white text-dark rounded-4 p-4 mb-4">
                                <i class="las la-check-circle text-success" style="font-size: 3rem;"></i>
                                <h4 class="text-white mt-3 mb-2">Hồ sơ thợ đã tồn tại</h4>
                                <p class="text-white mb-3">Bạn đã có hồ sơ thợ với tên: <strong>{{ $existingCompany->name }}</strong></p>
                                <p class="text-white mb-4">
                                    Trạng thái: 
                                    @if($existingCompany->status == 1)
                                        <span class="badge bg-success">Đã phê duyệt</span>
                                    @elseif($existingCompany->status == 2)
                                        <span class="badge bg-warning">Đang chờ duyệt</span>
                                    @else
                                        <span class="badge bg-danger">Bị từ chối</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('user.company.index') }}" class="btn btn-light btn-lg">
                                    <i class="las la-eye me-2"></i>Xem hồ sơ
                                </a>
                                <a href="{{ route('user.company.edit', $existingCompany->id) }}" class="btn btn-outline-light btn-lg">
                                    <i class="las la-edit me-2"></i>Chỉnh sửa
                                </a>
                            </div>
                            
                            <!-- Nút Zalo hỗ trợ -->
                            <div class="mt-4">
                                <p class="text-white-50 mb-3">Cần hỗ trợ thêm?</p>
                                <a href="https://zalo.me/{{ gs('zalo_phone') ?? '0972585990' }}" 
                                   class="btn btn-success btn-lg zalo-chat-btn"
                                   target="_blank">
                                    <i class="las la-comments me-2"></i>
                                    Zalo Chat - Hỗ trợ thợ chuyên nghiệp
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- Authenticated user without company - Redirect to create company -->
                        <div class="registration-container text-center">
                            <div class="mb-4">
                                <i class="las la-tools" style="font-size: 4rem; color: #ffa500;"></i>
                                <h4 class="text-white mt-3 mb-3">Bước cuối cùng!</h4>
                                <p class="text-white-50 mb-4">Bạn đã đăng nhập thành công. Hãy tạo hồ sơ thợ để bắt đầu nhận việc.</p>
                                </div>
                                
                            <a href="{{ url('user-data-v2') }}" class="btn btn-light btn-lg">
                                <i class="las la-plus-circle me-2"></i>Tạo hồ sơ thợ ngay
                            </a>
                            
                            <!-- Nút Zalo hỗ trợ -->
                            <div class="mt-4">
                                <p class="text-white-50 mb-3">Cần hỗ trợ tạo hồ sơ?</p>
                                <a href="https://zalo.me/{{ gs('zalo_phone') ?? '0972585990' }}" 
                                   class="btn btn-success btn-lg zalo-chat-btn"
                                   target="_blank">
                                    <i class="las la-comments me-2"></i>
                                    Zalo Chat - Hỗ trợ tạo hồ sơ thợ
                                </a>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Not authenticated - Show simple action buttons -->
                    <div class="registration-container text-center">
                        <div class="mb-4">
                            <i class="las la-user-circle" style="font-size: 4rem; color: #ffa500;"></i>
                            <h4 class="text-white mt-3 mb-3">Bắt đầu hành trình của bạn</h4>
                            <p class="text-white-50 mb-4">Đăng ký hoặc đăng nhập để tạo hồ sơ thợ chuyên nghiệp và bắt đầu kiếm tiền từ kỹ năng của bạn.</p>
                        </div>

                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <a href="{{ route('user.register') }}" class="btn btn-light btn-lg">
                                <i class="las la-user-plus me-2"></i>Đăng ký tài khoản
                            </a>
                            <a href="{{ route('user.login.v2') }}" class="btn btn-outline-light btn-lg">
                                <i class="las la-sign-in-alt me-2"></i>Đăng nhập
                            </a>
                                </div>
                                
                        <div class="mt-4">
                            <p class="text-white-50 small">
                                <i class="las la-info-circle me-1"></i>
                                Sau khi đăng nhập, bạn sẽ được chuyển đến trang tạo hồ sơ thợ
                            </p>
                        </div>
                        
                        <!-- Nút Zalo hỗ trợ -->
                        <div class="mt-4">
                            <p class="text-white-50 mb-3">Cần hỗ trợ đăng ký hoặc tạo hồ sơ?</p>
                            <a href="https://zalo.me/{{ gs('zalo_phone') ?? '0972585990' }}" 
                               class="btn btn-success btn-lg zalo-chat-btn"
                               target="_blank">
                                <i class="las la-comments me-2"></i>
                                Zalo Chat - Hướng dẫn đăng ký và tạo hồ sơ thợ
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Câu chuyện thành công</h2>
            <p class="section-subtitle">Những thợ chuyên nghiệp đã thành công cùng Doitay.vn</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="story-card">
                    <div class="story-avatar">
                        <img src="{{ asset('assets/images/frontend/success_stories/tho_dien.png') }}" alt="Anh Minh">
                    </div>
                    <h5>Anh Minh - Thợ điện</h5>
                    <div class="story-stats">
                        <div class="stat">
                            <strong>150+</strong>
                            <span>Công việc</span>
                        </div>
                        <div class="stat">
                            <strong>25M</strong>
                            <span>Thu nhập/tháng</span>
                        </div>
                    </div>
                    <p>"Từ khi tham gia Doitay.vn, thu nhập của tôi đã tăng gấp 3 lần. 
                       Khách hàng đều chất lượng, tôi có thêm nguồn thu nhập mới"</p>
                    <div class="rating">
                        ⭐⭐⭐⭐⭐ <span>4.9/5</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="story-card">
                    <div class="story-avatar">
                        <img src="{{ asset('assets/images/frontend/success_stories/tho_son.png') }}" alt="Anh Tùng">
                    </div>
                    <h5>Anh Tùng - Thợ sơn</h5>
                    <div class="story-stats">
                        <div class="stat">
                            <strong>80+</strong>
                            <span>Công việc</span>
                        </div>
                        <div class="stat">
                            <strong>20M</strong>
                            <span>Thu nhập/tháng</span>
                        </div>
                    </div>
                    <p>"Doitay.vn rất chuyên nghiệp, hỗ trợ tốt. Tôi có thể cân bằng 
                       công việc và chăm sóc gia đình một cách dễ dàng."</p>
                    <div class="rating">
                        ⭐⭐⭐⭐⭐ <span>4.8/5</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="story-card">
                    <div class="story-avatar">
                        <img src="{{ asset('assets/images/frontend/success_stories/tho_xd.png') }}" alt="Anh Tuấn">
                    </div>
                    <h5>Anh Tuấn - Thợ xây dựng</h5>
                    <div class="story-stats">
                        <div class="stat">
                            <strong>200+</strong>
                            <span>Công việc</span>
                        </div>
                        <div class="stat">
                            <strong>35M</strong>
                            <span>Thu nhập/tháng</span>
                        </div>
                    </div>
                    <p>"Doitay.vn giúp tôi mở rộng khách hàng, tiếp cận nhiều công trình 
                       lớn hơn. Đây chính là bước ngoặt trong sự nghiệp."</p>
                    <div class="rating">
                        ⭐⭐⭐⭐⭐ <span>5.0/5</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Contractor Hero Section */
.contractor-hero {
    background: linear-gradient(135deg, #102f4b 0%, #1a4568 100%);
    color: white;
    position: relative;
    overflow: hidden;
}

/* Section Title */
.section-title {
    color: #102f4b;
    font-weight: 700;
    margin-bottom: 1rem;
}

.section-subtitle {
    color: #5a6c7d;
    font-size: 1.1rem;
    opacity: 0.85;
}

.contractor-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></svg>') repeat;
    animation: float 6s ease-in-out infinite;
    pointer-events: none; /* Important: Allow clicks to pass through */
}

.hero-content {
    position: relative;
    z-index: 5;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    color: #ffa500;
}

.gradient-text {
    background: linear-gradient(45deg, #48bbe2, #ffa500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.25rem;
    opacity: 0.9;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-item {
    text-align: center;
}

.stat-item h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #ffa500;
}

.stat-item p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    position: relative;
    z-index: 10;
}

.hero-actions .btn {
    padding: 1rem 2rem;
    font-weight: 600;
    border-radius: 12px;
    position: relative;
    z-index: 10;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.hero-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    text-decoration: none;
}

/* Hero Visual */
.hero-visual {
    position: relative;
    padding: 2rem;
}

.visual-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.visual-card h4 {
    color: #ffa500;
    font-weight: 600;
    margin-bottom: 1rem;
}

.visual-card p {
    color: white;
    font-weight: 500;
    margin: 0;
}

.earning-chart {
    display: flex;
    gap: 0.5rem;
    height: 60px;
    align-items: end;
    margin: 1rem 0;
}

.bar {
    background: linear-gradient(45deg, #48bbe2, #ffa500);
    border-radius: 4px;
    flex: 1;
    animation: grow 2s ease;
}

.job-notifications {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.notification {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    border-left: 3px solid #ffa500;
    animation: slideIn 1s ease;
}

/* Benefits Section */
.benefit-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    height: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.benefit-card:hover {
    transform: translateY(-5px);
}

.benefit-icon {
    width: 60px;
    height: 60px;
    background: rgba(72, 187, 226, 0.15);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.benefit-icon i {
    font-size: 2rem;
}

.benefit-card h4 {
    font-weight: 700;
    margin-bottom: 1rem;
    color: #102f4b;
}

.benefit-card p {
    color: #5a6c7d;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.benefit-card ul {
    list-style: none;
    padding: 0;
    margin-top: 1rem;
}

.benefit-card ul li {
    padding: 0.25rem 0;
    color: #48bbe2;
    font-weight: 500;
}

/* Process Cards */
.process-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    height: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    position: relative;
    transition: transform 0.3s ease;
}

.process-card h5 {
    color: #102f4b;
    font-weight: 600;
    margin-bottom: 1rem;
}

.process-card p {
    color: #5a6c7d;
    line-height: 1.6;
}

.process-card:hover {
    transform: translateY(-5px);
}

.step-number {
    position: absolute;
    top: -15px;
    right: -15px;
    background: #48bbe2;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}

.step-icon {
    width: 60px;
    height: 60px;
    background: rgba(72, 187, 226, 0.15);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.step-icon i {
    font-size: 2rem;
    color: #48bbe2;
}

/* Registration Section */
.registration-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Success Stories */
.story-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    height: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease;
}

.story-card h5 {
    color: #102f4b;
    font-weight: 600;
    margin-bottom: 1rem;
}

.story-card p {
    color: #5a6c7d;
    line-height: 1.6;
    font-style: italic;
}

.story-card:hover {
    transform: translateY(-5px);
}

.story-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    overflow: hidden;
}

.story-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.story-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin: 1rem 0;
}

.story-stats .stat {
    text-align: center;
}

.story-stats .stat strong {
    display: block;
    font-size: 1.25rem;
    color: #48bbe2;
}

.story-stats .stat span {
    font-size: 0.875rem;
    color: #5a6c7d;
    opacity: 0.9;
}

.rating {
    margin-top: 1rem;
    color: #ffa500;
    font-weight: 600;
}

/* Animations */
@keyframes grow {
    from { height: 0; }
}

@keyframes slideIn {
    from { 
        opacity: 0; 
        transform: translateX(-20px); 
    }
    to { 
        opacity: 1; 
        transform: translateX(0); 
    }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(5deg); }
}

/* Additional Improvements */
.hero-actions .btn-primary {
    background: linear-gradient(45deg, #48bbe2, #ffa500);
    border: none;
    box-shadow: 0 4px 15px rgba(72, 187, 226, 0.3);
}

/* Override btn-light with orange theme */
.btn-light {
    background: #ffa500 !important;
    border-color: #ffa500 !important;
    color: white !important;
    font-weight: 600;
}

.btn-light:hover {
    background: #e6940e !important;
    border-color: #e6940e !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 165, 0, 0.3);
}

.hero-actions .btn-primary:hover {
    background: linear-gradient(45deg, #3a9bc1, #e6940e);
    box-shadow: 0 6px 20px rgba(72, 187, 226, 0.4);
}

.hero-actions .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Comparison Infographic Styles */
.comparison-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.comparison-container {
    display: flex;
    align-items: center;
    gap: 2rem;
    position: relative;
}

.comparison-card {
    flex: 1;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.comparison-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.comparison-card.traditional:hover {
    opacity: 0.9;
    transform: translateY(-3px);
}

.comparison-card.doitay:hover {
    transform: translateY(-8px) scale(1.03);
    box-shadow: 0 25px 70px rgba(0, 123, 255, 0.25);
}

.comparison-card.traditional {
    border-left: 5px solid #dc3545;
    background: #f8f9fa;
    opacity: 0.8;
}

.comparison-card.doitay {
    border-left: 5px solid #007bff;
    background: white;
    box-shadow: 0 15px 50px rgba(0, 123, 255, 0.15);
    transform: scale(1.02);
}

.card-header {
    padding: 2rem 2rem 1rem;
    text-align: center;
}

.traditional .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
}

.doitay .card-header {
    background: linear-gradient(135deg, #e8f4ff 0%, #d4e9ff 100%);
    color: #0d47a1;
}

.doitay .card-subtitle {
    color: #1976d2;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2.5rem;
}

.traditional .icon-wrapper {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    opacity: 0.7;
}

.doitay .icon-wrapper {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
}

.card-header h4 {
    margin: 0 0 0.5rem 0;
    font-weight: 700;
    color: #2d3748;
}

.doitay .card-header h4 {
    color: #0d47a1;
}

.card-subtitle {
    margin: 0;
    font-size: 0.9rem;
    opacity: 0.8;
    font-weight: 500;
}

.card-body {
    padding: 2rem;
}

.feature-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.feature-list li {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding: 0.75rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.feature-list li.negative {
    background: #f8f9fa;
    color: #6c757d;
    border-left: 3px solid #dc3545;
}

.feature-list li.positive {
    background: #f8fbff;
    color: #0d47a1;
    border-left: 3px solid #007bff;
}

.feature-list li i {
    font-size: 1.2rem;
    margin-right: 1rem;
    min-width: 20px;
}

.feature-list li span {
    font-weight: 500;
    line-height: 1.4;
}

.feature-list li span strong {
    display: block;
    margin-bottom: 0.25rem;
}

.feature-list li span small {
    opacity: 0.8;
    font-size: 0.85rem;
}

.vs-badge {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    color: white;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 800;
    box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
    z-index: 10;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Zalo Chat Button Styles */
.zalo-chat-btn {
    background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%) !important;
    border: none !important;
    color: white !important;
    padding: 1rem 2rem !important;
    border-radius: 50px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 15px rgba(0, 166, 255, 0.3) !important;
}

.zalo-chat-btn:hover {
    background: linear-gradient(135deg, #0088CC 0%, #006699 100%) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 25px rgba(0, 166, 255, 0.4) !important;
    color: white !important;
}

.zalo-chat-btn i {
    margin-right: 0.5rem;
    font-size: 1.2rem;
}



/* Mobile Responsive for Comparison */
@media (max-width: 768px) {
    .comparison-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .vs-badge {
        order: -1;
        margin: 1rem 0;
    }
    
    .hero-actions {
        flex-direction: column;
    }
    
    .hero-actions .btn {
        width: 100%;
        margin-bottom: 1rem;
    }
    

}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .registration-tabs {
        flex-direction: column;
    }
    
    .story-stats {
        gap: 1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
    
    .zalo-chat-btn {
        padding: 0.75rem 1.5rem !important;
        font-size: 0.9rem !important;
    }
}
</style>

@push('script')
<script>
"use strict";

// Global functions for inline onclick handlers
window.scrollToForm = function(event) {
    event.preventDefault();
    console.log('Scroll to form clicked');
    const target = document.querySelector('#registration-form');
    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    } else {
        console.log('Registration form not found');
    }
};

window.goToHome = function(event) {
    console.log('Go to home clicked');
    window.location.href = '{{ route("home") }}';
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('Become contractor page loaded');
    
    // Smooth scroll for "Đăng ký ngay" button
    document.querySelectorAll('.scroll-to-form').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Scroll button clicked via event listener');
            scrollToForm(e);
        });
    });

    // Smooth scrolling for all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endpush



@endsection 