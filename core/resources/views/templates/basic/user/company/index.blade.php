@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="main-container">
            <!-- Header Section -->
            <div class="header-section">
                <div class="header-content">
                    <div class="header-info">
                        <h2 class="header-title-force">
                            <i class="las la-store me-2"></i>
                            @lang('Thợ của tôi')
                        </h2>
                        <p class="header-subtitle-force">@lang('Quản lý và theo dõi thông tin thợ của bạn')</p>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('user.company.create') }}" class="btn-create">
                            <i class="las la-plus"></i>
                            @lang('Tạo thợ mới')
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <div class="search-section">
                        <div class="search-container">
                            <input type="text" id="searchInput" class="modern-search-input" placeholder="@lang('Tìm kiếm theo tên thợ...')" autocomplete="off">
                            <i class="las la-search search-icon-modern"></i>
                        </div>
                    </div>
                    <div class="filters-section">
                        <button class="modern-filter-btn active" data-filter="all">
                            <i class="las la-list"></i>
                            @lang('Tất cả') 
                            <span class="filter-badge">{{ $companies->total() }}</span>
                        </button>
                        <button class="modern-filter-btn" data-filter="approved">
                            <i class="las la-check-circle"></i>
                            @lang('Đã duyệt')
                        </button>
                        <button class="modern-filter-btn" data-filter="pending">
                            <i class="las la-clock"></i>
                            @lang('Chờ duyệt')
                        </button>
                        <button class="modern-filter-btn" data-filter="rejected">
                            <i class="las la-times-circle"></i>
                            @lang('Từ chối')
                        </button>
                    </div>
                </div>
            </div>

            <!-- Companies Grid -->
            <div class="companies-grid-section">
                <div class="companies-grid" id="companiesGrid">
                    @forelse($companies as $company)
                        <div class="modern-company-card company-card" data-status="{{ $company->status }}">
                            <div class="modern-card-header">
                                <div class="modern-image-container">
                                    @if ($company->status == 1)
                                        <a href="{{ route('company.details', [$company->id, slug($company->name)]) }}" class="image-link">
                                            <img src="{{ getImage(getFilePath('company') . '/' . ($company->image ?? 'default.png'), getFileSize('company')) }}" alt="{{ $company->name }}" class="modern-company-image">
                                        </a>
                                    @else
                                        <img src="{{ getImage(getFilePath('company') . '/' . ($company->image ?? 'default.png'), getFileSize('company')) }}" alt="{{ $company->name }}" class="modern-company-image">
                                    @endif
                                    <div class="modern-status-badge">
                                        @php echo $company->statusBadge @endphp
                                    </div>
                                </div>
                                <div class="modern-actions">
                                    <!-- @if ($company->status == 1)
                                        <button class="modern-action-btn primary infoScript" data-bs-toggle="modal" 
                                            title="@lang('Mã nhúng đánh giá')" data-bs-target="#scriptModal" 
                                            data-name="{{ $company->name }}" data-id="{{ $company->id }}"
                                            data-sitename="{{ gs('site_name') }}" 
                                            data-url="{{ route('company.rating', encrypt($company->id)) }}"
                                            data-redirectURL="{{ route('company.details', [$company->id, slug($company->name)]) }}">
                                            <i class="las la-code"></i>
                                        </button>
                                    @endif -->
                                    <a href="{{ route('user.company.edit', $company->id) }}" class="modern-action-btn warning" title="@lang('Chỉnh sửa')">
                                        <i class="las la-edit"></i>
                                    </a>
                                    <!-- @if ($company->admin_feedback)
                                        <button class="modern-action-btn info feedback" data-bs-toggle="modal"
                                            data-bs-target="#companyFeedBackModal" title="@lang('Phản hồi từ admin')"
                                            data-feedback="{{ $company->admin_feedback }}">
                                            <i class="las la-comment-dots"></i>
                                        </button>
                                    @endif -->
                                </div>
                            </div>
                            
                            <div class="modern-card-body">
                                <div class="modern-company-title">
                                    @if ($company->status == 1)
                                        <a href="{{ route('company.details', [$company->id, $company->name]) }}" class="modern-title-link">
                                            {{ $company->name }}
                                        </a>
                                    @else
                                        <span class="modern-title-text">{{ $company->name }}</span>
                                    @endif
                                    <div class="modern-category-badge">
                                        <i class="las la-tag"></i>
                                        {{ $company->category->name ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="modern-company-meta">
                                    <div class="modern-meta-item">
                                        <i class="las la-map-marker-alt modern-meta-icon"></i>
                                        <span>{{ Str::limit($company->address, 40) }}</span>
                                    </div>
                                    <div class="modern-meta-item">
                                        <i class="las la-phone modern-meta-icon"></i>
                                        <span>{{ $company->phone ?: 'Chưa cập nhật' }}</span>
                                    </div>
                                    <div class="modern-meta-item">
                                        <i class="las la-calendar modern-meta-icon"></i>
                                        <span>{{ $company->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <div class="modern-rating-section">
                                    <div class="rating-stars">
                                        @php echo avgRating($company->avg_rating); @endphp
                                    </div>
                                    <div class="modern-rating-info">
                                        <span class="modern-rating-score">{{ number_format($company->avg_rating, 1) }}</span>
                                        <span class="modern-rating-count">({{ $company->reviews_count }} đánh giá)</span>
                                    </div>
                                </div>

                                @if($company->experience)
                                    <div class="modern-experience-badge">
                                        <i class="las la-medal"></i>
                                        {{ $company->experience }} năm kinh nghiệm
                                    </div>
                                @endif
                            </div>

                            <div class="modern-card-footer">
                                <div class="modern-stats-grid">
                                    <div class="modern-stat-item">
                                        <span class="modern-stat-value">0</span>
                                        <span class="modern-stat-label">Dự án</span>
                                    </div>
                                    <div class="modern-stat-item">
                                        <span class="modern-stat-value">{{ $company->reviews_count }}</span>
                                        <span class="modern-stat-label">Đánh giá</span>
                                    </div>
                                    <div class="modern-stat-item">
                                        <span class="modern-stat-value">0</span>
                                        <span class="modern-stat-label">Lượt xem</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="modern-empty-state">
                            <div class="modern-empty-icon">
                                <i class="las la-store"></i>
                            </div>
                            <h3 class="modern-empty-title">@lang('Chưa có thợ nào')</h3>
                            <p class="modern-empty-description">@lang('Bạn chưa tạo thông tin thợ nào. Hãy tạo thợ đầu tiên để bắt đầu nhận việc!')</p>
                            <a href="{{ route('user.company.create') }}" class="btn-create">
                                <i class="las la-plus"></i>
                                @lang('Tạo thợ đầu tiên')
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if ($companies->hasPages())
                <div class="pagination-wrapper">
                    {{ paginateLinks($companies) }}
                </div>
            @endif

            <!-- Advertisement -->
            <div class="advertisement-section">
                    @php echo getAdvertisement('728x90'); @endphp
            </div>
        </div>
    </section>

    <!-- Feedback Modal -->
    <div class="modal fade" id="companyFeedBackModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-comment-dots me-2"></i>
                        @lang('Phản hồi từ quản trị viên')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="feedback-content admin-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Modal -->
    <div class="modal fade" id="scriptModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-code me-2"></i>
                        @lang('Mã nhúng đánh giá')
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">@lang('Sao chép mã bên dưới và dán vào website của bạn để hiển thị đánh giá:')</p>
                    <div class="code-container">
                    <div id="copyURL" class="companyScript"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--base copyBoard" id="copyBoard">
                        <i class="las la-copy me-2"></i> 
                        @lang('Sao chép mã')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
:root {
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #06b6d4;
    --dark-color: #1f2937;
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
}

/* MODERN CONTAINER SYSTEM */
.contact-section {
    padding: 2rem 0 4rem 0 !important;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.contact-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.main-container {
    max-width: 1400px !important;
    margin: 0 auto !important;
    padding: 0 2rem !important;
    position: relative;
    z-index: 1;
}

/* MODERN CONTAINER SYSTEM */
.contact-section {
    padding: 2rem 0 4rem 0 !important;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
    overflow: hidden;
}

.contact-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    pointer-events: none;
}

.main-container {
    max-width: 1400px !important;
    margin: 0 auto !important;
    padding: 0 2rem !important;
    position: relative;
    z-index: 1;
}

/* ELEGANT HEADER SECTION */
.header-section {
    background: #102f4b !important;
    backdrop-filter: blur(20px) !important;
    border-radius: 20px !important;
    padding: 2.5rem !important;
    margin-bottom: 2.5rem !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    /* box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3) !important; */
}

.header-content {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 1.5rem !important;
}

.header-info h2.header-title-force {
    color: #ffffff !important;
    font-size: 2.25rem !important;
    font-weight: 800 !important;
    margin-bottom: 0.75rem !important;
    /* text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8) !important;
    background: rgba(0, 0, 0, 0.5) !important; */
    padding: 12px 20px !important;
    border-radius: 12px !important;
    display: inline-block !important;
    border: 2px solid rgba(255, 255, 255, 0.2) !important;
}

.header-info p.header-subtitle-force {
    color: #ffffff !important;
    font-size: 1.125rem !important;
    font-weight: 600 !important;
    margin: 0 !important;
    text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8) !important;
    /* background: rgba(0, 0, 0, 0.4) !important; */
    padding: 8px 16px !important;
    border-radius: 8px !important;
    display: inline-block !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

.header-actions .btn-create {
    background: rgba(255, 255, 255, 0.2) !important;
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
    padding: 1rem 2rem !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    border-radius: 12px !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(10px) !important;
    text-decoration: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
}

.header-actions .btn-create:hover {
    background: rgba(255, 255, 255, 0.3) !important;
    border-color: rgba(255, 255, 255, 0.5) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2) !important;
    color: white !important;
}

/* MODERN FILTER SECTION */
.filter-section {
    background: white !important;
    border-radius: 20px !important;
    padding: 2rem !important;
    margin-bottom: 2.5rem !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    border: 1px solid #f1f5f9 !important;
}

.filter-row {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 1.5rem !important;
    align-items: center !important;
}

.search-section {
    flex: 1 !important;
    min-width: 300px !important;
}

.search-container {
    position: relative !important;
    width: 100% !important;
}

.modern-search-input {
    width: 100% !important;
    padding: 1rem 1rem 1rem 3rem !important;
    border: 2px solid #e2e8f0 !important;
    border-radius: 12px !important;
    font-size: 1rem !important;
    background: #f8fafc !important;
    transition: all 0.3s ease !important;
    outline: none !important;
}

.modern-search-input:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    background: white !important;
}

.search-icon-modern {
    position: absolute !important;
    left: 1rem !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    color: #64748b !important;
    font-size: 1.25rem !important;
    pointer-events: none !important;
}

.filters-section {
    display: flex !important;
    gap: 0.75rem !important;
    flex-wrap: wrap !important;
    align-items: center !important;
}

.modern-filter-btn {
    padding: 0.75rem 1.25rem !important;
    border: 2px solid #e2e8f0 !important;
    background: white !important;
    border-radius: 10px !important;
    font-weight: 500 !important;
    color: #475569 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    font-size: 0.875rem !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    white-space: nowrap !important;
}

.modern-filter-btn:hover {
    border-color: var(--primary-color) !important;
    color: var(--primary-color) !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15) !important;
}

.modern-filter-btn.active {
    background: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
}

.filter-badge {
    background: rgba(255, 255, 255, 0.2) !important;
    color: inherit !important;
    padding: 0.25rem 0.5rem !important;
    border-radius: 6px !important;
    font-size: 0.75rem !important;
    font-weight: 600 !important;
}

.modern-filter-btn.active .filter-badge {
    background: rgba(255, 255, 255, 0.3) !important;
    color: white !important;
}

/* BEAUTIFUL COMPANIES GRID - COMPACT DESIGN */
.companies-grid-section {
    background: transparent !important;
    max-width: 1200px !important; /* Limit max width */
    margin: 0 auto !important; /* Center the grid */
    padding: 0 1rem !important; /* Add some padding */
}

.companies-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)) !important; /* Smaller cards */
    gap: 1.5rem !important; /* Reduced gap */
    margin-bottom: 3rem !important;
    max-width: 100% !important;
}

/* COMPACT COMPANY CARDS */
.modern-company-card {
    background: white !important;
    border-radius: 16px !important; /* Slightly smaller radius */
    overflow: hidden !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1) !important; /* Softer shadow */
    transition: all 0.3s ease !important;
    border: 1px solid #e2e8f0 !important;
    position: relative !important;
    height: auto !important; /* Auto height for compact design */
    display: flex !important;
    flex-direction: column !important;
    max-width: 400px !important; /* Limit card width */
    margin: 0 auto !important; /* Center cards */
}

.modern-company-card:hover {
    transform: translateY(-4px) !important; /* Reduced hover effect */
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    border-color: #cbd5e1 !important;
}

/* COMPACT CARD HEADER */
.modern-card-header {
    position: relative !important;
    height: 160px !important; /* Reduced height */
    overflow: hidden !important;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
}

.modern-image-container {
    width: 100% !important;
    height: 100% !important;
    position: relative !important;
}

.image-link,
.modern-image-container {
    display: block !important;
    width: 100% !important;
    height: 100% !important;
    border-radius: 0 !important;
    overflow: hidden !important;
}

.modern-company-image {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    transition: transform 0.4s ease !important;
}

.modern-company-card:hover .modern-company-image {
    transform: scale(1.05) !important;
}

.modern-status-badge {
    position: absolute !important;
    top: 1rem !important;
    left: 1rem !important;
    z-index: 2 !important;
}

/* SOPHISTICATED ACTION BUTTONS */
.modern-actions {
    position: absolute !important;
    top: 1rem !important;
    right: 1rem !important;
    display: flex !important;
    gap: 0.5rem !important;
    opacity: 0 !important;
    transform: translateY(-10px) !important;
    transition: all 0.3s ease !important;
    z-index: 3 !important;
}

.modern-company-card:hover .modern-actions {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.modern-action-btn {
    width: 44px !important;
    height: 44px !important;
    border-radius: 12px !important;
    border: 2px solid rgba(255, 255, 255, 0.9) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 18px !important;
    text-decoration: none !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    backdrop-filter: blur(10px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25) !important;
}

.modern-action-btn.primary { background: #3b82f6 !important; }
.modern-action-btn.warning { background: #f59e0b !important; }
.modern-action-btn.info { background: #06b6d4 !important; }

.modern-action-btn:hover {
    transform: scale(1.1) !important;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3) !important;
    border-color: white !important;
    color: white !important;
}

/* COMPACT CARD BODY */
.modern-card-body {
    padding: 1.25rem !important; /* Reduced padding */
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    gap: 1rem !important; /* Reduced gap */
}

.modern-company-title {
    display: flex !important;
    justify-content: space-between !important;
    align-items: flex-start !important;
    gap: 0.75rem !important;
    margin-bottom: 0.5rem !important;
}

.modern-title-link,
.modern-title-text {
    font-size: 1.125rem !important; /* Slightly smaller */
    font-weight: 700 !important;
    color: #1e293b !important;
    text-decoration: none !important;
    line-height: 1.3 !important;
    flex: 1 !important;
    margin: 0 !important;
}

.modern-title-link:hover {
    color: var(--primary-color) !important;
}

.modern-category-badge {
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0) !important;
    color: #475569 !important;
    padding: 0.375rem 0.625rem !important; /* Smaller padding */
    border-radius: 6px !important;
    font-size: 0.7rem !important; /* Smaller font */
    font-weight: 600 !important;
    white-space: nowrap !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.25rem !important;
    flex-shrink: 0 !important;
}

.modern-company-meta {
    display: flex !important;
    flex-direction: column !important;
    gap: 0.5rem !important; /* Reduced gap */
}

.modern-meta-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    color: #64748b !important;
    font-size: 0.8rem !important; /* Smaller font */
}

.modern-meta-icon {
    color: #94a3b8 !important;
    font-size: 1rem !important;
    width: 18px !important;
    flex-shrink: 0 !important;
}

.modern-rating-section {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 0.75rem !important; /* Reduced padding */
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.modern-rating-info {
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
}

.modern-rating-score {
    font-size: 1.125rem !important;
    font-weight: 700 !important;
    color: #f59e0b !important;
}

.modern-rating-count {
    color: #64748b !important;
    font-size: 0.875rem !important;
}

.modern-experience-badge {
    background: linear-gradient(135deg, #fbbf24, #f59e0b) !important;
    color: white !important;
    padding: 0.375rem 0.75rem !important; /* Smaller padding */
    border-radius: 16px !important;
    font-size: 0.7rem !important; /* Smaller font */
    font-weight: 600 !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.375rem !important;
    align-self: flex-start !important;
}

/* COMPACT CARD FOOTER */
.modern-card-footer {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
    border-top: 1px solid #e2e8f0 !important;
    padding: 1rem 1.25rem !important; /* Reduced padding */
    margin-top: auto !important;
}

.modern-stats-grid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 1rem !important; /* Reduced gap */
}

.modern-stat-item {
    text-align: center !important;
}

.modern-stat-value {
    display: block !important;
    font-size: 1.25rem !important; /* Smaller font */
    font-weight: 700 !important;
    color: #3b82f6 !important;
    line-height: 1.2 !important;
    margin-bottom: 0.25rem !important;
}

.modern-stat-label {
    font-size: 0.7rem !important; /* Smaller font */
    color: #64748b !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
}

/* BEAUTIFUL EMPTY STATE */
.modern-empty-state {
    text-align: center !important;
    padding: 4rem 2rem !important;
    background: white !important;
    border-radius: 20px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    border: 1px solid #f1f5f9 !important;
}

.modern-empty-icon {
    width: 120px !important;
    height: 120px !important;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6) !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 auto 2rem !important;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3) !important;
}

.modern-empty-icon i {
    font-size: 3rem !important;
    color: white !important;
}

.modern-empty-title {
    font-size: 1.5rem !important;
    font-weight: 700 !important;
    color: #1e293b !important;
    margin-bottom: 1rem !important;
}

.modern-empty-description {
    color: #64748b !important;
    margin-bottom: 2rem !important;
    max-width: 400px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    line-height: 1.6 !important;
}

/* RESPONSIVE DESIGN - COMPACT */
@media (max-width: 1024px) {
    .main-container {
        padding: 0 1.5rem !important;
    }
    
    .companies-grid-section {
        max-width: 100% !important;
        padding: 0 0.5rem !important;
    }
    
    .companies-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) !important;
        gap: 1.25rem !important;
    }
    
    .modern-company-card {
        max-width: 350px !important;
    }
}

@media (max-width: 768px) {
    .main-container {
        padding: 0 1rem !important;
    }
    
    .header-section {
        padding: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }
    
    .header-content {
        flex-direction: column !important;
        align-items: stretch !important;
        text-align: center !important;
    }
    
    .header-info h2.header-title-force {
        font-size: 1.75rem !important; /* Smaller on mobile */
        padding: 8px 16px !important;
    }
    
    .header-info p.header-subtitle-force {
        font-size: 1rem !important; /* Smaller on mobile */
        padding: 6px 12px !important;
    }
    
    .filter-section {
        padding: 1.25rem !important;
        margin-bottom: 1.5rem !important;
    }
    
    .filter-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 1rem !important;
    }
    
    .search-section {
        min-width: auto !important;
    }
    
    .filters-section {
        justify-content: center !important;
    }
    
    .companies-grid-section {
        padding: 0 !important;
    }
    
    .companies-grid {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .modern-company-card {
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    .modern-actions {
        opacity: 1 !important;
        transform: translateY(0) !important;
        position: static !important;
        justify-content: center !important;
        margin-top: 1rem !important;
    }
    
    .modern-card-header {
        height: 140px !important;
    }
    
    .modern-company-title {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 0.5rem !important;
    }
    
    .modern-stats-grid {
        gap: 0.75rem !important;
    }
    
    .modern-stat-value {
        font-size: 1.125rem !important;
    }
    
    .modern-stat-label {
        font-size: 0.65rem !important;
    }
}

@media (max-width: 480px) {
    .header-section {
        padding: 1rem !important;
        border-radius: 16px !important;
    }
    
    .header-info h2.header-title-force {
        font-size: 1.5rem !important;
        padding: 6px 12px !important;
    }
    
    .header-info p.header-subtitle-force {
        font-size: 0.9rem !important;
        padding: 4px 8px !important;
    }
    
    .filter-section {
        padding: 1rem !important;
        border-radius: 16px !important;
    }
    
    .modern-card-body {
        padding: 1rem !important;
    }
    
    .modern-card-footer {
        padding: 0.75rem 1rem !important;
    }
    
    .filters-section {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    
    .modern-filter-btn {
        justify-content: center !important;
        padding: 0.625rem 1rem !important;
    }
    
    .modern-company-card {
        border-radius: 12px !important;
    }
    
    .modern-card-header {
        height: 120px !important;
    }
}

/* SMOOTH ANIMATIONS */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-company-card {
    animation: slideInUp 0.6s ease-out;
}

.modern-company-card:nth-child(1) { animation-delay: 0.1s; }
.modern-company-card:nth-child(2) { animation-delay: 0.2s; }
.modern-company-card:nth-child(3) { animation-delay: 0.3s; }
.modern-company-card:nth-child(4) { animation-delay: 0.4s; }
.modern-company-card:nth-child(5) { animation-delay: 0.5s; }
.modern-company-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function() {
    // Search functionality
    $('#searchInput').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.company-card').each(function() {
            const companyName = $(this).find('.modern-title-link, .modern-title-text').text().toLowerCase();
            if (companyName.includes(searchTerm)) {
                $(this).show().addClass('animate__animated animate__fadeIn');
            } else {
                $(this).hide();
            }
        });
    });

    // Filter functionality
    $('.modern-filter-btn').on('click', function() {
        $('.modern-filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const filter = $(this).data('filter');
        $('.company-card').each(function() {
            if (filter === 'all') {
                $(this).show().addClass('animate__animated animate__fadeIn');
            } else {
                const status = $(this).data('status');
                let showCard = false;
                
                if (filter === 'approved' && status == 1) showCard = true;
                if (filter === 'pending' && status == 2) showCard = true;
                if (filter === 'rejected' && status == 0) showCard = true;
                
                if (showCard) {
                    $(this).show().addClass('animate__animated animate__fadeIn');
                } else {
                    $(this).hide();
                }
            }
        });
    });

    // Feedback modal
            $(".feedback").on('click', function() {
        $(".admin-feedback").text($(this).data('feedback'));
    });

    // Script modal
            $(".infoScript").on('click', function() {
                $(".companyScript").empty();

                let cid = $(this).data('id');
                let cName = $(this).data('name');
                let url = $(this).data('url');
                let siteUrl = $(this).data('redirecturl');
                let siteName = $(this).data('sitename');
                let halfStar = '{{ getImage('assets/images/half-star.svg') }}';
                let fullStar = '{{ getImage('assets/images/full-star.svg') }}';
                let blankStar = '{{ getImage('assets/images/blank-star.svg') }}';

        let scriptData = `<div title="${cName}" class="rating--here-${cid}" style="text-align: center; margin: 30px auto 30px;"></div><script>fetch("${url}").then((t=>t.json())).then((t=>{let a=t.rating?t.rating:0,s=0,e="",l=document.getElementsByClassName("rating--here-${cid}"),n=t=>e+='<img width="25px" style="margin: 5px auto 5px;" src="'+t+'"/>';for(;s<5;)n(a-s>=1?"${fullStar}":a-s>0?"${halfStar}":"${blankStar}"),s++;for(let a=0;a<l.length;a++)l[a].innerHTML="<div >"+e+"</div> <h6>"+t.rating+t.outOf+'</h6><a href="${siteUrl}" style="color: #d38a04;" target="_blank" class="text--base">Powered By : ${siteName} </a>'})).catch((function(t){console.warn("Something went wrong in script.",t)}));</script>`;

        $(".companyScript").text(scriptData);
            });

    // Copy functionality
            $('.copyBoard').on("click", function() {
        const textToCopy = document.getElementById("copyURL").textContent;
        navigator.clipboard.writeText(textToCopy).then(function() {
            notify('success', "@lang('Đã sao chép mã thành công')");
        }).catch(function() {
            // Fallback for older browsers
            const range = document.createRange();
                range.selectNode(document.getElementById("copyURL"));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand("copy");
                window.getSelection().removeAllRanges();
                notify('success', "@lang('Đã sao chép mã thành công')");
        });
    });

    // Smooth scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.company-card').forEach(card => {
        observer.observe(card);
            });
        });
    </script>
@endpush
