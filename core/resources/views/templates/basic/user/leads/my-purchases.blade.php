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
                        <h1 class="h3 mb-1">Nhu cầu đã mua</h1>
                        <p class="page-subtitle mb-0">Quản lý các nhu cầu bạn đã mua</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="service-nav">
                    <!-- Navigation Toggle -->
                    <div class="service-nav-toggle mb-3">
                        <div class="btn-group" role="group">
                            <a href="{{ route('user.leads.index') }}" class="btn btn-outline-light">
                                <i class="las la-list me-1"></i>
                                <span class="d-none d-md-inline">Tất cả Nhu cầu</span>
                                <span class="d-md-none">Tất cả</span>
                            </a>
                            <a href="{{ route('user.leads.my-purchases') }}" class="btn btn-light active">
                                <i class="las la-shopping-bag me-1"></i>
                                <span class="d-none d-md-inline">Đã mua</span>
                                <span class="d-md-none">Đã mua</span>
                            </a>
                        </div>
                    </div>
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
                    <small class="text-muted">Tổng nhu cầu đã mua</small>
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
            <h5><i class="las la-list me-2"></i>Danh sách nhu cầu đã mua</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nhu cầu</th>
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
                                    <strong>{{ ($purchase->lead->customer->firstname ?? '') . ' ' . ($purchase->lead->customer->lastname ?? '') ?: 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $purchase->lead->customer->mobile ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="text-success fw-bold">
                                    {{ number_format($purchase->lead->budget_min) }}₫ - {{ number_format($purchase->lead->budget_max) }}₫
                                </span>
                            </td>
                            <td>
                                @if($purchase->customer_confirmed)
                                    <span class="badge bg-success">✅ Đã xác nhận</span>
                                    <br><small class="text-muted">{{ $purchase->confirmed_at->format('d/m/Y H:i') }}</small>
                                @elseif($purchase->contractor_reported)
                                    <span class="badge bg-warning">⏳ Chờ xác nhận</span>
                                    <br><small class="text-muted">Đã báo: {{ $purchase->reported_at->format('d/m/Y H:i') }}</small>
                                @elseif($purchase->status == 'contacted')
                                    <span class="badge bg-info">📞 Đã liên hệ</span>
                                    @if($purchase->contacted_at)
                                        <br><small class="text-muted">{{ $purchase->contacted_at->format('d/m/Y H:i') }}</small>
                                    @endif
                                @elseif($purchase->status == 'quoted')
                                    <span class="badge bg-warning">💵 Đã báo giá</span>
                                    @if($purchase->quote_amount)
                                        <br><small>{{ number_format($purchase->quote_amount) }}₫</small>
                                    @endif
                                @elseif($purchase->status == 'won')
                                    <span class="badge bg-success">🏆 Thành công (Cũ)</span>
                                @elseif($purchase->status == 'lost')
                                    <span class="badge bg-danger">❌ Không thành công</span>
                                @else
                                    <span class="badge bg-secondary">🆕 Mới mua</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $purchase->created_at->format('d/m/Y H:i') }}</small><br>
                                <small class="text-muted">{{ $purchase->created_at->locale('vi')->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group-vertical" role="group">
                                    <a href="{{ route('user.leads.show', $purchase->lead->id) }}" 
                                       class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="las la-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    @if(!$purchase->customer_confirmed && !$purchase->contractor_reported && $purchase->lead->status == 'active')
                                        <button type="button" class="btn btn-sm btn-success mb-1" 
                                                data-bs-toggle="modal" data-bs-target="#reportModal{{ $purchase->id }}">
                                            <i class="las la-handshake me-1"></i>Khách đã chọn tôi
                                        </button>
                                    @endif
                                    
                                    @if($purchase->contractor_reported && !$purchase->customer_confirmed)
                                        <span class="btn btn-sm btn-warning mb-1 disabled">
                                            <i class="las la-clock me-1"></i>Chờ khách xác nhận
                                        </span>
                                    @endif
                                    
                                    @if(!$purchase->customer_confirmed)
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                data-bs-toggle="modal" data-bs-target="#updateModal{{ $purchase->id }}">
                                            <i class="las la-edit me-1"></i>Cập nhật
                                        </button>
                                    @endif
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

                        <!-- NEW: Report Selected Modal -->
                        <div class="modal fade" id="reportModal{{ $purchase->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title">
                                            <i class="las la-handshake me-2"></i>Báo cáo được khách hàng chọn
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('user.leads.report-selected', $purchase->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                <i class="las la-info-circle me-2"></i>
                                                <strong>Lưu ý:</strong> Chỉ báo cáo khi khách hàng thực sự đã chọn bạn. 
                                                Khách hàng sẽ nhận được thông báo để xác nhận.
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Thông tin công việc:</label>
                                                <div class="bg-light p-3 rounded">
                                                    <h6>{{ $purchase->lead->title }}</h6>
                                                    <p class="mb-1"><i class="las la-map-marker me-1"></i>{{ $purchase->lead->location }}</p>
                                                    <p class="mb-0"><i class="las la-dollar-sign me-1"></i>{{ $purchase->lead->getBudgetRange() }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Ghi chú (tùy chọn)</label>
                                                <textarea name="notes" class="form-control" rows="3" 
                                                          placeholder="Ví dụ: Khách hàng đã đồng ý với báo giá 2,500,000₫ và hẹn làm việc vào thứ 2..."></textarea>
                                                <small class="text-muted">Ghi chú này sẽ được gửi đến khách hàng để xác nhận.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="las la-paper-plane me-1"></i>Gửi yêu cầu xác nhận
                                            </button>
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
            <h4 class="mt-3">Chưa có nhu cầu nào</h4>
            <p class="text-muted">Bạn chưa mua nhu cầu nào. Hãy tìm kiếm và mua nhu cầu phù hợp.</p>
            <a href="{{ route('user.leads.index') }}" class="btn btn-primary">
                <i class="las la-search me-2"></i>Tìm nhu cầu
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
    color: #667eea;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.service-nav-toggle .btn i {
    font-size: 0.9rem;
}

/* === CONSISTENT TEXT SIZING === */
.card-header h5 {
    font-size: 0.95rem;
    font-weight: 600;
}

.card-header h6 {
    font-size: 0.9rem;
    font-weight: 600;
}

.card-body h4 {
    font-size: 1.1rem;
    font-weight: 600;
}

.card-body h5 {
    font-size: 0.95rem;
    font-weight: 600;
}

.card-body h6 {
    font-size: 0.9rem;
    font-weight: 600;
}

.card-body p {
    font-size: 0.9rem;
    line-height: 1.5;
}

.card-body small {
    font-size: 0.8rem;
}

.table {
    font-size: 0.9rem;
}

.table th {
    font-size: 0.85rem;
    font-weight: 600;
}

.table td {
    font-size: 0.85rem;
}

.table h6 {
    font-size: 0.9rem;
    font-weight: 600;
}

.table small {
    font-size: 0.75rem;
}

.badge {
    font-size: 0.7rem;
    padding: 0.3rem 0.6rem;
}

.form-label {
    font-size: 0.9rem;
    font-weight: 500;
}

.form-control {
    font-size: 0.9rem;
}

.btn {
    font-size: 0.85rem;
    font-weight: 500;
}

.btn-sm {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

.modal-title {
    font-size: 1rem;
    font-weight: 600;
}

/* === RESPONSIVE === */
@media (max-width: 991px) {
    /* Hide profile background on mobile */
    .profile-bg {
        display: none !important;
    }
}

@media (max-width: 768px) {
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
    
    .card-header h5 {
        font-size: 0.9rem;
    }
    
    .card-body h4 {
        font-size: 1rem;
    }
    
    .table {
        font-size: 0.8rem;
    }
    
    .table th {
        font-size: 0.75rem;
    }
    
    .table td {
        font-size: 0.75rem;
    }
    
    .table h6 {
        font-size: 0.8rem;
    }
    
    .table small {
        font-size: 0.7rem;
    }
    
    .btn-group-vertical .btn {
        font-size: 0.75rem;
        padding: 0.35rem 0.6rem;
    }
}

@media (max-width: 480px) {
    .service-nav-toggle .btn {
        font-size: 0.75rem;
        padding: 8px 12px;
        min-height: 36px;
    }
    
    .card-header h5 {
        font-size: 0.85rem;
    }
    
    .card-body h4 {
        font-size: 0.95rem;
    }
    
    .table {
        font-size: 0.75rem;
    }
    
    .table th {
        font-size: 0.7rem;
    }
    
    .table td {
        font-size: 0.7rem;
    }
    
    .table h6 {
        font-size: 0.75rem;
    }
    
    .table small {
        font-size: 0.65rem;
    }
    
    .btn-group-vertical .btn {
        font-size: 0.7rem;
        padding: 0.3rem 0.5rem;
    }
    
    .badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }
    
    .modal-title {
        font-size: 0.9rem;
    }
}
</style>
@endpush 