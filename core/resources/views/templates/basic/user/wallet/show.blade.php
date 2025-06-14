@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="wallet-show-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <div class="breadcrumb-nav">
                            <a href="{{ route('user.wallet.index') }}" class="breadcrumb-link">
                                <i class="las la-arrow-left"></i>
                                Quay lại danh sách ví
                            </a>
                        </div>
                        <h1 class="page-title">
                            <i class="las la-wallet me-3"></i>
                            Ví {{ $wallet->company->name }}
                        </h1>
                        <p class="page-subtitle">Chi tiết và lịch sử giao dịch của ví</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <a href="{{ route('user.wallet.transactions') }}" class="btn btn-outline-light">
                            <i class="las la-history me-2"></i>
                            Tất cả giao dịch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallet Overview -->
    <div class="wallet-overview">
        <div class="container">
            <div class="row g-4">
                <!-- Main Wallet Card -->
                <div class="col-lg-8">
                    <div class="wallet-main-card">
                        <div class="wallet-header">
                            <div class="company-info">
                                <div class="company-avatar">
                                    <i class="las la-building"></i>
                                </div>
                                <div class="company-details">
                                    <h3>{{ $wallet->company->name }}</h3>
                                    <p>{{ $wallet->company->category->name ?? 'Chưa phân loại' }}</p>
                                    <span class="company-location">
                                        <i class="las la-map-marker-alt"></i>
                                        {{ $wallet->company->address ?? 'Chưa cập nhật địa chỉ' }}
                                    </span>
                                </div>
                            </div>
                            <div class="wallet-status">
                                <span class="status-badge {{ $wallet->balance > 50000 ? 'active' : 'low' }}">
                                    @if($wallet->balance > 50000)
                                        <i class="las la-check-circle"></i> Hoạt động tốt
                                    @else
                                        <i class="las la-exclamation-triangle"></i> Số dư thấp
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="wallet-balance-section">
                            <div class="balance-display">
                                <h2>{{ number_format($wallet->balance) }} VND</h2>
                                <p>Số dư hiện tại</p>
                            </div>
                            <div class="balance-info">
                                <div class="info-item">
                                    <span class="label">Lịch hẹn có thể xác nhận:</span>
                                    <span class="value">{{ floor($wallet->balance / 50000) }} lịch hẹn</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Ngày tạo ví:</span>
                                    <span class="value">{{ $wallet->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Sidebar -->
                <div class="col-lg-4">
                    <div class="stats-sidebar">
                        <div class="stat-card">
                            <div class="stat-icon spent">
                                <i class="las la-arrow-down"></i>
                            </div>
                            <div class="stat-content">
                                <h4>{{ number_format($stats['total_spent']) }}</h4>
                                <p>Tổng chi tiêu (VND)</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon bonus">
                                <i class="las la-gift"></i>
                            </div>
                            <div class="stat-content">
                                <h4>{{ number_format($stats['total_bonuses']) }}</h4>
                                <p>Tổng thưởng (VND)</p>
                            </div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon monthly">
                                <i class="las la-calendar-alt"></i>
                            </div>
                            <div class="stat-content">
                                <h4>{{ number_format($stats['monthly_spending']) }}</h4>
                                <p>Chi tiêu tháng này (VND)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="transactions-section">
        <div class="container">
            <div class="section-header">
                <h3>Lịch sử giao dịch</h3>
                <div class="header-actions">
                    <select class="form-select" id="transactionFilter">
                        <option value="">Tất cả giao dịch</option>
                        <option value="credit">Tiền vào</option>
                        <option value="debit">Tiền ra</option>
                    </select>
                </div>
            </div>

            @if($transactions->count() > 0)
                <div class="transactions-list">
                    @foreach($transactions as $transaction)
                        <div class="transaction-item" data-type="{{ $transaction->type }}">
                            <div class="transaction-icon {{ $transaction->type }}">
                                @if($transaction->transaction_type === 'customer_info_access')
                                    <i class="las la-user-lock"></i>
                                @elseif($transaction->transaction_type === 'welcome_bonus')
                                    <i class="las la-gift"></i>
                                @elseif($transaction->transaction_type === 'referral_bonus')
                                    <i class="las la-users"></i>
                                @elseif($transaction->transaction_type === 'admin_adjustment')
                                    <i class="las la-cog"></i>
                                @else
                                    <i class="las la-exchange-alt"></i>
                                @endif
                            </div>
                            
                            <div class="transaction-details">
                                <div class="transaction-main">
                                    <h5>
                                        @if($transaction->transaction_type === 'customer_info_access')
                                            Phí truy cập thông tin khách hàng
                                        @elseif($transaction->transaction_type === 'welcome_bonus')
                                            Thưởng chào mừng
                                        @elseif($transaction->transaction_type === 'referral_bonus')
                                            Thưởng giới thiệu
                                        @elseif($transaction->transaction_type === 'admin_adjustment')
                                            Điều chỉnh từ admin
                                        @else
                                            {{ $transaction->description }}
                                        @endif
                                    </h5>
                                    <p>{{ $transaction->description }}</p>
                                </div>
                                <div class="transaction-meta">
                                    <span class="transaction-date">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                                    @if($transaction->processedBy)
                                        <span class="processed-by">Bởi: {{ $transaction->processedBy->username }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="transaction-amount {{ $transaction->type }}">
                                <span class="amount">
                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }} VND
                                </span>
                                <span class="balance-after">
                                    Số dư: {{ number_format($transaction->balance_after) }} VND
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
                </div>
            @else
                <div class="empty-transactions">
                    <div class="empty-icon">
                        <i class="las la-receipt"></i>
                    </div>
                    <h4>Chưa có giao dịch nào</h4>
                    <p>Ví này chưa có giao dịch nào. Giao dịch sẽ xuất hiện khi bạn xác nhận lịch hẹn hoặc nhận thưởng.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="container">
            <div class="quick-actions-card">
                <h4>Hành động nhanh</h4>
                <div class="actions-list">
                    <a href="{{ route('company.appointments.index') }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-calendar-check"></i>
                        </div>
                        <div class="action-content">
                            <h6>Quản lý lịch hẹn</h6>
                            <p>Xem và xử lý lịch hẹn cho công ty này</p>
                        </div>
                    </a>
                    <a href="{{ route('user.company.edit', $wallet->company->id) }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-edit"></i>
                        </div>
                        <div class="action-content">
                            <h6>Cập nhật công ty</h6>
                            <p>Chỉnh sửa thông tin công ty</p>
                        </div>
                    </a>
                    <a href="{{ route('user.wallet.transactions') }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-history"></i>
                        </div>
                        <div class="action-content">
                            <h6>Tất cả giao dịch</h6>
                            <p>Xem giao dịch của tất cả ví</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* === WALLET SHOW PAGE STYLES === */
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

.wallet-show-page {
    background: var(--gray-50);
    min-height: 100vh;
}

/* === PAGE HEADER === */
.page-header {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
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

/* === WALLET OVERVIEW === */
.wallet-overview {
    margin-bottom: 2rem;
}

.wallet-main-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    animation: fadeInUp 0.6s ease-out;
}

.wallet-header {
    padding: 2rem;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.company-info {
    display: flex;
    gap: 1.5rem;
}

.company-avatar {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}

.company-details h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.company-details p {
    color: var(--gray-600);
    margin-bottom: 0.75rem;
}

.company-location {
    color: var(--gray-500);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.status-badge.active {
    background: var(--success-light);
    color: var(--success);
}

.status-badge.low {
    background: var(--warning-light);
    color: var(--warning);
}

.wallet-balance-section {
    padding: 2rem;
    background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
}

.balance-display {
    text-align: center;
    margin-bottom: 2rem;
}

.balance-display h2 {
    font-size: 3rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.balance-display p {
    color: var(--gray-600);
    font-size: 1.1rem;
    margin: 0;
}

.balance-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-sm);
}

.info-item .label {
    color: var(--gray-600);
    font-weight: 500;
}

.info-item .value {
    color: var(--gray-900);
    font-weight: 600;
}

/* === STATS SIDEBAR === */
.stats-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    display: flex;
    align-items: center;
    gap: 1rem;
    animation: fadeInUp 0.6s ease-out;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-icon.spent {
    background: var(--danger-light);
    color: var(--danger);
}

.stat-icon.bonus {
    background: #f3e8ff;
    color: #8b5cf6;
}

.stat-icon.monthly {
    background: var(--warning-light);
    color: var(--warning);
}

.stat-content h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.stat-content p {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.9rem;
}

/* === TRANSACTIONS SECTION === */
.transactions-section {
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

.header-actions .form-select {
    min-width: 200px;
}

.transactions-list {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.transaction-item {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    transition: all 0.3s ease;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-item:hover {
    background: var(--gray-50);
}

.transaction-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.transaction-icon.credit {
    background: var(--success-light);
    color: var(--success);
}

.transaction-icon.debit {
    background: var(--danger-light);
    color: var(--danger);
}

.transaction-details {
    flex: 1;
}

.transaction-main h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.transaction-main p {
    color: var(--gray-600);
    font-size: 0.9rem;
    margin: 0;
}

.transaction-meta {
    margin-top: 0.5rem;
    display: flex;
    gap: 1rem;
}

.transaction-date,
.processed-by {
    color: var(--gray-500);
    font-size: 0.8rem;
}

.transaction-amount {
    text-align: right;
    flex-shrink: 0;
}

.transaction-amount .amount {
    font-size: 1.1rem;
    font-weight: 700;
    display: block;
    margin-bottom: 0.25rem;
}

.transaction-amount.credit .amount {
    color: var(--success);
}

.transaction-amount.debit .amount {
    color: var(--danger);
}

.transaction-amount .balance-after {
    color: var(--gray-500);
    font-size: 0.8rem;
}

/* === EMPTY TRANSACTIONS === */
.empty-transactions {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
}

.empty-icon {
    font-size: 4rem;
    color: var(--gray-300);
    margin-bottom: 1.5rem;
}

.empty-transactions h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.75rem;
}

.empty-transactions p {
    color: var(--gray-600);
    max-width: 400px;
    margin: 0 auto;
}

/* === QUICK ACTIONS === */
.quick-actions-section {
    margin-bottom: 2rem;
}

.quick-actions-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-md);
}

.quick-actions-card h4 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
}

.actions-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 12px;
    background: var(--gray-50);
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-item:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}

.action-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.action-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.action-content p {
    color: var(--gray-600);
    font-size: 0.8rem;
    margin: 0;
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
    
    .wallet-header {
        flex-direction: column;
        gap: 1.5rem;
        align-items: flex-start;
    }
    
    .company-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .balance-display h2 {
        font-size: 2.5rem;
    }
    
    .balance-info {
        grid-template-columns: 1fr;
    }
    
    .transaction-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .transaction-amount {
        text-align: left;
        width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .actions-list {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .wallet-main-card,
    .transactions-list,
    .quick-actions-card {
        margin: 0 -0.5rem;
        border-radius: 12px;
    }
    
    .stats-sidebar {
        margin: 0 -0.5rem;
    }
    
    .stat-card {
        margin: 0;
        border-radius: 12px;
    }
}

/* === FILTER FUNCTIONALITY === */
.transaction-item.hidden {
    display: none;
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Transaction filter functionality
    const filterSelect = document.getElementById('transactionFilter');
    const transactionItems = document.querySelectorAll('.transaction-item');
    
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            const filterValue = this.value;
            
            transactionItems.forEach(item => {
                if (filterValue === '' || item.dataset.type === filterValue) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });
    }
    
    // Add stagger animation to transaction items
    transactionItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.05}s`;
        item.style.animation = 'fadeInUp 0.6s ease-out forwards';
    });
});
</script>
@endpush 