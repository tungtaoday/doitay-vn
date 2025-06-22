@extends($activeTemplate . 'layouts.frontend')

@php
    $content = getContent('breadcrumb.content', true);
@endphp

@section('content')
    <!-- Main Container -->
    <div class="contractor-profile-page">
        <!-- Header Section -->
        <section class="profile-header">
            <div class="container">
                <div class="header-content">
                    <div class="row align-items-center">
                        <!-- Left - Contractor Info -->
                        <div class="col-lg-8">
                            <div class="contractor-info">
                                <div class="contractor-avatar-section">
                                    <div class="avatar-container">
                                        <img src="{{ getCompanyAvatar($company) }}" 
                                             alt="{{ $company->name }}" class="contractor-avatar">
                                        <div class="status-badge">
                                            <i class="las la-shield-alt"></i>
                                            <span>Đã xác minh</span>
                                        </div>
                                    </div>
                                    
                                    <div class="contractor-details">
                                        <h1 class="contractor-name">{{ $company->name }}</h1>
                                        <p class="contractor-category">{{ $company->category->name ?? 'Chuyên gia dịch vụ' }}</p>
                                        
                                        <div class="contractor-meta">
                                            <div class="rating-section">
                                                <div class="rating-stars">
                                                    @php echo avgRating($company->avg_rating); @endphp
                                                </div>
                                                <span class="rating-score">{{ number_format($company->avg_rating, 1) }}</span>
                                                <span class="rating-count">({{ $company->ratings->count() }} đánh giá)</span>
                                            </div>
                                            
                                            <div class="experience-badge">
                                                <i class="las la-medal"></i>
                                                <span>{{ $company->experience ?? 5 }}+ năm kinh nghiệm</span>
                                            </div>
                                            
                                            <div class="location-info">
                                                <i class="las la-map-marker-alt"></i>
                                                <span>{{ $company->address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right - Location Info -->
                        <div class="col-lg-4">
                            <div class="location-cta-section">
                                <div class="location-card">
                                    <div class="location-header">
                                        <i class="las la-map-marker-alt"></i>
                                        <span>Khu vực hoạt động</span>
                                    </div>
                                    <div class="location-address">
                                        {{ $company->address }}
                                    </div>
                                    
                                    <button type="button" class="btn btn-primary-cta" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                                        <i class="las la-calendar-check"></i>
                                        Đặt lịch tư vấn
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Navigation Tabs -->
        <section class="profile-navigation">
            <div class="container">
                <nav class="profile-tabs">
                    <ul class="nav nav-tabs" id="profileTabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#overview-section">
                                <i class="las la-info-circle"></i>
                                Tổng quan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services-section">
                                <i class="las la-tools"></i>
                                Dịch vụ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reviews-section">
                                <i class="las la-star"></i>
                                Đánh giá ({{ $company->ratings->count() }})
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#photos-section">
                                <i class="las la-images"></i>
                                Hình ảnh
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>

        <!-- Main Content -->
        <section class="profile-content">
            <div class="container">
                <div class="row">
                    <!-- Left Column - Content Sections -->
                    <div class="col-lg-8">
                        <div class="profile-sections">
                            <!-- Overview Section -->
                            <div class="content-section" id="overview-section">
                                <!-- About -->
                                <div class="about-section">
                                    <h2 class="section-title">
                                        <i class="las la-user-tie"></i>
                                        Giới thiệu
                                    </h2>
                                    <div class="about-content">
                                        <p class="description">{{ $company->description }}</p>
                                        
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
                                                    <p>Đã xác minh danh tính và có bảo hiểm</p>
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

                                <!-- Quick Stats -->
                                <div class="stats-section">
                                    <h2 class="section-title">
                                        <i class="las la-chart-bar"></i>
                                        Thống kê
                                    </h2>
                                    <div class="stats-grid">
                                        <div class="stat-card">
                                            <div class="stat-number">{{ number_format($company->avg_rating, 1) }}</div>
                                            <div class="stat-label">Điểm đánh giá</div>
                                            <div class="stat-sublabel">Trên {{ $company->ratings->count() }} đánh giá</div>
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

                            <!-- Services Section -->
                            <div class="content-section" id="services-section">
                                <h2 class="section-title">
                                    <i class="las la-tools"></i>
                                    Dịch vụ chuyên môn
                                </h2>
                                
                                <div class="services-grid">
                                    @if(isset($features) && $features->count() > 0)
                                        @foreach($features as $feature)
                                            <div class="service-item">
                                                <div class="service-icon">
                                                    <i class="las la-check-circle"></i>
                                                </div>
                                                <div class="service-content">
                                                    <h3>{{ $feature->name }}</h3>
                                                    <p>Dịch vụ chuyên nghiệp với chất lượng cao</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="no-services">
                                            <div class="empty-state">
                                                <i class="las la-tools"></i>
                                                <h3>Dịch vụ đang cập nhật</h3>
                                                <p>Thông tin dịch vụ chi tiết sẽ sớm được bổ sung</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Reviews Section -->
                            <div class="content-section" id="reviews-section">
                                <!-- Review Summary -->
                                <div class="review-summary">
                                    <div class="summary-header">
                                        <h2 class="section-title">
                                            <i class="las la-star"></i>
                                            Đánh giá khách hàng
                                        </h2>
                                    </div>
                                    
                                    <div class="summary-content">
                                        <div class="rating-overview">
                                            <div class="overall-rating">
                                                <div class="rating-number">{{ number_format($company->avg_rating, 1) }}</div>
                                                <div class="rating-stars">
                                                    @php echo avgRating($company->avg_rating); @endphp
                                                </div>
                                                <div class="rating-text">{{ $company->ratings->count() }} đánh giá</div>
                                            </div>
                                            
                                            <div class="rating-breakdown">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    @php
                                                        $reviewCount = $company->ratings->filter(function ($rating) use ($i) {
                                                            return round($rating->rating ?? $rating->avg_rating ?? 0) == $i;
                                                        })->count();
                                                        $totalReviews = $company->ratings->count();
                                                        $percentage = $totalReviews > 0 ? ($reviewCount / $totalReviews) * 100 : 0;
                                                    @endphp
                                                    <div class="rating-row">
                                                        <span class="star-label">{{ $i }} sao</span>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                        <span class="percentage">{{ number_format($percentage, 0) }}%</span>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reviews List -->
                                <div class="reviews-list">
                                    @if($company->ratings->count() > 0)
                                        @foreach($company->ratings->take(10) as $rating)
                                            <div class="review-item">
                                                <div class="review-header">
                                                    <div class="reviewer-info">
                                                        <div class="reviewer-avatar">
                                                            <i class="las la-user-circle"></i>
                                                        </div>
                                                        <div class="reviewer-details">
                                                            <h4 class="reviewer-name">{{ $rating->user->fullname ?? 'Khách hàng' }}</h4>
                                                            <p class="review-date">{{ $rating->created_at->format('d/m/Y') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="review-rating">
                                                        @php 
                                                            $reviewRating = $rating->rating ?? $rating->avg_rating ?? 0;
                                                            echo avgRating($reviewRating); 
                                                        @endphp
                                                    </div>
                                                </div>
                                                <div class="review-content">
                                                    <p>{{ $rating->comment ?? 'Dịch vụ tốt, tôi rất hài lòng và sẽ giới thiệu cho bạn bè.' }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="no-reviews">
                                            <div class="empty-state">
                                                <i class="las la-comment-dots"></i>
                                                <h3>Chưa có đánh giá</h3>
                                                <p>Hãy là người đầu tiên đánh giá dịch vụ của {{ $company->name }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Photos Section -->
                            <div class="content-section" id="photos-section">
                                <h2 class="section-title">
                                    <i class="las la-images"></i>
                                    Hình ảnh dự án
                                </h2>
                                
                                <div class="photo-gallery">
                                    @if($company->portfolios->count() > 0)
                                        <div class="main-photo">
                                            <img src="{{ getImage(getFilePath('portfolio') . '/' . $company->portfolios->first()->image) }}" 
                                                 alt="{{ $company->portfolios->first()->title }}" class="gallery-main-image">
                                        </div>
                                        
                                        <div class="photo-grid">
                                            @foreach($company->portfolios->take(8) as $portfolio)
                                                <div class="photo-item">
                                                    <img src="{{ getImage(getFilePath('portfolio') . '/' . $portfolio->image) }}" 
                                                         alt="{{ $portfolio->title }}" class="gallery-image"
                                                         title="{{ $portfolio->title }}">
                                                    @if($portfolio->title)
                                                        <div class="photo-overlay">
                                                            <span class="photo-title">{{ $portfolio->title }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="no-portfolio-message">
                                            <div class="no-portfolio-icon">
                                                <i class="las la-images"></i>
                                            </div>
                                            <h4>Chưa có dự án nào</h4>
                                            <p>{{ $company->name }} chưa tải lên hình ảnh dự án nào.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Sidebar -->
                    <div class="col-lg-4">
                        <div class="profile-sidebar">
                            <!-- Sidebar Start Spacer -->
                            <div class="sidebar-spacer"></div>
                            
                            <!-- Business Hours Card -->
                            <div class="sidebar-card hours-card">
                                <h3 class="card-title">
                                    <i class="las la-clock"></i>
                                    Giờ làm việc
                                </h3>
                                
                                <div class="hours-list">
                                    <div class="hours-item">
                                        <span class="day">Thứ 2 - Thứ 6</span>
                                        <span class="time">8:00 - 18:00</span>
                                    </div>
                                    <div class="hours-item">
                                        <span class="day">Thứ 7</span>
                                        <span class="time">8:00 - 16:00</span>
                                    </div>
                                    <div class="hours-item">
                                        <span class="day">Chủ nhật</span>
                                        <span class="time">Nghỉ</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Response Card -->
                            <div class="sidebar-card response-card">
                                <h3 class="card-title">
                                    <i class="las la-tachometer-alt"></i>
                                    Thời gian phản hồi
                                </h3>
                                
                                <div class="response-stats">
                                    <div class="response-item">
                                        <div class="response-icon">
                                            <i class="las la-reply"></i>
                                        </div>
                                        <div class="response-text">
                                            <strong>Trong vòng 2 giờ</strong>
                                            <span>Phản hồi tin nhắn</span>
                                        </div>
                                    </div>
                                    
                                    <div class="response-item">
                                        <div class="response-icon">
                                            <i class="las la-file-alt"></i>
                                        </div>
                                        <div class="response-text">
                                            <strong>Trong 24 giờ</strong>
                                            <span>Gửi báo giá</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Advertisement -->
                            <div class="sidebar-card ad-card">
                                @php
                                    echo getAdvertisement('300x250');
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Update Review Modal -->
    <div class="modal fade" id="reviewUpdateModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewUpdateModalLabel">@lang('Cập nhật đánh giá')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.review.update') }}" method="POST" class="disableSubmission">
                        @csrf
                        <div class="row align-items-center mb-3">
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center">
                                    <div class="t-company-content">
                                        <h6 class="view-company"></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($features as $feature)
                            <div class="mb-3">
                                <label for="feature-rating-{{ $feature->id }}" class="form-label">{{ $feature->name }}</label>
                                <div class="give-rating-update text--base" id="feature-rating-{{ $feature->id }}">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <span id="existed-rating-{{ $feature->id }}-{{ $i }}">
                                            <input id="star{{ $feature->id }}-{{ $i }}" name="rating[{{ $feature->id }}]" type="radio" value="{{ $i }}" data-feature-id="{{ $feature->id }}">
                                            <label for="star{{ $feature->id }}-{{ $i }}"><i class="las la-star fa-sm"></i></label>
                                        </span>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" class="edit-id" value="" name="id">
                        <textarea name="review" class="form-control edit-review"></textarea>
                        <div class="text-end">
                            <button type="submit" class="btn btn--base">@lang('Cập nhật')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Delete Modal -->
    <div class="modal fade" id="reviewDeleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewDeleteModalLabel">@lang('Xác nhận')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>@lang('Bạn có chắc chắn muốn xóa đánh giá này?')</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('user.review.delete') }}" method="POST" class="disableSubmission">
                        @csrf
                        <input type="hidden" name="id" value="" class="delete-id">
                        <button type="button" class="btn btn-sm btn--dark" data-bs-dismiss="modal">@lang('Không')</button>
                        <button type="submit" class="btn btn-sm btn--base">@lang('Có')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Appointment Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('appointments.create') }}" id="appointmentForm" novalidate>
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="appointmentModalLabel">
                            <i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn với') {{ $company->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        
                        <!-- Progress Steps -->
                        <div class="booking-steps mb-4">
                            <div class="d-flex justify-content-between mb-3">
                                <div class="step active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Liên hệ</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Thời gian</div>
                                </div>
                                <div class="step" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Chi tiết</div>
                                </div>
                            </div>
                            <div class="progress" style="height: 3px;">
                                <div class="progress-bar bg-primary" style="width: 33%"></div>
                            </div>
                        </div>

                        <!-- Step 1: Contact Info -->
                        <div class="booking-step-content" data-step="1">
                            @auth
                                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                <div class="user-info-card mb-3 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="recipient_name" value="{{ auth()->user()->name }}">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">@lang('Số điện thoại')</label>
                                    <input type="tel" id="phone" name="recipient_phone" class="form-control form-control-lg" 
                                           value="{{ auth()->user()->mobile }}" placeholder="0901234567" required>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="las la-info-circle me-2"></i>
                                    Chúng tôi sẽ tạo tài khoản cho bạn để theo dõi lịch hẹn
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">@lang('Họ và tên') <span class="text-danger">*</span></label>
                                        <input type="text" id="name" name="recipient_name" class="form-control form-control-lg" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">@lang('Số điện thoại') <span class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="recipient_phone" class="form-control form-control-lg" 
                                               placeholder="0901234567" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">@lang('Email') <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg"
                                           placeholder="email@example.com" required>
                                    <div id="email-error" class="text-danger mt-2" style="display: none;">
                                        @lang('Email đã tồn tại, vui lòng') <a href="{{ route('user.login.v2') }}">@lang('đăng nhập')</a>.
                                    </div>
                                </div>
                            @endauth
                        </div>

                        <!-- Step 2: Date & Time -->
                        <div class="booking-step-content d-none" data-step="2">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="appointmentDate" class="form-label">@lang('Chọn ngày') <span class="text-danger">*</span></label>
                                    <input type="date" id="appointmentDate" name="appointmentDate" class="form-control form-control-lg" 
                                           min="{{ date('Y-m-d') }}" required>
                                    <small class="text-muted">Từ hôm nay trở đi</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="appointmentTime" class="form-label">@lang('Chọn giờ') <span class="text-danger">*</span></label>
                                    <select id="appointmentTime" name="appointmentTime" class="form-select form-select-lg" required>
                                        <option value="">Chọn giờ hẹn</option>
                                        <option value="08:00">08:00 - Sáng sớm</option>
                                        <option value="09:00">09:00 - Giờ hành chính</option>
                                        <option value="10:00">10:00 - Giờ hành chính</option>
                                        <option value="11:00">11:00 - Trước giờ nghỉ trưa</option>
                                        <option value="13:00">13:00 - Sau giờ nghỉ trưa</option>
                                        <option value="14:00">14:00 - Giờ hành chính</option>
                                        <option value="15:00">15:00 - Giờ hành chính</option>
                                        <option value="16:00">16:00 - Chiều tối</option>
                                        <option value="17:00">17:00 - Sau giờ làm</option>
                                        <option value="18:00">18:00 - Tối</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Quick Time Slots -->
                            <div class="quick-time-slots mb-3">
                                <label class="form-label">@lang('Hoặc chọn nhanh:')</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-outline-primary quick-time" data-time="09:00">9h Sáng</button>
                                    <button type="button" class="btn btn-outline-primary quick-time" data-time="14:00">2h Chiều</button>
                                    <button type="button" class="btn btn-outline-primary quick-time" data-time="16:00">4h Chiều</button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Additional Details -->
                        <div class="booking-step-content d-none" data-step="3">
                            <div class="mb-3">
                                <label for="address" class="form-label">@lang('Địa chỉ thực hiện dịch vụ') <span class="text-danger">*</span></label>
                                <textarea id="address" name="recipient_address" class="form-control" rows="2" 
                                          placeholder="Nhập địa chỉ chi tiết..." required></textarea>
                                <small class="text-muted">Để thợ có thể đến đúng địa điểm</small>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">@lang('Mô tả công việc')</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" 
                                          placeholder="Mô tả chi tiết công việc cần làm..."></textarea>
                                <small class="text-muted">Thông tin chi tiết giúp thợ chuẩn bị tốt hơn</small>
                            </div>
                            
                            <!-- Booking Summary -->
                            <div class="booking-summary p-3 bg-light rounded">
                                <h6 class="mb-3">Tóm tắt lịch hẹn:</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Thợ:</strong><br>
                                        <span>{{ $company->name }}</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Thời gian:</strong><br>
                                        <span id="summary-datetime">Chưa chọn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" id="prevStep" style="display: none;">
                            <i class="las la-arrow-left me-1"></i> Quay lại
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Đóng</button>
                            <button type="button" class="btn btn-primary" id="nextStep">
                                Tiếp theo <i class="las la-arrow-right ms-1"></i>
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBooking" style="display: none;">
                                <i class="las la-calendar-check me-1"></i> Đặt lịch hẹn
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-appointment-btn d-none">
        <button type="button" class="btn btn-primary btn-lg w-100 py-3 appointment-btn" data-bs-toggle="modal" data-bs-target="#appointmentModal">
            <i class="las la-calendar-check me-2"></i>@lang('Đặt Hẹn với chuyên gia')
        </button>
    </div>    
@endsection

@push('style')
<style>
/* === MODERN CONTRACTOR PROFILE - THUMBSTACK & YELP INSPIRED === */

/* Color Scheme Variables */
:root {
    /* Primary Colors */
    --primary-dark: #102f4b;    /* Professional, reliable, stable */
    --primary-light: #48bbe2;   /* Modern, dynamic, bright */
    --accent-orange: #ffa500;   /* Development, fresh, friendly */
    
    /* Extended Palette */
    --primary-dark-light: rgba(16, 47, 75, 0.1);
    --primary-light-light: rgba(72, 187, 226, 0.1);
    --accent-orange-light: rgba(255, 165, 0, 0.1);
    
    /* Neutral Colors */
    --white: #ffffff;
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
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    
    /* Transitions */
    --transition-fast: 0.15s ease-in-out;
    --transition-normal: 0.3s ease-in-out;
    --transition-slow: 0.5s ease-in-out;
}

/* === MAIN CONTAINER === */
.contractor-profile-page {
    background: var(--gray-50);
    min-height: 100vh;
}

/* === PROFILE HEADER === */
.profile-header {
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    padding: 32px 0;
}

.contractor-avatar-section {
    display: flex;
    align-items: flex-start;
    gap: 24px;
}

.avatar-container {
    position: relative;
    flex-shrink: 0;
}

.contractor-avatar {
    width: 120px;
    height: 120px;
    border-radius: 16px;
    object-fit: cover;
    border: 3px solid var(--white);
    box-shadow: var(--shadow-lg);
}

.status-badge {
    position: absolute;
    bottom: -8px;
    right: -8px;
    background: #10b981;
    color: var(--white);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    z-index: 10;
}

.contractor-details {
    flex: 1;
}

.contractor-name {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-dark);
    margin-bottom: 8px;
    line-height: 1.2;
}

.contractor-category {
    font-size: 1.2rem;
    color: var(--gray-600);
    margin-bottom: 20px;
    font-weight: 500;
}

.contractor-meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.rating-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-score {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-dark);
}

.rating-count {
    color: var(--gray-500);
}

.experience-badge,
.location-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-600);
    font-size: 15px;
}

.experience-badge i,
.location-info i {
    color: var(--primary-light);
    font-size: 16px;
}

/* === LOCATION CTA SECTION === */
.location-cta-section {
    display: flex;
    justify-content: flex-end;
}

.location-card {
    background: var(--white);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--gray-200);
    max-width: 320px;
    width: 100%;
}

.location-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    color: var(--primary-dark);
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.location-header i {
    color: var(--primary-light);
    font-size: 16px;
}

.location-address {
    color: var(--gray-700);
    font-size: 1.1rem;
    line-height: 1.5;
    margin-bottom: 20px;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 12px;
    border-left: 4px solid var(--primary-light);
}

.btn-primary-cta {
    background: linear-gradient(135deg, var(--accent-orange), #e6940e);
    color: var(--white);
    border: none;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    width: 100%;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 16px;
}

.btn-primary-cta:hover {
    background: linear-gradient(135deg, #e6940e, var(--accent-orange));
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.contact-methods {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    color: var(--gray-700);
    text-decoration: none;
    border-radius: 8px;
    transition: var(--transition-fast);
    font-size: 14px;
}

.contact-method:hover {
    background: var(--gray-100);
    color: var(--primary-dark);
    text-decoration: none;
}

.contact-method i {
    color: var(--primary-light);
    font-size: 16px;
}

.response-time {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--gray-500);
    font-size: 13px;
    justify-content: center;
}

.response-time i {
    color: #10b981;
}

/* === NAVIGATION TABS === */
.profile-navigation {
    background: var(--white);
    border-bottom: 1px solid var(--gray-200);
    position: sticky;
    top: 0;
    z-index: 10;
}

.profile-tabs {
    padding: 0;
}

.nav-tabs {
    border-bottom: none;
    gap: 0;
}

.nav-tabs .nav-item {
    margin-bottom: 0;
}

.nav-tabs .nav-link {
    border: none;
    background: none;
    color: var(--gray-600);
    font-weight: 600;
    padding: 16px 24px;
    border-radius: 0;
    border-bottom: 3px solid transparent;
    transition: var(--transition-fast);
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 15px;
}

.nav-tabs .nav-link:hover {
    color: var(--primary-dark);
    border-color: var(--gray-300);
    background: var(--gray-50);
}

.nav-tabs .nav-link.active {
    color: var(--primary-dark);
    border-bottom-color: var(--primary-light);
    background: none;
}

.nav-tabs .nav-link i {
    font-size: 16px;
}

/* === MAIN CONTENT === */
.profile-content {
    padding: 32px 0;
}

.profile-content .row {
    --bs-gutter-x: 2rem;
}

/* === PROFILE SECTIONS === */
.profile-sections {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.content-section {
    background: var(--white);
    border-radius: 16px;
    padding: 32px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    scroll-margin-top: 120px; /* Account for sticky navigation */
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title i {
    color: var(--primary-light);
    font-size: 1.6rem;
}

/* === ABOUT SECTION === */
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
    border-left: 4px solid var(--primary-light);
    transition: var(--transition-normal);
}

.highlight-item:hover {
    background: var(--primary-light-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

.highlight-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    background: var(--primary-light);
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
    color: var(--primary-dark);
    margin-bottom: 6px;
}

.highlight-text p {
    color: var(--gray-600);
    line-height: 1.5;
    margin: 0;
}

/* === STATS SECTION === */
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
    transition: var(--transition-normal);
    border: 1px solid var(--gray-200);
}

.stat-card:hover {
    background: var(--white);
    box-shadow: var(--shadow-md);
    transform: translateY(-4px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-dark);
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

/* === SERVICES SECTION === */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.service-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    transition: var(--transition-normal);
}

.service-item:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.service-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: var(--primary-light-light);
    color: var(--primary-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.service-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 6px;
}

.service-content p {
    color: var(--gray-600);
    line-height: 1.5;
    margin: 0;
}

.no-services .empty-state {
    text-align: center;
    padding: 40px;
    color: var(--gray-500);
}

.empty-state i {
    font-size: 48px;
    color: var(--gray-400);
    margin-bottom: 16px;
}

.empty-state h3 {
    font-size: 1.3rem;
    color: var(--gray-600);
    margin-bottom: 8px;
}

/* === REVIEWS SECTION === */
.review-summary {
    margin-bottom: 32px;
}

.rating-overview {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-light));
    color: var(--white);
    border-radius: 16px;
    padding: 32px;
    display: flex;
    gap: 32px;
    align-items: center;
}

.overall-rating {
    flex-shrink: 0;
    text-align: center;
}

.rating-number {
    font-size: 3.5rem;
    font-weight: 800;
    display: block;
    line-height: 1;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.rating-stars {
    margin: 12px 0;
}

.rating-text {
    font-size: 1.1rem;
    opacity: 0.9;
}

.rating-breakdown {
    flex: 1;
}

.rating-row {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 12px;
}

.star-label {
    min-width: 60px;
    font-weight: 500;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--accent-orange);
    border-radius: 4px;
    transition: width 0.6s ease;
}

.percentage {
    min-width: 40px;
    text-align: right;
    font-weight: 500;
}

/* === REVIEWS LIST === */
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 24px;
    transition: var(--transition-normal);
}

.review-item:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-sm);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.reviewer-avatar {
    width: 40px;
    height: 40px;
    background: var(--primary-light-light);
    color: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.reviewer-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 2px;
}

.review-date {
    font-size: 0.85rem;
    color: var(--gray-500);
}

.review-content p {
    color: var(--gray-700);
    line-height: 1.6;
    margin: 0;
}

/* === PHOTO GALLERY === */
.photo-gallery {
    display: grid;
    gap: 16px;
}

.main-photo {
    grid-column: 1 / -1;
    aspect-ratio: 16/9;
    border-radius: 12px;
    overflow: hidden;
}

.gallery-main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.photo-item {
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition-normal);
}

.gallery-image:hover {
    transform: scale(1.05);
}

/* Photo Overlay */
.photo-item {
    position: relative;
}

.photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 12px 8px 8px;
    opacity: 0;
    transition: var(--transition-normal);
}

.photo-item:hover .photo-overlay {
    opacity: 1;
}

.photo-title {
    font-size: 0.8rem;
    font-weight: 500;
    line-height: 1.2;
    display: block;
}

/* No Portfolio Message */
.no-portfolio-message {
    text-align: center;
    padding: 60px 20px;
    background: var(--gray-50);
    border-radius: 12px;
    border: 2px dashed var(--gray-300);
}

.no-portfolio-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: var(--gray-200);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-400);
    font-size: 2.5rem;
}

.no-portfolio-message h4 {
    color: var(--gray-600);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.no-portfolio-message p {
    color: var(--gray-500);
    font-size: 0.95rem;
    margin: 0;
}

/* === SIDEBAR === */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-spacer {
    height: 0;
    visibility: hidden;
}

.sidebar-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--gray-200);
    overflow: hidden;
}

.card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 24px 24px 0;
}

.card-title i {
    color: var(--primary-light);
    font-size: 1.3rem;
}

/* === CONTACT CARD === */
.contact-info {
    padding: 0 24px 24px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--gray-200);
}

.contact-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.contact-icon {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    background: var(--primary-light-light);
    color: var(--primary-light);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.contact-text {
    flex: 1;
}

.contact-text label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--gray-500);
    text-transform: uppercase;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}

.contact-text span {
    color: var(--gray-700);
    font-weight: 500;
    line-height: 1.4;
}

.contact-actions {
    padding: 0 24px 24px;
}

.btn-contact-primary {
    background: linear-gradient(135deg, var(--accent-orange), #e6940e);
    color: var(--white);
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    width: 100%;
    transition: var(--transition-normal);
    box-shadow: var(--shadow-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-contact-primary:hover {
    background: linear-gradient(135deg, #e6940e, var(--accent-orange));
    color: var(--white);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* === HOURS CARD === */
.hours-list {
    padding: 0 24px 24px;
}

.hours-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid var(--gray-200);
}

.hours-item:last-child {
    border-bottom: none;
}

.day {
    color: var(--gray-700);
    font-weight: 500;
}

.time {
    color: var(--gray-600);
    font-weight: 600;
}

/* === RESPONSE CARD === */
.response-stats {
    padding: 0 24px 24px;
}

.response-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.response-item:last-child {
    margin-bottom: 0;
}

.response-icon {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    background: var(--primary-light-light);
    color: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}

.response-text {
    flex: 1;
}

.response-text strong {
    display: block;
    color: var(--primary-dark);
    font-weight: 600;
    margin-bottom: 2px;
}

.response-text span {
    color: var(--gray-500);
    font-size: 0.9rem;
}

/* === DESKTOP SPECIFIC STYLES === */
@media (min-width: 1200px) {
    .profile-sidebar {
        position: sticky;
        top: 140px; /* Aligned với content section */
    }
    
    .sidebar-spacer {
        height: 140px; /* Tạo space để align với content */
        visibility: visible;
    }
    
    .profile-navigation {
        z-index: 5; /* Giảm z-index để không conflict */
    }
}

/* === RESPONSIVE DESIGN === */
@media (max-width: 1199px) {
    .location-cta-section {
        justify-content: center;
    }
    
    .location-card {
        max-width: 100%;
    }
}

@media (max-width: 991px) {
    .profile-header {
        padding: 24px 0;
    }
    
    .contractor-name {
        font-size: 2rem;
    }
    
    .contractor-avatar {
        width: 80px;
        height: 80px;
    }
    
    .status-badge {
        bottom: -6px;
        right: -6px;
        padding: 5px 10px;
        font-size: 11px;
        border-radius: 16px;
        transform: scale(0.9);
    }
    
    .contractor-avatar-section {
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .location-cta-section {
        justify-content: stretch;
        margin-top: 0;
    }
    
    .location-card {
        max-width: 100%;
        margin-bottom: 0;
    }
    
    .profile-content {
        padding: 24px 0;
    }
    
    .content-section {
        padding: 24px;
        margin-bottom: 16px;
    }
    
    .highlights {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    
    .rating-overview {
        flex-direction: column;
        gap: 24px;
        text-align: center;
        padding: 24px;
    }
    
    .profile-sidebar {
        margin-top: 24px;
        gap: 16px;
    }
}

@media (max-width: 767px) {
    .profile-header {
        padding: 20px 0;
    }
    
    .contractor-name {
        font-size: 1.8rem;
        margin-bottom: 6px;
    }
    
    .contractor-category {
        font-size: 1rem;
        margin-bottom: 16px;
    }
    
    .contractor-avatar-section {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 16px;
    }
    
    .contractor-avatar {
        width: 90px;
        height: 90px;
    }
    
    .status-badge {
        bottom: -4px;
        right: -4px;
        padding: 4px 8px;
        font-size: 10px;
        border-radius: 12px;
        transform: scale(0.85);
    }
    
    .contractor-meta {
        align-items: center;
        gap: 8px;
    }
    
    .rating-section {
        justify-content: center;
    }
    
    .experience-badge,
    .location-info {
        justify-content: center;
        font-size: 14px;
    }
    
    .location-card {
        padding: 20px;
    }
    
    .location-address {
        font-size: 1rem;
        padding: 12px;
    }
    
    .btn-primary-cta {
        padding: 12px 20px;
        font-size: 15px;
    }
    
    .profile-navigation {
        position: static;
    }
    
    .nav-tabs {
        overflow-x: auto;
        flex-wrap: nowrap;
        -webkit-overflow-scrolling: touch;
    }
    
    .nav-tabs .nav-item {
        flex-shrink: 0;
    }
    
    .nav-tabs .nav-link {
        padding: 12px 16px;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .content-section {
        padding: 20px;
        border-radius: 12px;
    }
    
    .section-title {
        font-size: 1.3rem;
        margin-bottom: 20px;
    }
    
    .description {
        font-size: 1rem;
        margin-bottom: 24px;
    }
    
    .highlight-item {
        padding: 16px;
        gap: 12px;
    }
    
    .highlight-icon {
        width: 40px;
        height: 40px;
        font-size: 18px;
    }
    
    .highlight-text h4 {
        font-size: 1rem;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .service-item {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
    
    .rating-overview {
        padding: 20px;
        gap: 20px;
    }
    
    .rating-number {
        font-size: 3rem;
    }
    
    .rating-breakdown {
        width: 100%;
    }
    
    .rating-row {
        gap: 12px;
        margin-bottom: 10px;
    }
    
    .star-label {
        min-width: 50px;
        font-size: 14px;
    }
    
    .percentage {
        min-width: 35px;
        font-size: 14px;
    }
    
    .review-item {
        padding: 16px;
    }
    
    .reviewer-avatar {
        width: 36px;
        height: 36px;
        font-size: 18px;
    }
    
    .photo-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }
    
    .sidebar-card {
        border-radius: 12px;
    }
    
    .card-title {
        font-size: 1.1rem;
        padding: 20px 20px 0;
    }
    
    .contact-info,
    .hours-list,
    .response-stats {
        padding: 0 20px 20px;
    }
    
    .contact-actions {
        padding: 0 20px 20px;
    }
    
    .profile-sidebar {
        position: static;
        margin-top: 20px;
    }
    
    .sidebar-spacer {
        display: none;
    }
}

    /* Enhanced Booking Modal Styles */
    .booking-steps {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 20px;
    }
    
    .step {
        text-align: center;
        position: relative;
        flex: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .step.active .step-number {
        background: #007bff;
        color: white;
    }
    
    .step.completed .step-number {
        background: #28a745;
        color: white;
    }
    
    .step-title {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }
    
    .step.active .step-title {
        color: #007bff;
        font-weight: 600;
    }
    
    .user-info-card {
        border: 2px solid #007bff;
    }
    
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
    }
    
    .quick-time-slots .btn {
        border-radius: 25px;
        color: #007bff !important;
        border-color: #007bff !important;
        background: transparent !important;
    }
    
    .quick-time-slots .btn:hover {
        background: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    .quick-time-slots .btn.active {
        background: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    /* Additional specificity for btn-outline-primary in quick-time-slots */
    .quick-time-slots .btn.btn-outline-primary {
        color: #007bff !important;
        border-color: #007bff !important;
        background-color: transparent !important;
    }
    
    .quick-time-slots .btn.btn-outline-primary:hover {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    .quick-time-slots .btn.btn-outline-primary.active {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    /* Maximum specificity to override any global CSS */
    .quick-time-slots .d-flex .btn.btn-outline-primary.quick-time {
        color: #007bff !important;
        border-color: #007bff !important;
        background-color: transparent !important;
        font-weight: 500 !important;
    }
    
    .quick-time-slots .d-flex .btn.btn-outline-primary.quick-time:hover,
    .quick-time-slots .d-flex .btn.btn-outline-primary.quick-time:focus {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    .quick-time-slots .d-flex .btn.btn-outline-primary.quick-time.active {
        background-color: #007bff !important;
        color: white !important;
        border-color: #007bff !important;
    }
    
    /* Ultra-specific selector to ensure color is visible */
    button.btn.btn-outline-primary.quick-time[data-time] {
        color: #007bff !important;
        border: 1px solid #007bff !important;
        background: transparent !important;
        text-decoration: none !important;
    }
    
    button.btn.btn-outline-primary.quick-time[data-time]:hover {
        background: #007bff !important;
        color: #ffffff !important;
        border-color: #007bff !important;
    }
    
    button.btn.btn-outline-primary.quick-time[data-time].active {
        background: #007bff !important;
        color: #ffffff !important;
        border-color: #007bff !important;
    }
    
    .booking-summary {
        border: 1px solid #dee2e6;
    }
    
    .form-control-lg, .form-select-lg {
        padding: 12px 16px;
        font-size: 16px;
    }
    
    /* Animation for step transitions */
    .booking-step-content {
        transition: all 0.3s ease;
    }
    
    .booking-step-content.fade-in {
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
            // Enhanced Booking Modal Script
            let currentStep = 1;
            const totalSteps = 3;
            
            // Initialize modal
            function initBookingModal() {
                updateStepDisplay();
                updateButtonsVisibility();
            }
            
            // Update step display
            function updateStepDisplay() {
                $('.step').removeClass('active completed');
                $('.booking-step-content').addClass('d-none');
                
                // Mark completed steps
                for(let i = 1; i < currentStep; i++) {
                    $(`.step[data-step="${i}"]`).addClass('completed');
                }
                
                // Mark current step as active
                $(`.step[data-step="${currentStep}"]`).addClass('active');
                $(`.booking-step-content[data-step="${currentStep}"]`).removeClass('d-none').addClass('fade-in');
                
                // Update progress bar
                const progress = (currentStep / totalSteps) * 100;
                $('.progress-bar').css('width', progress + '%');
            }
            
            // Update buttons visibility
            function updateButtonsVisibility() {
                if(currentStep === 1) {
                    $('#prevStep').hide();
                } else {
                    $('#prevStep').show();
                }
                
                if(currentStep === totalSteps) {
                    $('#nextStep').hide();
                    $('#submitBooking').show();
                } else {
                    $('#nextStep').show();
                    $('#submitBooking').hide();
                }
            }
            
            // Validate current step
            function validateCurrentStep() {
                let isValid = true;
                const currentStepContent = $(`.booking-step-content[data-step="${currentStep}"]`);
                
                // Clear previous error messages
                currentStepContent.find('.error-message').remove();
                currentStepContent.find('.is-invalid').removeClass('is-invalid');
                
                // Debug: Log current step and required fields
                console.log('Validating step:', currentStep);
                const requiredFields = currentStepContent.find('input[required], select[required], textarea[required]');
                console.log('Required fields found:', requiredFields.length);
                
                // Validate required fields (including textarea)
                requiredFields.each(function() {
                    const fieldName = $(this).attr('name');
                    const fieldValue = $(this).val();
                    console.log(`Field ${fieldName}:`, fieldValue);
                    
                    if (!fieldValue || fieldValue.trim() === '') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        $(this).after(`<div class="error-message text-danger mt-1">Vui lòng điền thông tin này</div>`);
                        console.log(`Field ${fieldName} is invalid`);
                    }
                });
                
                // Additional validation for specific fields
                if (currentStep === 1) {
                    // Validate phone number format
                    const phoneInput = $('#phone');
                    if (phoneInput.val()) {
                        const phoneRegex = /^[0-9]{10,11}$/;
                        if (!phoneRegex.test(phoneInput.val().replace(/\s/g, ''))) {
                            isValid = false;
                            phoneInput.addClass('is-invalid');
                            phoneInput.after(`<div class="error-message text-danger mt-1">Số điện thoại không hợp lệ (10-11 số)</div>`);
                        }
                    }
                    
                    // Validate email format if not logged in
                    const emailInput = $('#email');
                    if (emailInput.length && !emailInput.is(':hidden') && emailInput.val()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(emailInput.val())) {
                            isValid = false;
                            emailInput.addClass('is-invalid');
                            emailInput.after(`<div class="error-message text-danger mt-1">Email không hợp lệ</div>`);
                        }
                    }
                }
                
                console.log('Validation result:', isValid);
                return isValid;
            }
            
            // Next step button
            $('#nextStep').on('click', function() {
                if (validateCurrentStep() && currentStep < totalSteps) {
                    currentStep++;
                    updateStepDisplay();
                    updateButtonsVisibility();
                    updateSummary();
                }
            });
            
            // Previous step button
            $('#prevStep').on('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepDisplay();
                    updateButtonsVisibility();
                }
            });
            
            // Quick time slot buttons
            $('.quick-time').on('click', function() {
                const time = $(this).data('time');
                $('#appointmentTime').val(time);
                $('.quick-time').removeClass('active');
                $(this).addClass('active');
            });
            
            // Update summary
            function updateSummary() {
                const date = $('#appointmentDate').val();
                const time = $('#appointmentTime').val();
                
                if (date && time) {
                    const formattedDate = new Date(date).toLocaleDateString('vi-VN');
                    $('#summary-datetime').text(`${formattedDate} lúc ${time}`);
                } else {
                    $('#summary-datetime').text('Chưa chọn');
                }
            }
            
            // Date and time change events
            $('#appointmentDate, #appointmentTime').on('change', updateSummary);
            
            // Initialize modal when opened
            $('#appointmentModal').on('show.bs.modal', function() {
                currentStep = 1;
                initBookingModal();
            });
            
            // Remove validation classes on input
            $('#appointmentForm input, #appointmentForm select, #appointmentForm textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).next('.error-message').remove();
            });
            
            // Form submission
            $('#appointmentForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate all steps before submission
                let allStepsValid = true;
                let firstInvalidStep = null;
                
                for (let step = 1; step <= totalSteps; step++) {
                    currentStep = step;
                    if (!validateCurrentStep()) {
                        allStepsValid = false;
                        if (firstInvalidStep === null) {
                            firstInvalidStep = step;
                        }
                        console.log(`Step ${step} validation failed`);
                    }
                }
                
                if (!allStepsValid) {
                    // Jump to first invalid step
                    currentStep = firstInvalidStep;
                    updateStepDisplay();
                    updateButtonsVisibility();
                    console.log(`Jumping to invalid step: ${firstInvalidStep}`);
                    return;
                }
                
                // Reset to final step for submission
                currentStep = totalSteps;
                
                console.log('All steps valid, proceeding with submission');
                
                // Check if user is not logged in and email exists
                if (!$('input[name="email"]').is(':hidden')) {
                    const email = $('#email').val();
                    $.ajax({
                        url: '{{ route("appointments.checkEmail") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            email: email
                        },
                        success: function(response) {
                            if (response.exists) {
                                currentStep = 1; // Go back to step 1 for email error
                                updateStepDisplay();
                                updateButtonsVisibility();
                                $('#email').addClass('is-invalid');
                                $('#email').after(`<div class="error-message text-danger mt-1">Email đã tồn tại, vui lòng <a href="{{ route('user.login.v2') }}">đăng nhập</a></div>`);
                            } else {
                                submitForm();
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra, vui lòng thử lại');
                        }
                    });
                } else {
                    submitForm();
                }
            });
            
            function submitForm() {
                // Show loading state
                const submitBtn = $('#appointmentForm button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.html('<i class="las la-spinner la-spin me-2"></i>Đang xử lý...');
                submitBtn.prop('disabled', true);
                
                const formData = new FormData($('#appointmentForm')[0]);
                
                $.ajax({
                    url: $('#appointmentForm').attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            // Show success message with better UX
                            $('#appointmentModal').modal('hide');
                            
                            // Show success toast
                            const toast = $(`
                                <div class="toast-container position-fixed top-0 end-0 p-3">
                                    <div class="toast show" role="alert">
                                        <div class="toast-header bg-success text-white">
                                            <i class="las la-check-circle me-2"></i>
                                            <strong class="me-auto">Thành công!</strong>
                                        </div>
                                        <div class="toast-body">
                                            Đặt lịch thành công! Đang chuyển hướng...
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('body').append(toast);
                            
                            // Redirect after 2 seconds
                            setTimeout(() => {
                                window.location.href = response.redirect || '{{ route("appointments.index") }}';
                            }, 2000);
                        } else {
                            // Reset button state
                            submitBtn.html(originalText);
                            submitBtn.prop('disabled', false);
                            alert(response.message || 'Có lỗi xảy ra, vui lòng thử lại');
                        }
                    },
                    error: function(xhr) {
                        // Reset button state
                        submitBtn.html(originalText);
                        submitBtn.prop('disabled', false);
                        
                        const response = xhr.responseJSON;
                        if (response && response.errors) {
                            Object.keys(response.errors).forEach(key => {
                                const input = $(`[name="${key}"]`);
                                input.addClass('is-invalid');
                                input.after(`<div class="error-message text-danger mt-1">${response.errors[key][0]}</div>`);
                            });
                        } else {
                            alert('Có lỗi xảy ra, vui lòng thử lại');
                        }
                    }
                });
            }
        });
    </script>
@endpush

@push('script')
    <script>
        "use strict";
        function checkEmailExists(email) {
            fetch(`user/check-email?email=${email}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById('email-error').style.display = 'block';
                    } else {
                        document.getElementById('email-error').style.display = 'none';
                    }
                })
                .catch(error => console.error('Error checking email:', error));
        }

        // Navigation smooth scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#profileTabs .nav-link');
            
            // Handle navigation click
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    navLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Get target section
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);
                    
                    if (targetSection) {
                        // Calculate offset for sticky navigation
                        const navHeight = document.querySelector('.profile-navigation').offsetHeight;
                        const targetPosition = targetSection.offsetTop - navHeight - 20;
                        
                        // Smooth scroll to section
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Update active nav on scroll
            window.addEventListener('scroll', function() {
                const sections = document.querySelectorAll('.content-section');
                const navHeight = document.querySelector('.profile-navigation').offsetHeight;
                const scrollPos = window.scrollY + navHeight + 100; // Add offset
                
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    const correspondingNav = document.querySelector(`#profileTabs a[href="#${sectionId}"]`);
                    
                    if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                        // Remove active class from all nav links
                        navLinks.forEach(l => l.classList.remove('active'));
                        
                        // Add active class to corresponding nav
                        if (correspondingNav) {
                            correspondingNav.classList.add('active');
                        }
                    }
                });
            });
        });

        // Smooth scrolling functions
        function scrollToSection(sectionId) {
            const element = document.querySelector(sectionId);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function scrollToContact() {
            const element = document.querySelector('#reviews-section');
            if (element) {
                const navHeight = document.querySelector('.profile-navigation').offsetHeight;
                const targetPosition = element.offsetTop - navHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }

        // Intersection Observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);

            // Observe all content sections
            document.querySelectorAll('.content-section, .sidebar-card, .floating-badge').forEach(el => {
                observer.observe(el);
            });

            // Parallax effect for decorations
            window.addEventListener('scroll', function() {
                const scrolled = window.pageYOffset;
                const decorations = document.querySelectorAll('.decoration-circle');
                
                decorations.forEach((decoration, index) => {
                    const speed = (index + 1) * 0.5;
                    decoration.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });

            // Auto-hide scroll indicator after first scroll
            let hasScrolled = false;
            window.addEventListener('scroll', function() {
                if (!hasScrolled && window.pageYOffset > 100) {
                    hasScrolled = true;
                    const indicator = document.querySelector('.scroll-indicator');
                    if (indicator) {
                        indicator.style.opacity = '0';
                    }
                }
            });
        });
    </script>
@endpush

@push('meta')
    <meta name="description" content="Khám phá chuyên gia {{ $company->name }} tại {{ $company->address }}. Xem kỹ năng, dự án tiêu biểu và đánh giá.">
    <meta name="keywords" content="{{ $company->name }}, thuê chuyên gia, {{ $company->address }}, {{ is_array($company->tags) ? implode(', ', $company->tags) : $company->tags }}">
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "{{ $company->name }}",
        "jobTitle": "Chuyên Gia",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $company->address }}"
        },
        "email": "{{ $company->email }}"
    }
    </script>
@endpush