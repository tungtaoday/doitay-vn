@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="company-preview-v2">
    <!-- Header Section -->
    <div class="preview-header">
        <div class="container">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="page-title">
                        <i class="las la-eye"></i>
                        Xem trước hồ sơ
                    </h1>
                    <p class="page-subtitle">Đây là cách hồ sơ của bạn sẽ hiển thị với khách hàng</p>
                </div>
                
                <div class="header-actions">
                    <a href="{{ route('company.create.v2') }}" class="btn btn-outline-light">
                        <i class="las la-edit"></i>
                        Chỉnh sửa
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-light">
                        <i class="las la-home"></i>
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Content -->
    <div class="preview-content">
        <div class="container">
            <!-- Status Banner -->
            <div class="status-banner">
                <div class="status-icon">
                    <i class="las la-clock"></i>
                </div>
                <div class="status-info">
                    <h3>Hồ sơ đang chờ duyệt</h3>
                    <p>Chúng tôi sẽ xem xét và phê duyệt hồ sơ của bạn trong vòng 24-48 giờ. Bạn sẽ nhận được thông báo qua email khi hoàn tất.</p>
                </div>
            </div>

            <!-- Company Profile Preview -->
            <div class="profile-preview">
                <!-- Company Header -->
                <div class="company-header">
                    <div class="container">
                        <div class="row align-items-center">
                            <!-- Left: Company Info -->
                            <div class="col-lg-8">
                                <div class="company-main-info">
                                    <div class="company-avatar">
                                        @if(isset($company->image) && $company->image)
                                            <img src="{{ getImage(getFilePath('company') . '/' . $company->image) }}" 
                                                 alt="{{ $company->name }}" class="avatar-image">
                                        @else
                                            <div class="avatar-placeholder">
                                                <i class="las la-user-tie"></i>
                                            </div>
                                        @endif
                                        <div class="status-badge">
                                            <i class="las la-clock"></i>
                                            Chờ duyệt
                                        </div>
                                    </div>
                                    
                                    <div class="company-details">
                                        <h1 class="company-name">{{ $company->name ?? 'Tên thợ' }}</h1>
                                        <div class="company-category">
                                            <i class="las la-tools"></i>
                                            {{ $company->category->name ?? 'Lĩnh vực chuyên môn' }}
                                        </div>
                                        
                                        <div class="company-meta">
                                            <div class="meta-item">
                                                <div class="rating-preview">
                                                    <div class="stars">
                                                        <i class="las la-star"></i>
                                                        <i class="las la-star"></i>
                                                        <i class="las la-star"></i>
                                                        <i class="las la-star"></i>
                                                        <i class="las la-star"></i>
                                                    </div>
                                                    <span class="rating-text">Chưa có đánh giá</span>
                                                </div>
                                            </div>
                                            
                                            <div class="meta-item">
                                                <i class="las la-certificate"></i>
                                                <span>{{ $company->experience ?? 5 }} năm kinh nghiệm</span>
                                            </div>
                                            
                                            <div class="meta-item">
                                                <i class="las la-map-marker-alt"></i>
                                                <span>{{ $company->address ?? 'Địa chỉ' }}, {{ $company->city ?? 'Thành phố' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right: Contact Actions -->
                            <div class="col-lg-4">
                                <div class="contact-actions">
                                    <div class="contact-card">
                                        <h3 class="contact-title">Thông tin liên hệ</h3>
                                        
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="las la-phone"></i>
                                            </div>
                                            <div class="contact-info">
                                                <label>Điện thoại</label>
                                                <span>{{ $company->phone ?? '0123456789' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-item">
                                            <div class="contact-icon">
                                                <i class="las la-envelope"></i>
                                            </div>
                                            <div class="contact-info">
                                                <label>Email</label>
                                                <span>{{ $company->email ?? 'email@example.com' }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="contact-buttons">
                                            <button class="btn btn-primary" disabled>
                                                <i class="las la-phone"></i>
                                                Gọi ngay
                                            </button>
                                            <button class="btn btn-outline-primary" disabled>
                                                <i class="las la-envelope"></i>
                                                Nhắn tin
                                            </button>
                                        </div>
                                        
                                        <div class="preview-note">
                                            <i class="las la-info-circle"></i>
                                            <small>Khách hàng sẽ thấy buttons này khi hồ sơ được duyệt</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="profile-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- About Section -->
                                <div class="content-section">
                                    <h2 class="section-title">
                                        <i class="las la-user-tie"></i>
                                        Giới thiệu
                                    </h2>
                                    <div class="about-content">
                                        <p class="description">{{ $company->description ?? 'Mô tả dịch vụ sẽ hiển thị ở đây...' }}</p>
                                        
                                        <div class="highlights">
                                            <div class="highlight-item">
                                                <div class="highlight-icon">
                                                    <i class="las la-certificate"></i>
                                                </div>
                                                <div class="highlight-text">
                                                    <h4>Chuyên nghiệp</h4>
                                                    <p>{{ $company->experience ?? 5 }}+ năm kinh nghiệm trong ngành</p>
                                                </div>
                                            </div>
                                            
                                            <div class="highlight-item">
                                                <div class="highlight-icon">
                                                    <i class="las la-shield-alt"></i>
                                                </div>
                                                <div class="highlight-text">
                                                    <h4>Đáng tin cậy</h4>
                                                    <p>Hồ sơ sẽ được xác minh và có bảo hiểm</p>
                                                </div>
                                            </div>
                                            
                                            <div class="highlight-item">
                                                <div class="highlight-icon">
                                                    <i class="las la-handshake"></i>
                                                </div>
                                                <div class="highlight-text">
                                                    <h4>Cam kết chất lượng</h4>
                                                    <p>Bảo hành dài hạn cho mọi dự án</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stats Section -->
                                <div class="content-section">
                                    <h2 class="section-title">
                                        <i class="las la-chart-bar"></i>
                                        Thống kê (Dự kiến)
                                    </h2>
                                    <div class="stats-grid">
                                        <div class="stat-card">
                                            <div class="stat-number">5.0</div>
                                            <div class="stat-label">Điểm đánh giá</div>
                                            <div class="stat-sublabel">Từ khách hàng</div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-number">{{ $company->experience ?? 5 }}+</div>
                                            <div class="stat-label">Năm kinh nghiệm</div>
                                            <div class="stat-sublabel">Trong ngành</div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-number">100%</div>
                                            <div class="stat-label">Hoàn thành</div>
                                            <div class="stat-sublabel">Tỷ lệ dự án</div>
                                        </div>
                                        <div class="stat-card">
                                            <div class="stat-number">24/7</div>
                                            <div class="stat-label">Hỗ trợ</div>
                                            <div class="stat-sublabel">Khách hàng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sidebar -->
                            <div class="col-lg-4">
                                <div class="sidebar">
                                    <!-- Next Steps Card -->
                                    <div class="sidebar-card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="las la-tasks"></i>
                                                Bước tiếp theo
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="steps-list">
                                                <div class="step-item completed">
                                                    <div class="step-icon">
                                                        <i class="las la-check"></i>
                                                    </div>
                                                    <div class="step-text">
                                                        <h4>Tạo hồ sơ</h4>
                                                        <p>Hoàn thành</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="step-item active">
                                                    <div class="step-icon">
                                                        <i class="las la-clock"></i>
                                                    </div>
                                                    <div class="step-text">
                                                        <h4>Chờ duyệt</h4>
                                                        <p>24-48 giờ</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="step-item">
                                                    <div class="step-icon">
                                                        <i class="las la-rocket"></i>
                                                    </div>
                                                    <div class="step-text">
                                                        <h4>Xuất bản</h4>
                                                        <p>Nhận việc đầu tiên</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tips Card -->
                                    <div class="sidebar-card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                <i class="las la-lightbulb"></i>
                                                Gợi ý
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="tips-list">
                                                <div class="tip-item">
                                                    <i class="las la-camera"></i>
                                                    <span>Thêm nhiều ảnh portfolio để thu hút khách hàng</span>
                                                </div>
                                                <div class="tip-item">
                                                    <i class="las la-star"></i>
                                                    <span>Hoàn thành dự án đầu tiên để có đánh giá tốt</span>
                                                </div>
                                                <div class="tip-item">
                                                    <i class="las la-users"></i>
                                                    <span>Phản hồi tin nhắn nhanh để tăng uy tín</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* === PREVIEW V2 STYLES === */
:root {
    --primary-color: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1e40af;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --white: #ffffff;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    --transition: all 0.3s ease;
}

.company-preview-v2 {
    background: var(--gray-50);
    min-height: 100vh;
}

/* === PREVIEW HEADER === */
.preview-header {
    background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
    color: var(--white);
    padding: 40px 0;
    position: relative;
    overflow: hidden;
}

.preview-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,100 1000,0"/></svg>');
    background-size: cover;
}

.header-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-title i {
    font-size: 3rem;
    opacity: 0.8;
}

.page-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.header-actions {
    display: flex;
    gap: 16px;
}

.btn-outline-light {
    background: transparent;
    color: var(--white);
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--white);
}

.btn-light {
    background: var(--white);
    color: var(--success-color);
    border: 2px solid var(--white);
}

.btn-light:hover {
    background: var(--gray-100);
    transform: translateY(-2px);
}

/* === STATUS BANNER === */
.status-banner {
    background: linear-gradient(135deg, var(--warning-color), #d97706);
    color: var(--white);
    padding: 24px;
    border-radius: 16px;
    margin: 32px 0;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: var(--shadow-lg);
}

.status-icon {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.status-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.status-info p {
    margin: 0;
    opacity: 0.9;
    line-height: 1.6;
}

/* === COMPANY HEADER === */
.company-header {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
    padding: 40px 0;
    margin-bottom: 32px;
}

.company-main-info {
    display: flex;
    align-items: flex-start;
    gap: 24px;
}

.company-avatar {
    position: relative;
    flex-shrink: 0;
}

.avatar-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--white);
    box-shadow: var(--shadow-lg);
}

.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: var(--gray-400);
    border: 4px solid var(--white);
    box-shadow: var(--shadow-lg);
}

.status-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--warning-color);
    color: var(--white);
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: var(--shadow-md);
}

.company-name {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--gray-900);
    margin-bottom: 8px;
    line-height: 1.2;
}

.company-category {
    font-size: 1.2rem;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.company-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-600);
    font-weight: 500;
}

.meta-item i {
    color: var(--gray-400);
    font-size: 1.2rem;
}

.rating-preview {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    color: var(--gray-300);
    font-size: 1.2rem;
}

/* === CONTACT CARD === */
.contact-card {
    background: var(--white);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--gray-200);
}

.contact-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 20px;
    text-align: center;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray-200);
}

.contact-item:last-of-type {
    border-bottom: none;
    margin-bottom: 24px;
}

.contact-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-light-light);
    color: var(--primary-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.contact-info label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 2px;
    letter-spacing: 0.5px;
}

.contact-info span {
    color: var(--gray-700);
    font-weight: 500;
}

.contact-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.contact-buttons .btn {
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    opacity: 0.6;
    cursor: not-allowed;
}

.preview-note {
    margin-top: 16px;
    text-align: center;
    color: var(--gray-500);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    background: var(--gray-50);
    border-radius: 8px;
}

/* === CONTENT SECTIONS === */
.content-section {
    background: var(--white);
    border-radius: 16px;
    padding: 32px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    margin-bottom: 24px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary-color);
    font-size: 1.6rem;
}

/* About Section */
.description {
    font-size: 1.1rem;
    line-height: 1.7;
    color: var(--gray-700);
    margin-bottom: 32px;
}

.highlights {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.highlight-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: var(--gray-50);
    border-radius: 12px;
    border-left: 4px solid var(--primary-color);
}

.highlight-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.highlight-text h4 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 6px;
}

.highlight-text p {
    color: var(--gray-600);
    line-height: 1.5;
    margin: 0;
}

/* Stats Section */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 24px;
}

.stat-card {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
    transition: var(--transition);
    border: 1px solid var(--gray-200);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    display: block;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-600);
    margin-top: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-sublabel {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-top: 4px;
}

/* === SIDEBAR === */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.card-header {
    background: var(--gray-50);
    padding: 20px;
    border-bottom: 1px solid var(--gray-200);
}

.card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body {
    padding: 20px;
}

/* Steps List */
.steps-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.step-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-radius: 8px;
    transition: var(--transition);
}

.step-item.completed {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.step-item.active {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.step-item:not(.completed):not(.active) {
    background: var(--gray-50);
    border: 1px solid var(--gray-200);
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.step-item.completed .step-icon {
    background: var(--success-color);
    color: var(--white);
}

.step-item.active .step-icon {
    background: var(--warning-color);
    color: var(--white);
}

.step-item:not(.completed):not(.active) .step-icon {
    background: var(--gray-300);
    color: var(--gray-600);
}

.step-text h4 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0 0 4px 0;
}

.step-text p {
    font-size: 0.85rem;
    color: var(--gray-600);
    margin: 0;
}

/* Tips List */
.tips-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: var(--gray-50);
    border-radius: 8px;
    border-left: 4px solid var(--info-color);
}

.tip-item i {
    color: var(--info-color);
    font-size: 1.2rem;
    margin-top: 2px;
    flex-shrink: 0;
}

.tip-item span {
    color: var(--gray-700);
    font-size: 0.9rem;
    line-height: 1.5;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .preview-header {
        padding: 24px 0;
    }
    
    .header-content {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .page-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 8px;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .company-main-info {
        flex-direction: column;
        text-align: center;
        align-items: center;
    }
    
    .company-name {
        font-size: 2rem;
    }
    
    .company-meta {
        justify-content: center;
    }
    
    .content-section {
        padding: 24px 20px;
    }
    
    .highlights {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .status-banner {
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .company-meta {
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
</style>
@endsection
</rewritten_file>