@extends($activeTemplate . 'layouts.frontend')
@section('content')

<!-- Hero Section - Main CTA -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-100 py-5">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title">
                        Tìm Thợ Chuyên Nghiệp <br>
                        <span class="text-primary">Nhanh & Tin Cậy</span>
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
                        <a href="#contractor-search" class="btn btn-outline-primary btn-lg">
                            <i class="las la-search me-2"></i>Tìm Thợ
                        </a>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="hero-stats mt-4">
                        <div class="row">
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">1000+</h4>
                                    <p class="stat-label">Thợ verified</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">5000+</h4>
                                    <p class="stat-label">Job hoàn thành</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="stat-number">4.8⭐</h4>
                                    <p class="stat-label">Đánh giá TB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="hero-placeholder">
                        <div class="placeholder-content">
                            <i class="las la-tools" style="font-size: 4rem; color: #0b92d4; margin-bottom: 1rem;"></i>
                            <h4 style="color: #0b92d4; margin-bottom: 0.5rem;">Thumbstack</h4>
                            <p style="color: #6c757d; font-size: 1.1rem;">Kết nối thợ chuyên nghiệp</p>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="mb-0">🚀 Tạo Lead - Tìm Thợ Trong 2 Phút</h3>
                        <p class="mb-0 mt-2 opacity-75">Mô tả công việc → Nhận báo giá → Chọn thợ phù hợp</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('user.customer.leads.store') }}" method="POST" class="lead-creation-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loại công việc *</label>
                                    <select name="category_id" class="form-select" required>
                                        <option value="">Chọn loại công việc</option>
                                        @foreach(App\Models\Category::where('status', 1)->get() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Khu vực *</label>
                                    <select name="district" class="form-select" required>
                                        <option value="">Chọn quận/huyện</option>
                                        <option value="Quận 1">Quận 1</option>
                                        <option value="Quận 2">Quận 2</option>
                                        <option value="Quận 3">Quận 3</option>
                                        <!-- Add more districts -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề công việc *</label>
                                <input type="text" name="title" class="form-control" 
                                       placeholder="VD: Sửa chữa điện nước tại nhà">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Mô tả chi tiết *</label>
                                <textarea name="description" class="form-control" rows="4" 
                                          placeholder="Mô tả chi tiết công việc cần làm..."></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngân sách tối thiểu</label>
                                    <input type="number" name="budget_min" class="form-control" 
                                           placeholder="VD: 200000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngân sách tối đa</label>
                                    <input type="number" name="budget_max" class="form-control" 
                                           placeholder="VD: 500000">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mức độ ưu tiên</label>
                                    <select name="urgency" class="form-select">
                                        <option value="medium">Bình thường</option>
                                        <option value="high">Khẩn cấp</option>
                                        <option value="low">Không gấp</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cần hoàn thành trước</label>
                                    <input type="date" name="needed_by" class="form-control" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Địa chỉ cụ thể *</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="ward" class="form-control mb-2" 
                                               placeholder="Phường/Xã">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="address" class="form-control mb-2" 
                                               placeholder="Số nhà, tên đường">
                                    </div>
                                </div>
                            </div>
                            
                            @auth
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="las la-rocket me-2"></i>Tạo Lead & Tìm Thợ Ngay
                                </button>
                            @else
                                <div class="text-center">
                                    <p class="mb-3">Bạn cần đăng nhập để tạo lead</p>
                                    <a href="{{ route('user.login') }}" class="btn btn-primary btn-lg me-2">
                                        Đăng nhập
                                    </a>
                                    <a href="{{ route('user.register') }}" class="btn btn-outline-primary btn-lg">
                                        Đăng ký miễn phí
                                    </a>
                                </div>
                            @endauth
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Cách Thumbstack Hoạt Động</h2>
            <p class="section-subtitle">Quy trình đơn giản 3 bước để tìm được thợ phù hợp</p>
        </div>
        
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-icon">
                        <span class="step-number">1</span>
                        <i class="las la-edit step-icon-bg"></i>
                    </div>
                    <h4>Tạo Lead</h4>
                    <p>Mô tả công việc cần làm, ngân sách và thời gian. Hệ thống sẽ thông báo cho các thợ phù hợp.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-icon">
                        <span class="step-number">2</span>
                        <i class="las la-users step-icon-bg"></i>
                    </div>
                    <h4>Nhận Báo Giá</h4>
                    <p>Các thợ quan tâm sẽ mua lead và liên hệ báo giá. Bạn so sánh và chọn thợ phù hợp nhất.</p>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-icon">
                        <span class="step-number">3</span>
                        <i class="las la-handshake step-icon-bg"></i>
                    </div>
                    <h4>Hoàn Thành</h4>
                    <p>Thợ thực hiện công việc, bạn thanh toán và đánh giá. Tích điểm loyalty cho lần sau.</p>
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
.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-description {
    font-size: 1.25rem;
    color: #6c757d;
    margin-bottom: 2rem;
}

.hero-actions .btn {
    padding: 1rem 2rem;
    border-radius: 12px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0b92d4;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0;
}

.process-step {
    position: relative;
    padding: 2rem 1rem;
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

.category-icon i {
    font-size: 3rem;
}

.contractor-avatar img {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.lead-creation-form {
    max-width: none;
}

.hero-placeholder {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 2px dashed #0b92d4;
    border-radius: 20px;
    height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    transition: all 0.3s ease;
}

.hero-placeholder:hover {
    border-color: #20c997;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(11, 146, 212, 0.15);
}

.placeholder-content {
    padding: 2rem;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .hero-actions .btn:last-child {
        margin-bottom: 0;
    }
    
    .hero-placeholder {
        height: 300px;
    }
}
</style>

@endsection
