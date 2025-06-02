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
                        <h3 class="mb-1">{{ $leadsStats['total_purchased'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Leads đã mua</p>
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
                        <h3 class="mb-1">{{ $leadsStats['contacted'] ?? 0 }}</h3>
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
                        <h3 class="mb-1">{{ $leadsStats['quoted'] ?? 0 }}</h3>
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
                        <h3 class="mb-1">{{ $leadsStats['won'] ?? 0 }}</h3>
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
                @if($recentPurchases && $recentPurchases->count() > 0)
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

    <!-- Available Leads -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="las la-bullhorn text-primary fs-2"></i>
                </div>
                <h4 class="mb-2">{{ $leadsStats['available_leads'] ?? 0 }}</h4>
                <p class="text-muted mb-3">Leads có sẵn</p>
                <a href="{{ route('user.leads.index') }}" class="btn btn--base btn-sm">
                    Tìm leads ngay
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('user.leads.index') }}" class="btn btn-outline-primary">
                        <i class="las la-search me-2"></i>Tìm kiếm leads
                    </a>
                    <a href="{{ route('user.leads.my-purchases') }}" class="btn btn-outline-success">
                        <i class="las la-list me-2"></i>Leads đã mua
                    </a>
                    <a href="{{ route('user.wallet.index') }}" class="btn btn-outline-info">
                        <i class="las la-wallet me-2"></i>Quản lý ví
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 