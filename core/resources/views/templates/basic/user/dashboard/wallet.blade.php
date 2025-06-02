<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100 bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="las la-wallet fs-1"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ number_format($walletStats['total_balance'] ?? 0) }} VNĐ</h3>
                        <p class="mb-0 opacity-75">Tổng số dư</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="las la-arrow-down text-danger fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ number_format($walletStats['total_spent_this_month'] ?? 0) }} VNĐ</h3>
                        <p class="text-muted mb-0">Chi tiêu tháng này</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="las la-gift text-success fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ number_format($walletStats['total_bonuses_received'] ?? 0) }} VNĐ</h3>
                        <p class="text-muted mb-0">Tổng thưởng nhận</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="las la-credit-card text-info fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ $walletStats['total_wallets'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Số ví</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Wallets List -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách ví</h5>
                    <a href="{{ route('user.wallet.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem chi tiết
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($wallets && $wallets->count() > 0)
                    @foreach($wallets as $wallet)
                    <div class="d-flex justify-content-between align-items-center p-3 mb-3 border rounded">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="las la-building text-primary fs-4"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $wallet->company->name }}</h6>
                                <small class="text-muted">{{ $wallet->company->category->name ?? 'N/A' }}</small>
                                <div class="mt-1">
                                    <small class="badge bg-light text-dark">{{ $wallet->currency }}</small>
                                    @if($wallet->is_active)
                                        <small class="badge bg-success">Hoạt động</small>
                                    @else
                                        <small class="badge bg-secondary">Tạm khóa</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-1 text-success">{{ $wallet->getFormattedBalance() }}</h5>
                            <div>
                                <a href="{{ route('user.wallet.show', $wallet->id) }}" class="btn btn-sm btn-outline-primary">
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="las la-wallet fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Chưa có ví nào</p>
                        @if(Auth::user()->companies->count() > 0)
                            <button class="btn btn--base" data-bs-toggle="modal" data-bs-target="#createWalletModal">
                                Tạo ví ngay
                            </button>
                        @else
                            <a href="{{ route('user.company.create') }}" class="btn btn--base">
                                Tạo công ty trước
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Chart -->
    <div class="col-lg-4">
        <!-- Recent Transactions -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Giao dịch gần đây</h6>
                    <a href="{{ route('user.wallet.transactions') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentTransactions && $recentTransactions->count() > 0)
                    @foreach($recentTransactions->take(5) as $transaction)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($transaction->type === 'credit')
                                    <i class="las la-arrow-up text-success fs-4"></i>
                                @else
                                    <i class="las la-arrow-down text-danger fs-4"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fs-6">{{ Str::limit($transaction->description, 25) }}</h6>
                                <small class="text-muted">{{ $transaction->created_at->format('d/m H:i') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                {{ $transaction->getFormattedAmount() }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="las la-history fs-2 text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Chưa có giao dịch</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Spending Chart -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="mb-0">Chi tiêu 6 tháng gần đây</h6>
            </div>
            <div class="card-body">
                <canvas id="spendingChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Create Wallet Modal -->
@if(Auth::user()->companies->filter(function($company) { return !$company->wallet; })->count() > 0)
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
                    <div class="mb-3">
                        <label class="form-label">Chọn công ty</label>
                        <select name="company_id" class="form-select" required>
                            <option value="">-- Chọn công ty --</option>
                            @foreach(Auth::user()->companies->filter(function($company) { return !$company->wallet; }) as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="las la-gift"></i>
                        <strong>Thưởng chào mừng:</strong> Bạn sẽ nhận được <strong>100,000 VNĐ</strong> khi tạo ví mới (tương đương 10 leads miễn phí)
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn--base">Tạo ví</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif 