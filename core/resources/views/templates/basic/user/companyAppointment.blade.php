@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="company-appointments-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <h1 class="page-title">
                            <i class="las la-briefcase me-3"></i>
                            Quản lý lịch hẹn
                        </h1>
                        <p class="page-subtitle">Quản lý và xử lý tất cả lịch hẹn từ khách hàng</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <!-- Navigation Toggle -->
                        <div class="service-nav-toggle mb-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('company.appointments.index') }}" class="btn btn-light active">
                                    <i class="las la-briefcase me-1"></i>
                                    Lịch hẹn người thợ
                                </a>
                                <a href="{{ route('user.wallet.index') }}" class="btn btn-outline-light">
                                    <i class="las la-wallet me-1"></i>
                                    Ví
                                </a>
                                <a href="{{ route('appointments.index') }}" class="btn btn-outline-light">
                                    <i class="las la-calendar-check me-1"></i>
                                    Lịch hẹn cá nhân
                                </a>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-light" onclick="refreshAppointments()">
                            <i class="las la-sync me-2"></i>
                            Làm mới
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Alert Section -->
    @if(!empty($stats['companies_without_wallet']) || !$stats['can_afford_all'])
        <div class="wallet-alert-section">
            <div class="container">
                @if(!empty($stats['companies_without_wallet']))
                    <div class="alert alert-warning wallet-alert">
                        <div class="alert-icon">
                            <i class="las la-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                                                <h5>Cần tạo ví cho người thợ</h5>
                    <p>Bạn có {{ count($stats['companies_without_wallet']) }} người thợ chưa có ví. Cần tạo ví để xác nhận lịch hẹn.</p>
                            <div class="alert-actions">
                                <a href="{{ route('user.wallet.index') }}" class="btn btn-warning">
                                    <i class="las la-wallet me-2"></i>
                                    Tạo ví ngay
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif(!$stats['can_afford_all'] && $stats['pending_cost'] > 0)
                    <div class="alert alert-info wallet-alert">
                        <div class="alert-icon">
                            <i class="las la-info-circle"></i>
                        </div>
                        <div class="alert-content">
                            <h5>Số dư ví không đủ</h5>
                            <p>Bạn cần <strong>{{ number_format($stats['pending_cost']) }} VND</strong> để xác nhận tất cả lịch hẹn đang chờ, nhưng chỉ có <strong>{{ number_format($stats['total_balance']) }} VND</strong>.</p>
                            <div class="alert-actions">
                                <a href="{{ route('user.wallet.index') }}" class="btn btn-info">
                                    <i class="las la-plus me-2"></i>
                                    Nạp tiền
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-section">
        <div class="container">
            <div class="row g-4">
                <!-- Wallet Balance Card -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card wallet {{ $stats['can_afford_all'] ? 'sufficient' : 'insufficient' }}">
                        <div class="stat-icon">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['total_balance']) }}</h3>
                            <p>Số dư ví (VND)</p>
                            @if($stats['pending_cost'] > 0)
                                <small class="stat-note">
                                    Cần: {{ number_format($stats['pending_cost']) }} VND
                                </small>
                            @endif
                        </div>
                        <div class="stat-action">
                            <a href="{{ route('user.wallet.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="las la-cog"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Pending Appointments -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card pending">
                        <div class="stat-icon">
                            <i class="las la-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $appointments->where('status', 'pending')->count() }}</h3>
                            <p>Chờ xử lý</p>
                            @if($appointments->where('status', 'pending')->count() > 0)
                                <small class="stat-note">
                                    Phí: {{ number_format($stats['pending_cost']) }} VND
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Confirmed Appointments -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card confirmed">
                        <div class="stat-icon">
                            <i class="las la-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $appointments->where('status', 'confirmed')->count() }}</h3>
                            <p>Đã xác nhận</p>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Revenue -->
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card revenue">
                        <div class="stat-icon">
                            <i class="las la-chart-line"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['revenue_this_month']) }}</h3>
                            <p>Chi phí tháng này (VND)</p>
                            <small class="stat-note">
                                {{ $stats['appointments_this_month'] }} lịch hẹn
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Content -->
    <div class="appointments-content">
        <div class="container">
            @if ($appointments->isEmpty())
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-illustration">
                        <i class="las la-calendar-times"></i>
                    </div>
                    <h3>Chưa có lịch hẹn nào</h3>
                    <p>Bạn chưa nhận được lịch hẹn nào từ khách hàng.</p>
                    <div class="empty-actions">
                        <a href="{{ route('user.company.index') }}" class="btn btn-primary btn-lg">
                            <i class="las la-cog me-2"></i>
                            Cập nhật hồ sơ người thợ
                        </a>
                    </div>
                </div>
            @else
                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <ul class="nav nav-pills" id="appointmentTabs">
                        <li class="nav-item">
                            <button class="nav-link active" data-filter="all">
                                Tất cả <span class="badge">{{ $appointments->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-filter="pending">
                                Chờ xử lý <span class="badge">{{ $appointments->where('status', 'pending')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-filter="confirmed">
                                Đã xác nhận <span class="badge">{{ $appointments->where('status', 'confirmed')->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-filter="completed">
                                Hoàn thành <span class="badge">{{ $appointments->where('status', 'completed')->count() }}</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Appointments Grid -->
                <div class="appointments-grid">
                    @foreach ($appointments as $appointment)
                        <div class="appointment-card" data-status="{{ $appointment->status }}">
                            <div class="appointment-header">
                                <div class="customer-info">
                                    <div class="customer-avatar">
                                        <i class="las la-user-circle"></i>
                                    </div>
                                    <div class="customer-details">
                                        <h4>{{ $appointment->recipient_name }}</h4>
                                        <p>{{ $appointment->recipient_phone }}</p>
                                    </div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge status-{{ $appointment->status }}">
                                        @if($appointment->status === 'pending')
                                            <i class="las la-clock"></i> Chờ xử lý
                                        @elseif($appointment->status === 'confirmed')
                                            <i class="las la-check-circle"></i> Đã xác nhận
                                        @elseif($appointment->status === 'completed')
                                            <i class="las la-flag-checkered"></i> Hoàn thành
                                        @else
                                            <i class="las la-times-circle"></i> {{ ucfirst($appointment->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="appointment-body">
                                <div class="appointment-datetime">
                                    <div class="datetime-item">
                                        <i class="las la-calendar"></i>
                                        <span>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="datetime-item">
                                        <i class="las la-clock"></i>
                                        <span>{{ $appointment->appointment_time }}</span>
                                    </div>
                                </div>

                                <div class="appointment-details">
                                    <div class="detail-item">
                                        <i class="las la-building"></i>
                                        <span>{{ $appointment->company->name ?? 'N/A' }}</span>
                                    </div>
                                    @if($appointment->recipient_address)
                                        <div class="detail-item">
                                            <i class="las la-map-marker"></i>
                                            <span>{{ Str::limit($appointment->recipient_address, 60) }}</span>
                                        </div>
                                    @endif
                                </div>

                                @if($appointment->notes)
                                    <div class="appointment-notes">
                                        <i class="las la-sticky-note"></i>
                                        <span>{{ Str::limit($appointment->notes, 100) }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="appointment-footer">
                                @if($appointment->status === 'pending')
                                    <div class="appointment-cost">
                                        <div class="cost-info">
                                            <i class="las la-wallet"></i>
                                            <span>Phí xác nhận: <strong>50,000 VND</strong></span>
                                        </div>
                                        @php
                                            $company = $companies->firstWhere('id', $appointment->company_id);
                                            $hasWallet = $company && $company->wallet;
                                            $canAfford = $hasWallet && $company->wallet->balance >= 50000;
                                        @endphp
                                        @if(!$hasWallet)
                                            <div class="wallet-status no-wallet">
                                                <i class="las la-exclamation-triangle"></i>
                                                <span>Chưa có ví</span>
                                            </div>
                                        @elseif(!$canAfford)
                                            <div class="wallet-status insufficient">
                                                <i class="las la-times-circle"></i>
                                                <span>Không đủ tiền</span>
                                            </div>
                                        @else
                                            <div class="wallet-status sufficient">
                                                <i class="las la-check-circle"></i>
                                                <span>Đủ tiền</span>
                                            </div>
                                        @endif
                                    </div>
                                @elseif($appointment->status === 'confirmed')
                                    <div class="appointment-cost paid">
                                        <div class="cost-info">
                                            <i class="las la-check-circle"></i>
                                            <span>Đã thanh toán: <strong>50,000 VND</strong></span>
                                        </div>
                                    </div>
                                @endif

                                <div class="appointment-actions">
                                    <a href="{{ route('company.appointments.show', $appointment->id) }}" class="btn btn-outline-primary">
                                        <i class="las la-eye"></i>
                                        Chi tiết
                                    </a>
                                    
                                    @if ($appointment->status === 'pending')
                                        @if(!$hasWallet)
                                            <a href="{{ route('user.wallet.index') }}" class="btn btn-warning">
                                                <i class="las la-wallet"></i>
                                                Tạo ví
                                            </a>
                                        @elseif(!$canAfford)
                                            <a href="{{ route('user.wallet.index') }}" class="btn btn-info">
                                                <i class="las la-plus"></i>
                                                Nạp tiền
                                            </a>
                                        @else
                                            <button class="btn btn-success" onclick="confirmAppointment({{ $appointment->id }})">
                                                <i class="las la-check"></i>
                                                Xác nhận
                                            </button>
                                        @endif
                                        <button class="btn btn-outline-danger" onclick="cancelAppointment({{ $appointment->id }})">
                                            <i class="las la-times"></i>
                                            Từ chối
                                        </button>
                                    @elseif ($appointment->status === 'confirmed')
                                        <button class="btn btn-primary" onclick="completeAppointment({{ $appointment->id }})">
                                            <i class="las la-flag-checkered"></i>
                                            Hoàn thành
                                        </button>
                                    @endif
                                </div>
                                <div class="appointment-time">
                                    <small class="text-muted">
                                        Tạo {{ $appointment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
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
                        <h6>Xác nhận lịch hẹn này?</h6>
                        <p>Bạn sẽ được trừ <strong>50,000 VND</strong> từ ví để truy cập thông tin khách hàng.</p>
                        <p class="text-muted small">Sau khi xác nhận, bạn có thể liên hệ trực tiếp với khách hàng.</p>
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
                        <p>Khách hàng sẽ nhận được thông báo về việc từ chối này.</p>
                        <p class="text-muted small">Hành động này không thể hoàn tác.</p>
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
                        <p>Xác nhận rằng bạn đã hoàn thành dịch vụ cho khách hàng.</p>
                        <p class="text-muted small">Khách hàng sẽ có thể đánh giá dịch vụ của bạn.</p>
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
/* === COMPANY APPOINTMENTS PAGE STYLES === */
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

.company-appointments-page {
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

/* === STATS SECTION === */
.stats-section {
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    border-left: 4px solid;
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.stat-card.pending {
    border-left-color: var(--warning);
}

.stat-card.confirmed {
    border-left-color: var(--success);
}

.stat-card.completed {
    border-left-color: var(--primary);
}

.stat-card.total {
    border-left-color: var(--gray-600);
}

.stat-card.wallet {
    border-left-color: var(--primary);
}

.stat-card.wallet.sufficient {
    border-left-color: var(--success);
}

.stat-card.wallet.insufficient {
    border-left-color: var(--danger);
}

.stat-card.revenue {
    border-left-color: #8b5cf6;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.stat-card.pending .stat-icon {
    background: var(--warning-light);
    color: var(--warning);
}

.stat-card.confirmed .stat-icon {
    background: var(--success-light);
    color: var(--success);
}

.stat-card.completed .stat-icon {
    background: var(--primary-light);
    color: var(--primary);
}

.stat-card.total .stat-icon {
    background: var(--gray-100);
    color: var(--gray-600);
}

/* === WALLET STYLES === */
.stat-card.wallet {
    position: relative;
    border-left-color: var(--primary);
}

.stat-card.wallet.sufficient {
    border-left-color: var(--success);
}

.stat-card.wallet.insufficient {
    border-left-color: var(--danger);
}

.stat-card.wallet .stat-icon {
    background: var(--primary-light);
    color: var(--primary);
}

.stat-card.wallet.sufficient .stat-icon {
    background: var(--success-light);
    color: var(--success);
}

.stat-card.wallet.insufficient .stat-icon {
    background: var(--danger-light);
    color: var(--danger);
}

.stat-card.revenue {
    border-left-color: #8b5cf6;
}

.stat-card.revenue .stat-icon {
    background: #f3e8ff;
    color: #8b5cf6;
}

.stat-note {
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-top: 0.25rem;
    display: block;
}

.stat-action {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

/* === WALLET ALERT === */
.wallet-alert-section {
    margin-bottom: 2rem;
}

.wallet-alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    border: none;
    box-shadow: var(--shadow-md);
}

.alert-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.alert-warning .alert-icon {
    background: var(--warning-light);
    color: var(--warning);
}

.alert-info .alert-icon {
    background: var(--primary-light);
    color: var(--primary);
}

.alert-content {
    flex: 1;
}

.alert-content h5 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gray-900);
}

.alert-content p {
    margin-bottom: 1rem;
    color: var(--gray-600);
}

.alert-actions {
    margin-top: 1rem;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.stat-content p {
    color: var(--gray-600);
    margin-bottom: 0;
    font-weight: 500;
}

/* === EMPTY STATE === */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    animation: fadeIn 0.6s ease-out;
}

.empty-illustration {
    font-size: 6rem;
    color: var(--gray-300);
    margin-bottom: 2rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--gray-600);
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* === FILTER TABS === */
.filter-tabs {
    margin-bottom: 2rem;
}

.nav-pills .nav-link {
    background: white;
    color: var(--gray-600);
    border: 1px solid var(--gray-200);
    border-radius: 12px;
    padding: 0.75rem 1.5rem;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-pills .nav-link:hover {
    background: var(--gray-50);
    border-color: var(--gray-300);
    color: var(--gray-700);
}

.nav-pills .nav-link.active {
    background: var(--success);
    border-color: var(--success);
    color: white;
}

.nav-pills .nav-link .badge {
    background: var(--gray-200);
    color: var(--gray-700);
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

.nav-pills .nav-link.active .badge {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* === APPOINTMENTS GRID === */
.appointments-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 1.5rem;
}

.appointment-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    transition: all 0.3s ease;
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.appointment-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.appointment-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.customer-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.customer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--gray-500);
    flex-shrink: 0;
}

.customer-details h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.customer-details p {
    color: var(--gray-500);
    font-size: 0.9rem;
    margin-bottom: 0;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

.appointment-body {
    padding: 1.5rem;
}

.appointment-datetime {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
}

.datetime-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-700);
    font-weight: 500;
}

.datetime-item i {
    color: var(--success);
}

.appointment-details {
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    color: var(--gray-600);
    font-size: 0.9rem;
}

.detail-item i {
    color: var(--gray-400);
    width: 16px;
}

.appointment-notes {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
    margin-bottom: 1rem;
    color: var(--gray-600);
    font-size: 0.9rem;
}

.appointment-notes i {
    color: var(--gray-400);
    margin-top: 0.1rem;
}

.appointment-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.appointment-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.appointment-actions .btn {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    border-radius: 8px;
    font-weight: 500;
}

.appointment-time small {
    color: var(--gray-500);
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
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

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
        font-size: 1.8rem;
    }
    
    .page-subtitle {
        font-size: 0.95rem;
    }
    
    .page-header {
        padding: 2rem 0;
        text-align: center;
    }
    
    .service-nav-toggle {
        margin-bottom: 0.5rem;
        width: 100%;
    }
    
    .service-nav-toggle .btn-group {
        width: 100%;
        flex-direction: column;
    }
    
    .service-nav-toggle .btn {
        flex: 1;
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    
    .appointments-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .appointment-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
        padding: 1rem;
    }
    
    .appointment-body {
        padding: 1rem;
    }
    
    .appointment-footer {
        padding: 1rem;
    }
    
    .appointment-datetime {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .appointment-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .appointment-actions {
        width: 100%;
        justify-content: space-between;
        gap: 0.5rem;
    }
    
    .appointment-actions .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
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
    
    .nav-pills {
        flex-wrap: wrap;
    }
    
    .nav-pills .nav-link {
        margin-right: 0.25rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .customer-details h4 {
        font-size: 0.95rem;
    }
    
    .customer-details p {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.25rem;
        flex-direction: column;
        text-align: center;
        gap: 4px;
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
    
    .appointment-card {
        margin: 0 -8px 12px -8px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .appointment-header {
        padding: 12px;
    }
    
    .appointment-body {
        padding: 0 12px 12px 12px;
    }
    
    .appointment-footer {
        padding: 12px;
        border-top: 1px solid #f0f0f0;
    }
    
    .customer-info {
        flex-direction: row;
        align-items: center;
        gap: 12px;
    }
    
    .customer-avatar {
        width: 32px;
        height: 32px;
        border-radius: 6px;
    }
    
    .customer-details h4 {
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    
    .customer-details p {
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    .appointment-actions {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .appointment-actions .btn {
        flex: 1;
        min-width: 80px;
        font-size: 0.7rem;
        padding: 8px 12px;
        border-radius: 6px;
        line-height: 1.2;
        min-height: 32px;
    }
    
    .status-badge {
        font-size: 0.65rem;
        padding: 4px 8px;
        border-radius: 4px;
        line-height: 1.2;
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
    
    .appointment-datetime .datetime-item {
        font-size: 0.7rem;
        gap: 6px;
    }
    
    .appointment-details .detail-item {
        font-size: 0.7rem;
        gap: 6px;
        margin-bottom: 4px;
    }
    
    .appointment-notes {
        font-size: 0.7rem;
        margin-top: 8px;
    }
    
    .appointment-time small {
        font-size: 0.65rem;
    }
    
    .wallet-status {
        font-size: 0.65rem;
        padding: 4px 8px;
    }
    
    .appointment-cost {
        padding: 8px 12px;
        margin-bottom: 8px;
        border-radius: 8px;
    }
    
    .cost-info {
        font-size: 0.7rem;
    }
}

/* === FILTER FUNCTIONALITY === */
.appointment-card[data-status]:not([data-status="all"]) {
    transition: all 0.3s ease;
}

.appointment-card.hidden {
    display: none;
}

/* === SERVICE NAVIGATION TOGGLE === */
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
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
}

.service-nav-toggle .btn:not(.active):hover {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.service-nav-toggle .btn.active {
    background: white;
    color: var(--success);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.service-nav-toggle .btn i {
    font-size: 0.9rem;
}

/* === APPOINTMENT COST & WALLET STATUS === */
.appointment-cost {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 12px;
    margin-bottom: 1rem;
    border: 1px solid var(--gray-200);
}

.appointment-cost.paid {
    background: var(--success-light);
    border-color: var(--success);
}

.cost-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: var(--gray-700);
}

.cost-info i {
    color: var(--primary);
}

.appointment-cost.paid .cost-info i {
    color: var(--success);
}

.wallet-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 500;
}

.wallet-status.sufficient {
    background: var(--success-light);
    color: var(--success);
}

.wallet-status.insufficient {
    background: var(--danger-light);
    color: var(--danger);
}

.wallet-status.no-wallet {
    background: var(--warning-light);
    color: var(--warning);
}

.appointment-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-100);
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterButtons = document.querySelectorAll('[data-filter]');
    const appointmentCards = document.querySelectorAll('.appointment-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter cards
            appointmentCards.forEach(card => {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
    
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
    
    // Refresh appointments
    window.refreshAppointments = function() {
        location.reload();
    };
    
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.appointment-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Auto refresh every 30 seconds for new appointments
    setInterval(function() {
        // You can implement AJAX refresh here
        console.log('Checking for new appointments...');
    }, 30000);
});
</script>
@endpush