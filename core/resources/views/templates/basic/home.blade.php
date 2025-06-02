@extends($activeTemplate . 'layouts.frontend')
@section('content')

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="hero-overlay"></div>
    <div class="hero-background">
        <div class="hero-particles">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <!-- Left Column - Lead Creation Form -->
            <div class="col-lg-5">
                <div class="lead-form-container">
                    <div class="form-header">
                        <h3 class="form-title">
                            <i class="las la-rocket me-2"></i>
                            Tạo Lead & Tìm Thợ Ngay
                        </h3>
                        <p class="form-subtitle">Miễn phí • Nhanh chóng • Hiệu quả</p>
                    </div>
                    
                    <form id="quickLeadForm" class="quick-lead-form">
                        @csrf
                        <!-- User Info Section (Auto-registration) -->
                        <div class="user-info-section">
                            <h5 class="section-title">📱 Thông tin liên hệ</h5>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" name="fullname" class="form-control" 
                                           placeholder="Họ và tên *" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="tel" name="mobile" class="form-control" 
                                           placeholder="Số điện thoại *" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" name="email" class="form-control" 
                                           placeholder="Email (tùy chọn)">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Job Info Section -->
                        <div class="job-info-section">
                            <h5 class="section-title">🔧 Thông tin công việc</h5>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Chọn loại công việc *</option>
                                        @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <input type="text" name="title" class="form-control" 
                                           placeholder="Tiêu đề công việc *" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <textarea name="description" class="form-control" rows="3" 
                                              placeholder="Mô tả ngắn gọn công việc cần làm *" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location & Budget -->
                        <div class="location-budget-section">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <select name="district" class="form-select" required>
                                        <option value="">Khu vực *</option>
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
                                        <option value="Thủ Đức">Thủ Đức</option>
                                        <option value="Bình Thạnh">Bình Thạnh</option>
                                        <option value="Tân Bình">Tân Bình</option>
                                        <option value="Tân Phú">Tân Phú</option>
                                        <option value="Phú Nhuận">Phú Nhuận</option>
                                        <option value="Gò Vấp">Gò Vấp</option>
                                        <option value="Bình Tân">Bình Tân</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <select name="urgency" class="form-select">
                                        <option value="medium">Bình thường</option>
                                        <option value="high">Khẩn cấp</option>
                                        <option value="low">Không gấp</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="number" name="budget_min" class="form-control" 
                                           placeholder="Ngân sách từ (VNĐ)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="number" name="budget_max" class="form-control" 
                                           placeholder="Ngân sách đến (VNĐ)">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100 submit-btn">
                            <i class="las la-rocket me-2"></i>
                            <span class="btn-text">Tạo Lead & Tìm Thợ Ngay</span>
                            <div class="btn-loader">
                                <div class="spinner-border spinner-border-sm me-2"></div>
                                Đang xử lý...
                            </div>
                        </button>
                        
                        <p class="form-note">
                            <i class="las la-shield-alt text-success me-1"></i>
                            Bằng cách tạo lead, bạn đồng ý với các 
                            <a href="{{ route('policy.pages', 'terms-of-service') }}">điều khoản</a> 
                            của chúng tôi
                        </p>
                    </form>
                </div>
            </div>
            
            <!-- Right Column - Hero Content -->
            <div class="col-lg-7">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="badge-text">🔥 #1 Nền tảng tìm thợ tại Việt Nam</span>
                    </div>
                    
                    <h1 class="hero-title">
                        Tìm Thợ Chuyên Nghiệp<br>
                        <span class="gradient-text">Nhanh & Tin Cậy</span>
                    </h1>
                    
                    <p class="hero-description">
                        Kết nối bạn với <strong>hàng ngàn thợ verified</strong> trong vòng <strong>15 phút</strong>. 
                        Từ điện nước, sửa chữa đến thi công - tất cả trong một nền tảng tin cậy.
                    </p>
                    
                    <!-- Trust Indicators -->
                    <div class="trust-indicators">
                        <div class="trust-item">
                            <i class="las la-shield-alt"></i>
                            <span>100% Verified</span>
                        </div>
                        <div class="trust-item">
                            <i class="las la-clock"></i>
                            <span>24/7 Hỗ trợ</span>
                        </div>
                        <div class="trust-item">
                            <i class="las la-medal"></i>
                            <span>Bảo hành chất lượng</span>
                        </div>
                    </div>
                    
                    <!-- Secondary CTAs -->
                    <div class="hero-actions">
                        <a href="{{ route('contractors.search') }}" class="btn btn-outline-white btn-lg">
                            <i class="las la-search me-2"></i>Xem Danh Sách Thợ
                        </a>
                        <a href="#how-it-works" class="btn btn-link btn-lg text-white">
                            <i class="las la-play-circle me-2"></i>Cách thức hoạt động
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="las la-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" data-count="1500">0</h3>
                            <p class="stat-label">Thợ Verified</p>
                            <span class="stat-growth">+25% tháng này</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="las la-tasks"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" data-count="8500">0</h3>
                            <p class="stat-label">Lead Hoàn Thành</p>
                            <span class="stat-growth">+40% tháng này</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="las la-star"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" data-count="4.9">0</h3>
                            <p class="stat-label">Đánh Giá Trung Bình</p>
                            <span class="stat-growth">⭐⭐⭐⭐⭐</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="las la-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3 class="stat-number" data-count="15">0</h3>
                            <p class="stat-label">Phút Trung Bình</p>
                            <span class="stat-growth">Thời gian phản hồi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="how-it-works-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Cách Doitay.vn Hoạt Động</h2>
            <p class="section-subtitle">Quy trình đơn giản 3 bước để tìm được thợ phù hợp nhất</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="process-card">
                    <div class="process-number">1</div>
                    <div class="process-icon">
                        <i class="las la-edit"></i>
                    </div>
                    <h4>Tạo Lead Miễn Phí</h4>
                    <p>Điền thông tin công việc và ngân sách. Hệ thống sẽ tự động tạo tài khoản và thông báo cho các thợ phù hợp.</p>
                    <ul class="feature-list">
                        <li>Tự động đăng ký tài khoản</li>
                        <li>AI matching thông minh</li>
                        <li>Thông báo real-time</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="process-card">
                    <div class="process-number">2</div>
                    <div class="process-icon">
                        <i class="las la-handshake"></i>
                    </div>
                    <h4>Nhận Báo Giá</h4>
                    <p>Các thợ quan tâm sẽ liên hệ báo giá. Bạn có thể xem profile, đánh giá và chọn thợ phù hợp nhất.</p>
                    <ul class="feature-list">
                        <li>So sánh nhiều báo giá</li>
                        <li>Xem portfolio thực tế</li>
                        <li>Đánh giá từ khách hàng</li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="process-card">
                    <div class="process-number">3</div>
                    <div class="process-icon">
                        <i class="las la-trophy"></i>
                    </div>
                    <h4>Hoàn Thành & Đánh Giá</h4>
                    <p>Thợ thực hiện công việc với chất lượng đảm bảo. Hoàn thành và đánh giá để giúp cộng đồng.</p>
                    <ul class="feature-list">
                        <li>Bảo hành chất lượng</li>
                        <li>Hỗ trợ 24/7</li>
                        <li>Thanh toán an toàn</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Dịch Vụ Phổ Biến</h2>
            <p class="section-subtitle">Tìm thợ chuyên nghiệp cho mọi nhu cầu của bạn</p>
        </div>
        
        <div class="row">
            @foreach(App\Models\Category::where('status', 1)->limit(8)->get() as $category)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="las la-tools"></i>
                    </div>
                    <h5>{{ $category->name }}</h5>
                    <p>{{ $category->companies_count ?? rand(15, 50) }}+ thợ có sẵn</p>
                    <a href="{{ route('companies.category', $category->id) }}" class="category-link">
                        Xem thợ <i class="las la-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('company.all') }}" class="btn btn-outline-primary btn-lg">
                Xem Tất Cả Danh Mục <i class="las la-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

@include('Template::partials.reviews')

<style>
/* Hero Banner Styles */
.hero-banner {
    position: relative;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
    display: flex;
    align-items: center;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.1);
    z-index: 1;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 0;
}

.hero-particles {
    position: absolute;
    width: 100%;
    height: 100%;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.particle:nth-child(2) {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.particle:nth-child(3) {
    width: 60px;
    height: 60px;
    top: 10%;
    right: 25%;
    animation-delay: 4s;
}

.particle:nth-child(4) {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 1s;
}

.particle:nth-child(5) {
    width: 40px;
    height: 40px;
    top: 50%;
    left: 50%;
    animation-delay: 3s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

/* Hero Content */
.hero-content {
    position: relative;
    z-index: 2;
    color: white;
    padding: 2rem 0;
}

.hero-badge {
    display: inline-block;
    margin-bottom: 1.5rem;
}

.badge-text {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.gradient-text {
    background: linear-gradient(45deg, #ffd700, #ffa500);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-description {
    font-size: 1.3rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.trust-indicators {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.trust-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.trust-item i {
    font-size: 1.5rem;
    color: #ffd700;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-outline-white {
    border: 2px solid white;
    color: white;
    font-weight: 600;
}

.btn-outline-white:hover {
    background: white;
    color: #667eea;
}

.btn-link {
    text-decoration: none;
}

.btn-link:hover {
    text-decoration: underline;
}

/* Lead Form Styles */
.lead-form-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 2;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    color: #333;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #28a745;
    font-weight: 600;
    margin: 0;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.quick-lead-form .form-control,
.quick-lead-form .form-select {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.quick-lead-form .form-control:focus,
.quick-lead-form .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.submit-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 12px;
    padding: 1rem;
    font-weight: 600;
    font-size: 1.1rem;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
}

.btn-loader {
    display: none;
}

.submit-btn.loading .btn-text {
    display: none;
}

.submit-btn.loading .btn-loader {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-note {
    text-align: center;
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 1rem;
    margin-bottom: 0;
}

.form-note a {
    color: #667eea;
    text-decoration: none;
}

/* Statistics Section */
.stats-section {
    padding: 4rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.stats-container {
    position: relative;
}

.stat-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stat-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.stat-growth {
    font-size: 0.9rem;
    color: #28a745;
    font-weight: 600;
}

/* How It Works Section */
.how-it-works-section {
    padding: 5rem 0;
    background: white;
}

.section-header {
    margin-bottom: 4rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #6c757d;
    margin: 0;
}

.process-card {
    text-align: center;
    padding: 2rem;
    height: 100%;
    position: relative;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 20px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.process-card:hover {
    border-color: #667eea;
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(102, 126, 234, 0.1);
}

.process-number {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.2rem;
}

.process-icon {
    font-size: 3rem;
    color: #667eea;
    margin: 2rem 0 1.5rem;
}

.process-card h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
}

.process-card p {
    color: #6c757d;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.feature-list {
    list-style: none;
    padding: 0;
    text-align: left;
}

.feature-list li {
    padding: 0.5rem 0;
    color: #28a745;
    font-weight: 600;
}

.feature-list li:before {
    content: "✓ ";
    margin-right: 0.5rem;
}

/* Categories Section */
.categories-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.category-card {
    background: white;
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
    border-color: #667eea;
}

.category-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.category-card h5 {
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.category-card p {
    color: #6c757d;
    margin-bottom: 1rem;
}

.category-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.category-link:hover {
    color: #764ba2;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-description {
        font-size: 1.1rem;
    }
    
    .trust-indicators {
        gap: 1rem;
    }
    
    .hero-actions {
        flex-direction: column;
    }
    
    .lead-form-container {
        margin-top: 2rem;
    }
    
    .stat-number {
        font-size: 2.5rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
}

/* Animation for counting numbers */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-number {
    animation: countUp 0.8s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate statistics numbers
    const statNumbers = document.querySelectorAll('.stat-number');
    const observerOptions = {
        threshold: 0.7
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalValue = target.getAttribute('data-count');
                const isDecimal = finalValue.includes('.');
                const duration = 2000;
                const steps = 60;
                const increment = finalValue / steps;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= finalValue) {
                        target.textContent = finalValue;
                        clearInterval(timer);
                    } else {
                        if (isDecimal) {
                            target.textContent = current.toFixed(1);
                        } else {
                            target.textContent = Math.floor(current);
                        }
                    }
                }, duration / steps);
                
                observer.unobserve(target);
            }
        });
    }, observerOptions);
    
    statNumbers.forEach(num => observer.observe(num));
    
    // Quick Lead Form Handler
    const quickLeadForm = document.getElementById('quickLeadForm');
    if (quickLeadForm) {
        quickLeadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.submit-btn');
            submitBtn.classList.add('loading');
            
            // Simulate form processing
            setTimeout(() => {
                // Here you would typically send the data to your backend
                // For now, we'll just show a success message
                alert('Lead đã được tạo thành công! Chúng tôi sẽ liên hệ với bạn trong vòng 15 phút.');
                submitBtn.classList.remove('loading');
                
                // Optionally redirect to a success page or clear the form
                this.reset();
            }, 2000);
        });
    }
    
    // Smooth scrolling for anchor links
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

@endsection
