@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="appointments-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <h1 class="page-title">
                            <i class="las la-calendar-check me-3"></i>
                            Lịch hẹn của tôi
                        </h1>
                        <p class="page-subtitle">Quản lý và theo dõi tất cả lịch hẹn của bạn</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <a href="{{ route('company.all') }}" class="btn btn-primary btn-lg">
                            <i class="las la-plus me-2"></i>
                            Đặt lịch mới
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card pending">
                        <div class="stat-icon">
                            <i class="las la-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $appointments->where('status', 'pending')->count() }}</h3>
                            <p>Chờ xác nhận</p>
                        </div>
                    </div>
                </div>
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
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card completed">
                        <div class="stat-icon">
                            <i class="las la-flag-checkered"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $appointments->where('status', 'completed')->count() }}</h3>
                            <p>Hoàn thành</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card total">
                        <div class="stat-icon">
                            <i class="las la-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $appointments->count() }}</h3>
                            <p>Tổng lịch hẹn</p>
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
                    <p>Bạn chưa có lịch hẹn nào. Hãy tìm kiếm thợ phù hợp và đặt lịch ngay!</p>
                    <div class="empty-actions">
                        <a href="{{ route('company.all') }}" class="btn btn-primary btn-lg">
                            <i class="las la-search me-2"></i>
                            Tìm thợ ngay
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
                                Chờ xác nhận <span class="badge">{{ $appointments->where('status', 'pending')->count() }}</span>
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
                                <div class="company-info">
                                    <div class="company-avatar">
                                        <img src="{{ getImage(getFilePath('company') . '/' . ($appointment->company->image ?? 'default.png'), getFileSize('company')) }}" 
                                             alt="{{ $appointment->company->name ?? 'Company' }}">
                                    </div>
                                    <div class="company-details">
                                        <h4>{{ $appointment->company->name ?? 'N/A' }}</h4>
                                        <p>{{ Str::limit($appointment->company->address ?? '', 50) }}</p>
                                    </div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge status-{{ $appointment->status }}">
                                        @if($appointment->status === 'pending')
                                            <i class="las la-clock"></i> Chờ xác nhận
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
                                        <i class="las la-user"></i>
                                        <span>{{ $appointment->recipient_name }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="las la-phone"></i>
                                        <span>{{ $appointment->recipient_phone }}</span>
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
                                <div class="appointment-actions">
                                    <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn-outline-primary">
                                        <i class="las la-eye"></i>
                                        Chi tiết
                                    </a>
                                    @if ($appointment->status === 'pending')
                                        <button class="btn btn-outline-danger" onclick="cancelAppointment({{ $appointment->id }})">
                                            <i class="las la-times"></i>
                                            Hủy
                                        </button>
                                    @endif
                                    @if ($appointment->status === 'completed')
                                        <button class="btn btn-outline-warning" onclick="reviewAppointment({{ $appointment->id }})">
                                            <i class="las la-star"></i>
                                            Đánh giá
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

<!-- Cancel Appointment Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận hủy lịch hẹn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy lịch hẹn này không?</p>
                <p class="text-muted small">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
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
/* === APPOINTMENTS PAGE STYLES === */
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

.appointments-page {
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
    background: var(--primary);
    border-color: var(--primary);
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

.company-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.company-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.company-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.company-details h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.company-details p {
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
    color: var(--primary);
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
}

.appointment-actions .btn {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    border-radius: 8px;
    font-weight: 500;
}

/* Fix btn-outline-primary color issue */
.appointment-actions .btn.btn-outline-primary {
    color: var(--primary) !important;
    border-color: var(--primary) !important;
    background-color: transparent !important;
}

.appointment-actions .btn.btn-outline-primary:hover {
    background-color: var(--primary) !important;
    color: white !important;
    border-color: var(--primary) !important;
}

.appointment-actions .btn.btn-outline-primary:focus {
    background-color: var(--primary) !important;
    color: white !important;
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25) !important;
}

/* Fix btn-outline-danger color issue */
.appointment-actions .btn.btn-outline-danger {
    color: var(--danger) !important;
    border-color: var(--danger) !important;
    background-color: transparent !important;
}

.appointment-actions .btn.btn-outline-danger:hover {
    background-color: var(--danger) !important;
    color: white !important;
    border-color: var(--danger) !important;
}

/* Fix btn-outline-warning color issue */
.appointment-actions .btn.btn-outline-warning {
    color: var(--warning) !important;
    border-color: var(--warning) !important;
    background-color: transparent !important;
}

.appointment-actions .btn.btn-outline-warning:hover {
    background-color: var(--warning) !important;
    color: white !important;
    border-color: var(--warning) !important;
}

/* Fix small button variants */
.btn.btn-outline-primary.btn-sm {
    color: var(--primary) !important;
    border-color: var(--primary) !important;
    background-color: transparent !important;
}

.btn.btn-outline-primary.btn-sm:hover {
    background-color: var(--primary) !important;
    color: white !important;
    border-color: var(--primary) !important;
}

/* Global fix for all btn-outline-primary buttons on this page */
.appointments-page .btn.btn-outline-primary {
    color: var(--primary) !important;
    border-color: var(--primary) !important;
    background-color: transparent !important;
}

.appointments-page .btn.btn-outline-primary:hover,
.appointments-page .btn.btn-outline-primary:focus {
    background-color: var(--primary) !important;
    color: white !important;
    border-color: var(--primary) !important;
}

.appointments-page .btn.btn-outline-primary:active {
    background-color: var(--primary) !important;
    color: white !important;
    border-color: var(--primary) !important;
}

.appointment-time small {
    color: var(--gray-500);
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
        font-size: 2rem;
    }
    
    .page-header {
        padding: 2rem 0;
        text-align: center;
    }
    
    .appointments-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .appointment-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
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
    
    .stat-card {
        padding: 1.5rem;
    }
    
    .nav-pills {
        flex-wrap: wrap;
    }
    
    .nav-pills .nav-link {
        margin-right: 0.25rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .appointment-card {
        margin: 0 -0.5rem;
        border-radius: 12px;
    }
    
    .company-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .appointment-actions {
        width: 100%;
        justify-content: space-between;
    }
    
    .appointment-actions .btn {
        flex: 1;
        margin: 0 0.25rem;
    }
}

/* === FILTER FUNCTIONALITY === */
.appointment-card[data-status]:not([data-status="all"]) {
    transition: all 0.3s ease;
}

.appointment-card.hidden {
    display: none;
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
    
    // Cancel appointment function
    window.cancelAppointment = function(appointmentId) {
        const form = document.getElementById('cancelForm');
        form.action = `/appointments/${appointmentId}/cancel`;
        
        const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
        modal.show();
    };
    
    // Review appointment function
    window.reviewAppointment = function(appointmentId) {
        // Implement review functionality
        alert('Chức năng đánh giá sẽ được triển khai sớm!');
    };
    
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.appointment-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});
</script>
@endpush