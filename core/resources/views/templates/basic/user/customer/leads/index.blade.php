@extends($activeTemplate . 'layouts.auth')

@section('content')
<div class="leads-page">
    <!-- Header Section -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="page-title-wrapper">
                    <h1 class="page-title">
                        <i class="las la-file-contract me-3"></i>
                        {{ $pageTitle }}
                    </h1>
                    <p class="page-subtitle">Quản lý các yêu cầu dịch vụ của bạn</p>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="page-actions">
                    <!-- Navigation Toggle -->
                    <div class="service-nav-toggle mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('user.customer.leads.index') }}" class="btn btn-primary active">
                                <i class="las la-file-contract me-1"></i>
                                Leads
                            </a>
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary">
                                <i class="las la-calendar-check me-1"></i>
                                Lịch hẹn
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('user.customer.leads.create') }}" class="btn btn--base btn-lg me-2">
                        <i class="las la-plus me-2"></i> Tạo Lead mới
                    </a>
                    <button class="btn btn-outline-secondary btn-lg" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="las la-filter me-1"></i> Bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Switch Banner -->
    <div class="quick-switch-banner mb-4">
        <div class="alert alert-info d-flex align-items-center">
            <i class="las la-info-circle me-2"></i>
            <div class="flex-grow-1">
                <strong>Mẹo:</strong> Bạn có thể chuyển sang xem <strong>Lịch hẹn</strong> để theo dõi các cuộc hẹn đã đặt trực tiếp, 
                hoặc ở lại đây để xem <strong>Leads</strong> đang tìm thợ.
            </div>
            <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary ms-3">
                <i class="las la-arrow-right me-1"></i>
                Xem Lịch hẹn
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-section mb-4">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card pending">
                    <div class="stat-icon">
                        <i class="las la-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $leads->where('status', 'active')->count() }}</h3>
                        <p>Đang hoạt động</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card confirmed">
                    <div class="stat-icon">
                        <i class="las la-handshake"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $leads->where('status', 'in_progress')->count() }}</h3>
                        <p>Đang thực hiện</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card completed">
                    <div class="stat-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $leads->where('status', 'completed')->count() }}</h3>
                        <p>Hoàn thành</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card total">
                    <div class="stat-icon">
                        <i class="las la-file-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $leads->count() }}</h3>
                        <p>Tổng leads</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leads Content -->
    <div class="leads-content">
        @if ($leads->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-illustration">
                    <i class="las la-file-times"></i>
                </div>
                <h3>Chưa có lead nào</h3>
                <p>Bạn chưa tạo lead nào. Hãy tạo yêu cầu dịch vụ để nhận báo giá từ các thợ!</p>
                <div class="empty-actions">
                    <a href="{{ route('user.customer.leads.create') }}" class="btn btn--base btn-lg">
                        <i class="las la-plus me-2"></i>
                        Tạo Lead ngay
                    </a>
                </div>
            </div>
        @else
            <!-- Filter Section -->
            <div class="collapse" id="filterCollapse">
                <div class="card card-body mb-4">
                    <form method="GET" action="{{ route('user.customer.leads.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Đang thực hiện</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn--base w-100">
                                    <i class="las la-search"></i> Lọc
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs mb-4">
                <ul class="nav nav-pills" id="leadTabs">
                    <li class="nav-item">
                        <button class="nav-link active" data-filter="all">
                            Tất cả <span class="badge">{{ $leads->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-filter="active">
                            Đang hoạt động <span class="badge">{{ $leads->where('status', 'active')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-filter="in_progress">
                            Đang thực hiện <span class="badge">{{ $leads->where('status', 'in_progress')->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-filter="completed">
                            Hoàn thành <span class="badge">{{ $leads->where('status', 'completed')->count() }}</span>
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Leads Grid -->
            <div class="leads-grid">
                @foreach($leads as $lead)
                    <div class="lead-card" data-status="{{ $lead->status }}">
                        <div class="lead-header">
                            <div class="lead-info">
                                <h4 class="lead-title">{{ Str::limit($lead->title, 50) }}</h4>
                                <p class="lead-category">
                                    <i class="las la-tag"></i>
                                    {{ $lead->category->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="lead-status">
                                <span class="status-badge status-{{ $lead->status }}">
                                    @if($lead->status === 'active')
                                        <i class="las la-clock"></i> Đang hoạt động
                                    @elseif($lead->status === 'in_progress')
                                        <i class="las la-handshake"></i> Đang thực hiện
                                    @elseif($lead->status === 'completed')
                                        <i class="las la-check-circle"></i> Hoàn thành
                                    @else
                                        <i class="las la-times-circle"></i> {{ ucfirst($lead->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="lead-body">
                            <div class="lead-details">
                                <div class="detail-item">
                                    <i class="las la-map-marker"></i>
                                    <span>{{ $lead->location }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="las la-dollar-sign"></i>
                                    <span>{{ $lead->getBudgetRange() }}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="las la-calendar"></i>
                                    <span>{{ $lead->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            @if($lead->description)
                                <div class="lead-description">
                                    <p>{{ Str::limit($lead->description, 100) }}</p>
                                </div>
                            @endif

                            <div class="lead-stats">
                                <div class="stat-item">
                                    <i class="las la-eye"></i>
                                    <span>{{ $lead->visibilities_count ?? 0 }} lượt xem</span>
                                </div>
                                <div class="stat-item">
                                    <i class="las la-shopping-cart"></i>
                                    <span>{{ $lead->purchases_count ?? 0 }} thợ quan tâm</span>
                                </div>
                            </div>
                        </div>

                        <div class="lead-footer">
                            <div class="lead-actions">
                                <a href="{{ route('user.customer.leads.show', $lead->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="las la-eye"></i>
                                    Chi tiết
                                </a>
                                @if($lead->status === 'active')
                                    <a href="{{ route('user.customer.leads.edit', $lead->id) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="las la-edit"></i>
                                        Chỉnh sửa
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($leads->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $leads->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

/* Stats Cards */
.stats-section {
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card.pending {
    border-left: 4px solid #ffc107;
}

.stat-card.confirmed {
    border-left: 4px solid #28a745;
}

.stat-card.completed {
    border-left: 4px solid #17a2b8;
}

.stat-card.total {
    border-left: 4px solid #6f42c1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.pending .stat-icon {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.confirmed .stat-icon {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.completed .stat-icon {
    background: rgba(23, 162, 184, 0.1);
    color: #17a2b8;
}

.total .stat-icon {
    background: rgba(111, 66, 193, 0.1);
    color: #6f42c1;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #2c3e50;
}

.stat-content p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.empty-illustration {
    font-size: 4rem;
    color: #e9ecef;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #adb5bd;
    margin-bottom: 2rem;
}

/* Filter Tabs */
.filter-tabs {
    background: white;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.filter-tabs .nav-pills .nav-link {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    margin-right: 0.5rem;
    border: none;
    background: transparent;
    color: #6c757d;
    font-weight: 500;
    transition: all 0.3s ease;
}

.filter-tabs .nav-pills .nav-link:hover {
    background: #f8f9fa;
    color: #495057;
}

.filter-tabs .nav-pills .nav-link.active {
    background: var(--bs-primary);
    color: white;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.filter-tabs .badge {
    background: rgba(255, 255, 255, 0.2);
    color: inherit;
    margin-left: 0.5rem;
}

.filter-tabs .nav-pills .nav-link.active .badge {
    background: rgba(255, 255, 255, 0.3);
}

/* Leads Grid */
.leads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.lead-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: none;
    height: fit-content;
}

.lead-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.lead-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.lead-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    line-height: 1.4;
}

.lead-category {
    color: #6c757d;
    font-size: 0.9rem;
    margin: 0;
}

.lead-category i {
    margin-right: 0.25rem;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    white-space: nowrap;
}

.status-badge.status-active {
    background: rgba(255, 193, 7, 0.1);
    color: #856404;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.status-badge.status-in_progress {
    background: rgba(40, 167, 69, 0.1);
    color: #155724;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.status-badge.status-completed {
    background: rgba(23, 162, 184, 0.1);
    color: #0c5460;
    border: 1px solid rgba(23, 162, 184, 0.3);
}

.lead-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.detail-item i {
    margin-right: 0.5rem;
    width: 16px;
    color: #adb5bd;
}

.lead-description {
    margin-bottom: 1rem;
}

.lead-description p {
    color: #6c757d;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.lead-stats {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.stat-item {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.85rem;
}

.stat-item i {
    margin-right: 0.25rem;
    color: #adb5bd;
}

.lead-footer {
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.lead-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.lead-actions .btn {
    font-size: 0.85rem;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
}

/* Service Navigation Toggle */
.service-nav-toggle .btn-group {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.service-nav-toggle .btn {
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    position: relative;
}

.service-nav-toggle .btn:not(.active) {
    background: #f8f9fa;
    color: #6c757d;
}

.service-nav-toggle .btn:not(.active):hover {
    background: #e9ecef;
    color: #495057;
}

.service-nav-toggle .btn.active {
    background: var(--bs-primary);
    color: white;
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.service-nav-toggle .btn i {
    font-size: 0.9rem;
}

/* Quick Switch Banner */
.quick-switch-banner .alert {
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-left: 4px solid #2196f3;
    margin-bottom: 0;
}

.quick-switch-banner .alert-info {
    color: #0c5460;
}

.quick-switch-banner .btn {
    border-radius: 6px;
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .page-header {
        padding: 2rem 0;
    }
    
    .page-title {
        font-size: 1.8rem;
    }
    
    .page-subtitle {
        font-size: 0.95rem;
    }
    
    .leads-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1.25rem;
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .stat-content h3 {
        font-size: 1.5rem;
    }
    
    .stat-content p {
        font-size: 0.85rem;
    }
    
    .service-nav-toggle {
        margin-bottom: 0.5rem;
        width: 100%;
    }
    
    .service-nav-toggle .btn-group {
        width: 100%;
    }
    
    .service-nav-toggle .btn {
        flex: 1;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    
    .quick-switch-banner .alert {
        flex-direction: column;
        text-align: center;
    }
    
    .quick-switch-banner .btn {
        margin-top: 0.5rem;
        margin-left: 0 !important;
    }
    
    .lead-header {
        flex-direction: column;
        gap: 0.5rem;
        padding: 1rem;
    }
    
    .lead-body {
        padding: 1rem;
    }
    
    .lead-footer {
        padding: 1rem;
    }
    
    .lead-actions {
        justify-content: center;
        gap: 0.5rem;
    }
    
    .lead-actions .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    
    .nav-pills .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.25rem;
        line-height: 1.3;
    }
    
    .page-subtitle {
        font-size: 0.8rem;
        line-height: 1.4;
    }
    
    .page-header {
        padding: 1.5rem 0;
    }
    
    .stat-card {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .stat-icon {
        width: 28px;
        height: 28px;
        font-size: 0.85rem;
        margin-bottom: 8px;
        border-radius: 6px;
    }
    
    .stat-content h3 {
        font-size: 1rem;
        line-height: 1.2;
        margin-bottom: 2px;
    }
    
    .stat-content p {
        font-size: 0.7rem;
        line-height: 1.3;
    }
    
    .service-nav-toggle .btn {
        font-size: 0.75rem;
        padding: 8px 12px;
        min-height: 36px;
    }
    
    .lead-card {
        margin: 0 -8px 12px -8px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .lead-header {
        padding: 12px;
    }
    
    .lead-body {
        padding: 0 12px 12px 12px;
    }
    
    .lead-footer {
        padding: 12px;
        border-top: 1px solid #f0f0f0;
    }
    
    .lead-actions .btn {
        flex: 1;
        min-width: 80px;
        font-size: 0.7rem;
        padding: 8px 12px;
        border-radius: 6px;
        line-height: 1.2;
        min-height: 32px;
    }
    
    .nav-pills .nav-link {
        padding: 8px 12px;
        font-size: 0.7rem;
        border-radius: 6px;
        margin-right: 4px;
        margin-bottom: 8px;
        line-height: 1.2;
        min-height: 32px;
    }
    
    .status-badge {
        font-size: 0.65rem;
        padding: 4px 8px;
        border-radius: 4px;
        line-height: 1.2;
    }
    
    .lead-info h4 {
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    
    .lead-info p {
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    .lead-budget {
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    .lead-location {
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    .lead-meta {
        font-size: 0.65rem;
    }
}

/* Animation for smooth transitions */
.service-nav-toggle .btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.service-nav-toggle .btn:hover {
    transform: translateY(-1px);
}

.service-nav-toggle .btn.active {
    transform: translateY(-2px);
}

/* Filter collapse */
.collapse .card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 12px;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
}

.pagination-wrapper .pagination {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

/* Filter Tabs JavaScript */
.filter-tabs .nav-link[data-filter] {
    cursor: pointer;
}

/* Hide/Show based on filter */
.lead-card[data-status] {
    display: block;
}

.lead-card[data-status].hidden {
    display: none;
}
</style>
@endpush

@push('script')
<script>
(function($) {
    "use strict";
    
    // Filter functionality
    $('#leadTabs .nav-link').on('click', function() {
        var filter = $(this).data('filter');
        
        // Update active tab
        $('#leadTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        
        // Filter leads
        if (filter === 'all') {
            $('.lead-card').show();
        } else {
            $('.lead-card').hide();
            $('.lead-card[data-status="' + filter + '"]').show();
        }
    });
    
    // Smooth animations
    $('.lead-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
})(jQuery);
</script>
@endpush 