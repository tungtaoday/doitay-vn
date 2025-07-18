@extends($activeTemplate . 'layouts.auth')

@section('content')
<div class="deposit-index-page">
    <!-- Page Header - adjusted for auth layout -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="las la-wallet me-3"></i>
                        Nạp tiền vào ví
                    </h2>
                    <p class="text-muted mb-0">Nạp tiền để mua leads và sử dụng dịch vụ</p>
                </div>
                <div>
                    <a href="{{ route('user.deposit.history') }}" class="btn btn-outline-primary">
                        <i class="las la-history me-2"></i>Lịch sử nạp tiền
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($wallets->count() > 0)
    <!-- Quick Actions -->
    <div class="quick-actions-section mb-4">
        <div class="custom--card">
            <div class="card-header bg--dark">
                <h5 class="text-white">
                    <i class="las la-bolt me-2"></i>
                    Hành động nhanh
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('user.deposit.history') }}" class="quick-action-card">
                            <div class="d-flex align-items-center">
                                <div class="quick-action-icon bg-primary">
                                    <i class="las la-history"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Lịch sử nạp tiền</h6>
                                    <small class="text-muted">Xem tất cả giao dịch</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('user.wallet.index') }}" class="quick-action-card">
                            <div class="d-flex align-items-center">
                                <div class="quick-action-icon bg-success">
                                    <i class="las la-wallet"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Quản lý ví</h6>
                                    <small class="text-muted">Xem chi tiết ví</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('user.wallet.transactions') }}" class="quick-action-card">
                            <div class="d-flex align-items-center">
                                <div class="quick-action-icon bg-warning">
                                    <i class="las la-exchange-alt"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Giao dịch</h6>
                                    <small class="text-muted">Lịch sử giao dịch</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets Grid -->
    <div class="wallets-section mb-4">
        <div class="custom--card">
            <div class="card-header bg--dark">
                <h5 class="text-white">
                    <i class="las la-credit-card me-2"></i>
                    Chọn ví cần nạp tiền
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($wallets as $wallet)
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="wallet-card">
                            <div class="wallet-header">
                                <div class="wallet-company">
                                    <div class="company-avatar">
                                        <i class="las la-building"></i>
                                    </div>
                                    <div class="company-info">
                                        <h6 class="mb-1">{{ $wallet->company->name }}</h6>
                                        <small class="text-muted">{{ $wallet->company->category->name ?? 'Chưa phân loại' }}</small>
                                    </div>
                                </div>
                                <div class="wallet-status">
                                    <span class="status-badge {{ $wallet->balance > 50000 ? 'success' : 'warning' }}">
                                        <i class="las {{ $wallet->balance > 50000 ? 'la-check-circle' : 'la-exclamation-triangle' }}"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="wallet-balance">
                                <span class="balance-label">Số dư hiện tại</span>
                                <h4 class="balance-amount">{{ number_format($wallet->balance, 0, '.', ',') }} VNĐ</h4>
                            </div>

                            <div class="wallet-actions">
                                <a href="{{ route('user.deposit.create', $wallet->id) }}" class="btn btn--base w-100">
                                    <i class="las la-plus me-2"></i>Nạp tiền ngay
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Deposit Requests -->
    <div class="deposits-section">
        <div class="custom--card">
            <div class="card-header bg--dark">
                <h5 class="text-white">
                    <i class="las la-receipt me-2"></i>
                    Yêu cầu nạp tiền gần đây
                </h5>
            </div>
            <div class="card-body p-0">
                @if($depositRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Mã yêu cầu</th>
                                <th>Ví</th>
                                <th>Số tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($depositRequests as $request)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">{{ $request->deposit_code }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="las la-building text-muted me-2"></i>
                                        <span>{{ $request->wallet->company->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ $request->getFormattedAmount() }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $request->getPaymentMethodName() }}</span>
                                </td>
                                <td>{!! $request->getStatusBadge() !!}</td>
                                <td>
                                    <span class="text-muted">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn--dark btn--sm dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="las la-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('user.deposit.show', $request->id) }}">
                                                    <i class="las la-eye me-2"></i>Xem chi tiết
                                                </a>
                                            </li>
                                            @if($request->canBeCancelled())
                                            <li>
                                                <form action="{{ route('user.deposit.cancel', $request->id) }}" method="POST" 
                                                      onsubmit="return confirm('Bạn có chắc muốn hủy yêu cầu này?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="las la-times me-2"></i>Hủy yêu cầu
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($depositRequests->hasPages())
                <div class="card-footer">
                    {{ $depositRequests->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="las la-receipt fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Chưa có yêu cầu nạp tiền nào</h5>
                        <p class="text-muted">Tạo yêu cầu nạp tiền đầu tiên của bạn</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <!-- No Wallets -->
    <div class="no-wallets-section">
        <div class="custom--card">
            <div class="card-body text-center py-5">
                <div class="empty-state">
                    <i class="las la-wallet fs-1 text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">Chưa có ví nào</h5>
                    <p class="text-muted">Bạn cần tạo ví trước khi có thể nạp tiền</p>
                    <a href="{{ route('user.wallet.index') }}" class="btn btn--base mt-3">
                        <i class="las la-plus me-2"></i>Tạo ví ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('style')
<style>
/* Deposit Index Page Styles - Adjusted for Auth Layout */
.deposit-index-page {
    background: var(--section-bg, #f8f9fa);
    padding: 0; /* Remove padding since auth layout handles spacing */
}

/* Quick Actions */
.quick-action-card {
    display: block;
    padding: 1rem;
    background: white;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    color: inherit;
    text-decoration: none;
}

.quick-action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Wallet Cards */
.wallet-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    height: 100%;
}

.wallet-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.wallet-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.wallet-company {
    display: flex;
    align-items: center;
}

.company-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 0.75rem;
    color: white;
    font-size: 1.25rem;
}

.status-badge {
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.success {
    background: #d1fae5;
    color: #10b981;
}

.status-badge.warning {
    background: #fef3c7;
    color: #f59e0b;
}

.wallet-balance {
    text-align: center;
    margin-bottom: 1.5rem;
}

.balance-label {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 0.5rem;
}

.balance-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #059669;
    margin: 0;
}

/* Table Styles */
.thead-dark th {
    background-color: #374151;
    color: white;
    border-color: #374151;
    font-weight: 600;
}

.table-hover tbody tr:hover {
    background-color: rgba(59, 130, 246, 0.05);
}

/* Empty State */
.empty-state i {
    font-size: 4rem !important;
    color: #d1d5db;
    margin-bottom: 1rem;
}

/* Custom Card Enhancements */
.custom--card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header.bg--dark {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%) !important;
    border-bottom: none;
}

/* Auth Layout Specific Adjustments */
.section--bg {
    /* Remove any conflicting background from auth layout */
    background: #f8f9fa !important;
}

/* Responsive */
@media (max-width: 768px) {
    .wallet-card {
        margin-bottom: 1rem;
    }
    
    .quick-action-card {
        margin-bottom: 0.5rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    h2 {
        font-size: 1.5rem;
    }
}
</style>
@endpush 