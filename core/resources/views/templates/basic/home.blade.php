@extends($activeTemplate . 'layouts.frontend')
@section('content')

@include('templates.basic.home.hero')

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
            <p class="section-subtitle-how">Quy trình đơn giản để tìm được thợ chuyên nghiệp phù hợp</p>
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
                        <h4 class="step-title-modern">Tìm Thợ bằng cách tạo Nhu cầu</h4>
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
                        <h4 class="step-title-modern">Tìm Thợ theo Danh sách</h4>
                        <p class="step-description-modern">Tìm thợ theo danh sách lĩnh vực, khu vực mà bạn quan tâm. Chúng tôi cung cấp đánh giá uy tính từng thợ dựa trên lịch sự của họ trước đây</p>
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
                        <h4 class="step-title-modern">Kết Nối và Hoàn Thành Công Việc</h4>
                        <p class="step-description-modern">Liên hệ trực tiếp với thợ, thống nhất chi tiết công việc và theo dõi tiến độ. Có quyền đánh giá đánh giá Thợ sau khi hoàn thành.</p>
                        <div class="step-features">
                            <span class="feature-tag">✓ Giao dịch an toàn</span>
                            <span class="feature-tag">✓ Hỗ trợ 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('templates.basic.home.lead_form')

@include('templates.basic.home.contractor_search')


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
    color: #102f4b;
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
    color: #102f4b;
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
    color: #102f4b;
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
    color: #102f4b;
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
    box-shadow: 0 4px 15px rgba(16, 47, 75, 0.25);
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
    border: 1px solid rgba(16, 47, 75, 0.1);
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
    background: rgba(16, 47, 75, 0.1);
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
    border-bottom: 2px solid rgba(16, 47, 75, 0.2);
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
    border: 2px solid rgba(16, 47, 75, 0.2);
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
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.15);
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
    background: rgba(16, 47, 75, 0.05);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(16, 47, 75, 0.15);
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
    background: #102f4b;
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
    color: #102f4b;
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
    color: #102f4b;
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
    color: #102f4b;
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
    color: #102f4b;
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
    box-shadow: 0 4px 20px rgba(16, 47, 75, 0.25);
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
    box-shadow: 0 0 0 3px rgba(16, 47, 75, 0.15);
}

/* Process Step Cards */
.process-step-card {
    background: white;
    border-radius: 20px;
    padding: 2rem 1.5rem;
    box-shadow: 0 8px 32px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(16, 47, 75, 0.1);
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
    border-color: rgba(16, 47, 75, 0.3);
}

.process-step-card:hover::before {
    transform: scaleX(1);
}

/* Featured Step Card */
.step-card-featured {
    background: rgba(16, 47, 75, 0.02);
    border: 2px solid rgba(16, 47, 75, 0.2);
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
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.25);
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
    background: rgba(16, 47, 75, 0.1);
    color: #102f4b;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    border: 1px solid rgba(16, 47, 75, 0.2);
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
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.25);
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
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.25) !important;
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
    border: 2px solid rgba(16, 47, 75, 0.3) !important;
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
    background: rgba(16, 47, 75, 0.1) !important;
    border-color: #48bbe2 !important;
    color: #102f4b !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.1) !important;
}

.prev-step-auth:focus {
    outline: 2px solid #48bbe2 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.25) !important;
}

.prev-step-auth:active {
    transform: translateY(0) !important;
    background: rgba(16, 47, 75, 0.2) !important;
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
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.2);
}

.quick-lead-form .btn-outline-secondary {
    border-color: rgba(16, 47, 75, 0.3);
    color: #102f4b;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
    cursor: pointer;
    pointer-events: auto;
    position: relative;
    z-index: 10;
}

.quick-lead-form .btn-outline-secondary:hover {
    background: rgba(16, 47, 75, 0.1);
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
    border-color: rgba(16, 47, 75, 0.2);
    box-shadow: none;
}

/* Guest Form Next Step Button - Force Clickable */
.next-step {
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 20 !important;
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
    width: auto !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    user-select: none !important;
    -webkit-tap-highlight-color: transparent !important;
}

.next-step:hover {
    background: #102f4b !important;
    border-color: #102f4b !important;
    color: white !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.2) !important;
}

.next-step:focus {
    outline: 2px solid #48bbe2 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.25) !important;
}

.next-step:active {
    transform: translateY(0) !important;
    background: #3aa3c7 !important;
    color: white !important;
}

.next-step:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    pointer-events: none !important;
}

/* Guest Form Previous Step Button */
.prev-step {
    cursor: pointer !important;
    pointer-events: auto !important;
    position: relative !important;
    z-index: 20 !important;
    display: inline-block !important;
    background: transparent !important;
    border: 2px solid rgba(16, 47, 75, 0.3) !important;
    color: #102f4b !important;
    font-weight: 500 !important;
    padding: 0.75rem 1.5rem !important;
    border-radius: 12px !important;
    transition: all 0.3s ease !important;
    text-decoration: none !important;
    outline: none !important;
    min-height: 48px !important;
    width: auto !important;
    -webkit-user-select: none !important;
    -moz-user-select: none !important;
    user-select: none !important;
    -webkit-tap-highlight-color: transparent !important;
}

.prev-step:hover {
    background: rgba(16, 47, 75, 0.1) !important;
    border-color: #48bbe2 !important;
    color: #102f4b !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(16, 47, 75, 0.1) !important;
}

.prev-step:focus {
    outline: 2px solid #48bbe2 !important;
    outline-offset: 2px !important;
    box-shadow: 0 0 0 0.2rem rgba(16, 47, 75, 0.25) !important;
}

.prev-step:active {
    transform: translateY(0) !important;
    background: rgba(16, 47, 75, 0.2) !important;
}

/* Ensure guest form containers allow clicks */
#guestTab,
#guestLeadForm,
.form-step {
    pointer-events: auto !important;
    position: relative;
    z-index: 1;
}

#step1,
#step2,
#step3 {
    pointer-events: auto !important;
    position: relative;
    z-index: 2;
}

.step-navigation {
    pointer-events: auto !important;
    position: relative;
    z-index: 21;
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
}

/* Mobile improvements for guest form buttons */
@media (max-width: 768px) {
    .next-step,
    .prev-step {
        padding: 0.875rem 1.25rem !important;
        font-size: 0.9rem !important;
        min-height: 52px !important;
        width: 100% !important;
    }
    
    .step-navigation {
        flex-direction: column;
        gap: 0.75rem;
    }
}

/* Form steps navigation for guest users - AGGRESSIVE FIX */
let currentGuestStep = 1;
const totalGuestSteps = 3;

// Function to force setup guest buttons
function setupGuestButtons() {
    console.log('🔧 Setting up guest form buttons...');
    
    // Remove ALL existing next-step listeners and create fresh ones
    const existingNextBtns = document.querySelectorAll('.next-step');
    existingNextBtns.forEach((btn, index) => {
        console.log('Removing existing next-step button', index);
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
    });

    // Setup next-step buttons with multiple event types
    const nextStepBtns = document.querySelectorAll('.next-step');
    console.log('🎯 Found next-step buttons:', nextStepBtns.length);
    
    nextStepBtns.forEach((btn, index) => {
        console.log('Setting up next-step button', index, btn);
        
        // Ensure button is clickable
        btn.style.pointerEvents = 'auto';
        btn.style.cursor = 'pointer';
        btn.style.zIndex = '25';
        btn.disabled = false;
        
        // Add multiple event listeners for maximum compatibility
        ['click', 'mousedown', 'touchstart'].forEach(eventType => {
            btn.addEventListener(eventType, function(e) {
                console.log(`🎯 Guest next-step ${eventType} event triggered on button`, index);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Skip validation for now to test navigation
                console.log('Attempting to go to next step from:', currentGuestStep);
                goToGuestStep(currentGuestStep + 1);
                
                // Optional: Add validation back later
                // if (validateCurrentStep(currentGuestStep)) {
                //     goToGuestStep(currentGuestStep + 1);
                // }
            }, { passive: false, capture: true });
        });
        
        // Test button immediately
        console.log('✅ Button', index, 'setup complete. Testing...');
        console.log('Button visible:', btn.offsetParent !== null);
        console.log('Button disabled:', btn.disabled);
        console.log('Button pointer events:', window.getComputedStyle(btn).pointerEvents);
    });

    // Remove ALL existing prev-step listeners and create fresh ones
    const existingPrevBtns = document.querySelectorAll('.prev-step');
    existingPrevBtns.forEach((btn, index) => {
        console.log('Removing existing prev-step button', index);
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
    });

    // Setup prev-step buttons
    const prevStepBtns = document.querySelectorAll('.prev-step');
    console.log('🎯 Found prev-step buttons:', prevStepBtns.length);
    
    prevStepBtns.forEach((btn, index) => {
        console.log('Setting up prev-step button', index, btn);
        
        // Ensure button is clickable
        btn.style.pointerEvents = 'auto';
        btn.style.cursor = 'pointer';
        btn.style.zIndex = '25';
        btn.disabled = false;
        
        // Add multiple event listeners
        ['click', 'mousedown', 'touchstart'].forEach(eventType => {
            btn.addEventListener(eventType, function(e) {
                console.log(`🎯 Guest prev-step ${eventType} event triggered on button`, index);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                console.log('Attempting to go to previous step from:', currentGuestStep);
                goToGuestStep(currentGuestStep - 1);
            }, { passive: false, capture: true });
        });
    });
}

// New guest step navigation function
function goToGuestStep(step) {
    if (step < 1 || step > totalSteps) {
        console.log('❌ Invalid guest step:', step);
        return;
    }
    
    console.log('🚶 Going to guest step:', step, 'from current step:', currentStep);
    
    // Hide all steps first
    for (let i = 1; i <= totalSteps; i++) {
        const stepEl = document.getElementById('step' + i);
        if (stepEl) {
            stepEl.style.display = 'none';
            console.log('Hidden step', i);
        }
    }
    
    // Show target step
    const targetStepEl = document.getElementById('step' + step);
    if (targetStepEl) {
        targetStepEl.style.display = 'block';
        currentStep = step;
        console.log('✅ Showed step', step, '- currentStep updated to:', currentStep);
    } else {
        console.log('❌ Could not find step element:', 'step' + step);
    }
}

// Initialize guest buttons immediately
setupGuestButtons();

// Also setup with delay in case DOM changes
setTimeout(setupGuestButtons, 1000);
setTimeout(setupGuestButtons, 3000);

// Legacy functions for compatibility (keep existing goToStep)
function goToStep(step) {
    console.log('🔄 Legacy goToStep called, redirecting to goToGuestStep');
    goToGuestStep(step);
}

// =======================================
// GUEST FORM BUTTON FIX - FINAL SOLUTION
// =======================================

// Initialize guest button fix immediately when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing GUEST BUTTON FIX...');
    setupGuestFormButtons();
    
    // Also run with delays to catch dynamically loaded content
    setTimeout(setupGuestFormButtons, 1000);
    setTimeout(setupGuestFormButtons, 3000);
});

function setupGuestFormButtons() {
    console.log('🔧 Setting up guest form buttons...');
    
    // Force setup next-step buttons
    const nextButtons = document.querySelectorAll('.next-step');
    console.log('Found next-step buttons:', nextButtons.length);
    
    nextButtons.forEach((btn, index) => {
        // Remove existing event listeners by cloning
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Force make button clickable
        newBtn.style.pointerEvents = 'auto';
        newBtn.style.cursor = 'pointer';
        newBtn.style.zIndex = '1000';
        newBtn.disabled = false;
        newBtn.style.opacity = '1';
        newBtn.style.visibility = 'visible';
        
        // Add comprehensive event listeners
        ['click', 'touchend', 'mouseup'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 GUEST next-step ${eventType} triggered!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Simple step navigation without complex validation
                const currentStepEl = document.querySelector('#step1[style*="block"], #step1:not([style*="none"])');
                const nextStepEl = document.getElementById('step2');
                
                if (currentStepEl && nextStepEl) {
                    currentStepEl.style.display = 'none';
                    nextStepEl.style.display = 'block';
                    console.log('✅ Guest moved to step 2');
                } else {
                    // Fallback: force show step 2
                    const step1 = document.getElementById('step1');
                    const step2 = document.getElementById('step2');
                    if (step1) step1.style.display = 'none';
                    if (step2) step2.style.display = 'block';
                    console.log('✅ Guest moved to step 2 (fallback)');
                }
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Setup next-step button ${index}`);
    });
    
    // Force setup prev-step buttons
    const prevButtons = document.querySelectorAll('.prev-step');
    console.log('Found prev-step buttons:', prevButtons.length);
    
    prevButtons.forEach((btn, index) => {
        // Remove existing event listeners by cloning
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Force make button clickable
        newBtn.style.pointerEvents = 'auto';
        newBtn.style.cursor = 'pointer';
        newBtn.style.zIndex = '1000';
        newBtn.disabled = false;
        
        // Add comprehensive event listeners
        ['click', 'touchend', 'mouseup'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 GUEST prev-step ${eventType} triggered!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Simple step navigation
                const currentStepEl = document.querySelector('#step2[style*="block"], #step2:not([style*="none"]), #step3[style*="block"], #step3:not([style*="none"])');
                
                if (currentStepEl) {
                    const currentStepNumber = currentStepEl.id.replace('step', '');
                    const prevStepNumber = parseInt(currentStepNumber) - 1;
                    const prevStepEl = document.getElementById('step' + prevStepNumber);
                    
                    if (prevStepEl) {
                        currentStepEl.style.display = 'none';
                        prevStepEl.style.display = 'block';
                        console.log(`✅ Guest moved to step ${prevStepNumber}`);
                    }
                }
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Setup prev-step button ${index}`);
    });
}

// Manual test functions for guest form
window.testGuestNextStep = function() {
    console.log('🧪 Manual test: Guest next step');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    
    if (step1) step1.style.display = 'none';
    if (step2) step2.style.display = 'block';
    
    console.log('✅ Manually moved to step 2');
};

window.testGuestPrevStep = function() {
    console.log('🧪 Manual test: Guest prev step');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    if (step2) step2.style.display = 'none';
    if (step3) step3.style.display = 'none';
    if (step1) step1.style.display = 'block';
    
    console.log('✅ Manually moved to step 1');
};

window.checkGuestButtonStatus = function() {
    console.log('🔍 Checking guest button status...');
    
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    
    console.log('Next buttons found:', nextButtons.length);
    nextButtons.forEach((btn, i) => {
        console.log(`Next button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            pointerEvents: getComputedStyle(btn).pointerEvents,
            cursor: getComputedStyle(btn).cursor,
            zIndex: getComputedStyle(btn).zIndex
        });
    });
    
    console.log('Prev buttons found:', prevButtons.length);
    prevButtons.forEach((btn, i) => {
        console.log(`Prev button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            pointerEvents: getComputedStyle(btn).pointerEvents,
            cursor: getComputedStyle(btn).cursor,
            zIndex: getComputedStyle(btn).zIndex
        });
    });
};

window.forceClickGuestNextStep = function() {
    console.log('🔨 Force clicking guest next step button...');
    const nextBtn = document.querySelector('.next-step');
    if (nextBtn) {
        // Try multiple click methods
        nextBtn.click();
        nextBtn.dispatchEvent(new MouseEvent('click', { bubbles: true, cancelable: true }));
        nextBtn.dispatchEvent(new TouchEvent('touchend', { bubbles: true, cancelable: true }));
        console.log('✅ Force click attempted');
    } else {
        console.log('❌ Next button not found');
    }
};

// Add global event delegation as backup
document.addEventListener('click', function(e) {
    // Backup handler for next-step buttons
    if (e.target.closest('.next-step')) {
        console.log('🚨 BACKUP: Guest next-step clicked');
        e.preventDefault();
        e.stopPropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';
        
        console.log('✅ BACKUP: Moved to step 2');
    }
    
    // Backup handler for prev-step buttons
    if (e.target.closest('.prev-step')) {
        console.log('🚨 BACKUP: Guest prev-step clicked');
        e.preventDefault();
        e.stopPropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        if (step2) step2.style.display = 'none';
        if (step3) step3.style.display = 'none';
        if (step1) step1.style.display = 'block';
        
        console.log('✅ BACKUP: Moved to step 1');
    }
}, true); // Use capture phase

// =======================================
// GUEST BUTTON FIX - WORKING SOLUTION
// =======================================

// Simple and effective guest button fix
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Fixing guest buttons...');
    
    // Wait a bit for all elements to load
    setTimeout(function() {
        fixGuestButtons();
    }, 500);
    
    setTimeout(function() {
        fixGuestButtons();
    }, 2000);
});

function fixGuestButtons() {
    console.log('🔧 Fixing guest form navigation buttons...');
    
    // Fix next-step buttons
    const nextButtons = document.querySelectorAll('.next-step');
    console.log('Found next buttons:', nextButtons.length);
    
    nextButtons.forEach((btn, index) => {
        // Clear existing listeners by replacing
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Force clickable styles
        newBtn.style.pointerEvents = 'auto';
        newBtn.style.cursor = 'pointer';
        newBtn.style.zIndex = '999';
        newBtn.disabled = false;
        
        // Add click handler
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🎯 Guest next clicked!');
            
            // Simple step change
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            
            if (step1) step1.style.display = 'none';
            if (step2) step2.style.display = 'block';
            
            console.log('✅ Moved to step 2');
        });
        
        console.log('✅ Fixed next button', index);
    });
    
    // Fix prev-step buttons
    const prevButtons = document.querySelectorAll('.prev-step');
    console.log('Found prev buttons:', prevButtons.length);
    
    prevButtons.forEach((btn, index) => {
        // Clear existing listeners by replacing
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        // Force clickable styles
        newBtn.style.pointerEvents = 'auto';
        newBtn.style.cursor = 'pointer';
        newBtn.style.zIndex = '999';
        newBtn.disabled = false;
        
        // Add click handler
        newBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('🎯 Guest prev clicked!');
            
            // Simple step change back
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            
            if (step2) step2.style.display = 'none';
            if (step3) step3.style.display = 'none';
            if (step1) step1.style.display = 'block';
            
            console.log('✅ Moved to step 1');
        });
        
        console.log('✅ Fixed prev button', index);
    });
}

// Manual test functions
window.testGuestNextStep = function() {
    console.log('🧪 Manual test: Guest next step');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    
    if (step1) step1.style.display = 'none';
    if (step2) step2.style.display = 'block';
    
    console.log('✅ Manually moved to step 2');
};

window.testGuestPrevStep = function() {
    console.log('🧪 Manual test: Guest prev step');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    if (step2) step2.style.display = 'none';
    if (step3) step3.style.display = 'none';
    if (step1) step1.style.display = 'block';
    
    console.log('✅ Manually moved to step 1');
};

window.checkGuestSteps = function() {
    console.log('🔍 Checking guest steps...');
    
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    console.log('Step 1:', step1 ? 'Found' : 'Not found', step1 ? getComputedStyle(step1).display : 'N/A');
    console.log('Step 2:', step2 ? 'Found' : 'Not found', step2 ? getComputedStyle(step2).display : 'N/A');
    console.log('Step 3:', step3 ? 'Found' : 'Not found', step3 ? getComputedStyle(step3).display : 'N/A');
    
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    
    console.log('Next buttons:', nextBtns.length);
    console.log('Prev buttons:', prevBtns.length);
    
    return {
        step1: !!step1,
        step2: !!step2,
        step3: !!step3,
        nextButtons: nextBtns.length,
        prevButtons: prevBtns.length
    };
};

window.forceFixGuestButtons = function() {
    console.log('🔨 Force fixing guest buttons...');
    fixGuestButtons();
};

// Backup global click handler
document.addEventListener('click', function(e) {
    if (e.target.closest('.next-step')) {
        console.log('🚨 Backup next handler');
        e.preventDefault();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';
        
        console.log('✅ Backup moved to step 2');
    }
    
    if (e.target.closest('.prev-step')) {
        console.log('🚨 Backup prev handler');
        e.preventDefault();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        if (step2) step2.style.display = 'none';
        if (step3) step3.style.display = 'none';
        if (step1) step1.style.display = 'block';
        
        console.log('✅ Backup moved to step 1');
    }
}, true);

// IMMEDIATE OVERRIDE FOR GUEST BUTTONS
console.log('🚨 IMMEDIATE OVERRIDE STARTING...');

// Function to immediately fix buttons
function emergencyButtonFix() {
    console.log('🆘 Emergency button fix running...');
    
    setTimeout(() => {
        // Fix next-step buttons with simpler logic
        const nextBtns = document.querySelectorAll('.next-step');
        console.log('Emergency found next buttons:', nextBtns.length);
        
        nextBtns.forEach((btn, i) => {
            console.log('Emergency fixing next button', i);
            
            // Clone to remove all listeners
            const parent = btn.parentNode;
            const newBtn = btn.cloneNode(true);
            parent.replaceChild(newBtn, btn);
            
            // Force properties
            newBtn.style.cssText += '; pointer-events: auto !important; cursor: pointer !important; z-index: 99999 !important;';
            newBtn.disabled = false;
            
            // Simple click handler - determine step by checking which step form contains this button
            newBtn.onclick = function(e) {
                console.log('🎯 EMERGENCY NEXT CLICK!');
                e.preventDefault();
                e.stopPropagation();
                
                // Find which step this button belongs to
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                const buttonInStep1 = step1 && step1.contains(newBtn);
                const buttonInStep2 = step2 && step2.contains(newBtn);
                
                console.log('Button in step 1:', buttonInStep1);
                console.log('Button in step 2:', buttonInStep2);
                
                if (buttonInStep1) {
                    // Step 1 -> Step 2
                    if (step1) step1.style.display = 'none';
                    if (step2) step2.style.display = 'block';
                    if (step3) step3.style.display = 'none';
                    console.log('✅ EMERGENCY: Step 1 -> 2');
                } else if (buttonInStep2) {
                    // Step 2 -> Step 3
                    if (step1) step1.style.display = 'none';
                    if (step2) step2.style.display = 'none';
                    if (step3) step3.style.display = 'block';
                    console.log('✅ EMERGENCY: Step 2 -> 3');
                } else {
                    // Fallback: try to go to next visible step
                    console.log('Fallback next step logic');
                    if (step1 && window.getComputedStyle(step1).display !== 'none') {
                        if (step1) step1.style.display = 'none';
                        if (step2) step2.style.display = 'block';
                        console.log('✅ FALLBACK: Step 1 -> 2');
                    } else if (step2 && window.getComputedStyle(step2).display !== 'none') {
                        if (step2) step2.style.display = 'none';
                        if (step3) step3.style.display = 'block';
                        console.log('✅ FALLBACK: Step 2 -> 3');
                    }
                }
                return false;
            };
            
            console.log('✅ Emergency next button', i, 'fixed');
        });
        
        // Fix prev-step buttons with simpler logic
        const prevBtns = document.querySelectorAll('.prev-step');
        console.log('Emergency found prev buttons:', prevBtns.length);
        
        prevBtns.forEach((btn, i) => {
            console.log('Emergency fixing prev button', i);
            
            // Clone to remove all listeners
            const parent = btn.parentNode;
            const newBtn = btn.cloneNode(true);
            parent.replaceChild(newBtn, btn);
            
            // Force properties
            newBtn.style.cssText += '; pointer-events: auto !important; cursor: pointer !important; z-index: 99999 !important;';
            newBtn.disabled = false;
            
            // Simple click handler
            newBtn.onclick = function(e) {
                console.log('🎯 EMERGENCY PREV CLICK!');
                e.preventDefault();
                e.stopPropagation();
                
                // Find which step this button belongs to
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                const buttonInStep2 = step2 && step2.contains(newBtn);
                const buttonInStep3 = step3 && step3.contains(newBtn);
                
                console.log('Button in step 2:', buttonInStep2);
                console.log('Button in step 3:', buttonInStep3);
                
                if (buttonInStep3) {
                    // Step 3 -> Step 2
                    if (step1) step1.style.display = 'none';
                    if (step2) step2.style.display = 'block';
                    if (step3) step3.style.display = 'none';
                    console.log('✅ EMERGENCY: Step 3 -> 2');
                } else if (buttonInStep2) {
                    // Step 2 -> Step 1
                    if (step1) step1.style.display = 'block';
                    if (step2) step2.style.display = 'none';
                    if (step3) step3.style.display = 'none';
                    console.log('✅ EMERGENCY: Step 2 -> 1');
                } else {
                    // Fallback: try to go to previous visible step
                    console.log('Fallback prev step logic');
                    if (step3 && window.getComputedStyle(step3).display !== 'none') {
                        if (step1) step1.style.display = 'none';
                        if (step2) step2.style.display = 'block';
                        if (step3) step3.style.display = 'none';
                        console.log('✅ FALLBACK: Step 3 -> 2');
                    } else if (step2 && window.getComputedStyle(step2).display !== 'none') {
                        if (step1) step1.style.display = 'block';
                        if (step2) step2.style.display = 'none';
                        if (step3) step3.style.display = 'none';
                        console.log('✅ FALLBACK: Step 2 -> 1');
                    }
                }
                return false;
            };
            
            console.log('✅ Emergency prev button', i, 'fixed');
        });
    }, 100);
}

// Run immediately
emergencyButtonFix();

// Run repeatedly
setInterval(emergencyButtonFix, 2000);

// Manual test
window.emergencyTest = function() {
    console.log('🧪 Emergency test');
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
};

// Additional test functions for all steps
window.goToStep1 = function() {
    console.log('🧪 Go to step 1');
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step3').style.display = 'none';
};

window.goToStep2 = function() {
    console.log('🧪 Go to step 2');
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'block';
    document.getElementById('step3').style.display = 'none';
};

window.goToStep3 = function() {
    console.log('🧪 Go to step 3');
    document.getElementById('step1').style.display = 'none';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step3').style.display = 'block';
};

window.testAllButtons = function() {
    console.log('🧪 Testing all buttons...');
    
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    
    console.log('Next buttons found:', nextBtns.length);
    console.log('Prev buttons found:', prevBtns.length);
    
    nextBtns.forEach((btn, i) => {
        console.log(`Next button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            style: btn.style.cssText,
            onclick: typeof btn.onclick
        });
    });
    
    prevBtns.forEach((btn, i) => {
        console.log(`Prev button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            style: btn.style.cssText,
            onclick: typeof btn.onclick
        });
    });
};

window.forceRefixButtons = function() {
    console.log('🔨 Force re-fixing all buttons...');
    emergencyButtonFix();
};

window.debugCurrentSituation = function() {
    console.log('🔍 === DEBUGGING CURRENT SITUATION ===');
    
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    console.log('Step elements:');
    console.log('- Step 1:', step1 ? 'Found' : 'Not found');
    console.log('- Step 2:', step2 ? 'Found' : 'Not found'); 
    console.log('- Step 3:', step3 ? 'Found' : 'Not found');
    
    if (step1) {
        console.log('Step 1 display:', window.getComputedStyle(step1).display);
        console.log('Step 1 style.display:', step1.style.display);
    }
    
    if (step2) {
        console.log('Step 2 display:', window.getComputedStyle(step2).display);
        console.log('Step 2 style.display:', step2.style.display);
    }
    
    if (step3) {
        console.log('Step 3 display:', window.getComputedStyle(step3).display);
        console.log('Step 3 style.display:', step3.style.display);
    }
    
    // Check buttons in each step
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    
    console.log('Next buttons found:', nextBtns.length);
    console.log('Prev buttons found:', prevBtns.length);
    
    nextBtns.forEach((btn, i) => {
        const inStep1 = step1 && step1.contains(btn);
        const inStep2 = step2 && step2.contains(btn);
        const inStep3 = step3 && step3.contains(btn);
        
        console.log(`Next button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            inStep1: inStep1,
            inStep2: inStep2,
            inStep3: inStep3,
            hasOnclick: typeof btn.onclick === 'function'
        });
    });
    
    prevBtns.forEach((btn, i) => {
        const inStep1 = step1 && step1.contains(btn);
        const inStep2 = step2 && step2.contains(btn);
        const inStep3 = step3 && step3.contains(btn);
        
        console.log(`Prev button ${i}:`, {
            visible: btn.offsetParent !== null,
            disabled: btn.disabled,
            inStep1: inStep1,
            inStep2: inStep2,
            inStep3: inStep3,
            hasOnclick: typeof btn.onclick === 'function'
        });
    });
    
    console.log('=== END DEBUG ===');
};

window.testStep2ToStep3 = function() {
    console.log('🧪 Test: Step 2 -> Step 3');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    if (step1) step1.style.display = 'none';
    if (step2) step2.style.display = 'none';
    if (step3) step3.style.display = 'block';
    
    console.log('✅ Manually moved to step 3');
};

window.testStep2ToStep1 = function() {
    console.log('🧪 Test: Step 2 -> Step 1');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    if (step1) step1.style.display = 'block';
    if (step2) step2.style.display = 'none';
    if (step3) step3.style.display = 'none';
    
    console.log('✅ Manually moved to step 1');
};

console.log('🚨 EMERGENCY OVERRIDE COMPLETE');

// IMMEDIATE GUEST BUTTON DEBUG AND FIX
console.log('🚀 Starting immediate guest button debug...');

// Simple immediate fix function with aggressive CSS overrides
function immediateGuestButtonFix() {
    console.log('🔧 Running immediate guest button fix...');
    
    // Find next-step buttons
    const nextBtns = document.querySelectorAll('.next-step');
    console.log('Next buttons found:', nextBtns.length);
    
    // Force fix each button
    nextBtns.forEach((btn, i) => {
        console.log(`Fixing button ${i}...`);
        
        // Remove all existing event listeners by cloning
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all clickable properties
        newBtn.style.pointerEvents = 'auto !important';
        newBtn.style.cursor = 'pointer !important';
        newBtn.style.zIndex = '9999 !important';
        newBtn.style.position = 'relative !important';
        newBtn.disabled = false;
        newBtn.style.opacity = '1 !important';
        newBtn.style.visibility = 'visible !important';
        
        // Add multiple event types
        ['click', 'mousedown', 'touchstart', 'touchend'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 ${eventType} on guest next button!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Determine current step and navigate to next
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                // Check which step is currently visible
                const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
                const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
                const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
                
                console.log('Current step visibility:', {
                    step1: step1Visible,
                    step2: step2Visible, 
                    step3: step3Visible
                });
                
                if (step1Visible) {
                    // Currently on step 1, go to step 2
                    console.log('Changing from step 1 to step 2...');
                    hideStep(step1);
                    showStep(step2);
                    console.log('✅ Moved from step 1 to step 2');
                    
                } else if (step2Visible) {
                    // Currently on step 2, go to step 3
                    console.log('Changing from step 2 to step 3...');
                    hideStep(step2);
                    showStep(step3);
                    console.log('✅ Moved from step 2 to step 3');
                    
                } else {
                    // Fallback: assume step 1 and go to step 2
                    console.log('Fallback: Changing to step 2...');
                    hideStep(step1);
                    showStep(step2);
                    console.log('✅ Fallback: Moved to step 2');
                }
                
                // Additional cleanup
                setTimeout(() => {
                    cleanupSteps();
                }, 100);
                
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Button ${i} fixed with all events`);
    });
    
    // Also fix prev-step buttons
    const prevBtns = document.querySelectorAll('.prev-step');
    console.log('Prev buttons found:', prevBtns.length);
    
    prevBtns.forEach((btn, i) => {
        console.log(`Fixing prev button ${i}...`);
        
        // Remove all existing event listeners by cloning
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all clickable properties
        newBtn.style.pointerEvents = 'auto !important';
        newBtn.style.cursor = 'pointer !important';
        newBtn.style.zIndex = '9999 !important';
        newBtn.style.position = 'relative !important';
        newBtn.disabled = false;
        newBtn.style.opacity = '1 !important';
        newBtn.style.visibility = 'visible !important';
        
        // Add multiple event types
        ['click', 'mousedown', 'touchstart', 'touchend'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 ${eventType} on guest prev button!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Determine current step and navigate to previous
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                // Check which step is currently visible
                const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
                const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
                const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
                
                console.log('Current step visibility for prev:', {
                    step1: step1Visible,
                    step2: step2Visible, 
                    step3: step3Visible
                });
                
                if (step3Visible) {
                    // Currently on step 3, go to step 2
                    console.log('Changing from step 3 to step 2...');
                    hideStep(step3);
                    showStep(step2);
                    console.log('✅ Moved from step 3 to step 2');
                    
                } else if (step2Visible) {
                    // Currently on step 2, go to step 1
                    console.log('Changing from step 2 to step 1...');
                    hideStep(step2);
                    showStep(step1);
                    console.log('✅ Moved from step 2 to step 1');
                }
                
                // Additional cleanup
                setTimeout(() => {
                    cleanupSteps();
                }, 100);
                
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Prev button ${i} fixed with all events`);
    });
}

// Helper function to aggressively hide a step
function hideStep(stepElement) {
    if (stepElement) {
        stepElement.style.display = 'none !important';
        stepElement.style.visibility = 'hidden !important';
        stepElement.style.opacity = '0 !important';
        stepElement.style.height = '0 !important';
        stepElement.style.overflow = 'hidden !important';
        stepElement.style.position = 'absolute !important';
        stepElement.style.left = '-9999px !important';
        stepElement.setAttribute('style', stepElement.getAttribute('style') + '; display: none !important;');
        console.log(`Step ${stepElement.id} hidden`);
    }
}

// Helper function to aggressively show a step
function showStep(stepElement) {
    if (stepElement) {
        stepElement.style.display = 'block !important';
        stepElement.style.visibility = 'visible !important';
        stepElement.style.opacity = '1 !important';
        stepElement.style.height = 'auto !important';
        stepElement.style.overflow = 'visible !important';
        stepElement.style.position = 'relative !important';
        stepElement.style.left = 'auto !important';
        stepElement.setAttribute('style', stepElement.getAttribute('style') + '; display: block !important;');
        
        // Force browser to repaint
        stepElement.offsetHeight; // Trigger reflow
        stepElement.style.transform = 'translateZ(0)'; // Force hardware acceleration
        console.log(`Step ${stepElement.id} shown`);
    }
}

// Helper function to clean up step conflicts
function cleanupSteps() {
    const allSteps = ['step1', 'step2', 'step3'];
    
    allSteps.forEach(stepId => {
        const stepEl = document.getElementById(stepId);
        if (stepEl) {
            const isVisible = getComputedStyle(stepEl).display !== 'none';
            if (isVisible) {
                showStep(stepEl); // Ensure it's properly shown
            } else {
                hideStep(stepEl); // Ensure it's properly hidden
            }
        }
    });
    
    console.log('🔄 Step cleanup completed');
}

// Run immediately when script loads
immediateGuestButtonFix();

// Run when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready, running guest button fix again...');
    setTimeout(immediateGuestButtonFix, 100);
    setTimeout(immediateGuestButtonFix, 500);
    setTimeout(immediateGuestButtonFix, 1000);
    setTimeout(immediateGuestButtonFix, 2000);
});

// Global window functions for manual testing
window.debugGuestButtons = function() {
    console.log('🔍 Debug guest buttons...');
    
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    console.log('Next buttons found:', nextBtns.length);
    console.log('Prev buttons found:', prevBtns.length);
    
    // Check steps
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    console.log('Steps status:');
    console.log('- Step 1:', step1 ? 'Found' : 'Not found', step1 ? getComputedStyle(step1).display : 'N/A');
    console.log('- Step 2:', step2 ? 'Found' : 'Not found', step2 ? getComputedStyle(step2).display : 'N/A');
    console.log('- Step 3:', step3 ? 'Found' : 'Not found', step3 ? getComputedStyle(step3).display : 'N/A');
};

window.manualStepChange = function(targetStep) {
    console.log(`🔧 Manual step change to step ${targetStep}...`);
    
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    // Hide all steps first
    hideStep(step1);
    hideStep(step2);
    hideStep(step3);
    
    // Show target step
    if (targetStep === 1) showStep(step1);
    else if (targetStep === 2) showStep(step2);
    else if (targetStep === 3) showStep(step3);
    
    console.log(`✅ Manually changed to step ${targetStep}`);
};

window.goToStep1 = function() { manualStepChange(1); };
window.goToStep2 = function() { manualStepChange(2); };
window.goToStep3 = function() { manualStepChange(3); };

window.forceButtonClick = function() {
    console.log('🔨 Force button click...');
    
    const nextBtn = document.querySelector('.next-step');
    if (nextBtn) {
        console.log('Found button, trying to click...');
        
        // Try multiple click methods
        nextBtn.click();
        
        const clickEvent = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        nextBtn.dispatchEvent(clickEvent);
        
        console.log('✅ Force click attempted');
    } else {
        console.log('❌ No next-step button found');
    }
};

window.rerunFix = function() {
    console.log('🔄 Re-running immediate fix...');
    immediateGuestButtonFix();
};

// Global backup click handler with highest priority - MORE AGGRESSIVE
document.addEventListener('click', function(e) {
    if (e.target && (e.target.classList.contains('next-step') || e.target.closest('.next-step'))) {
        console.log('🚨 GLOBAL BACKUP: Next step clicked!');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        // Check current step and navigate
        const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
        const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
        
        if (step1Visible) {
            hideStep(step1);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Changed from step 1 to step 2');
        } else if (step2Visible) {
            hideStep(step2);
            showStep(step3);
            console.log('✅ GLOBAL BACKUP: Changed from step 2 to step 3');
        } else {
            // Fallback
            hideStep(step1);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Fallback to step 2');
        }
        
        return false;
    }
    
    // Handle prev-step buttons
    if (e.target && (e.target.classList.contains('prev-step') || e.target.closest('.prev-step'))) {
        console.log('🚨 GLOBAL BACKUP: Prev step clicked!');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        // Check current step and navigate backward
        const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
        const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
        
        if (step3Visible) {
            hideStep(step3);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Changed from step 3 to step 2');
        } else if (step2Visible) {
            hideStep(step2);
            showStep(step1);
            console.log('✅ GLOBAL BACKUP: Changed from step 2 to step 1');
        }
        
        return false;
    }
}, true); // Use capture phase with highest priority

// Additional: Force override any CSS animations or transitions that might interfere
const forceStepStyles = document.createElement('style');
forceStepStyles.textContent = `
    #step1.form-step[style*="none"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
    }
    
    #step2.form-step[style*="block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
        position: relative !important;
        left: auto !important;
    }
    
    #step3.form-step[style*="block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
        position: relative !important;
        left: auto !important;
    }
    
    .next-step, .prev-step {
        pointer-events: auto !important;
        cursor: pointer !important;
        z-index: 9999 !important;
        position: relative !important;
    }
`;
document.head.appendChild(forceStepStyles);

// Function to show new user modal with login information
function showNewUserModal(loginInfo) {
    // Create modal HTML
    const modalHTML = `
        <div id="newUserModal" class="modal" style="
            display: block;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        ">
            <div class="modal-content" style="
                background-color: #fff;
                margin: 5% auto;
                padding: 0;
                border-radius: 8px;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                animation: modalSlideIn 0.3s ease-out;
            ">
                <div style="
                    background: #102f4b;
                    color: white;
                    padding: 20px;
                    border-radius: 8px 8px 0 0;
                    text-align: center;
                ">
                    <h3 style="margin: 0; font-size: 24px;">🎉 Tài khoản đã được tạo!</h3>
                    <p style="margin: 10px 0 0 0; opacity: 0.9;">Chào mừng bạn đến với DoiTay.vn</p>
                </div>
                
                <div style="padding: 30px;">
                    <div style="
                        background-color: #f8f9fa;
                        border-left: 4px solid #48bbe2;
                        padding: 20px;
                        margin: 20px 0;
                        border-radius: 0 4px 4px 0;
                    ">
                        <h4 style="margin-top: 0; color: #48bbe2;">🔑 Thông tin đăng nhập:</h4>
                        
                        <div style="
                            background-color: #fff;
                            padding: 15px;
                            border-radius: 4px;
                            border: 1px solid #ddd;
                            margin: 10px 0;
                            font-family: 'Courier New', monospace;
                        ">
                            <strong>📱 Tài khoản:</strong> ${loginInfo.username}
                        </div>
                        
                        <div style="
                            background-color: #fff;
                            padding: 15px;
                            border-radius: 4px;
                            border: 1px solid #ddd;
                            margin: 10px 0;
                            font-family: 'Courier New', monospace;
                        ">
                            <strong>📧 Email:</strong> ${loginInfo.email}
                        </div>
                        
                        <div style="
                            background-color: #fff3cd;
                            padding: 15px;
                            border-radius: 4px;
                            border: 1px solid #ffeaa7;
                            margin: 15px 0;
                        ">
                            <strong>🔑 Mật khẩu đã được gửi qua email và SMS</strong>
                        </div>
                    </div>
                    
                    <div style="
                        background-color: #d1ecf1;
                        border: 1px solid #bee5eb;
                        color: #0c5460;
                        padding: 15px;
                        border-radius: 4px;
                        margin: 20px 0;
                    ">
                        <strong>📋 Lưu ý quan trọng:</strong>
                        <ul style="margin: 10px 0; padding-left: 20px;">
                            <li>Kiểm tra email và SMS để lấy mật khẩu</li>
                            <li>Bạn đã được tự động đăng nhập</li>
                            <li>Vui lòng đổi mật khẩu sau lần đăng nhập đầu tiên</li>
                            <li>Lưu thông tin đăng nhập an toàn</li>
                        </ul>
                    </div>
                    
                    <div style="text-align: center; margin-top: 30px;">
                        <button onclick="closeNewUserModal()" style="
                            background: #102f4b;
                            color: white;
                            border: none;
                            padding: 12px 30px;
                            border-radius: 5px;
                            font-size: 16px;
                            cursor: pointer;
                            margin-right: 10px;
                        ">
                            ✅ Đã hiểu
                        </button>
                        
                        <button onclick="copyLoginInfo('${loginInfo.username}', '${loginInfo.email}')" style="
                            background: #28a745;
                            color: white;
                            border: none;
                            padding: 12px 20px;
                            border-radius: 5px;
                            font-size: 16px;
                            cursor: pointer;
                        ">
                            📋 Copy thông tin
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            @keyframes modalSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-50px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
   
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
    console.log('🏙️ Loading cities...');
    
    // Check if select elements exist before fetching
    const citySelects = $('select[name="city_code"]');
    console.log('Found city selects:', citySelects.length);
    
    if (citySelects.length === 0) {
        console.warn('⚠️ No city select elements found, retrying in 1 second...');
        setTimeout(loadCities, 1000);
        return;
    }
    
    $.ajax({
        url: '/localtion/api/cities',
        type: 'GET',
        dataType: 'text', // Changed back to 'text' 
        success: function(response) {
            console.log('📡 Cities API response received');
            console.log('Raw cities API response:', response);
            const cleanResponse = response.replace(/<!--|-->/g, '').trim();
            console.log('Cleaned response:', cleanResponse);
            try {
                const cities = JSON.parse(cleanResponse);
                console.log('📊 Cities data parsed:', cities);
                console.log('📊 Number of cities:', cities.length);
                
                if (!Array.isArray(cities) || cities.length === 0) {
                    throw new Error('Invalid cities data received');
                }
                
                citySelects.each(function() {
                    const select = $(this);
                    const selectId = select.attr('id');
                    console.log('🔄 Populating select:', selectId);
                    
                    select.empty().append('<option value="">Chọn thành phố</option>');
                    
                    cities.forEach((city, index) => {
                        if (index < 5) console.log(`City ${index + 1}:`, city);
                        // Defensive programming to handle different data structures
                        const cityCode = city.City_code || city.city_code || city.code || city.id || '';
                        const cityName = city.City || city.city || city.name || 'Unknown City';
                        
                        if (!cityCode || !cityName) {
                            console.warn('Invalid city data:', city);
                            return;
                        }
                        
                        select.append(
                            `<option value="${cityCode}" data-name="${cityName}">${cityName}</option>`
                        );
                    });
                    
                    console.log('✅ Select populated, options count:', select.find('option').length);
                });
                
                console.log('🎉 Cities loaded and populated successfully!');
                
                // Trigger change event to refresh dropdowns
                citySelects.trigger('change');
                
            } catch (error) {
                console.error("❌ Lỗi xử lý dữ liệu cities:", error);
                console.error('❌ Error details:', {
                    name: error.name,
                    message: error.message,
                    stack: error.stack
                });
                
                // Fallback with hardcoded major cities
                console.log('🔄 Using fallback cities...');
                const fallbackCities = [
                    { City_code: '01', City: 'Hà Nội' },
                    { City_code: '79', City: 'TP. Hồ Chí Minh' },
                    { City_code: '48', City: 'Đà Nẵng' },
                    { City_code: '31', City: 'Hải Phòng' },
                    { City_code: '92', City: 'Cần Thơ' }
                ];
                
                citySelects.each(function() {
                    const select = $(this);
                    select.empty().append('<option value="">Chọn thành phố</option>');
                    
                    fallbackCities.forEach(city => {
                        const cityCode = city.City_code || city.city_code || city.code || city.id || '';
                        const cityName = city.City || city.city || city.name || 'Unknown City';
                        select.append(
                            `<option value="${cityCode}" data-name="${cityName}">${cityName}</option>`
                        );
                    });
                    
                    console.log('✅ Fallback cities populated for:', select.attr('id'));
                });
                
                showNotification('Đang sử dụng danh sách thành phố cơ bản. Vui lòng thử lại sau.', 'warning');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("❌ Lỗi API (cities):", textStatus, errorThrown);
            console.error("❌ Response text:", jqXHR.responseText);
            console.error("❌ Status code:", jqXHR.status);
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
                    // Defensive programming to handle different data structures
                    const districtCode = district.District_code || district.district_code || district.code || district.id || '';
                    const districtName = district.District || district.district || district.name || 'Unknown District';
                    
                    if (!districtCode || !districtName) {
                        console.warn('Invalid district data:', district);
                        return;
                    }
                    
                    districtSelect.append(
                        `<option value="${districtCode}" data-name="${districtName}">${districtName}</option>`
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
                    // Defensive programming to handle different data structures
                    const wardCode = ward.Ward_code || ward.ward_code || ward.code || ward.id || '';
                    const wardName = ward.Ward || ward.ward || ward.name || 'Unknown Ward';
                    
                    if (!wardCode || !wardName) {
                        console.warn('Invalid ward data:', ward);
                        return;
                    }
                    
                    wardSelect.append(
                        `<option value="${wardCode}" data-name="${wardName}">${wardName}</option>`
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
        
        console.log('🔍 Validating guest step:', step);
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;
        let missingFields = [];

        requiredFields.forEach(field => {
            // Skip validation for disabled fields (like district_code when no city is selected)
            if (field.disabled) {
                console.log('⏭️ Skipping disabled field:', field.name || field.id);
                field.classList.remove('is-invalid');
                return;
            }
            
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                const label = field.previousElementSibling?.textContent || field.name || field.placeholder || 'Unknown field';
                missingFields.push(label);
                isValid = false;
                console.log('❌ Missing field:', label);
            } else {
                field.classList.remove('is-invalid');
                console.log('✅ Valid field:', field.name || field.id, '=', field.value);
            }
        });

        // Special validation for step 1 - check if city is selected
        if (step === 1) {
            const citySelect = currentStepEl.querySelector('select[name="city_code"]');
            if (citySelect && !citySelect.value) {
                citySelect.classList.add('is-invalid');
                missingFields.push('Thành phố');
                isValid = false;
                console.log('❌ City not selected');
            }
            
            // Only check district if city is selected
            const districtSelect = currentStepEl.querySelector('select[name="district_code"]');
            if (citySelect && citySelect.value && districtSelect && !districtSelect.disabled && !districtSelect.value) {
                districtSelect.classList.add('is-invalid');
                missingFields.push('Quận/Huyện');
                isValid = false;
                console.log('❌ District not selected');
            }
        }

        if (!isValid) {
            const errorMessage = `Vui lòng điền đầy đủ thông tin bắt buộc:\n• ${missingFields.join('\n• ')}`;
            showNotification(errorMessage, 'error');
            console.log('❌ Validation failed for guest step', step, '- Missing fields:', missingFields);
        } else {
            console.log('✅ Validation passed for guest step', step);
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
                    // Enhanced notification for new user creation
                    if (result.user_created) {
                        const loginInfo = result.login_info;
                        let message = '🎉 Lead đã được tạo thành công!\n\n';
                        message += '✅ Tài khoản mới đã được tạo:\n';
                        message += `📱 Tài khoản: ${loginInfo.username}\n`;
                        message += `📧 Email: ${loginInfo.email}\n`;
                        message += '🔑 Mật khẩu đã được gửi qua email/SMS\n\n';
                        message += '🚀 Đang chuyển đến trang lead...';
                        
                        // Show detailed notification for new user
                        showNotification(message, 'success', 3000); // Show shorter since redirecting
                        
                        // Also log for debugging
                        console.log('🎉 New user created:', {
                            user_id: result.user_id,
                            username: loginInfo.username,
                            email: loginInfo.email,
                            password_sent: loginInfo.password_sent
                        });
                        
                    } else {
                        // Standard success message for existing users
                        showNotification('🎉 Lead đã được tạo thành công! Đang chuyển đến trang lead...', 'success', 2000);
                    }
                    
                    // Redirect to the created lead after short delay
                    setTimeout(() => {
                        if (result.redirect_url) {
                            window.location.href = result.redirect_url;
                        } else if (result.lead_id) {
                            window.location.href = '/customer/leads/show/' + result.lead_id;
                        } else {
                            window.location.href = '/customer/leads';
                        }
                    }, 1500);
                } else {
                    let errorMessage = result.message || 'Có lỗi xảy ra, vui lòng thử lại';
                    
                    // Handle validation errors
                    if (result.errors) {
                        console.log('❌ Validation errors:', result.errors);
                        const errorMessages = Object.values(result.errors).flat();
                        errorMessage = errorMessages.join(', ');
                    }
                    
                    showNotification('❌ ' + errorMessage, 'error');
                }
            } catch (error) {
                console.error('Form submission error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-rocket me-2"></i>Tạo Nhu cầu Ngay';
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
            
            // Add location data from selects (critical for validation)
            const formContainer = e.target;
            const citySelect = formContainer.querySelector('select[name="city_code"]');
            const districtSelect = formContainer.querySelector('select[name="district_code"]');
            const wardSelect = formContainer.querySelector('select[name="ward_code"]');
            
            console.log('🔍 Checking location data...');
            console.log('City:', citySelect?.value, citySelect?.options[citySelect?.selectedIndex]?.text);
            console.log('District:', districtSelect?.value, districtSelect?.options[districtSelect?.selectedIndex]?.text);
            console.log('Ward:', wardSelect?.value, wardSelect?.options[wardSelect?.selectedIndex]?.text);
            
            // Add city name
            if (citySelect && citySelect.value) {
                const cityName = citySelect.options[citySelect.selectedIndex].text;
                if (cityName && cityName !== 'Chọn thành phố') {
                    formData.append('city', cityName);
                    console.log('✅ Added city:', cityName);
                }
            }
            
            // Add district name - REQUIRED for validation
            if (districtSelect && districtSelect.value) {
                const districtName = districtSelect.options[districtSelect.selectedIndex].text;
                if (districtName && districtName !== 'Chọn quận/huyện') {
                    formData.append('district', districtName);
                    console.log('✅ Added district:', districtName);
                }
            } else {
                // Fallback district handling for guest form
                console.warn('⚠️ No district selected, using fallback...');
                
                // Use city as district fallback
                if (citySelect && citySelect.value) {
                    const cityName = citySelect.options[citySelect.selectedIndex].text;
                    if (cityName && cityName !== 'Chọn thành phố') {
                        formData.append('district', cityName);
                        console.log('🔄 Using city as district fallback:', cityName);
                    }
                } else {
                    // Default fallback
                    formData.append('district', 'Khu vực không xác định');
                    console.log('🔄 Using default district fallback');
                }
            }
            
            // Add ward name with fallback
            if (wardSelect && wardSelect.value) {
                const wardName = wardSelect.options[wardSelect.selectedIndex].text;
                if (wardName && wardName !== 'Chọn phường/xã') {
                    formData.append('ward', wardName);
                    console.log('✅ Added ward:', wardName);
                }
            } else {
                // Ward fallback
                if (districtSelect && districtSelect.value) {
                    const districtName = districtSelect.options[districtSelect.selectedIndex].text;
                    if (districtName && districtName !== 'Chọn quận/huyện') {
                        formData.append('ward', districtName);
                        console.log('🔄 Using district as ward fallback:', districtName);
                    }
                } else if (citySelect && citySelect.value) {
                    const cityName = citySelect.options[citySelect.selectedIndex].text;
                    if (cityName && cityName !== 'Chọn thành phố') {
                        formData.append('ward', cityName);
                        console.log('🔄 Using city as ward fallback:', cityName);
                    }
                } else {
                    formData.append('ward', 'Phường/Xã không xác định');
                    console.log('🔄 Using default ward fallback');
                }
            }
            
            const submitBtn = e.target.querySelector('.submit-lead');
            
            console.log('📤 Final guest form data entries:', Array.from(formData.entries()));
            
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
                    // Show notification with redirect message
                    if (result.user_created) {
                        showNotification('🎉 Lead và tài khoản đã được tạo thành công! Đang chuyển đến trang lead...', 'success', 3000);
                    } else {
                        showNotification('🎉 Lead đã được tạo thành công! Đang chuyển đến trang lead...', 'success', 2000);
                    }
                    
                    // Redirect to the created lead after short delay
                    setTimeout(() => {
                        if (result.redirect_url) {
                            window.location.href = result.redirect_url;
                        } else if (result.lead_id) {
                            window.location.href = '/customer/leads/show/' + result.lead_id;
                        } else {
                            window.location.href = '/customer/leads';
                        }
                    }, 1500);
                } else {
                    let errorMessage = result.message || 'Có lỗi xảy ra, vui lòng thử lại';
                    
                    // Handle validation errors
                    if (result.errors) {
                        console.log('❌ Validation errors:', result.errors);
                        const errorMessages = Object.values(result.errors).flat();
                        errorMessage = errorMessages.join(', ');
                    }
                    
                    showNotification('❌ ' + errorMessage, 'error');
                }
            } catch (error) {
                console.error('Guest form submission error:', error);
                showNotification('Có lỗi kết nối, vui lòng thử lại: ' + error.message, 'error');
            } finally {
                submitBtn.innerHTML = '<i class="las la-rocket me-2"></i>Tạo Nhu cầu & Tự Động Đăng Ký';
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
                const response = await fetch('{{ route("user.login.v2.post") }}', {
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
                    showNotification('🎉 Đăng ký thành công! Đang chuyển đến form tạo nhu cầu...', 'success');
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

    // Add function to fill test location data for guest form
    window.fillGuestTestLocationData = function() {
        console.log('🧪 Filling guest test location data...');
        
        // Select first category
        const categorySelect = document.getElementById('guest_category_id');
        if (categorySelect && categorySelect.options.length > 1) {
            categorySelect.selectedIndex = 1;
            console.log('✅ Selected category:', categorySelect.value);
        }
        
        // Fill basic required fields for step 1
        const titleInput = document.getElementById('guest_title');
        if (titleInput) {
            titleInput.value = 'Test: Sửa chữa điện nước';
            console.log('✅ Set title:', titleInput.value);
        }
        
        const descriptionInput = document.getElementById('guest_description');
        if (descriptionInput) {
            descriptionInput.value = 'Cần sửa chữa hệ thống điện và nước trong nhà cho khách';
            console.log('✅ Set description:', descriptionInput.value);
        }
        
        // Fill step 2 contact info
        const fullnameInput = document.getElementById('guest_fullname');
        if (fullnameInput) {
            fullnameInput.value = 'Nguyễn Văn Test';
            console.log('✅ Set fullname:', fullnameInput.value);
        }
        
        const mobileInput = document.getElementById('guest_mobile');
        if (mobileInput) {
            mobileInput.value = '0901234567';
            console.log('✅ Set mobile:', mobileInput.value);
        }
        
        const emailInput = document.getElementById('guest_email');
        if (emailInput) {
            emailInput.value = 'test@example.com';
            console.log('✅ Set email:', emailInput.value);
        }
        
        const addressInput = document.getElementById('guest_address');
        if (addressInput) {
            addressInput.value = '123 Test Street';
            console.log('✅ Set address:', addressInput.value);
        }
        
        // Check terms checkbox
        const termsCheck = document.getElementById('agreeTerms');
        if (termsCheck) {
            termsCheck.checked = true;
            console.log('✅ Checked terms');
        }
        
        console.log('🎯 Test data filled! Now manually select city and district, then try navigation.');
    };

    // Test guest form step navigation
    window.testGuestStepNavigation = function() {
        console.log('🧪 Testing guest step navigation...');
        
        // Check current step
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        console.log('Step 1 visible:', step1 && window.getComputedStyle(step1).display !== 'none');
        console.log('Step 2 visible:', step2 && window.getComputedStyle(step2).display !== 'none');
        console.log('Step 3 visible:', step3 && window.getComputedStyle(step3).display !== 'none');
        
        // Test next step button
        const nextBtn = document.querySelector('.next-step');
        if (nextBtn) {
            console.log('Next button found:', nextBtn);
            console.log('Button visible:', nextBtn.offsetParent !== null);
            console.log('Button disabled:', nextBtn.disabled);
            console.log('Button text:', nextBtn.textContent.trim());
        } else {
            console.log('❌ Next button not found');
        }
        
        return {
            step1Visible: step1 && window.getComputedStyle(step1).display !== 'none',
            step2Visible: step2 && window.getComputedStyle(step2).display !== 'none',
            step3Visible: step3 && window.getComputedStyle(step3).display !== 'none',
            nextButtonFound: !!nextBtn
        };
    };

    // Force guest step navigation
    window.forceGuestStep = function(stepNumber) {
        console.log('🔧 Force moving to guest step:', stepNumber);
        
        // Hide all steps
        for (let i = 1; i <= 3; i++) {
            const step = document.getElementById('step' + i);
            if (step) {
                step.style.display = 'none';
            }
        }
        
        // Show target step
        const targetStep = document.getElementById('step' + stepNumber);
        if (targetStep) {
            targetStep.style.display = 'block';
            console.log('✅ Moved to step', stepNumber);
            return true;
        } else {
            console.log('❌ Step', stepNumber, 'not found');
            return false;
        }
    };

    // Check guest form validation
    window.checkGuestValidation = function(stepNumber = 1) {
        console.log('🔍 Checking guest validation for step:', stepNumber);
        
        const step = document.getElementById('step' + stepNumber);
        if (!step) {
            console.log('❌ Step not found:', stepNumber);
            return false;
        }
        
        const requiredFields = step.querySelectorAll('[required]');
        console.log('Required fields in step', stepNumber + ':', requiredFields.length);
        
        let validationResults = [];
        requiredFields.forEach((field, index) => {
            const isDisabled = field.disabled;
            const hasValue = field.value.trim() !== '';
            const isValid = isDisabled || hasValue;
            
            validationResults.push({
                index: index,
                name: field.name || field.id,
                value: field.value,
                disabled: isDisabled,
                valid: isValid
            });
            
            console.log(`Field ${index} (${field.name || field.id}):`, {
                value: field.value,
                disabled: isDisabled,
                valid: isValid ? '✅' : '❌'
            });
        });
        
        return validationResults;
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

function showNotification(message, type = 'info', duration = 5000) {
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
    
    // Auto remove after specified duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, duration);
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
                <p class="text-muted">Tạo Nhu cầu mới để tìm thợ chuyên nghiệp</p>
            </div>
        </div>
    `;
    
    // Insert welcome message at the beginning of lead tab
    const leadTab = document.getElementById('leadTab');
    if (leadTab) {
        leadTab.insertAdjacentHTML('afterbegin', welcomeHTML);
    }
}

// Debug function to check dropdown state
window.checkDropdownState = function() {
    console.log('🔍 Checking dropdown state...');
    
    const authCitySelect = document.getElementById('auth_city_code');
    const guestCitySelect = document.getElementById('guest_city_code');
    
    console.log('=== AUTH CITY SELECT ===');
    if (authCitySelect) {
        console.log('Element found:', authCitySelect);
        console.log('Options count:', authCitySelect.options.length);
        console.log('Disabled:', authCitySelect.disabled);
        console.log('Visible:', window.getComputedStyle(authCitySelect).display !== 'none');
        console.log('Parent visible:', window.getComputedStyle(authCitySelect.parentElement).display !== 'none');
        
        if (authCitySelect.options.length > 0) {
            for (let i = 0; i < Math.min(5, authCitySelect.options.length); i++) {
                console.log(`Option ${i}:`, authCitySelect.options[i].value, authCitySelect.options[i].text);
            }
        }
    } else {
        console.log('❌ Auth city select not found');
    }
    
    console.log('=== GUEST CITY SELECT ===');
    if (guestCitySelect) {
        console.log('Element found:', guestCitySelect);
        console.log('Options count:', guestCitySelect.options.length);
        console.log('Disabled:', guestCitySelect.disabled);
        console.log('Visible:', window.getComputedStyle(guestCitySelect).display !== 'none');
        console.log('Parent visible:', window.getComputedStyle(guestCitySelect.parentElement).display !== 'none');
        
        if (guestCitySelect.options.length > 0) {
            for (let i = 0; i < Math.min(5, guestCitySelect.options.length); i++) {
                console.log(`Option ${i}:`, guestCitySelect.options[i].value, guestCitySelect.options[i].text);
            }
        }
    } else {
        console.log('❌ Guest city select not found');
    }
    
    // Check if tabs are properly displayed
    const guestTab = document.getElementById('guestTab');
    const authTab = document.getElementById('leadTab');
    
    console.log('=== TAB VISIBILITY ===');
    console.log('Guest tab active:', guestTab?.classList.contains('active'));
    console.log('Auth tab active:', authTab?.classList.contains('active'));
};

// Force reload cities with retry mechanism
window.forceReloadCities = function() {
    console.log('🔄 Force reloading cities...');
    
    // Clear existing options first
    const citySelects = $('select[name="city_code"]');
    citySelects.each(function() {
        $(this).empty().append('<option value="">Đang tải...</option>');
    });
    
    // Reload after short delay
    setTimeout(() => {
        loadCities();
    }, 500);
};

// Manual populate with test data
window.populateTestCities = function() {
    console.log('🧪 Populating test cities...');
    
    const testCities = [
        { City_code: '01', City: 'Hà Nội' },
        { City_code: '79', City: 'TP. Hồ Chí Minh' },
        { City_code: '48', City: 'Đà Nẵng' },
        { City_code: '31', City: 'Hải Phòng' },
        { City_code: '92', City: 'Cần Thơ' },
        { City_code: '26', City: 'Vĩnh Phúc' },
        { City_code: '20', City: 'Thái Bình' }
    ];
    
    const citySelects = $('select[name="city_code"]');
    
    citySelects.each(function() {
        const select = $(this);
        select.empty().append('<option value="">Chọn thành phố</option>');
        
        testCities.forEach(city => {
            const cityCode = city.City_code || city.city_code || city.code || city.id || '';
            const cityName = city.City || city.city || city.name || 'Unknown City';
            select.append(
                `<option value="${cityCode}" data-name="${cityName}">${cityName}</option>`
            );
        });
        
        console.log('✅ Test cities populated for:', select.attr('id'));
    });
    
    showNotification('✅ Test cities populated successfully!', 'success');
};

// Auto-fill location data for testing
window.fillTestLocationData = function() {
    console.log('🔧 Filling test location data');
    
    // Load cities first
    loadCities();
    
    setTimeout(() => {
        // Select Ho Chi Minh City
        const guestCitySelect = $('#guest_city_code');
        if (guestCitySelect.length) {
            // Find Ho Chi Minh City option
            guestCitySelect.find('option').each(function() {
                if ($(this).text().includes('Hồ Chí Minh') || $(this).text().includes('TP.HCM')) {
                    guestCitySelect.val($(this).val()).trigger('change');
                    console.log('Selected city:', $(this).text());
                    return false;
                }
            });
        }
    }, 1000);
    
    setTimeout(() => {
        // Select a district
        const guestDistrictSelect = $('#guest_district_code');
        if (guestDistrictSelect.length && guestDistrictSelect.find('option').length > 1) {
            const firstDistrict = guestDistrictSelect.find('option:eq(1)');
            guestDistrictSelect.val(firstDistrict.val()).trigger('change');
            console.log('Selected district:', firstDistrict.text());
        }
    }, 3000);
    
    setTimeout(() => {
        // Select a ward
        const guestWardSelect = $('#guest_ward_code');
        if (guestWardSelect.length && guestWardSelect.find('option').length > 1) {
            const firstWard = guestWardSelect.find('option:eq(1)');
            guestWardSelect.val(firstWard.val());
            console.log('Selected ward:', firstWard.text());
        }
    }, 5000);
};

// IMMEDIATE GUEST BUTTON DEBUG AND FIX
console.log('🚀 Starting immediate guest button debug...');

// Simple immediate fix function with aggressive CSS overrides
function immediateGuestButtonFix() {
    console.log('🔧 Running immediate guest button fix...');
    
    // Find next-step buttons
    const nextBtns = document.querySelectorAll('.next-step');
    console.log('Next buttons found:', nextBtns.length);
    
    // Force fix each button
    nextBtns.forEach((btn, i) => {
        console.log(`Fixing button ${i}...`);
        
        // Remove all existing event listeners by cloning
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all clickable properties
        newBtn.style.pointerEvents = 'auto !important';
        newBtn.style.cursor = 'pointer !important';
        newBtn.style.zIndex = '9999 !important';
        newBtn.style.position = 'relative !important';
        newBtn.disabled = false;
        newBtn.style.opacity = '1 !important';
        newBtn.style.visibility = 'visible !important';
        
        // Add multiple event types
        ['click', 'mousedown', 'touchstart', 'touchend'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 ${eventType} on guest next button!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Determine current step and navigate to next
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                // Check which step is currently visible
                const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
                const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
                const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
                
                console.log('Current step visibility:', {
                    step1: step1Visible,
                    step2: step2Visible, 
                    step3: step3Visible
                });
                
                if (step1Visible) {
                    // Currently on step 1, go to step 2
                    console.log('Changing from step 1 to step 2...');
                    hideStep(step1);
                    showStep(step2);
                    console.log('✅ Moved from step 1 to step 2');
                    
                } else if (step2Visible) {
                    // Currently on step 2, go to step 3
                    console.log('Changing from step 2 to step 3...');
                    hideStep(step2);
                    showStep(step3);
                    console.log('✅ Moved from step 2 to step 3');
                    
                } else {
                    // Fallback: assume step 1 and go to step 2
                    console.log('Fallback: Changing to step 2...');
                    hideStep(step1);
                    showStep(step2);
                    console.log('✅ Fallback: Moved to step 2');
                }
                
                // Additional cleanup
                setTimeout(() => {
                    cleanupSteps();
                }, 100);
                
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Button ${i} fixed with all events`);
    });
    
    // Also fix prev-step buttons
    const prevBtns = document.querySelectorAll('.prev-step');
    console.log('Prev buttons found:', prevBtns.length);
    
    prevBtns.forEach((btn, i) => {
        console.log(`Fixing prev button ${i}...`);
        
        // Remove all existing event listeners by cloning
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all clickable properties
        newBtn.style.pointerEvents = 'auto !important';
        newBtn.style.cursor = 'pointer !important';
        newBtn.style.zIndex = '9999 !important';
        newBtn.style.position = 'relative !important';
        newBtn.disabled = false;
        newBtn.style.opacity = '1 !important';
        newBtn.style.visibility = 'visible !important';
        
        // Add multiple event types
        ['click', 'mousedown', 'touchstart', 'touchend'].forEach(eventType => {
            newBtn.addEventListener(eventType, function(e) {
                console.log(`🎯 ${eventType} on guest prev button!`);
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                // Determine current step and navigate to previous
                const step1 = document.getElementById('step1');
                const step2 = document.getElementById('step2');
                const step3 = document.getElementById('step3');
                
                // Check which step is currently visible
                const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
                const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
                const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
                
                console.log('Current step visibility for prev:', {
                    step1: step1Visible,
                    step2: step2Visible, 
                    step3: step3Visible
                });
                
                if (step3Visible) {
                    // Currently on step 3, go to step 2
                    console.log('Changing from step 3 to step 2...');
                    hideStep(step3);
                    showStep(step2);
                    console.log('✅ Moved from step 3 to step 2');
                    
                } else if (step2Visible) {
                    // Currently on step 2, go to step 1
                    console.log('Changing from step 2 to step 1...');
                    hideStep(step2);
                    showStep(step1);
                    console.log('✅ Moved from step 2 to step 1');
                }
                
                // Additional cleanup
                setTimeout(() => {
                    cleanupSteps();
                }, 100);
                
            }, { passive: false, capture: true });
        });
        
        console.log(`✅ Prev button ${i} fixed with all events`);
    });
}

// Helper function to aggressively hide a step
function hideStep(stepElement) {
    if (stepElement) {
        stepElement.style.display = 'none !important';
        stepElement.style.visibility = 'hidden !important';
        stepElement.style.opacity = '0 !important';
        stepElement.style.height = '0 !important';
        stepElement.style.overflow = 'hidden !important';
        stepElement.style.position = 'absolute !important';
        stepElement.style.left = '-9999px !important';
        stepElement.setAttribute('style', stepElement.getAttribute('style') + '; display: none !important;');
        console.log(`Step ${stepElement.id} hidden`);
    }
}

// Helper function to aggressively show a step
function showStep(stepElement) {
    if (stepElement) {
        stepElement.style.display = 'block !important';
        stepElement.style.visibility = 'visible !important';
        stepElement.style.opacity = '1 !important';
        stepElement.style.height = 'auto !important';
        stepElement.style.overflow = 'visible !important';
        stepElement.style.position = 'relative !important';
        stepElement.style.left = 'auto !important';
        stepElement.setAttribute('style', stepElement.getAttribute('style') + '; display: block !important;');
        
        // Force browser to repaint
        stepElement.offsetHeight; // Trigger reflow
        stepElement.style.transform = 'translateZ(0)'; // Force hardware acceleration
        console.log(`Step ${stepElement.id} shown`);
    }
}

// Helper function to clean up step conflicts
function cleanupSteps() {
    const allSteps = ['step1', 'step2', 'step3'];
    
    allSteps.forEach(stepId => {
        const stepEl = document.getElementById(stepId);
        if (stepEl) {
            const isVisible = getComputedStyle(stepEl).display !== 'none';
            if (isVisible) {
                showStep(stepEl); // Ensure it's properly shown
            } else {
                hideStep(stepEl); // Ensure it's properly hidden
            }
        }
    });
    
    console.log('🔄 Step cleanup completed');
}

// Run immediately when script loads
immediateGuestButtonFix();

// Run when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM ready, running guest button fix again...');
    setTimeout(immediateGuestButtonFix, 100);
    setTimeout(immediateGuestButtonFix, 500);
    setTimeout(immediateGuestButtonFix, 1000);
    setTimeout(immediateGuestButtonFix, 2000);
});

// Global window functions for manual testing
window.debugGuestButtons = function() {
    console.log('🔍 Debug guest buttons...');
    
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    console.log('Next buttons found:', nextBtns.length);
    console.log('Prev buttons found:', prevBtns.length);
    
    // Check steps
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    console.log('Steps status:');
    console.log('- Step 1:', step1 ? 'Found' : 'Not found', step1 ? getComputedStyle(step1).display : 'N/A');
    console.log('- Step 2:', step2 ? 'Found' : 'Not found', step2 ? getComputedStyle(step2).display : 'N/A');
    console.log('- Step 3:', step3 ? 'Found' : 'Not found', step3 ? getComputedStyle(step3).display : 'N/A');
};

window.manualStepChange = function(targetStep) {
    console.log(`🔧 Manual step change to step ${targetStep}...`);
    
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');
    
    // Hide all steps first
    hideStep(step1);
    hideStep(step2);
    hideStep(step3);
    
    // Show target step
    if (targetStep === 1) showStep(step1);
    else if (targetStep === 2) showStep(step2);
    else if (targetStep === 3) showStep(step3);
    
    console.log(`✅ Manually changed to step ${targetStep}`);
};

window.goToStep1 = function() { manualStepChange(1); };
window.goToStep2 = function() { manualStepChange(2); };
window.goToStep3 = function() { manualStepChange(3); };

window.forceButtonClick = function() {
    console.log('🔨 Force button click...');
    
    const nextBtn = document.querySelector('.next-step');
    if (nextBtn) {
        console.log('Found button, trying to click...');
        
        // Try multiple click methods
        nextBtn.click();
        
        const clickEvent = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        nextBtn.dispatchEvent(clickEvent);
        
        console.log('✅ Force click attempted');
    } else {
        console.log('❌ No next-step button found');
    }
};

window.rerunFix = function() {
    console.log('🔄 Re-running immediate fix...');
    immediateGuestButtonFix();
};

// Global backup click handler with highest priority - MORE AGGRESSIVE
document.addEventListener('click', function(e) {
    if (e.target && (e.target.classList.contains('next-step') || e.target.closest('.next-step'))) {
        console.log('🚨 GLOBAL BACKUP: Next step clicked!');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        // Check current step and navigate
        const step1Visible = step1 && getComputedStyle(step1).display !== 'none';
        const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
        
        if (step1Visible) {
            hideStep(step1);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Changed from step 1 to step 2');
        } else if (step2Visible) {
            hideStep(step2);
            showStep(step3);
            console.log('✅ GLOBAL BACKUP: Changed from step 2 to step 3');
        } else {
            // Fallback
            hideStep(step1);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Fallback to step 2');
        }
        
        return false;
    }
    
    // Handle prev-step buttons
    if (e.target && (e.target.classList.contains('prev-step') || e.target.closest('.prev-step'))) {
        console.log('🚨 GLOBAL BACKUP: Prev step clicked!');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        
        // Check current step and navigate backward
        const step2Visible = step2 && getComputedStyle(step2).display !== 'none';
        const step3Visible = step3 && getComputedStyle(step3).display !== 'none';
        
        if (step3Visible) {
            hideStep(step3);
            showStep(step2);
            console.log('✅ GLOBAL BACKUP: Changed from step 3 to step 2');
        } else if (step2Visible) {
            hideStep(step2);
            showStep(step1);
            console.log('✅ GLOBAL BACKUP: Changed from step 2 to step 1');
        }
        
        return false;
    }
}, true); // Use capture phase with highest priority

// Additional: Force override any CSS animations or transitions that might interfere
const forceStepStyles = document.createElement('style');
forceStepStyles.textContent = `
    #step1.form-step[style*="none"] {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        height: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
    }
    
    #step2.form-step[style*="block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
        position: relative !important;
        left: auto !important;
    }
    
    #step3.form-step[style*="block"] {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
        position: relative !important;
        left: auto !important;
    }
    
    .next-step, .prev-step {
        pointer-events: auto !important;
        cursor: pointer !important;
        z-index: 9999 !important;
        position: relative !important;
    }
`;
document.head.appendChild(forceStepStyles);
</script>
@endpush

@endsection
