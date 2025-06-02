@extends($activeTemplate . 'layouts.auth')
@section('content')
    <div class="notice"></div>
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Lịch sử giao dịch</h2>
                    <p class="text-muted mb-0">Theo dõi tất cả các giao dịch ví của bạn</p>
                </div>
                <div>
                    <a href="{{ route('user.wallet.index') }}" class="btn btn-outline-primary">
                        <i class="las la-arrow-left"></i> Quay lại ví
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('user.wallet.transactions') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Loại giao dịch</label>
                                <select name="transaction_type" class="form-select">
                                    <option value="">Tất cả</option>
                                    @foreach($transactionTypes as $key => $label)
                                        <option value="{{ $key }}" {{ request('transaction_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kiểu</label>
                                <select name="type" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Tiền vào</option>
                                    <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Tiền ra</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Từ ngày</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Đến ngày</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn--base w-100">
                                    <i class="las la-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">Giao dịch ({{ $transactions->total() }} kết quả)</h5>
                </div>
                <div class="card-body p-0">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Ví</th>
                                        <th>Mô tả</th>
                                        <th>Loại</th>
                                        <th>Số tiền</th>
                                        <th>Số dư sau GD</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $transaction->created_at->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ $transaction->wallet->company->name }}</h6>
                                                <small class="text-muted">ID: #{{ $transaction->wallet->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <p class="mb-1">{{ $transaction->description }}</p>
                                                @if($transaction->reference_id)
                                                    <small class="text-muted">Ref: {{ $transaction->reference_id }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($transaction->type === 'credit')
                                                    <i class="las la-arrow-down text-success me-2 fs-4"></i>
                                                @else
                                                    <i class="las la-arrow-up text-danger me-2 fs-4"></i>
                                                @endif
                                                <div>
                                                    {!! $transaction->getTypeBadge() !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->getFormattedAmount() }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($transaction->balance_after, 0, '.', ',') }} VNĐ</strong>
                                        </td>
                                        <td>
                                            {!! $transaction->getStatusBadge() !!}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="las la-history fs-1 text-muted"></i>
                            <h4 class="mt-3">Không có giao dịch</h4>
                            <p class="text-muted">Không tìm thấy giao dịch nào với bộ lọc hiện tại</p>
                            <a href="{{ route('user.wallet.transactions') }}" class="btn btn--base">
                                Xem tất cả giao dịch
                            </a>
                        </div>
                    @endif
                </div>

                @if($transactions->hasPages())
                    <div class="card-footer bg-transparent">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="las la-arrow-down text-success fs-1"></i>
                    <h5 class="mt-2 mb-1">{{ number_format($transactions->where('type', 'credit')->sum('amount'), 0, '.', ',') }} VNĐ</h5>
                    <small class="text-muted">Tổng tiền vào</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="las la-arrow-up text-danger fs-1"></i>
                    <h5 class="mt-2 mb-1">{{ number_format($transactions->where('type', 'debit')->sum('amount'), 0, '.', ',') }} VNĐ</h5>
                    <small class="text-muted">Tổng tiền ra</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="las la-chart-line text-primary fs-1"></i>
                    <h5 class="mt-2 mb-1">{{ $transactions->count() }}</h5>
                    <small class="text-muted">Số giao dịch</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <i class="las la-balance-scale text-info fs-1"></i>
                    <h5 class="mt-2 mb-1">
                        {{ number_format($transactions->where('type', 'credit')->sum('amount') - $transactions->where('type', 'debit')->sum('amount'), 0, '.', ',') }} VNĐ
                    </h5>
                    <small class="text-muted">Chênh lệch</small>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table th {
        border: none;
        background: #f8f9fa;
        font-weight: 600;
        color: #495057;
        padding: 15px 12px;
    }
    
    .table td {
        border: none;
        padding: 15px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .fs-4 {
        font-size: 1.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
</style>
@endpush 