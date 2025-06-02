<!-- Loyalty Points Overview -->
<div class="row mb-4">
    <div class="col-12">
        <div class="loyalty-overview bg-gradient-warning text-white rounded-4 p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-2 text-white">
                        <i class="las la-star me-2"></i>
                        Chương trình điểm thưởng
                    </h3>
                    <p class="mb-3 opacity-90">
                        Tích lũy điểm thưởng từ mỗi lịch hẹn hoàn thành và đổi lấy ưu đãi hấp dẫn!
                    </p>
                    <div class="loyalty-stats">
                        <div class="row">
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="mb-1 text-white">{{ number_format($stats['loyalty_points']) }}</h4>
                                    <small class="opacity-75">Điểm hiện tại</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="mb-1 text-white">{{ number_format($user->total_loyalty_earned ?? 0) }}</h4>
                                    <small class="opacity-75">Tổng tích lũy</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <h4 class="mb-1 text-white">{{ number_format($user->total_loyalty_redeemed ?? 0) }}</h4>
                                    <small class="opacity-75">Đã sử dụng</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="loyalty-badge-large">
                        <div class="badge-circle bg-white text-warning rounded-circle p-4 mx-auto" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="las la-crown fs-1"></i>
                            <small class="fw-bold">VIP</small>
                        </div>
                        <p class="mt-3 mb-0 text-white">Khách hàng thân thiết</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How to Earn Points -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="las la-lightbulb text-warning me-2"></i>
                    Cách tích điểm thưởng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="earning-method text-center p-3 border rounded">
                            <div class="method-icon bg-success bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-check-circle text-success fs-2"></i>
                            </div>
                            <h6 class="mb-2">Hoàn thành lịch hẹn</h6>
                            <p class="text-muted mb-2">+100 điểm</p>
                            <small class="text-muted">Mỗi lần hoàn thành dịch vụ</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="earning-method text-center p-3 border rounded">
                            <div class="method-icon bg-warning bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-star text-warning fs-2"></i>
                            </div>
                            <h6 class="mb-2">Viết đánh giá</h6>
                            <p class="text-muted mb-2">+50 điểm</p>
                            <small class="text-muted">Chia sẻ trải nghiệm của bạn</small>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="earning-method text-center p-3 border rounded">
                            <div class="method-icon bg-info bg-opacity-10 rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="las la-user-friends text-info fs-2"></i>
                            </div>
                            <h6 class="mb-2">Giới thiệu bạn bè</h6>
                            <p class="text-muted mb-2">+200 điểm</p>
                            <small class="text-muted">Khi bạn bè đăng ký thành công</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Redemption Options -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="las la-gift text-primary me-2"></i>
                    Đổi điểm thưởng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="redemption-card border rounded p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">Giảm giá 50,000 VNĐ</h6>
                                    <p class="text-muted mb-0">Áp dụng cho lần đặt lịch tiếp theo</p>
                                </div>
                                <span class="badge bg-warning text-dark fs-6">500 điểm</span>
                            </div>
                            <div class="redemption-progress mb-3">
                                @php
                                    $progress = min(($stats['loyalty_points'] / 500) * 100, 100);
                                @endphp
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">
                                    {{ $stats['loyalty_points'] }}/500 điểm
                                </small>
                            </div>
                            <button class="btn btn-outline-primary w-100" 
                                    {{ $stats['loyalty_points'] >= 500 ? '' : 'disabled' }}>
                                <i class="las la-exchange-alt me-2"></i>
                                {{ $stats['loyalty_points'] >= 500 ? 'Đổi ngay' : 'Chưa đủ điểm' }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="redemption-card border rounded p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="mb-1">Giảm giá 100,000 VNĐ</h6>
                                    <p class="text-muted mb-0">Áp dụng cho lần đặt lịch tiếp theo</p>
                                </div>
                                <span class="badge bg-success fs-6">1000 điểm</span>
                            </div>
                            <div class="redemption-progress mb-3">
                                @php
                                    $progress = min(($stats['loyalty_points'] / 1000) * 100, 100);
                                @endphp
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                </div>
                                <small class="text-muted">
                                    {{ $stats['loyalty_points'] }}/1000 điểm
                                </small>
                            </div>
                            <button class="btn btn-outline-success w-100"
                                    {{ $stats['loyalty_points'] >= 1000 ? '' : 'disabled' }}>
                                <i class="las la-exchange-alt me-2"></i>
                                {{ $stats['loyalty_points'] >= 1000 ? 'Đổi ngay' : 'Chưa đủ điểm' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Loyalty Transactions -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="mb-0">
                    <i class="las la-history text-info me-2"></i>
                    Lịch sử điểm thưởng
                </h5>
            </div>
            <div class="card-body">
                @php
                    // Mock data for loyalty transactions
                    $loyaltyTransactions = collect([
                        (object)[
                            'id' => 1,
                            'type' => 'earned',
                            'points' => 100,
                            'description' => 'Hoàn thành lịch hẹn với Thợ Sửa Điện ABC',
                            'created_at' => now()->subDays(2)
                        ],
                        (object)[
                            'id' => 2,
                            'type' => 'earned',
                            'points' => 50,
                            'description' => 'Viết đánh giá cho Thợ Sửa Ống Nước XYZ',
                            'created_at' => now()->subDays(5)
                        ],
                        (object)[
                            'id' => 3,
                            'type' => 'redeemed',
                            'points' => -500,
                            'description' => 'Đổi voucher giảm giá 50,000 VNĐ',
                            'created_at' => now()->subDays(10)
                        ]
                    ]);
                @endphp
                
                @if($loyaltyTransactions->count() > 0)
                    <div class="loyalty-transactions">
                        @foreach($loyaltyTransactions as $transaction)
                            <div class="transaction-item d-flex justify-content-between align-items-center py-3 border-bottom">
                                <div class="transaction-info">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($transaction->type === 'earned')
                                            <div class="transaction-icon bg-success bg-opacity-10 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="las la-plus text-success"></i>
                                            </div>
                                        @else
                                            <div class="transaction-icon bg-warning bg-opacity-10 rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="las la-minus text-warning"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $transaction->description }}</h6>
                                            <small class="text-muted">{{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="transaction-points">
                                    <span class="badge {{ $transaction->type === 'earned' ? 'bg-success' : 'bg-warning text-dark' }} fs-6">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }} điểm
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary">
                            <i class="las la-eye me-2"></i>
                            Xem tất cả giao dịch
                        </button>
                    </div>
                @else
                    <div class="empty-state text-center py-4">
                        <i class="las la-history text-muted" style="font-size: 3rem;"></i>
                        <h6 class="text-muted mt-3">Chưa có giao dịch điểm thưởng</h6>
                        <p class="text-muted">Hoàn thành lịch hẹn đầu tiên để bắt đầu tích điểm!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}

.redemption-card {
    transition: all 0.3s ease;
}

.redemption-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.earning-method {
    transition: all 0.3s ease;
}

.earning-method:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.transaction-item:last-child {
    border-bottom: none !important;
}
</style> 