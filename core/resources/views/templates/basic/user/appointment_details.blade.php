@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="appointment-details-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <div class="breadcrumb-nav">
                            <a href="{{ route('appointments.index') }}" class="breadcrumb-link">
                                <i class="las la-arrow-left"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                        <h1 class="page-title">
                            <i class="las la-calendar-check me-3"></i>
                            Chi tiết lịch hẹn
                        </h1>
                        <p class="page-subtitle">Thông tin chi tiết về lịch hẹn #{{ $appointment->id }}</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <div class="status-badge status-{{ $appointment->status }}">
                            @if($appointment->status === 'pending')
                                <i class="las la-clock"></i> Chờ xác nhận
                            @elseif($appointment->status === 'confirmed')
                                <i class="las la-check-circle"></i> Đã xác nhận
                            @elseif($appointment->status === 'completed')
                                <i class="las la-flag-checkered"></i> Hoàn thành
                            @else
                                <i class="las la-times-circle"></i> {{ ucfirst($appointment->status) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="appointment-content">
        <div class="container">
            <div class="row g-4">
                <!-- Left Column - Main Details -->
                <div class="col-lg-8">
                    <!-- Company Information Card -->
                    <div class="detail-card company-card">
                        <div class="card-header">
                            <h3><i class="las la-building me-2"></i>Thông tin thợ</h3>
                        </div>
                        <div class="card-body">
                            <div class="company-profile">
                                <div class="company-avatar">
                                    <img src="{{ getImage(getFilePath('company') . '/' . ($appointment->company->image ?? 'default.png'), getFileSize('company')) }}" 
                                         alt="{{ $appointment->company->name ?? 'Company' }}">
                                </div>
                                <div class="company-info">
                                    <h4 class="company-name">{{ $appointment->company->name ?? 'N/A' }}</h4>
                                    <div class="company-category" data-category-id="{{ $appointment->company->category_id ?? '' }}" style="display: none;"></div>
                                    <div class="company-details">
                                        @if($appointment->company->address)
                                            <div class="detail-item">
                                                <i class="las la-map-marker-alt"></i>
                                                <span>{{ $appointment->company->address }}</span>
                                            </div>
                                        @endif
                                        @if($appointment->company->phone && $appointment->status === 'confirmed')
                                            <div class="detail-item">
                                                <i class="las la-phone"></i>
                                                <a href="tel:{{ $appointment->company->phone }}">{{ $appointment->company->phone }}</a>
                                            </div>
                                        @elseif($appointment->company->phone && $appointment->status !== 'confirmed')
                                            <div class="detail-item">
                                                <i class="las la-phone"></i>
                                                <span class="text-muted">Số điện thoại sẽ hiển thị sau khi thợ xác nhận</span>
                                            </div>
                                        @endif
                                        @if($appointment->company->email)
                                            <div class="detail-item">
                                                <i class="las la-envelope"></i>
                                                <a href="mailto:{{ $appointment->company->email }}">{{ $appointment->company->email }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="company-actions">
                                    @if($appointment->company->phone && $appointment->status === 'confirmed')
                                        <a href="tel:{{ $appointment->company->phone }}" class="btn btn-primary">
                                            <i class="las la-phone"></i>
                                            Gọi ngay
                                        </a>
                                    @endif
                                    <a href="{{ route('company.details', [$appointment->company->id, slug($appointment->company->name ?? 'company')]) }}" class="btn btn-outline-primary">
                                        <i class="las la-eye"></i>
                                        Xem hồ sơ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details Card -->
                    <div class="detail-card appointment-info-card">
                        <div class="card-header">
                            <h3><i class="las la-calendar-alt me-2"></i>Thông tin lịch hẹn</h3>
                        </div>
                        <div class="card-body">
                            <div class="appointment-timeline">
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="las la-calendar"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Ngày hẹn</h5>
                                        <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="las la-clock"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Thời gian</h5>
                                        <p>{{ $appointment->appointment_time }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="las la-user"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Người nhận dịch vụ</h5>
                                        <p>{{ $appointment->recipient_name }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="las la-phone"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Số điện thoại</h5>
                                        <p><a href="tel:{{ $appointment->recipient_phone }}">{{ $appointment->recipient_phone }}</a></p>
                                    </div>
                                </div>
                                @if($appointment->recipient_address)
                                    <div class="timeline-item">
                                        <div class="timeline-icon">
                                            <i class="las la-map-marker"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5>Địa chỉ</h5>
                                            <p>{{ $appointment->recipient_address }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes Card -->
                    @if($appointment->notes)
                        <div class="detail-card notes-card">
                            <div class="card-header">
                                <h3><i class="las la-sticky-note me-2"></i>Ghi chú</h3>
                            </div>
                            <div class="card-body">
                                <div class="notes-content">
                                    <p>{{ $appointment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Actions & Status -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="detail-card status-card">
                        <div class="card-header">
                            <h3><i class="las la-info-circle me-2"></i>Trạng thái</h3>
                        </div>
                        <div class="card-body">
                            <div class="status-info">
                                <div class="current-status">
                                    <div class="status-badge status-{{ $appointment->status }} large">
                                        @if($appointment->status === 'pending')
                                            <i class="las la-clock"></i>
                                            <div>
                                                <span class="status-title">Chờ xác nhận</span>
                                                <small>Thợ sẽ liên hệ sớm</small>
                                            </div>
                                        @elseif($appointment->status === 'confirmed')
                                            <i class="las la-check-circle"></i>
                                            <div>
                                                <span class="status-title">Đã xác nhận</span>
                                                <small>Lịch hẹn đã được xác nhận</small>
                                            </div>
                                        @elseif($appointment->status === 'completed')
                                            <i class="las la-flag-checkered"></i>
                                            <div>
                                                <span class="status-title">Hoàn thành</span>
                                                <small>Dịch vụ đã hoàn tất</small>
                                            </div>
                                        @else
                                            <i class="las la-times-circle"></i>
                                            <div>
                                                <span class="status-title">{{ ucfirst($appointment->status) }}</span>
                                                <small>Lịch hẹn đã bị hủy</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="status-timeline">
                                    <div class="timeline-step {{ $appointment->created_at ? 'completed' : '' }}">
                                        <div class="step-icon">
                                            <i class="las la-plus"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Tạo lịch hẹn</h6>
                                            <small>{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="timeline-step {{ in_array($appointment->status, ['confirmed', 'completed']) ? 'completed' : '' }}">
                                        <div class="step-icon">
                                            <i class="las la-check"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Xác nhận</h6>
                                            <small>{{ in_array($appointment->status, ['confirmed', 'completed']) ? 'Đã xác nhận' : 'Chờ xác nhận' }}</small>
                                        </div>
                                    </div>
                                    <div class="timeline-step {{ $appointment->status === 'completed' ? 'completed' : '' }}">
                                        <div class="step-icon">
                                            <i class="las la-flag-checkered"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Hoàn thành</h6>
                                            <small>{{ $appointment->status === 'completed' ? 'Đã hoàn thành' : 'Chưa hoàn thành' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="detail-card actions-card">
                        <div class="card-header">
                            <h3><i class="las la-cogs me-2"></i>Hành động</h3>
                        </div>
                        <div class="card-body">
                            <div class="action-buttons">
                                @if($appointment->status === 'pending')
                                    <button class="btn btn-danger btn-block" onclick="cancelAppointment({{ $appointment->id }})">
                                        <i class="las la-times me-2"></i>
                                        Hủy lịch hẹn
                                    </button>
                                @endif
                                
                                @if($appointment->status === 'completed')
                                    <button class="btn btn-warning btn-block" onclick="reviewAppointment({{ $appointment->id }})">
                                        <i class="las la-star me-2"></i>
                                        Đánh giá dịch vụ
                                    </button>
                                @endif

                                <a href="{{ route('company.details', [$appointment->company->id, slug($appointment->company->name ?? 'company')]) }}" class="btn btn-outline-primary btn-block">
                                    <i class="las la-redo me-2"></i>
                                    Đặt lịch lại
                                </a>

                                <button class="btn btn-outline-secondary btn-block" onclick="shareAppointment()">
                                    <i class="las la-share me-2"></i>
                                    Chia sẻ
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="detail-card quick-info-card">
                        <div class="card-header">
                            <h3><i class="las la-info me-2"></i>Thông tin nhanh</h3>
                        </div>
                        <div class="card-body">
                            <div class="quick-info-list">
                                <div class="info-item">
                                    <span class="label">Mã lịch hẹn:</span>
                                    <span class="value">#{{ $appointment->id }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Ngày tạo:</span>
                                    <span class="value">{{ $appointment->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Cập nhật:</span>
                                    <span class="value">{{ $appointment->updated_at->locale('vi')->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="cancel-warning">
                    <div class="warning-icon">
                        <i class="las la-exclamation-triangle"></i>
                    </div>
                    <div class="warning-content">
                        <h6>Bạn có chắc chắn muốn hủy lịch hẹn này?</h6>
                        <p>Lịch hẹn với <strong>{{ $appointment->company->name ?? 'N/A' }}</strong> vào ngày <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</strong> sẽ bị hủy.</p>
                        <p class="text-muted small">Hành động này không thể hoàn tác.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không, giữ lại</button>
                <form id="cancelForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Có, hủy lịch hẹn</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* === APPOINTMENT DETAILS PAGE STYLES === */
:root {
    --primary: #3b82f6;
    --primary-light: #dbeafe;
    --success: #10b981;
    --success-light: #d1fae5;
    --warning: #f59e0b;
    --warning-light: #fef3c7;
    --danger: #ef4444;
    --danger-light: #fee2e2;
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
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.appointment-details-page {
    background: var(--gray-50);
    min-height: 100vh;
}

/* === PAGE HEADER === */
.page-header {
    background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
    color: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
}

.breadcrumb-nav {
    margin-bottom: 1rem;
}

.breadcrumb-link {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.breadcrumb-link:hover {
    color: white;
    transform: translateX(-2px);
}

.page-title-wrapper {
    animation: slideInLeft 0.6s ease-out;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.page-actions {
    animation: slideInRight 0.6s ease-out;
}

/* === DETAIL CARDS === */
.detail-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out;
}

.detail-card:hover {
    box-shadow: var(--shadow-lg);
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    background: var(--gray-50);
}

.card-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    display: flex;
    align-items: center;
}

.card-body {
    padding: 1.5rem;
}

/* === COMPANY CARD === */
.company-profile {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
}

.company-avatar {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    overflow: hidden;
    flex-shrink: 0;
}

.company-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.company-info {
    flex: 1;
}

.company-info h4 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--gray-900);
}

.company-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--gray-600);
}

.detail-item i {
    color: var(--primary);
    width: 20px;
    font-size: 1.1rem;
}

.detail-item a {
    color: var(--primary);
    text-decoration: none;
}

.detail-item a:hover {
    text-decoration: underline;
}

.company-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    min-width: 150px;
}

/* === APPOINTMENT TIMELINE === */
.appointment-timeline {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.timeline-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.timeline-content h5 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.timeline-content p {
    color: var(--gray-600);
    margin: 0;
    font-size: 1rem;
}

/* === STATUS CARD === */
.status-badge {
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.status-badge.large {
    padding: 1.25rem;
    font-size: 1rem;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.status-badge.large i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.status-title {
    font-weight: 600;
    font-size: 1.1rem;
    display: block;
}

.status-badge small {
    opacity: 0.8;
    font-size: 0.85rem;
}

.status-pending {
    background: var(--warning-light);
    color: var(--warning);
}

.status-confirmed {
    background: var(--success-light);
    color: var(--success);
}

.status-completed {
    background: var(--primary-light);
    color: var(--primary);
}

.status-canceled {
    background: var(--danger-light);
    color: var(--danger);
}

/* === STATUS TIMELINE === */
.status-timeline {
    margin-top: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.timeline-step {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
}

.timeline-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 40px;
    width: 2px;
    height: 20px;
    background: var(--gray-200);
}

.timeline-step.completed:not(:last-child)::after {
    background: var(--success);
}

.step-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-500);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.timeline-step.completed .step-icon {
    background: var(--success);
    color: white;
}

.step-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.step-content small {
    color: var(--gray-500);
    font-size: 0.8rem;
}

/* === ACTION BUTTONS === */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-block {
    width: 100%;
    justify-content: center;
}

/* === NOTES CARD === */
.notes-content {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 1.5rem;
    border-left: 4px solid var(--primary);
}

.notes-content p {
    margin: 0;
    color: var(--gray-700);
    line-height: 1.6;
}

/* === QUICK INFO === */
.quick-info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item .label {
    color: var(--gray-600);
    font-weight: 500;
}

.info-item .value {
    color: var(--gray-900);
    font-weight: 600;
}

/* === CANCEL MODAL === */
.cancel-warning {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.warning-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--warning-light);
    color: var(--warning);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.warning-content h6 {
    font-weight: 600;
    margin-bottom: 0.75rem;
    color: var(--gray-900);
}

.warning-content p {
    margin-bottom: 0.5rem;
    color: var(--gray-600);
}

/* === ANIMATIONS === */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .page-header {
        padding: 2rem 0;
        text-align: center;
    }
    
    .company-profile {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .company-actions {
        width: 100%;
        flex-direction: row;
    }
    
    .appointment-timeline {
        gap: 1rem;
    }
    
    .timeline-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .timeline-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .status-badge.large {
        text-align: center;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .detail-card {
        margin: 0 -0.5rem 1.5rem;
        border-radius: 12px;
    }
    
    .card-header,
    .card-body {
        padding: 1rem;
    }
    
    .company-actions {
        flex-direction: column;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

/* === STAGGER ANIMATIONS === */
.detail-card:nth-child(1) { animation-delay: 0.1s; }
.detail-card:nth-child(2) { animation-delay: 0.2s; }
.detail-card:nth-child(3) { animation-delay: 0.3s; }
.detail-card:nth-child(4) { animation-delay: 0.4s; }
.detail-card:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cancel appointment function
    window.cancelAppointment = function(appointmentId) {
        const form = document.getElementById('cancelForm');
        form.action = `/appointments/${appointmentId}/cancel`;
        
        const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
        modal.show();
    };
    
    // Review appointment function
    window.reviewAppointment = function(appointmentId) {
        // Redirect to appointments page with review modal
        window.location.href = '/appointments#review-' + appointmentId;
    };
    
    // Share appointment function
    window.shareAppointment = function() {
        if (navigator.share) {
            navigator.share({
                title: 'Lịch hẹn #{{ $appointment->id }}',
                text: 'Lịch hẹn với {{ $appointment->company->name ?? "N/A" }} vào {{ \Carbon\Carbon::parse($appointment->appointment_date)->format("d/m/Y") }}',
                url: window.location.href
            });
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Đã sao chép liên kết vào clipboard!');
            });
        }
    };
    
    // Add smooth scrolling for internal links
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