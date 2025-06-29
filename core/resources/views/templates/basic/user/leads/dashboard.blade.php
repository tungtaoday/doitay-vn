@extends('Template::layouts.master')

@section('content')
<div class="container-fluid px-3 px-sm-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                            <h2 class="mb-1">Dashboard Nhu cầu</h2>
        <p class="text-muted mb-0">Quản lý nhu cầu và theo dõi hiệu suất kinh doanh</p>
                </div>
                <div>
                    <a href="{{ route('user.leads.index') }}" class="btn btn--base">
                        <i class="las la-search"></i> Tìm Nhu cầu Mới
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                <i class="las la-shopping-cart text-primary fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1">{{ $stats['total_purchased'] }}</h3>
                            <p class="text-muted mb-0">Nhu cầu đã mua</p>
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
                            <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                <i class="las la-phone text-warning fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1">{{ $stats['contacted'] }}</h3>
                            <p class="text-muted mb-0">Đã liên hệ</p>
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
                                <i class="las la-file-invoice-dollar text-info fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1">{{ $stats['quoted'] }}</h3>
                            <p class="text-muted mb-0">Đã báo giá</p>
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
                                <i class="las la-trophy text-success fs-2"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1">{{ $stats['won'] }}</h3>
                            <p class="text-muted mb-0">Thành công</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Purchases -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Leads gần đây</h5>
                        <a href="{{ route('user.leads.my-purchases') }}" class="btn btn-sm btn-outline-primary">
                            Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentPurchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Lead</th>
                                        <th>Danh mục</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày mua</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPurchases as $purchase)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-1">{{ Str::limit($purchase->lead->title, 40) }}</h6>
                                                <small class="text-muted">{{ $purchase->lead->location }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $purchase->lead->category->name }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ number_format($purchase->price_paid) }} VNĐ</strong>
                                        </td>
                                        <td>
                                            {!! $purchase->getStatusBadge() !!}
                                        </td>
                                        <td>
                                            <small>{{ $purchase->created_at->format('d/m/Y') }}</small>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="las la-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Chưa có leads nào được mua</p>
                            <a href="{{ route('user.leads.index') }}" class="btn btn--base">
                                Tìm leads ngay
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Wallet Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ví của tôi</h5>
                        <a href="{{ route('user.wallet.index') }}" class="btn btn-sm btn-outline-primary">
                            Chi tiết
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($wallets->count() > 0)
                        @foreach($wallets as $wallet)
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                            <div>
                                <h6 class="mb-1">{{ $wallet->company->name }}</h6>
                                <small class="text-muted">{{ $wallet->company->category->name ?? 'N/A' }}</small>
                            </div>
                            <div class="text-end">
                                <h6 class="mb-0 text-success">{{ $wallet->getFormattedBalance() }}</h6>
                                <small class="text-muted">Số dư</small>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between">
                                <span>Tổng số dư:</span>
                                <strong class="text-success">{{ number_format($wallets->sum('balance')) }} VNĐ</strong>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="las la-wallet fs-1 text-muted"></i>
                            <p class="text-muted mt-2 mb-3">Chưa có ví nào</p>
                            @if(Auth::user()->companies->count() > 0)
                                <button class="btn btn--base btn-sm" data-bs-toggle="modal" data-bs-target="#createWalletModal">
                                    Tạo ví ngay
                                </button>
                            @else
                                <a href="{{ route('user.company.create') }}" class="btn btn--base btn-sm">
                                    Tạo công ty trước
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Leads -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="las la-bullhorn text-primary fs-2"></i>
                    </div>
                    <h4 class="mb-2">{{ $stats['available_leads'] }}</h4>
                    <p class="text-muted mb-3">Leads có sẵn</p>
                    <a href="{{ route('user.leads.index') }}" class="btn btn--base btn-sm">
                        Xem ngay
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Wallet Modal -->
@if(Auth::user()->companies->count() > 0)
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
                            @foreach(Auth::user()->companies as $company)
                                @if(!$company->wallet)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i>
                        Bạn sẽ nhận được <strong>100,000 VNĐ</strong> thưởng chào mừng (tương đương 10 leads miễn phí)
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
@endsection

@push('style')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .fs-2 {
        font-size: 1.5rem;
    }
    .bg-opacity-10 {
        background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
    }
</style>
@endpush 