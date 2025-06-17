@extends($activeTemplate . 'layouts.auth')
@section('content')
    <div class="notice"></div>
    
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-header bg-gradient-primary text-white rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1 text-white">
                            <i class="las la-heart text-warning me-2"></i>
                            Chào mừng, {{ $user->fullname }}!
                        </h2>
                        <p class="mb-0 opacity-75">Quản lý lịch hẹn và nhận thưởng điểm loyalty</p>
                    </div>
                    <div class="text-end">
                        <div class="loyalty-points-display">
                            <div class="loyalty-badge bg-warning text-dark rounded-pill px-3 py-2">
                                <i class="las la-star fs-5"></i>
                                <strong class="ms-1">{{ number_format($stats['loyalty_points']) }}</strong>
                                <small class="ms-1">điểm</small>
                            </div>
                            <small class="text-white-50 d-block mt-1">
                                ≈ {{ number_format($stats['loyalty_points'] * 100) }} VNĐ
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon bg-primary bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="las la-calendar-check text-primary fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['total_appointments'] }}</h3>
                    <p class="text-muted mb-0">Tổng lịch hẹn</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon bg-warning bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="las la-clock text-warning fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['pending_appointments'] }}</h3>
                    <p class="text-muted mb-0">Đang chờ</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="las la-check-circle text-success fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ $stats['completed_appointments'] }}</h3>
                    <p class="text-muted mb-0">Hoàn thành</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon bg-info bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="las la-star text-info fs-2"></i>
                    </div>
                    <h3 class="mb-1">{{ number_format($stats['loyalty_points']) }}</h3>
                    <p class="text-muted mb-0">Điểm thưởng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab">
                                <i class="las la-calendar-check me-2"></i>Lịch hẹn của tôi
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="loyalty-tab" data-bs-toggle="tab" data-bs-target="#loyalty" type="button" role="tab">
                                <i class="las la-gift me-2"></i>Điểm thưởng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                <i class="las la-star me-2"></i>Đánh giá
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="customerTabsContent">
                        <!-- Appointments Tab -->
                        <div class="tab-pane fade show active" id="appointments" role="tabpanel">
                            @include($activeTemplate . 'user.customer.appointments')
                        </div>

                        <!-- Loyalty Tab -->
                        <div class="tab-pane fade" id="loyalty" role="tabpanel">
                            @include($activeTemplate . 'user.customer.loyalty')
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @include($activeTemplate . 'user.customer.reviews')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h6 class="mb-0">
                        <i class="las la-rocket me-2 text-primary"></i>
                        Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('company.all') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="las la-search fs-4 d-block mb-2"></i>
                                Tìm thợ
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('user.appointments.index') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="las la-calendar-alt fs-4 d-block mb-2"></i>
                                Xem lịch hẹn
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <a href="{{ route('user.profile.view') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="las la-user-cog fs-4 d-block mb-2"></i>
                                Thông tin cá nhân
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button class="btn btn-outline-warning w-100 py-3" data-bs-toggle="modal" data-bs-target="#redeemPointsModal">
                                <i class="las la-gift fs-4 d-block mb-2"></i>
                                Đổi điểm thưởng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Redeem Points Modal -->
<div class="modal fade" id="redeemPointsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-gift text-warning me-2"></i>
                    Đổi điểm thưởng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="loyalty-points-large">
                        <i class="las la-star text-warning fs-1"></i>
                        <h3 class="text-primary">{{ number_format($stats['loyalty_points']) }} điểm</h3>
                        <p class="text-muted">Điểm khả dụng của bạn</p>
                    </div>
                </div>
                
                <div class="redemption-options">
                    <div class="redemption-option border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Giảm giá 50,000 VNĐ</h6>
                                <small class="text-muted">Cho lần đặt lịch tiếp theo</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning text-dark">500 điểm</span>
                                <button class="btn btn-sm btn-outline-primary ms-2" 
                                        {{ $stats['loyalty_points'] >= 500 ? '' : 'disabled' }}>
                                    Đổi ngay
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="redemption-option border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Giảm giá 100,000 VNĐ</h6>
                                <small class="text-muted">Cho lần đặt lịch tiếp theo</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning text-dark">1000 điểm</span>
                                <button class="btn btn-sm btn-outline-primary ms-2"
                                        {{ $stats['loyalty_points'] >= 1000 ? '' : 'disabled' }}>
                                    Đổi ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--base) 0%, #667eea 100%);
    }
    
    .loyalty-badge {
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .nav-tabs .nav-link {
        border: none;
        background: none;
        color: #6c757d;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        margin-right: 8px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(var(--base-rgb), 0.1);
        color: var(--base);
    }

    .nav-tabs .nav-link.active {
        background: var(--base);
        color: white;
        border: none;
    }
    
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .redemption-option {
        transition: all 0.3s ease;
    }
    
    .redemption-option:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
    }
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabTriggerList = document.querySelectorAll('#customerTabs button');
    tabTriggerList.forEach(tabTrigger => {
        const tabInstance = new bootstrap.Tab(tabTrigger);
        
        tabTrigger.addEventListener('click', event => {
            event.preventDefault();
            tabInstance.show();
        });
    });
});
</script>
@endpush 