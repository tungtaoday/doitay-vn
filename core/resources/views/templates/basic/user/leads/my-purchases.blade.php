@extends($activeTemplate . 'layouts.auth')
@section('content')

<!-- Header Section -->
<div class="page-header-section bg-gradient-primary text-white py-4 mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-title d-flex align-items-center gap-3">
                    <div class="page-icon">
                        <i class="las la-shopping-bag"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1">Leads đã mua</h1>
                        <p class="page-subtitle mb-0">Quản lý các leads bạn đã mua</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="service-nav">
                    <a href="{{ route('user.leads.index') }}" class="btn btn-light btn-sm me-2">
                        <i class="las la-list me-1"></i>Tất cả Leads
                    </a>
                    <a href="{{ route('user.leads.dashboard') }}" class="btn btn-outline-light btn-sm">
                        <i class="las la-chart-bar me-1"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    @if($purchases->count() > 0)
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="las la-shopping-cart text-primary fs-1"></i>
                    <h4>{{ $purchases->total() }}</h4>
                    <small class="text-muted">Tổng leads đã mua</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="las la-phone text-success fs-1"></i>
                    <h4>{{ $purchases->where('status', 'contacted')->count() }}</h4>
                    <small class="text-muted">Đã liên hệ</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="las la-file-invoice-dollar text-warning fs-1"></i>
                    <h4>{{ $purchases->where('status', 'quoted')->count() }}</h4>
                    <small class="text-muted">Đã báo giá</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <i class="las la-trophy text-success fs-1"></i>
                    <h4>{{ $purchases->where('status', 'won')->count() }}</h4>
                    <small class="text-muted">Thành công</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchases List -->
    <div class="card">
        <div class="card-header">
            <h5><i class="las la-list me-2"></i>Danh sách leads đã mua</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Lead</th>
                            <th>Khách hàng</th>
                            <th>Ngân sách</th>
                            <th>Trạng thái</th>
                            <th>Ngày mua</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-1">{{ $purchase->lead->title }}</h6>
                                    <small class="text-muted">
                                        <i class="las la-tag me-1"></i>{{ $purchase->lead->category->name ?? 'N/A' }}
                                    </small><br>
                                    <small class="text-muted">
                                        <i class="las la-map-marker me-1"></i>{{ $purchase->lead->district }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $purchase->lead->customer->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $purchase->lead->customer->phone ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="text-success fw-bold">
                                    {{ number_format($purchase->lead->budget_min) }}₫ - {{ number_format($purchase->lead->budget_max) }}₫
                                </span>
                            </td>
                            <td>
                                @if($purchase->status == 'contacted')
                                    <span class="badge bg-info">Đã liên hệ</span>
                                @elseif($purchase->status == 'quoted')
                                    <span class="badge bg-warning">Đã báo giá</span>
                                    @if($purchase->quote_amount)
                                        <br><small>{{ number_format($purchase->quote_amount) }}₫</small>
                                    @endif
                                @elseif($purchase->status == 'won')
                                    <span class="badge bg-success">Thành công</span>
                                @elseif($purchase->status == 'lost')
                                    <span class="badge bg-danger">Không thành công</span>
                                @else
                                    <span class="badge bg-secondary">Mới mua</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $purchase->created_at->format('d/m/Y H:i') }}</small><br>
                                <small class="text-muted">{{ $purchase->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user.leads.show', $purchase->lead->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="las la-eye me-1"></i>Xem
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#updateModal{{ $purchase->id }}">
                                        <i class="las la-edit me-1"></i>Cập nhật
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Update Status Modal -->
                        <div class="modal fade" id="updateModal{{ $purchase->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Cập nhật trạng thái: {{ $purchase->lead->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('user.leads.update-status', $purchase->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Trạng thái</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="contacted" {{ $purchase->status == 'contacted' ? 'selected' : '' }}>
                                                        Đã liên hệ
                                                    </option>
                                                    <option value="quoted" {{ $purchase->status == 'quoted' ? 'selected' : '' }}>
                                                        Đã báo giá
                                                    </option>
                                                    <option value="won" {{ $purchase->status == 'won' ? 'selected' : '' }}>
                                                        Thành công
                                                    </option>
                                                    <option value="lost" {{ $purchase->status == 'lost' ? 'selected' : '' }}>
                                                        Không thành công
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Số tiền báo giá (₫)</label>
                                                <input type="number" name="quote_amount" class="form-control" 
                                                       placeholder="Nhập số tiền báo giá" value="{{ $purchase->quote_amount }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Ghi chú</label>
                                                <textarea name="notes" class="form-control" rows="3" 
                                                          placeholder="Ghi chú về quá trình xử lý">{{ $purchase->notes }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($purchases->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="las la-shopping-bag text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3">Chưa có leads nào</h4>
            <p class="text-muted">Bạn chưa mua leads nào. Hãy tìm kiếm và mua leads phù hợp.</p>
            <a href="{{ route('user.leads.index') }}" class="btn btn-primary">
                <i class="las la-search me-2"></i>Tìm leads
            </a>
        </div>
    </div>
    @endif
</div>

@endsection

@push('style')
<style>
.page-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.375rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 0.25rem;
}

.badge {
    font-size: 0.75rem;
}

.modal-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush 