@extends($activeTemplate . 'layouts.auth')
@section('content')
    <div class="notice"></div>
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Dashboard</h2>
                    <p class="text-muted mb-0">Tổng quan hoạt động và quản lý công việc</p>
                </div>
                <div>
                    <a href="{{ route('user.profile.setting') }}" class="btn btn-outline-success me-2">
                        <i class="las la-user-cog"></i> Hồ sơ cá nhân
                    </a>
                    <a href="{{ route('company.all') }}" class="btn btn--base me-2">
                        <i class="las la-users"></i> Xem thợ chuyên nghiệp
                    </a>
                    <a href="{{ route('user.company.create') }}" class="btn btn-outline-primary">
                        <i class="las la-plus"></i> Tạo công ty
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <ul class="nav nav-tabs card-header-tabs" id="dashboardTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="las la-tachometer-alt me-2"></i>Tổng quan
                            </button>
                        </li>
                        {{-- Hide leads tab for new business model --}}
                        {{-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="leads-tab" data-bs-toggle="tab" data-bs-target="#leads" type="button" role="tab">
                                <i class="las la-bullhorn me-2"></i>Leads
                            </button>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet" type="button" role="tab">
                                <i class="las la-wallet me-2"></i>Ví
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                                <i class="las la-star me-2"></i>Đánh giá
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab">
                                <i class="las la-calendar-check me-2"></i>Lịch hẹn
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="dashboardTabsContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            @include($activeTemplate . 'user.dashboard.overview')
                        </div>

                        {{-- Hide leads tab --}}
                        {{-- <div class="tab-pane fade" id="leads" role="tabpanel">
                            @include($activeTemplate . 'user.dashboard.leads')
                        </div> --}}

                        <!-- Wallet Tab -->
                        <div class="tab-pane fade" id="wallet" role="tabpanel">
                            @include($activeTemplate . 'user.dashboard.wallet')
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            @include($activeTemplate . 'user.dashboard.reviews')
                        </div>

                        <!-- Appointments Tab -->
                        <div class="tab-pane fade" id="appointments" role="tabpanel">
                            @include($activeTemplate . 'user.dashboard.appointments')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
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
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--base) 0%, #667eea 100%);
    }
    
    .fs-2 {
        font-size: 1.5rem;
    }
    
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabTriggerList = document.querySelectorAll('#dashboardTabs button');
    tabTriggerList.forEach(tabTrigger => {
        const tabInstance = new bootstrap.Tab(tabTrigger);
        
        tabTrigger.addEventListener('click', event => {
            event.preventDefault();
            tabInstance.show();
        });
    });

    // Monthly Spending Chart (load when wallet tab is shown)
    document.getElementById('wallet-tab').addEventListener('shown.bs.tab', function() {
        if (!window.spendingChartLoaded) {
            loadSpendingChart();
            window.spendingChartLoaded = true;
        }
    });

    function loadSpendingChart() {
        const ctx = document.getElementById('spendingChart');
        if (ctx) {
            const monthlyData = @json($monthlyData ?? []);
            
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthlyData.map(item => item.month),
                    datasets: [{
                        label: 'Chi tiêu (VNĐ)',
                        data: monthlyData.map(item => item.amount),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endpush
