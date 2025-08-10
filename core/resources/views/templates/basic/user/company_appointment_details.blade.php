@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="company-appointment-details-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <div class="breadcrumb-nav">
                            <a href="{{ route('company.appointments.index') }}" class="breadcrumb-link">
                                <i class="las la-arrow-left"></i>
                                Quay lại danh sách
                            </a>
                        </div>
                        <h1 class="page-title">
                            <i class="las la-briefcase me-3"></i>
                            Chi tiết lịch hẹn
                        </h1>
                        <p class="page-subtitle">Thông tin chi tiết về lịch hẹn #{{ $appointment->id }}</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <div class="status-badge status-{{ $appointment->status }}">
                            @if($appointment->status === 'pending')
                                <i class="las la-clock"></i> Chờ xử lý
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
                    <!-- Customer Information Card -->
                    <div class="detail-card customer-card">
                        <div class="card-header">
                            <h3><i class="las la-user me-2"></i>Thông tin khách hàng</h3>
                            @if($appointment->status === 'pending')
                                <span class="unlock-badge">
                                    <i class="las la-lock"></i> Cần xác nhận để mở khóa
                                </span>
                            @elseif(isset($appointment->customer_info_unlocked) && $appointment->customer_info_unlocked)
                                <span class="unlock-badge unlocked">
                                    <i class="las la-unlock"></i> Đã mở khóa
                                </span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="customer-profile">
                                <div class="customer-avatar">
                                    <i class="las la-user-circle"></i>
                                </div>
                                <div class="customer-info">
                                    <h4>{{ $appointment->recipient_name }}</h4>
                                    <div class="customer-details">
                                        @if($appointment->status !== 'pending' || (isset($appointment->customer_info_unlocked) && $appointment->customer_info_unlocked))
                                            <div class="detail-item">
                                                <i class="las la-phone"></i>
                                                <a href="tel:{{ $appointment->recipient_phone }}">{{ $appointment->recipient_phone }}</a>
                                            </div>
                                            @if($appointment->recipient_address)
                                                <div class="detail-item">
                                                    <i class="las la-map-marker-alt"></i>
                                                    <span>{{ $appointment->recipient_address }}</span>
                                                </div>
                                            @endif
                                            @if($appointment->user && $appointment->user->email)
                                                <div class="detail-item">
                                                    <i class="las la-envelope"></i>
                                                    <a href="mailto:{{ $appointment->user->email }}">{{ $appointment->user->email }}</a>
                                                </div>
                                            @endif
                                        @else
                                            <div class="locked-info">
                                                <i class="las la-lock"></i>
                                                <p>Thông tin liên hệ sẽ được hiển thị sau khi xác nhận lịch hẹn</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="customer-actions">
                                    @if($appointment->status !== 'pending' && $appointment->recipient_phone)
                                        <a href="tel:{{ $appointment->recipient_phone }}" class="btn btn-success">
                                            <i class="las la-phone"></i>
                                            Gọi ngay
                                        </a>
                                    @endif
                                    @if($appointment->status !== 'pending' && $appointment->user && $appointment->user->email)
                                        <a href="mailto:{{ $appointment->user->email }}" class="btn btn-outline-primary">
                                            <i class="las la-envelope"></i>
                                            Gửi email
                                        </a>
                                    @endif
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
                                        <i class="las la-building"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Người thợ phụ trách</h5>
                                        <p>{{ $appointment->company->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-icon">
                                        <i class="las la-map-marker"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5>Địa điểm làm việc</h5>
                                        <p>{{ $appointment->recipient_address ?? 'Chưa cung cấp' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Notes Card -->
                    @if($appointment->notes)
                        <div class="detail-card notes-card">
                            <div class="card-header">
                                <h3><i class="las la-sticky-note me-2"></i>Yêu cầu từ khách hàng</h3>
                            </div>
                            <div class="card-body">
                                <div class="notes-content">
                                    <p>{{ $appointment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Information -->
                    @if($appointment->status !== 'pending')
                        <div class="detail-card payment-card">
                            <div class="card-header">
                                <h3><i class="las la-credit-card me-2"></i>Thông tin thanh toán</h3>
                            </div>
                            <div class="card-body">
                                <div class="payment-info">
                                    <div class="payment-item">
                                        <span class="label">Phí truy cập thông tin:</span>
                                        <span class="value">50,000 VND</span>
                                    </div>
                                    <div class="payment-item">
                                        <span class="label">Trạng thái thanh toán:</span>
                                        <span class="value success">Đã thanh toán</span>
                                    </div>
                                    <div class="payment-item">
                                        <span class="label">Thời gian thanh toán:</span>
                                        <span class="value">{{ $appointment->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
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
                                                <span class="status-title">Chờ xử lý</span>
                                                <small>Cần xác nhận để truy cập thông tin khách hàng</small>
                                            </div>
                                        @elseif($appointment->status === 'confirmed')
                                            <i class="las la-check-circle"></i>
                                            <div>
                                                <span class="status-title">Đã xác nhận</span>
                                                <small>Có thể liên hệ khách hàng</small>
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
                                            <h6>Nhận lịch hẹn</h6>
                                            <small>{{ $appointment->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <div class="timeline-step {{ in_array($appointment->status, ['confirmed', 'completed']) ? 'completed' : '' }}">
                                        <div class="step-icon">
                                            <i class="las la-check"></i>
                                        </div>
                                        <div class="step-content">
                                            <h6>Xác nhận & Thanh toán</h6>
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
                                    <button class="btn btn-success btn-block" onclick="confirmAppointment({{ $appointment->id }})">
                                        <i class="las la-check me-2"></i>
                                        Xác nhận & Thanh toán 50k
                                    </button>
                                    <button class="btn btn-danger btn-block" onclick="cancelAppointment({{ $appointment->id }})">
                                        <i class="las la-times me-2"></i>
                                        Từ chối lịch hẹn
                                    </button>
                                @elseif($appointment->status === 'confirmed')
                                    <button class="btn btn-primary btn-block" onclick="completeAppointment({{ $appointment->id }})">
                                        <i class="las la-flag-checkered me-2"></i>
                                        Đánh dấu hoàn thành
                                    </button>
                                @endif

                                @if($appointment->status !== 'pending' && $appointment->recipient_phone)
                                    <a href="tel:{{ $appointment->recipient_phone }}" class="btn btn-outline-success btn-block">
                                        <i class="las la-phone me-2"></i>
                                        Gọi khách hàng
                                    </a>
                                @endif

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
                                    <span class="value">{{ $appointment->updated_at->diffForHumans() }}</span>
                                </div>
                                @if($appointment->status !== 'pending')
                                    <div class="info-item">
                                        <span class="label">Phí đã trả:</span>
                                        <span class="value">50,000 VND</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Appointment Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="confirm-warning">
                    <div class="warning-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                    <div class="warning-content">
                        <h6>Xác nhận lịch hẹn với {{ $appointment->recipient_name }}?</h6>
                        <p>Bạn sẽ được trừ <strong>50,000 VND</strong> từ ví để truy cập thông tin khách hàng đầy đủ.</p>
                        <p class="text-muted small">Sau khi xác nhận, bạn có thể liên hệ trực tiếp với khách hàng và sắp xếp lịch làm việc.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="confirmForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Xác nhận & Thanh toán</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Từ chối lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="cancel-warning">
                    <div class="warning-icon">
                        <i class="las la-exclamation-triangle"></i>
                    </div>
                    <div class="warning-content">
                        <h6>Bạn có chắc chắn muốn từ chối lịch hẹn này?</h6>
                        <p>Khách hàng <strong>{{ $appointment->recipient_name }}</strong> sẽ nhận được thông báo về việc từ chối.</p>
                        <p class="text-muted small">Hành động này không thể hoàn tác và bạn sẽ không thể truy cập thông tin khách hàng.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                <form id="cancelForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Có, từ chối</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Complete Appointment Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hoàn thành lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="complete-warning">
                    <div class="warning-icon">
                        <i class="las la-flag-checkered"></i>
                    </div>
                    <div class="warning-content">
                        <h6>Đánh dấu lịch hẹn đã hoàn thành?</h6>
                        <p>Xác nhận rằng bạn đã hoàn thành dịch vụ cho khách hàng <strong>{{ $appointment->recipient_name }}</strong>.</p>
                        <p class="text-muted small">Khách hàng sẽ có thể đánh giá dịch vụ của bạn sau khi hoàn thành.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="completeForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Hoàn thành</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* === COMPANY APPOINTMENT DETAILS PAGE STYLES === */
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

.company-appointment-details-page {
    background: var(--gray-50);
    min-height: 100vh;
}

/* === PAGE HEADER === */
.page-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    display: flex;
    align-items: center;
}

.unlock-badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--warning-light);
    color: var(--warning);
}

.unlock-badge.unlocked {
    background: var(--success-light);
    color: var(--success);
}

.card-body {
    padding: 1.5rem;
}

/* === CUSTOMER CARD === */
.customer-profile {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
}

.customer-avatar {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: var(--gray-500);
    flex-shrink: 0;
}

.customer-info {
    flex: 1;
}

.customer-info h4 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--gray-900);
}

.customer-details {
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
    color: var(--success);
    width: 20px;
    font-size: 1.1rem;
}

.detail-item a {
    color: var(--success);
    text-decoration: none;
}

.detail-item a:hover {
    text-decoration: underline;
}

.locked-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: var(--gray-50);
    border-radius: 12px;
    color: var(--gray-500);
}

.locked-info i {
    font-size: 1.5rem;
    color: var(--gray-400);
}

.customer-actions {
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
    background: var(--success-light);
    color: var(--success);
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
    border-left: 4px solid var(--success);
}

.notes-content p {
    margin: 0;
    color: var(--gray-700);
    line-height: 1.6;
}

/* === PAYMENT CARD === */
.payment-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.payment-item:last-child {
    border-bottom: none;
}

.payment-item .label {
    color: var(--gray-600);
    font-weight: 500;
}

.payment-item .value {
    color: var(--gray-900);
    font-weight: 600;
}

.payment-item .value.success {
    color: var(--success);
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

/* === MODAL STYLES === */
.confirm-warning,
.cancel-warning,
.complete-warning {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.warning-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.confirm-warning .warning-icon {
    background: var(--success-light);
    color: var(--success);
}

.cancel-warning .warning-icon {
    background: var(--warning-light);
    color: var(--warning);
}

.complete-warning .warning-icon {
    background: var(--primary-light);
    color: var(--primary);
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
    
    .customer-profile {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .customer-actions {
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
    
    .customer-actions {
        flex-direction: column;
    }
    
    .payment-item,
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

/* === FIX BTN-OUTLINE-SUCCESS === */
.btn.btn-outline-success {
    color: var(--success) !important;
    border-color: var(--success) !important;
    background-color: transparent !important;
}

.btn.btn-outline-success:hover,
.btn.btn-outline-success:focus {
    background-color: var(--success) !important;
    color: white !important;
    border-color: var(--success) !important;
}

.btn.btn-outline-success:active {
    background-color: var(--success) !important;
    color: white !important;
    border-color: var(--success) !important;
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm appointment function
    window.confirmAppointment = function(appointmentId) {
        const form = document.getElementById('confirmForm');
        form.action = `/company/appointments/${appointmentId}/confirm`;
        
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
        modal.show();
    };
    
    // Cancel appointment function
    window.cancelAppointment = function(appointmentId) {
        const form = document.getElementById('cancelForm');
        form.action = `/company/appointments/${appointmentId}/cancel`;
        
        const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
        modal.show();
    };
    
    // Complete appointment function
    window.completeAppointment = function(appointmentId) {
        const form = document.getElementById('completeForm');
        form.action = `/company/appointments/${appointmentId}/complete`;
        
        const modal = new bootstrap.Modal(document.getElementById('completeModal'));
        modal.show();
    };
    
    // Share appointment function
    window.shareAppointment = function() {
        if (navigator.share) {
            navigator.share({
                title: 'Lịch hẹn #{{ $appointment->id }}',
                text: 'Lịch hẹn với {{ $appointment->recipient_name }} vào {{ \Carbon\Carbon::parse($appointment->appointment_date)->format("d/m/Y") }}',
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