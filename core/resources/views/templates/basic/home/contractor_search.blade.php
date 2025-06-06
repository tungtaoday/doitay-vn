<!-- Contractor Search Section -->
<section id="contractor-search" class="py-5 contractor-search-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge-contractor">
                <i class="las la-search"></i>
                <span>Tìm Kiếm Thợ</span>
            </div>
            <h2 class="section-title-contractor">Tìm Thợ Theo Chuyên Môn</h2>
            <p class="section-subtitle-contractor">Kết nối với hàng ngàn thợ chuyên nghiệp đã được xác minh</p>
            <div class="title-decoration-contractor">
                <div class="decoration-line-contractor"></div>
                <div class="decoration-circle-contractor"></div>
                <div class="decoration-line-contractor"></div>
            </div>
        </div>
        
        <div class="row g-3">
            @foreach(App\Models\Category::where('status', 1)->take(12)->get() as $category)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <a href="{{ route('companies.category', $category->id) }}" class="category-card-compact">
                    <div class="category-card-content-compact">
                        <div class="category-icon-compact">
                            <i class="las la-tools"></i>
                            <span class="category-count-badge">{{ $category->companies_count ?? 0 }}</span>
                        </div>
                        <h6 class="category-title-compact">{{ $category->name }}</h6>
                        <div class="category-hover-effect">
                            <i class="las la-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('company.all') }}" class="btn btn-view-all">
                <i class="las la-th-large me-2"></i>Xem Tất Cả Danh Mục
            </a>
        </div>
    </div>
</section>

<!-- Featured Contractors -->
<section class="py-5 featured-contractors-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge-featured">
                <i class="las la-star"></i>
                <span>Nổi Bật</span>
            </div>
            <h2 class="section-title-featured">Thợ Chuyên Nghiệp Hàng Đầu</h2>
            <p class="section-subtitle-featured">Được khách hàng tin tưởng và đánh giá cao nhất</p>
        </div>
        
        <div class="row g-3">
            @foreach(App\Models\Company::with(['user', 'ratings'])->approved()->take(8)->get() as $company)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="contractor-card-compact">
                    <div class="contractor-header-compact">
                        <div class="contractor-avatar-compact">
                            <img src="{{ getImage(getFilePath('company') . '/' . $company->image, getFileSize('company')) }}" 
                                 alt="{{ $company->name }}" class="contractor-img">
                            <div class="verified-badge-compact">
                                <i class="las la-check"></i>
                            </div>
                        </div>
                        <div class="contractor-info-compact">
                            <a href="{{ route('company.details', [$company->id, slug($company->name)]) }}" class="contractor-name-link">
                                <h6 class="contractor-name-compact">{{ Str::limit($company->name, 20) }}</h6>
                            </a>
                            <p class="contractor-category-compact">{{ $company->category->name ?? 'N/A' }}</p>
                            
                            @php $avgRating = $company->ratings->avg('avg_rating') ?? 0; @endphp
                            <div class="rating-compact">
                                <div class="stars-compact">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="las la-star {{ $i <= $avgRating ? 'filled' : 'empty' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-text">{{ number_format($avgRating, 1) }} ({{ $company->ratings->count() }})</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="contractor-stats-compact">
                        <span class="stat-compact">{{ $company->ratings->count() }} dự án</span>
                        <span class="stat-compact">{{ $company->created_at->diffInYears() }}+ năm KN</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('company.all') }}" class="btn btn-view-all-contractors">
                <i class="las la-users me-2"></i>Khám Phá Thêm Thợ
            </a>
        </div>
    </div>
</section>

<!-- Customer Testimonials -->
<section class="py-5 testimonials-section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-badge-testimonials">
                <i class="las la-quote-right"></i>
                <span>Phản Hồi</span>
            </div>
            <h2 class="section-title-testimonials">Khách Hàng Nói Gì Về Chúng Tôi</h2>
            <p class="section-subtitle-testimonials">Trải nghiệm thực tế từ những khách hàng đã sử dụng dịch vụ</p>
        </div>
        
        <div class="row g-3">
            @foreach(App\Models\Rating::with(['user', 'company'])->where('status', 1)->latest()->take(6)->get() as $review)
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card-compact">
                    <div class="testimonial-header-compact">
                        <div class="stars-compact">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="las la-star {{ $i <= $review->avg_rating ? 'filled' : 'empty' }}"></i>
                            @endfor
                        </div>
                        <i class="las la-quote-right quote-icon-compact"></i>
                    </div>
                    
                    <p class="testimonial-text-compact">{{ Str::limit($review->suggest, 80) }}</p>
                    
                    <div class="reviewer-compact">
                        <img src="{{ getImage(getFilePath('userProfile').'/'.$review->user->image, getFileSize('userProfile')) }}" 
                             alt="{{ $review->user->fullname }}" class="reviewer-img-compact">
                        <div class="reviewer-info-compact">
                            <h6 class="reviewer-name-compact">{{ $review->user->fullname }}</h6>
                            <p class="reviewer-company-compact">{{ Str::limit($review->company->name, 15) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section for Contractors -->
<section class="py-5 cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="cta-text">
                        <h2 class="cta-title">Bạn Là Thợ Chuyên Nghiệp?</h2>
                        <p class="cta-subtitle">Tham gia nền tảng để kết nối với hàng ngàn khách hàng đang tìm kiếm dịch vụ của bạn!</p>
                        <div class="cta-features">
                            <div class="cta-feature">
                                <i class="las la-chart-line"></i>
                                <span>Tăng thu nhập 3x</span>
                            </div>
                            <div class="cta-feature">
                                <i class="las la-users"></i>
                                <span>Tiếp cận khách hàng mới</span>
                            </div>
                            <div class="cta-feature">
                                <i class="las la-shield-alt"></i>
                                <span>Hỗ trợ 24/7</span>
                            </div>
                            <div class="cta-feature">
                                <i class="las la-handshake"></i>
                                <span>Công việc ổn định</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="cta-actions">
                        <a href="{{ route('become.contractor') }}" class="btn btn-cta-primary">
                            <i class="las la-hammer me-2"></i>Đăng Ký Làm Thợ
                        </a>
                        
                        <a href="{{ route('company.all') }}" class="btn btn-cta-secondary">
                            <i class="las la-eye me-2"></i>Xem Thợ Khác
                        </a>
                        
                        <div class="cta-stats">
                            <div class="cta-stat">
                                <span class="cta-stat-number">{{ App\Models\Company::approved()->count() }}+</span>
                                <span class="cta-stat-label">Thợ đã tham gia</span>
                            </div>
                            <div class="cta-stat">
                                <span class="cta-stat-number">{{ App\Models\Rating::count() }}+</span>
                                <span class="cta-stat-label">Dự án hoàn thành</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* ============================================
   CONTRACTOR SEARCH PROFESSIONAL STYLING
   Colors: #102f4b (Primary) & #48bbe2 (Accent)
============================================ */

/* Contractor Search Section */
.contractor-search-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.contractor-search-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="%2348bbe2" opacity="0.03"/></svg>') repeat;
    pointer-events: none;
}

.section-badge-contractor {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
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

.section-title-contractor {
    font-size: 2.75rem;
    font-weight: 700;
    color: #102f4b;
    margin-bottom: 1rem;
    position: relative;
    z-index: 2;
}

.section-subtitle-contractor {
    font-size: 1.2rem;
    color: #6c757d;
    font-weight: 400;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto 2rem;
    position: relative;
    z-index: 2;
}

.title-decoration-contractor {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
}

.decoration-line-contractor {
    width: 60px;
    height: 2px;
    background: #48bbe2;
    opacity: 0.6;
}

.decoration-circle-contractor {
    width: 8px;
    height: 8px;
    background: #48bbe2;
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(72, 187, 226, 0.15);
}

/* Compact Category Cards */
.category-card-compact {
    display: block;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.category-card-content-compact {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 4px 16px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(72, 187, 226, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    min-height: 120px;
}

.category-card-content-compact::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #48bbe2, #3aa8d1);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.category-card-compact:hover .category-card-content-compact {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.12);
    border-color: rgba(72, 187, 226, 0.3);
}

.category-card-compact:hover .category-card-content-compact::before {
    transform: scaleX(1);
}

.category-icon-compact {
    position: relative;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, rgba(72, 187, 226, 0.1), rgba(72, 187, 226, 0.05));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.75rem;
    border: 2px solid rgba(72, 187, 226, 0.2);
    transition: all 0.3s ease;
}

.category-icon-compact i {
    font-size: 1.5rem;
    color: #48bbe2;
    transition: all 0.3s ease;
}

.category-count-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #102f4b;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.65rem;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(16, 47, 75, 0.2);
}

.category-title-compact {
    font-size: 0.9rem;
    font-weight: 600;
    color: #102f4b;
    margin-bottom: 0;
    line-height: 1.3;
    flex-grow: 1;
    display: flex;
    align-items: center;
}

.category-hover-effect {
    color: #48bbe2;
    font-size: 1rem;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    position: absolute;
    bottom: 8px;
}

.category-card-compact:hover .category-hover-effect {
    opacity: 1;
    transform: translateY(0);
}

.category-card-compact:hover .category-icon-compact {
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
    border-color: #48bbe2;
}

.category-card-compact:hover .category-icon-compact i {
    color: white;
    transform: scale(1.1);
}

/* Featured Contractors Section */
.featured-contractors-section {
    background: white;
    position: relative;
}

.section-badge-featured {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #102f4b, #1a3f5c);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(16, 47, 75, 0.25);
}

.section-title-featured {
    font-size: 2.75rem;
    font-weight: 700;
    color: #102f4b;
    margin-bottom: 1rem;
}

.section-subtitle-featured {
    font-size: 1.2rem;
    color: #6c757d;
    font-weight: 400;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto 2rem;
}

/* Compact Contractor Cards */
.contractor-card-compact {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(72, 187, 226, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    padding: 1rem;
    position: relative;
}

.contractor-card-compact:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.12);
    border-color: rgba(72, 187, 226, 0.3);
}

.contractor-header-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.contractor-avatar-compact {
    position: relative;
    flex-shrink: 0;
}

.contractor-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(72, 187, 226, 0.2);
}

.verified-badge-compact {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 18px;
    height: 18px;
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
    box-shadow: 0 1px 4px rgba(72, 187, 226, 0.3);
}

.verified-badge-compact i {
    color: white;
    font-size: 0.7rem;
    font-weight: 900;
}

.contractor-info-compact {
    flex-grow: 1;
    min-width: 0;
}

.contractor-name-link {
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.contractor-name-link:hover {
    color: #48bbe2;
}

.contractor-name-compact {
    font-size: 1rem;
    font-weight: 600;
    color: #102f4b;
    margin-bottom: 0.25rem;
    line-height: 1.2;
    transition: color 0.3s ease;
    cursor: pointer;
}

.contractor-name-link:hover .contractor-name-compact {
    color: #48bbe2;
}

.contractor-category-compact {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.rating-compact {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.stars-compact {
    display: flex;
    gap: 1px;
}

.stars-compact i {
    font-size: 0.8rem;
}

.stars-compact i.filled {
    color: #ffc107;
}

.stars-compact i.empty {
    color: #e9ecef;
}

.rating-text {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.contractor-stats-compact {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    margin-bottom: 0.75rem;
    border-top: 1px solid rgba(72, 187, 226, 0.1);
    border-bottom: 1px solid rgba(72, 187, 226, 0.1);
}

.stat-compact {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}



/* Compact Testimonials Section */
.testimonials-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
}

.section-badge-testimonials {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 20px rgba(72, 187, 226, 0.25);
}

.section-title-testimonials {
    font-size: 2.75rem;
    font-weight: 700;
    color: #102f4b;
    margin-bottom: 1rem;
}

.section-subtitle-testimonials {
    font-size: 1.2rem;
    color: #6c757d;
    font-weight: 400;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto 2rem;
}

.testimonial-card-compact {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 4px 16px rgba(16, 47, 75, 0.08);
    border: 1px solid rgba(72, 187, 226, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.testimonial-card-compact::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #48bbe2, #3aa8d1);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.testimonial-card-compact:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.12);
}

.testimonial-card-compact:hover::before {
    transform: scaleX(1);
}

.testimonial-header-compact {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stars-compact {
    display: flex;
    gap: 1px;
}

.stars-compact i.filled {
    color: #ffc107;
    font-size: 0.9rem;
}

.stars-compact i.empty {
    color: #e9ecef;
    font-size: 0.9rem;
}

.quote-icon-compact {
    color: #48bbe2;
    font-size: 1.5rem;
    opacity: 0.3;
}

.testimonial-text-compact {
    font-size: 0.95rem;
    line-height: 1.6;
    color: #102f4b;
    font-style: italic;
    margin-bottom: 1rem;
}

.reviewer-compact {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(72, 187, 226, 0.1);
}

.reviewer-img-compact {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(72, 187, 226, 0.2);
    flex-shrink: 0;
}

.reviewer-info-compact {
    min-width: 0;
    flex-grow: 1;
}

.reviewer-name-compact {
    font-size: 0.9rem;
    font-weight: 600;
    color: #102f4b;
    margin-bottom: 0.25rem;
    line-height: 1.2;
}

.reviewer-company-compact {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0;
    line-height: 1.2;
}

/* CTA Section */
.cta-section {
    background: linear-gradient(135deg, #102f4b 0%, #1a3f5c 100%);
    position: relative;
    overflow: hidden;
}

.cta-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="1" fill="%2348bbe2" opacity="0.1"/></svg>') repeat;
    pointer-events: none;
}

.cta-content {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    padding: 3rem 2rem;
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 2;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.cta-features {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.cta-feature {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.9);
}

.cta-feature i {
    color: #48bbe2;
    font-size: 1.2rem;
}

.cta-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-cta-primary {
    background: linear-gradient(135deg, #48bbe2, #3aa8d1);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.btn-cta-primary:hover {
    background: linear-gradient(135deg, #3aa8d1, #2a95c0);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(72, 187, 226, 0.3);
    color: white;
}

.btn-cta-secondary {
    background: transparent;
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-cta-secondary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

.btn-view-all,
.btn-view-all-contractors {
    background: linear-gradient(135deg, #102f4b, #1a3f5c);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-view-all:hover,
.btn-view-all-contractors:hover {
    background: linear-gradient(135deg, #1a3f5c, #2a4f6c);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(16, 47, 75, 0.3);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .section-title-contractor,
    .section-title-featured,
    .section-title-testimonials {
        font-size: 2rem;
    }
    
    .cta-title {
        font-size: 1.75rem;
    }
    
    .cta-features {
        flex-direction: column;
        gap: 1rem;
    }
    
    .contractor-card-header {
        padding: 1.5rem 1.5rem 0;
    }
    
    .contractor-card-body {
        padding: 0 1.5rem 1rem;
    }
    
    .contractor-card-footer {
        padding: 0 1.5rem 1.5rem;
    }
    
    .testimonial-card-modern {
        padding: 1.5rem;
    }
    
    .cta-content {
        padding: 2rem 1.5rem;
    }
    
    .category-card-content {
        padding: 1.5rem 1rem;
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .section-title {
        font-size: 2rem;
    }
    
    .section-title-featured {
        font-size: 2rem;
    }
    
    .section-title-testimonials {
        font-size: 2rem;
    }
    
    .section-title-cta {
        font-size: 2rem;
    }
    
    .category-card-content-compact {
        min-height: 100px;
        padding: 0.75rem;
    }
    
    .category-icon-compact {
        width: 40px;
        height: 40px;
        margin-bottom: 0.5rem;
    }
    
    .category-icon-compact i {
        font-size: 1.25rem;
    }
    
    .category-title-compact {
        font-size: 0.8rem;
    }
    
    .contractor-card-compact {
        padding: 0.75rem;
    }
    
    .contractor-img {
        width: 45px;
        height: 45px;
    }
    
    .contractor-name-compact {
        font-size: 0.9rem;
    }
    
    .contractor-category-compact {
        font-size: 0.7rem;
    }
    
    .testimonial-card-compact {
        padding: 1rem;
    }
    
    .testimonial-text-compact {
        font-size: 0.85rem;
    }
    
    .reviewer-img-compact {
        width: 35px;
        height: 35px;
    }
    
    .reviewer-name-compact {
        font-size: 0.8rem;
    }
    
    .reviewer-company-compact {
        font-size: 0.7rem;
    }
}

@media (max-width: 576px) {
    .section-title {
        font-size: 1.75rem;
    }
    
    .section-title-featured {
        font-size: 1.75rem;
    }
    
    .section-title-testimonials {
        font-size: 1.75rem;
    }
    
    .section-title-cta {
        font-size: 1.75rem;
    }
    
    .category-card-content-compact {
        min-height: 90px;
        padding: 0.5rem;
    }
    
    .category-icon-compact {
        width: 35px;
        height: 35px;
        margin-bottom: 0.4rem;
    }
    
    .category-icon-compact i {
        font-size: 1rem;
    }
    
    .category-title-compact {
        font-size: 0.75rem;
    }
    
    .category-count-badge {
        width: 16px;
        height: 16px;
        font-size: 0.6rem;
    }
    
    .contractor-card-compact {
        padding: 0.5rem;
    }
    
    .contractor-img {
        width: 40px;
        height: 40px;
    }
    
    .contractor-name-compact {
        font-size: 0.85rem;
    }
    
    .contractor-category-compact {
        font-size: 0.65rem;
    }
    
    .stars-compact i {
        font-size: 0.7rem;
    }
    
    .rating-text {
        font-size: 0.7rem;
    }
    
    .stat-compact {
        font-size: 0.7rem;
    }
}

/* CTA Stats for Contractors */
.cta-stats {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    gap: 2rem;
}

.cta-stat {
    text-align: center;
    flex: 1;
}

.cta-stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #48bbe2;
    line-height: 1.2;
    margin-bottom: 0.25rem;
}

.cta-stat-label {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.2;
}

/* Mobile CTA Stats */
@media (max-width: 768px) {
    .cta-stats {
        gap: 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
    }
    
    .cta-stat-number {
        font-size: 1.25rem;
    }
    
    .cta-stat-label {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .cta-stats {
        flex-direction: column;
        gap: 0.75rem;
        text-align: center;
    }
    
    .cta-stat-number {
        font-size: 1.1rem;
    }
    
    .cta-stat-label {
        font-size: 0.75rem;
    }
}
</style> 