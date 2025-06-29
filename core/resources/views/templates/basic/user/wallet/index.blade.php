@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="wallet-index-page">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="page-title-wrapper">
                        <h1 class="page-title">
                            <i class="las la-wallet me-3"></i>
                            Quản lý ví
                        </h1>
                        <p class="page-subtitle">Quản lý tài chính và giao dịch của các người thợ</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="page-actions">
                        <!-- Navigation Toggle -->
                        <div class="service-nav-toggle mb-3">
                            <div class="btn-group" role="group">
                                <a href="{{ route('user.wallet.index') }}" class="btn btn-light active">
                                    <i class="las la-wallet me-1"></i>
                                    Ví
                                </a>
                                <a href="{{ route('company.appointments.index') }}" class="btn btn-outline-light">
                                    <i class="las la-briefcase me-1"></i>
                                    Lịch hẹn người thợ
                                </a>
                                <a href="{{ route('appointments.index') }}" class="btn btn-outline-light">
                                    <i class="las la-calendar-check me-1"></i>
                                    Lịch hẹn cá nhân
                                </a>
                            </div>
                        </div>
                        
                        <a href="{{ route('user.wallet.transactions') }}" class="btn btn-outline-light">
                            <i class="las la-history me-2"></i>
                            Lịch sử giao dịch
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card balance">
                        <div class="stat-icon">
                            <i class="las la-coins"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['total_balance']) }}</h3>
                            <p>Tổng số dư (VND)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card spent">
                        <div class="stat-icon">
                            <i class="las la-arrow-down"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['total_spent']) }}</h3>
                            <p>Tổng chi tiêu (VND)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card bonus">
                        <div class="stat-icon">
                            <i class="las la-gift"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['total_bonuses']) }}</h3>
                            <p>Tổng thưởng (VND)</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card monthly">
                        <div class="stat-icon">
                            <i class="las la-calendar-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ number_format($stats['monthly_spending']) }}</h3>
                            <p>Chi tiêu tháng này (VND)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wallets Section -->
    <div class="wallets-section">
        <div class="container">
            @if($wallets->isEmpty())
                <!-- No Wallets State -->
                <div class="empty-state">
                    <div class="empty-illustration">
                        <i class="las la-wallet"></i>
                    </div>
                    <h3>Chưa có ví nào</h3>
                    <p>Bạn cần tạo ví cho các người thợ để có thể xác nhận lịch hẹn và truy cập thông tin khách hàng.</p>
                    @if(auth()->user()->companies->count() > 0)
                        <div class="empty-actions">
                            <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#createWalletModal">
                                <i class="las la-plus me-2"></i>
                                Tạo ví đầu tiên
                            </button>
                        </div>
                    @else
                        <div class="empty-actions">
                            <a href="{{ route('user.company.create') }}" class="btn btn-primary btn-lg">
                                <i class="las la-building me-2"></i>
                                Tạo hồ sơ thợ trước
                            </a>
                        </div>
                    @endif
                </div>
            @else
                <!-- Wallets Grid -->
                <div class="section-header">
                    <h2>Ví của bạn</h2>
                    @if(auth()->user()->companies->whereNotIn('id', $wallets->pluck('company_id'))->count() > 0)
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createWalletModal">
                            <i class="las la-plus me-2"></i>
                            Tạo ví mới
                        </button>
                    @endif
                </div>

                <div class="wallets-grid">
                    @foreach($wallets as $wallet)
                        <div class="wallet-card">
                            <div class="wallet-header">
                                <div class="wallet-company">
                                    <div class="company-avatar">
                                        <i class="las la-building"></i>
                                    </div>
                                    <div class="company-info">
                                        <h4>{{ $wallet->company->name }}</h4>
                                        <p>{{ $wallet->company->category->name ?? 'Chưa phân loại' }}</p>
                                    </div>
                                </div>
                                <div class="wallet-status">
                                    <span class="status-badge {{ $wallet->balance > 50000 ? 'active' : 'low' }}">
                                        @if($wallet->balance > 50000)
                                            <i class="las la-check-circle"></i> Hoạt động
                                        @else
                                            <i class="las la-exclamation-triangle"></i> Số dư thấp
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="wallet-balance">
                                <div class="balance-amount">
                                    <h3>{{ number_format($wallet->balance) }} VND</h3>
                                    <p>Số dư hiện tại</p>
                                </div>
                                <div class="balance-actions">
                                    <a href="{{ route('user.wallet.show', $wallet->id) }}" class="btn btn-outline-primary">
                                        <i class="las la-eye"></i>
                                        Chi tiết
                                    </a>
                                </div>
                            </div>

                            <div class="wallet-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Lịch hẹn có thể xác nhận:</span>
                                    <span class="stat-value">{{ floor($wallet->balance / 50000) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Chi tiêu tháng này:</span>
                                    <span class="stat-value">{{ number_format($wallet->getMonthlySpending()) }} VND</span>
                                </div>
                            </div>

                            @if($wallet->transactions->count() > 0)
                                <div class="wallet-recent">
                                    <h6>Giao dịch gần đây</h6>
                                    <div class="recent-transactions">
                                        @foreach($wallet->transactions->take(3) as $transaction)
                                            <div class="transaction-item">
                                                <div class="transaction-info">
                                                    <span class="transaction-type">
                                                        @if($transaction->transaction_type === 'customer_info_access')
                                                            Phí truy cập thông tin
                                                        @elseif($transaction->transaction_type === 'welcome_bonus')
                                                            Thưởng chào mừng
                                                        @else
                                                            {{ $transaction->description }}
                                                        @endif
                                                    </span>
                                                    <small class="transaction-date">{{ $transaction->created_at->diffForHumans() }}</small>
                                                </div>
                                                <div class="transaction-amount {{ $transaction->type }}">
                                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount) }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section">
        <div class="container">
            <div class="quick-actions-card">
                <h3>Hành động nhanh</h3>
                <div class="actions-grid">
                    <a href="{{ route('company.appointments.index') }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-calendar-check"></i>
                        </div>
                        <div class="action-content">
                            <h5>Quản lý lịch hẹn</h5>
                            <p>Xem và xử lý lịch hẹn từ khách hàng</p>
                        </div>
                    </a>
                    <a href="{{ route('user.wallet.transactions') }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-history"></i>
                        </div>
                        <div class="action-content">
                            <h5>Lịch sử giao dịch</h5>
                            <p>Xem tất cả giao dịch của các ví</p>
                        </div>
                    </a>
                    <a href="{{ route('user.company.index') }}" class="action-item">
                        <div class="action-icon">
                            <i class="las la-building"></i>
                        </div>
                        <div class="action-content">
                                            <h5>Quản lý người thợ</h5>
                <p>Cập nhật thông tin người thợ</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Wallet Modal -->
<div class="modal fade" id="createWalletModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo ví mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.wallet.create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="create-wallet-info">
                        <div class="info-icon">
                            <i class="las la-info-circle"></i>
                        </div>
                        <div class="info-content">
                                                <h6>Tạo ví cho người thợ</h6>
                    <p>Chọn người thợ để tạo ví. Mỗi người thợ chỉ có thể có một ví duy nhất.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company_id" class="form-label">Chọn người thợ</label>
                        <select name="company_id" id="company_id" class="form-select" required>
                            <option value="">-- Chọn người thợ --</option>
                            @foreach(auth()->user()->companies->whereNotIn('id', $wallets->pluck('company_id')) as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bonus-info">
                        <div class="bonus-highlight">
                            <i class="las la-gift"></i>
                            <span>Bạn sẽ nhận được <strong>100,000 VND</strong> thưởng chào mừng khi tạo ví!</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo ví</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* === WALLET INDEX PAGE STYLES === */
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

.wallet-index-page {
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

.stat-card.balance {
    border-left-color: var(--success);
}

.stat-card.spent {
    border-left-color: var(--danger);
}

.stat-card.bonus {
    border-left-color: #8b5cf6;
}

.stat-card.monthly {
    border-left-color: var(--warning);
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

.stat-card.balance .stat-icon {
    background: var(--success-light);
    color: var(--success);
}

.stat-card.spent .stat-icon {
    background: var(--danger-light);
    color: var(--danger);
}

.stat-card.bonus .stat-icon {
    background: #f3e8ff;
    color: #8b5cf6;
}

.stat-card.monthly .stat-icon {
    background: var(--warning-light);
    color: var(--warning);
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

/* === WALLETS SECTION === */
.wallets-section {
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gray-900);
    margin: 0;
}

.wallets-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 2rem;
}

.wallet-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out;
}

.wallet-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-xl);
}

.wallet-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-100);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.wallet-company {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.company-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary-light);
    color: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.company-info h4 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--gray-900);
}

.company-info p {
    color: var(--gray-500);
    font-size: 0.9rem;
    margin: 0;
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

.status-badge.active {
    background: var(--success-light);
    color: var(--success);
}

.status-badge.low {
    background: var(--warning-light);
    color: var(--warning);
}

.wallet-balance {
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--gray-50);
}

.balance-amount h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.balance-amount p {
    color: var(--gray-600);
    margin: 0;
}

.wallet-stats {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-label {
    color: var(--gray-600);
    font-size: 0.9rem;
}

.stat-value {
    color: var(--gray-900);
    font-weight: 600;
}

.wallet-recent {
    padding: 1.5rem;
    border-top: 1px solid var(--gray-100);
}

.wallet-recent h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1rem;
}

.recent-transactions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.transaction-info {
    flex: 1;
}

.transaction-type {
    font-size: 0.85rem;
    color: var(--gray-700);
    display: block;
}

.transaction-date {
    color: var(--gray-500);
    font-size: 0.75rem;
}

.transaction-amount {
    font-weight: 600;
    font-size: 0.9rem;
}

.transaction-amount.credit {
    color: var(--success);
}

.transaction-amount.debit {
    color: var(--danger);
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

.quick-actions-card h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 1.5rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
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
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.action-content h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.25rem;
}

.action-content p {
    color: var(--gray-600);
    font-size: 0.85rem;
    margin: 0;
}

/* === MODAL STYLES === */
.create-wallet-info {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: var(--primary-light);
    border-radius: 12px;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.info-content h6 {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--gray-900);
}

.info-content p {
    color: var(--gray-600);
    margin: 0;
    font-size: 0.9rem;
}

.bonus-info {
    margin-top: 1.5rem;
}

.bonus-highlight {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--success-light);
    border-radius: 12px;
    color: var(--success);
    font-weight: 500;
}

.bonus-highlight i {
    font-size: 1.25rem;
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
    color: var(--primary);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.service-nav-toggle .btn i {
    font-size: 0.9rem;
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
    
    .wallets-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .wallet-header {
        padding: 1rem;
    }
    
    .wallet-body {
        padding: 1rem;
    }
    
    .wallet-balance {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .action-item {
        padding: 1rem;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .company-info h4 {
        font-size: 0.95rem;
    }
    
    .company-info p {
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
    
    .wallet-card {
        margin: 0 -8px 12px -8px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .wallet-header {
        padding: 12px;
    }
    
    .wallet-body {
        padding: 0 12px 12px 12px;
    }
    
    .company-avatar {
        width: 32px;
        height: 32px;
        border-radius: 6px;
    }
    
    .company-info h4 {
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    
    .company-info p {
        font-size: 0.7rem;
        line-height: 1.2;
    }
    
    .status-badge {
        font-size: 0.65rem;
        padding: 4px 8px;
        border-radius: 4px;
        line-height: 1.2;
    }
    
    .action-item {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .action-icon {
        width: 28px;
        height: 28px;
        font-size: 0.85rem;
        border-radius: 6px;
    }
    
    .action-content h5 {
        font-size: 0.85rem;
        line-height: 1.3;
        margin-bottom: 2px;
    }
    
    .action-content p {
        font-size: 0.7rem;
        line-height: 1.2;
    }
}

/* === STAGGER ANIMATIONS === */
.wallet-card:nth-child(1) { animation-delay: 0.1s; }
.wallet-card:nth-child(2) { animation-delay: 0.2s; }
.wallet-card:nth-child(3) { animation-delay: 0.3s; }
.wallet-card:nth-child(4) { animation-delay: 0.4s; }

/* === MOBILE APP COMPACT OVERRIDE === */
@media (max-width: 480px) {
    .wallet-card {
        margin: 0 -8px 12px -8px !important;
        border-radius: 8px !important;
    }
    
    .wallet-header {
        padding: 12px !important;
    }
    
    .wallet-body {
        padding: 0 12px 12px 12px !important;
    }
    
    .company-avatar {
        width: 32px !important;
        height: 32px !important;
        border-radius: 6px !important;
    }
    
    .company-info h4 {
        font-size: 0.85rem !important;
        line-height: 1.3 !important;
        margin-bottom: 2px !important;
    }
    
    .company-info p {
        font-size: 0.7rem !important;
        line-height: 1.2 !important;
    }
    
    .action-item {
        padding: 12px !important;
        border-radius: 8px !important;
        margin-bottom: 8px !important;
    }
    
    .action-icon {
        width: 28px !important;
        height: 28px !important;
        font-size: 0.85rem !important;
        border-radius: 6px !important;
    }
    
    .action-content h5 {
        font-size: 0.85rem !important;
        line-height: 1.3 !important;
        margin-bottom: 2px !important;
    }
    
    .action-content p {
        font-size: 0.7rem !important;
        line-height: 1.2 !important;
    }
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add stagger animation to cards
    const cards = document.querySelectorAll('.wallet-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Form validation
    const createWalletForm = document.querySelector('#createWalletModal form');
    if (createWalletForm) {
        createWalletForm.addEventListener('submit', function(e) {
            const companySelect = document.getElementById('company_id');
            if (!companySelect.value) {
                e.preventDefault();
                alert('Vui lòng chọn người thợ để tạo ví');
                companySelect.focus();
            }
        });
    }
});
</script>
@endpush 