@extends($activeTemplate . 'layouts.auth')
@section('content')

<!-- Professional Header with Navigation -->
<div class="page-header-section bg-gradient-primary text-white py-4 mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-title d-flex align-items-center gap-3">
                    <div class="page-icon">
                        <i class="las la-file-alt"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1">Chi tiết Lead #{{ $lead->id }}</h1>
                        <p class="page-subtitle mb-0">{{ $lead->title }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <!-- Service Navigation -->
                <div class="service-nav">
                    <a href="{{ route('user.customer.leads.index') }}" class="btn btn-light btn-sm me-2">
                        <i class="las la-list me-1"></i>Tất cả Leads
                    </a>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="las la-calendar me-1"></i>Lịch hẹn
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Sidebar Section - Now at the top -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="sidebar-section">
                <div class="sidebar-grid">
                    <!-- Quick Stats Card -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6><i class="las la-chart-bar me-2"></i>Thống kê nhanh</h6>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="las la-users"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $lead->purchases->count() }}</h4>
                                    <p>Thợ quan tâm</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon bg-warning">
                                    <i class="las la-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $lead->created_at->diffInDays(now()) }}</h4>
                                    <p>Ngày đã tạo</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon bg-info">
                                    <i class="las la-eye"></i>
                                </div>
                                <div class="stat-content">
                                    <h4>{{ $lead->visibilities ? $lead->visibilities->count() : 0 }}</h4>
                                    <p>Lượt xem</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lead Status Card -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6><i class="las la-info-circle me-2"></i>Trạng thái</h6>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="status-badge-large">
                                @if($lead->status == 'active')
                                    <span class="badge-status active">
                                        <i class="las la-check-circle"></i>
                                        <span>Đang hoạt động</span>
                                    </span>
                                @elseif($lead->status == 'closed')
                                    <span class="badge-status closed">
                                        <i class="las la-times-circle"></i>
                                        <span>Đã đóng</span>
                                    </span>
                                @elseif($lead->status == 'expired')
                                    <span class="badge-status expired">
                                        <i class="las la-clock"></i>
                                        <span>Đã hết hạn</span>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="info-list mt-3">
                                <div class="info-item">
                                    <i class="las la-calendar-plus text-primary"></i>
                                    <span>{{ $lead->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($lead->expires_at)
                                <div class="info-item">
                                    <i class="las la-calendar-times text-warning"></i>
                                    <span>{{ \Carbon\Carbon::parse($lead->expires_at)->format('d/m/Y') }}</span>
                                </div>
                                @endif
                                @if($lead->needed_by)
                                <div class="info-item">
                                    <i class="las la-flag text-danger"></i>
                                    <span>{{ \Carbon\Carbon::parse($lead->needed_by)->format('d/m/Y') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if($lead->status == 'active')
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6><i class="las la-cogs me-2"></i>Hành động</h6>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="action-buttons">
                                <a href="{{ route('user.customer.leads.edit', $lead->id) }}" 
                                   class="btn btn-primary btn-sm w-100 mb-2">
                                    <i class="las la-edit me-2"></i>Chỉnh sửa
                                </a>
                                <form action="{{ route('user.customer.leads.close', $lead->id) }}" 
                                      method="POST" onsubmit="return confirm('Bạn có chắc muốn đóng lead này?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                        <i class="las la-times-circle me-2"></i>Đóng Lead
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Category & Tags -->
                    <div class="sidebar-card">
                        <div class="sidebar-card-header">
                            <h6><i class="las la-tags me-2"></i>Phân loại</h6>
                        </div>
                        <div class="sidebar-card-body">
                            <div class="category-tag">
                                <i class="las la-tag me-2"></i>
                                <span>{{ $lead->category->name ?? 'N/A' }}</span>
                            </div>
                            <div class="urgency-tag mt-2">
                                @if($lead->urgency == 'high')
                                    <span class="badge bg-danger">
                                        <i class="las la-exclamation-circle me-1"></i>Khẩn cấp
                                    </span>
                                @elseif($lead->urgency == 'medium')
                                    <span class="badge bg-warning">
                                        <i class="las la-clock me-1"></i>Bình thường
                                    </span>
                                @else
                                    <span class="badge bg-info">
                                        <i class="las la-hourglass me-1"></i>Không gấp
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <div class="col-12">
            <div class="main-content">
                <!-- Lead Details Card -->
                <div class="content-card mb-4">
                    <div class="card-header">
                        <h5><i class="las la-file-alt me-2"></i>Thông tin chi tiết</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Description -->
                                <div class="detail-section mb-4">
                                    <h6 class="section-title">
                                        <i class="las la-align-left me-2"></i>Mô tả công việc
                                    </h6>
                                    <div class="description-content">
                                        <p>{{ $lead->description }}</p>
                                    </div>
                                </div>

                                <!-- Address -->
                                @if(isset($lead->address['detail']))
                                <div class="detail-section mb-4">
                                    <h6 class="section-title">
                                        <i class="las la-map-marked-alt me-2"></i>Địa chỉ cụ thể
                                    </h6>
                                    <div class="address-content">
                                        <div class="location-info">
                                            <i class="las la-map-marker text-primary me-2"></i>
                                            <strong>{{ $lead->location }}</strong>
                                        </div>
                                        <div class="address-detail mt-2">
                                            <i class="las la-home text-secondary me-2"></i>
                                            {{ $lead->address['detail'] }}
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Requirements -->
                                @if(!empty($lead->requirements))
                                <div class="detail-section mb-4">
                                    <h6 class="section-title">
                                        <i class="las la-clipboard-list me-2"></i>Yêu cầu đặc biệt
                                    </h6>
                                    <div class="requirements-content">
                                        <ul class="requirement-list">
                                            @foreach($lead->requirements as $requirement)
                                                <li><i class="las la-check text-success me-2"></i>{{ $requirement }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @endif

                                <!-- Attachments -->
                                @if(!empty($lead->attachments))
                                <div class="detail-section">
                                    <h6 class="section-title">
                                        <i class="las la-paperclip me-2"></i>Tệp đính kèm
                                    </h6>
                                    <div class="attachments-grid">
                                        @foreach($lead->attachments as $attachment)
                                        <div class="attachment-item">
                                            <div class="attachment-icon">
                                                <i class="las la-file"></i>
                                            </div>
                                            <div class="attachment-info">
                                                <a href="{{ Storage::url($attachment['path']) }}" target="_blank" class="attachment-name">
                                                    {{ $attachment['name'] }}
                                                </a>
                                                <small class="attachment-size">
                                                    {{ formatBytes($attachment['size'] ?? 0) }}
                                                </small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="col-lg-4">
                                <!-- Budget & Timeline -->
                                <div class="info-card">
                                    <h6 class="info-card-title">
                                        <i class="las la-money-bill-wave me-2"></i>Ngân sách & Thời gian
                                    </h6>
                                    <div class="info-card-content">
                                        @if($lead->budget_min || $lead->budget_max)
                                        <div class="budget-info">
                                            <span class="label">Ngân sách:</span>
                                            <span class="value">
                                                @if($lead->budget_min && $lead->budget_max)
                                                    {{ number_format($lead->budget_min) }}₫ - {{ number_format($lead->budget_max) }}₫
                                                @elseif($lead->budget_min)
                                                    Từ {{ number_format($lead->budget_min) }}₫
                                                @elseif($lead->budget_max)
                                                    Tối đa {{ number_format($lead->budget_max) }}₫
                                                @endif
                                            </span>
                                        </div>
                                        @endif
                                        
                                        @if($lead->needed_by)
                                        <div class="timeline-info">
                                            <span class="label">Deadline:</span>
                                            <span class="value text-danger">
                                                {{ \Carbon\Carbon::parse($lead->needed_by)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        @endif
                                        
                                        <div class="price-info">
                                            <span class="label">Giá Lead:</span>
                                            <span class="value text-success">
                                                {{ number_format($lead->lead_price ?? 50000) }}₫
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contractors Section -->
                @if($lead->purchases->count() > 0)
                <div class="content-card">
                    <div class="card-header">
                        <h5><i class="las la-users me-2"></i>Thợ quan tâm ({{ $lead->purchases->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="contractors-grid">
                            @foreach($lead->purchases as $purchase)
                            <div class="contractor-card">
                                <div class="contractor-header">
                                    <div class="contractor-avatar">
                                        <img src="{{ getImage(getFilePath('company') . '/' . $purchase->company->image, getFileSize('company')) }}" 
                                             alt="{{ $purchase->company->name }}">
                                    </div>
                                    <div class="contractor-info">
                                        <h6 class="contractor-name">{{ $purchase->company->name }}</h6>
                                        <div class="contractor-meta">
                                            <span class="purchase-time">
                                                <i class="las la-clock me-1"></i>
                                                {{ $purchase->created_at->diffForHumans() }}
                                            </span>
                                            @if($purchase->company->average_rating)
                                            <span class="contractor-rating">
                                                <i class="las la-star text-warning me-1"></i>
                                                {{ number_format($purchase->company->average_rating, 1) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="contractor-actions">
                                    <a href="{{ route('company.details', [$purchase->company->id, slug($purchase->company->name)]) }}" 
                                       class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="las la-eye me-1"></i>Xem profile
                                    </a>
                                    
                                    @if($lead->status == 'active')
                                    <form action="{{ route('user.customer.leads.select-contractor', $lead->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="company_id" value="{{ $purchase->company->id }}">
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Bạn có chắc muốn chọn thợ này?')">
                                            <i class="las la-handshake me-1"></i>Chọn thợ
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="content-card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="las la-hourglass-half"></i>
                            </div>
                            <h5>Chưa có thợ quan tâm</h5>
                            <p class="text-muted">
                                Lead của bạn đang được hiển thị cho các thợ phù hợp.<br>
                                Hãy kiên nhẫn chờ đợi nhé!
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Header Styles */
.page-header-section {
    background: linear-gradient(135deg, #0b92d4 0%, #0d84c1 100%);
    border-radius: 0 0 16px 16px;
    margin: -24px -15px 0 -15px;
    padding: 24px 15px !important;
}

.page-title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-icon {
    width: 48px;
    height: 48px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.page-title h1 {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1.3;
    margin: 0;
}

.page-subtitle {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    line-height: 1.4;
}

.service-nav .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 8px 16px;
    transition: all 0.3s ease;
}

.service-nav .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Sidebar Section Styles */
.sidebar-section {
    margin-bottom: 0;
}

.sidebar-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 cards per row */
    gap: 20px;
}

.sidebar-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.sidebar-card-header {
    background: #f8f9fa;
    padding: 16px 20px;
    border-bottom: 1px solid #e9ecef;
}

.sidebar-card-header h6 {
    margin: 0;
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.sidebar-card-body {
    padding: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
}

.stat-item:not(:last-child) {
    border-bottom: 1px solid #f1f3f4;
    margin-bottom: 8px;
    padding-bottom: 16px;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: #0b92d4;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.stat-icon.bg-warning {
    background: #ffc107;
}

.stat-icon.bg-info {
    background: #0dcaf0;
}

.stat-content h4 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #212529;
}

.stat-content p {
    margin: 0;
    font-size: 0.8rem;
    color: #6c757d;
    line-height: 1.2;
}

.badge-status {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 16px;
    border-radius: 10px;
    font-weight: 500;
    width: 100%;
    justify-content: center;
}

.badge-status.active {
    background: #d1edff;
    color: #0b92d4;
    border: 1px solid #b3e0ff;
}

.badge-status.closed {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f1aeb5;
}

.badge-status.expired {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #6c757d;
}

.info-item i {
    width: 16px;
    font-size: 14px;
}

.action-buttons .btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.category-tag, .urgency-tag {
    font-size: 0.85rem;
    color: #495057;
}

/* Main Content Styles */
.main-content {
    padding-left: 0;
}

.content-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.content-card .card-header {
    background: #f8f9fa;
    padding: 20px 24px;
    border-bottom: 1px solid #e9ecef;
}

.content-card .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: #495057;
    font-size: 1.1rem;
}

.content-card .card-body {
    padding: 24px;
}

.detail-section {
    margin-bottom: 32px;
}

.section-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f3f4;
}

.description-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    border-left: 4px solid #0b92d4;
}

.description-content p {
    margin: 0;
    line-height: 1.6;
    color: #495057;
}

.address-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.location-info, .address-detail {
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.requirements-content {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.requirement-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.requirement-list li {
    padding: 8px 0;
    display: flex;
    align-items: center;
}

.requirement-list li:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.attachments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 16px;
}

.attachment-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.attachment-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.attachment-icon {
    width: 40px;
    height: 40px;
    background: #0b92d4;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.attachment-info {
    flex: 1;
}

.attachment-name {
    font-weight: 500;
    color: #495057;
    text-decoration: none;
    display: block;
}

.attachment-name:hover {
    color: #0b92d4;
}

.attachment-size {
    color: #6c757d;
    font-size: 0.8rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.info-card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e9ecef;
}

.info-card-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.budget-info, .timeline-info, .price-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.budget-info:not(:last-child),
.timeline-info:not(:last-child),
.price-info:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.value {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

/* Contractors Section */
.contractors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.contractor-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.contractor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.contractor-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.contractor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #0b92d4;
}

.contractor-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.contractor-info {
    flex: 1;
}

.contractor-name {
    font-size: 1rem;
    font-weight: 600;
    color: #495057;
    margin: 0 0 4px 0;
}

.contractor-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.purchase-time, .contractor-rating {
    font-size: 0.8rem;
    color: #6c757d;
    display: flex;
    align-items: center;
}

.contractor-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.contractor-actions .btn {
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.85rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2.5rem;
    color: #6c757d;
}

.empty-state h5 {
    color: #6c757d;
    margin-bottom: 12px;
}

.empty-state p {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 991px) {
    .sidebar-grid {
        grid-template-columns: repeat(2, 1fr); /* Keep 2-2 on tablet */
        gap: 16px;
    }
    
    .contractors-grid {
        grid-template-columns: 1fr;
    }
    
    .contractor-actions {
        justify-content: stretch;
    }
    
    .contractor-actions .btn {
        flex: 1;
    }
}

@media (max-width: 767px) {
    .sidebar-grid {
        grid-template-columns: 1fr; /* Single column on mobile */
        gap: 16px;
    }
    
    .page-title {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .page-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
    
    .page-title h1 {
        font-size: 1.25rem;
    }
    
    .page-subtitle {
        font-size: 0.8rem;
    }
    
    .service-nav .btn {
        font-size: 0.8rem;
        padding: 6px 12px;
    }
    
    .sidebar-card-body,
    .content-card .card-body {
        padding: 16px;
    }
    
    .stat-item {
        flex-direction: row;
        text-align: left;
        gap: 12px;
    }
    
    .stat-icon {
        width: 32px;
        height: 32px;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .stat-content h4 {
        font-size: 1rem;
    }
    
    .stat-content p {
        font-size: 0.75rem;
    }
    
    .contractor-header {
        flex-direction: column;
        text-align: center;
    }
    
    .contractor-avatar {
        width: 50px;
        height: 50px;
    }
    
    .contractor-actions {
        flex-direction: column;
    }
    
    .attachments-grid {
        grid-template-columns: 1fr;
    }
    
    .info-card {
        padding: 16px;
    }
    
    .budget-info, .timeline-info, .price-info {
        padding: 6px 0;
    }
    
    .label {
        font-size: 0.8rem;
    }
    
    .value {
        font-size: 0.85rem;
    }
}

/* Tablet specific - 2 rows of 2 cards */
@media (min-width: 480px) and (max-width: 767px) {
    .sidebar-grid {
        grid-template-columns: repeat(2, 1fr); /* 2-2 layout on larger mobile/small tablet */
        gap: 12px;
    }
    
    .sidebar-card-body {
        padding: 14px;
    }
    
    .stat-item {
        padding: 6px 0;
    }
    
    .stat-item:not(:last-child) {
        margin-bottom: 6px;
        padding-bottom: 12px;
    }
}
</style>

@endsection 