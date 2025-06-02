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
                        <a href="#registration-form" class="btn btn-primary btn-lg">
                            <i class="las la-rocket me-2"></i>Đăng ký ngay
                        </a>
                        <a href="#why-choose-us" class="btn btn-outline-light btn-lg">
                            <i class="las la-play-circle me-2"></i>Tìm hiểu thêm
                        </a>
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
                        <i class="las la-users text-primary"></i>
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
                        <i class="las la-clock text-success"></i>
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
                        <i class="las la-dollar-sign text-warning"></i>
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
<section id="registration-form" class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h2 class="text-white">Sẵn sàng bắt đầu?</h2>
                    <p class="text-white-50">Đăng ký ngay để tham gia mạng lưới thợ chuyên nghiệp</p>
                </div>
                
                <div class="registration-container">
                    <div class="registration-tabs">
                        <button class="tab-btn active" data-tab="check-account">
                            <i class="las la-user-check"></i>
                            Kiểm tra tài khoản
                        </button>
                        <button class="tab-btn" data-tab="register-user">
                            <i class="las la-user-plus"></i>
                            Đăng ký mới
                        </button>
                        <button class="tab-btn" data-tab="create-contractor">
                            <i class="las la-tools"></i>
                            Tạo hồ sơ thợ
                        </button>
                    </div>

                    <!-- Check Account Tab -->
                    <div class="tab-content active" id="checkAccountTab">
                        <form id="loginForm" class="contractor-form">
                            @csrf
                            <input type="hidden" name="action" value="login">
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Email hoặc Số điện thoại</label>
                                <input type="text" name="username" class="form-control" 
                                       placeholder="Nhập email hoặc số điện thoại" required>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="login_password" class="form-control" 
                                       placeholder="Nhập mật khẩu" required>
                            </div>
                            
                            <button type="submit" class="btn btn-light btn-lg w-100">
                                <i class="las la-sign-in-alt me-2"></i>Đăng nhập & Tạo hồ sơ thợ
                            </button>
                            
                            <div class="text-center mt-3">
                                <p class="text-white-50">Chưa có tài khoản? 
                                    <a href="#" class="text-warning" onclick="switchTab('register-user')">Đăng ký ngay</a>
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Register User Tab -->
                    <div class="tab-content" id="registerUserTab">
                        <form id="registerForm" class="contractor-form">
                            @csrf
                            <input type="hidden" name="action" value="register">
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="fullname" class="form-control" 
                                       placeholder="Nguyễn Văn A" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" 
                                           placeholder="email@domain.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="mobile" class="form-control" 
                                           placeholder="0123456789" required>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Tối thiểu 6 ký tự" required>
                            </div>
                            
                            <button type="submit" class="btn btn-light btn-lg w-100">
                                <i class="las la-user-plus me-2"></i>Đăng ký tài khoản
                            </button>
                            
                            <div class="text-center mt-3">
                                <p class="text-white-50">Đã có tài khoản? 
                                    <a href="#" class="text-warning" onclick="switchTab('check-account')">Đăng nhập</a>
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Create Contractor Tab -->
                    <div class="tab-content" id="createContractorTab">
                        <form id="contractorForm" class="contractor-form">
                            @csrf
                            <input type="hidden" name="action" value="create_contractor">
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Tên công ty/Tên thợ</label>
                                <input type="text" name="company_name" class="form-control" 
                                       placeholder="VD: Thợ điện Minh An" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Chuyên môn</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Chọn chuyên môn</option>
                                    @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Mô tả kỹ năng & kinh nghiệm</label>
                                <textarea name="description" class="form-control" rows="4" 
                                          placeholder="Mô tả chi tiết về kỹ năng, kinh nghiệm và dịch vụ bạn cung cấp..." required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="las la-tools me-2"></i>Tạo hồ sơ thợ chuyên nghiệp
                            </button>
                        </form>
                    </div>
                </div>
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
                        <img src="{{ asset('assets/images/avatar-placeholder.png') }}" alt="Anh Minh">
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
                       Khách hàng đều chất lượng, thanh toán đúng hẹn."</p>
                    <div class="rating">
                        ⭐⭐⭐⭐⭐ <span>4.9/5</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="story-card">
                    <div class="story-avatar">
                        <img src="{{ asset('assets/images/avatar-placeholder.png') }}" alt="Chị Lan">
                    </div>
                    <h5>Chị Lan - Thợ trang trí</h5>
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
                    <p>"Platform rất chuyên nghiệp, hỗ trợ tốt. Tôi có thể cân bằng 
                       công việc và chăm sóc gia đình một cách dễ dàng."</p>
                    <div class="rating">
                        ⭐⭐⭐⭐⭐ <span>4.8/5</span>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="story-card">
                    <div class="story-avatar">
                        <img src="{{ asset('assets/images/avatar-placeholder.png') }}" alt="Anh Tuấn">
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
                    <p>"Doitay.vn giúp tôi mở rộng thị trường, tiếp cận nhiều dự án 
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    overflow: hidden;
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
}

.gradient-text {
    background: linear-gradient(45deg, #ffd700, #ffa500);
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
}

.stat-item p {
    font-size: 0.9rem;
    opacity: 0.8;
    margin: 0;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.hero-actions .btn {
    padding: 1rem 2rem;
    font-weight: 600;
    border-radius: 12px;
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

.earning-chart {
    display: flex;
    gap: 0.5rem;
    height: 60px;
    align-items: end;
    margin: 1rem 0;
}

.bar {
    background: linear-gradient(45deg, #ffd700, #ffa500);
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
    border-left: 3px solid #ffd700;
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
    background: rgba(11, 146, 212, 0.1);
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
    color: #2c3e50;
}

.benefit-card ul {
    list-style: none;
    padding: 0;
    margin-top: 1rem;
}

.benefit-card ul li {
    padding: 0.25rem 0;
    color: #28a745;
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

.process-card:hover {
    transform: translateY(-5px);
}

.step-number {
    position: absolute;
    top: -15px;
    right: -15px;
    background: #0b92d4;
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
    background: rgba(11, 146, 212, 0.1);
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.step-icon i {
    font-size: 2rem;
    color: #0b92d4;
}

/* Registration Section */
.registration-container {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    padding: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.registration-tabs {
    display: flex;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 0.25rem;
    margin-bottom: 2rem;
}

.registration-tabs .tab-btn {
    flex: 1;
    border: none;
    background: transparent;
    color: rgba(255, 255, 255, 0.7);
    padding: 1rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.registration-tabs .tab-btn.active {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

.contractor-form .form-control,
.contractor-form .form-select {
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: #2c3e50;
}

.contractor-form .form-control:focus,
.contractor-form .form-select:focus {
    background: white;
    border-color: #ffd700;
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.2);
}

.contractor-form .form-label {
    color: white;
    font-weight: 600;
    margin-bottom: 0.5rem;
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
    color: #0b92d4;
}

.story-stats .stat span {
    font-size: 0.875rem;
    color: #6c757d;
}

.rating {
    margin-top: 1rem;
    color: #ffa500;
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
    }
    
    .hero-actions .btn {
        width: 100%;
    }
    
    .registration-tabs {
        flex-direction: column;
    }
    
    .story-stats {
        gap: 1rem;
    }
}
</style>

@push('script')
<script>
"use strict";

document.addEventListener('DOMContentLoaded', function() {
    // Handle tab switching
    window.switchTab = function(tabName) {
        // Remove active class from all tabs and contents
        document.querySelectorAll('.registration-tabs .tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active class to target tab and content
        const targetBtn = document.querySelector(`[data-tab="${tabName}"]`);
        const targetContent = document.getElementById(tabName.replace('-', '') + 'Tab');
        
        if (targetBtn) targetBtn.classList.add('active');
        if (targetContent) targetContent.classList.add('active');
    };

    // Tab click handlers
    document.querySelectorAll('.registration-tabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });

    // Form submissions
    document.getElementById('loginForm').addEventListener('submit', handleFormSubmit);
    document.getElementById('registerForm').addEventListener('submit', handleFormSubmit);
    document.getElementById('contractorForm').addEventListener('submit', handleFormSubmit);

    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("become.contractor.register") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message, 'success');
                
                if (result.next_step === 'create_contractor') {
                    // Switch to contractor creation tab
                    setTimeout(() => switchTab('create-contractor'), 1000);
                } else if (result.redirect) {
                    // Redirect to dashboard
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 2000);
                } else {
                    form.reset();
                }
            } else {
                showNotification(result.message, 'error');
            }
        } catch (error) {
            showNotification('Có lỗi kết nối, vui lòng thử lại', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    function showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.contractor-notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `contractor-notification alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
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

    // Smooth scrolling
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