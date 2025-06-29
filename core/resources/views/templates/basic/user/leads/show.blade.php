@extends($activeTemplate . 'layouts.auth')
@section('content')

<!-- Header Section -->
<div class="page-header-section bg-gradient-primary text-white py-4 mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="page-title d-flex align-items-center gap-3">
                    <div class="page-icon">
                        <i class="las la-briefcase"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1">Chi tiết Nhu cầu #{{ $lead->id }}</h1>
                        <p class="page-subtitle mb-0">{{ $lead->title }}</p>
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
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Nhu cầu Details Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="las la-file-alt me-2"></i>Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <!-- Title & Category -->
                    <div class="mb-4">
                        <h4>{{ $lead->title }}</h4>
                        <span class="badge bg-primary">{{ $lead->category->name ?? 'Không xác định' }}</span>
                        @if($lead->urgency == 'high')
                            <span class="badge bg-danger ms-2">
                                <i class="las la-exclamation-circle me-1"></i>Khẩn cấp
                            </span>
                        @elseif($lead->urgency == 'medium')
                            <span class="badge bg-warning ms-2">
                                <i class="las la-clock me-1"></i>Bình thường
                            </span>
                        @else
                            <span class="badge bg-info ms-2">
                                <i class="las la-hourglass me-1"></i>Không gấp
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6><i class="las la-align-left me-2"></i>Mô tả công việc</h6>
                        <div class="description-content">
                            <p>{{ $lead->description }}</p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <h6><i class="las la-map-marker me-2"></i>Địa điểm</h6>
                        <p>
                            @if($lead->address && is_array($lead->address) && isset($lead->address['detail']))
                                {{ $lead->address['detail'] }}, 
                            @elseif($lead->address && is_string($lead->address))
                                {{ $lead->address }}, 
                            @endif
                            {{ $lead->ward }}, {{ $lead->district }}
                        </p>
                    </div>

                    <!-- Budget -->
                    <div class="mb-4">
                        <h6><i class="las la-dollar-sign me-2"></i>Ngân sách dự kiến</h6>
                        <p class="h5 text-success">
                            {{ number_format($lead->budget_min) }}₫ - {{ number_format($lead->budget_max) }}₫
                        </p>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-4">
                        <h6><i class="las la-calendar me-2"></i>Thời gian</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">Ngày tạo:</small>
                                <p>{{ $lead->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            @if($lead->needed_by)
                            <div class="col-md-6">
                                <small class="text-muted">Cần hoàn thành trước:</small>
                                <p>{{ \Carbon\Carbon::parse($lead->needed_by)->format('d/m/Y') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($hasPurchased && $userPurchase)
                    <!-- Customer Contact Info (Only if purchased) -->
                    <div class="alert alert-success">
                        <h6><i class="las la-user me-2"></i>Thông tin khách hàng</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Tên:</strong> {{ ($lead->customer->firstname ?? '') . ' ' . ($lead->customer->lastname ?? '') }}<br>
                                <strong>Email:</strong> {{ $lead->customer->email ?? 'Chưa cập nhật' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Điện thoại:</strong> {{ $lead->customer->mobile ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>

                    <!-- NEW: Contractor Self-Report Section -->
                    @if($userPurchase->customer_confirmed)
                        <div class="alert alert-success mb-3">
                            <div class="d-flex align-items-center">
                                <i class="las la-check-circle me-2 text-success" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">✅ Khách hàng đã xác nhận chọn bạn!</h6>
                                    <small class="text-muted">Xác nhận lúc: {{ $userPurchase->confirmed_at->format('d/m/Y H:i') }}</small>
                                    @if($userPurchase->confirmation_notes)
                                        <p class="mb-0 mt-1"><strong>Ghi chú:</strong> {{ $userPurchase->confirmation_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($userPurchase->contractor_reported)
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex align-items-center">
                                <i class="las la-clock me-2 text-warning" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1">⏳ Đang chờ khách hàng xác nhận</h6>
                                    <small class="text-muted">Báo cáo lúc: {{ $userPurchase->reported_at->format('d/m/Y H:i') }}</small>
                                    @if($userPurchase->report_notes)
                                        <p class="mb-0 mt-1"><strong>Ghi chú:</strong> {{ $userPurchase->report_notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif($lead->status == 'active')
                        <div class="contractor-report-section mb-3">
                            <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light">
                                <div>
                                    <h6 class="mb-1">💼 Đã thương lượng xong với khách hàng?</h6>
                                    <small class="text-muted">Nếu khách hàng đã chọn bạn, hãy báo cáo để được xác nhận</small>
                                </div>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportSelectedModal">
                                    <i class="las la-handshake me-1"></i>Khách đã chọn tôi
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Purchase Status -->
                    <div class="mb-3">
                        <h6><i class="las la-info-circle me-2"></i>Trạng thái xử lý</h6>
                        <form action="{{ route('user.leads.update-status', $userPurchase->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="status" class="form-select" required>
                                        <option value="contacted" {{ $userPurchase->status == 'contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                                        <option value="quoted" {{ $userPurchase->status == 'quoted' ? 'selected' : '' }}>Đã báo giá</option>
                                        <option value="won" {{ $userPurchase->status == 'won' ? 'selected' : '' }}>Thành công</option>
                                        <option value="lost" {{ $userPurchase->status == 'lost' ? 'selected' : '' }}>Không thành công</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="quote_amount" class="form-control" placeholder="Số tiền báo giá" value="{{ $userPurchase->quote_amount }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </div>
                            <div class="mt-2">
                                <textarea name="notes" class="form-control" rows="2" placeholder="Ghi chú">{{ $userPurchase->notes }}</textarea>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Purchase Card -->
            @if(!$hasPurchased && $lead->status == 'active')
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="las la-shopping-cart me-2"></i>Mua Nhu cầu này</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ number_format($lead->lead_price ?? 50000) }}₫</h4>
                        <small class="text-muted">Giá mua nhu cầu</small>
                    </div>
                    
                    @if(Auth::user()->companies->count() > 0)
                        <form action="{{ route('user.leads.purchase', $lead->id) }}" method="POST">
                            @csrf
                            @if(Auth::user()->companies->count() > 1)
                                <div class="mb-3">
                                    <label class="form-label">Chọn người thợ:</label>
                                    <select name="company_id" class="form-select" required>
                                        @foreach(Auth::user()->companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <input type="hidden" name="company_id" value="{{ Auth::user()->companies->first()->id }}">
                            @endif
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="las la-credit-card me-2"></i>Mua ngay
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <small>Bạn cần tạo hồ sơ người thợ trước khi mua nhu cầu</small>
                        </div>
                        <a href="{{ route('user.company.create') }}" class="btn btn-outline-primary w-100">
                            <i class="las la-plus me-2"></i>Tạo hồ sơ thợ
                        </a>
                    @endif
                </div>
            </div>
            @elseif($hasPurchased)
            <div class="alert alert-success">
                <i class="las la-check-circle me-2"></i>
                <strong>Đã mua nhu cầu này</strong><br>
                <small>Mua lúc: {{ $userPurchase->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @else
            <div class="alert alert-secondary">
                <i class="las la-info-circle me-2"></i>
                Nhu cầu này không còn khả dụng
            </div>
            @endif

            <!-- Nhu cầu Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6><i class="las la-chart-bar me-2"></i>Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="stat-item d-flex justify-content-between mb-2">
                        <span>Số thợ quan tâm:</span>
                        <strong>{{ $lead->purchases->count() }}</strong>
                    </div>
                    <div class="stat-item d-flex justify-content-between mb-2">
                        <span>Ngày tạo:</span>
                        <strong>{{ $lead->created_at->diffForHumans() }}</strong>
                    </div>
                    <div class="stat-item d-flex justify-content-between">
                        <span>Trạng thái:</span>
                        @if($lead->status == 'active')
                            <span class="badge bg-success">Đang hoạt động</span>
                        @elseif($lead->status == 'closed')
                            <span class="badge bg-secondary">Đã đóng</span>
                        @else
                            <span class="badge bg-warning">{{ ucfirst($lead->status) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Related Nhu cầu -->
            <div class="card">
                <div class="card-header">
                    <h6><i class="las la-list me-2"></i>Nhu cầu tương tự</h6>
                </div>
                <div class="card-body">
                    <div class="related-leads">
                        <!-- This would need additional controller logic to populate -->
                        <p class="text-muted">Đang cập nhật...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Selected Modal for Nhu cầu Detail Page -->
@if($hasPurchased && $userPurchase && !$userPurchase->contractor_reported && $lead->status == 'active')
<div class="modal fade" id="reportSelectedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="las la-handshake me-2"></i>Báo cáo được khách hàng chọn
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('user.leads.report-selected', $userPurchase->id) }}" method="POST">
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
                            <h6>{{ $lead->title }}</h6>
                            <p class="mb-1"><i class="las la-map-marker me-1"></i>{{ $lead->district }}</p>
                            <p class="mb-0"><i class="las la-dollar-sign me-1"></i>{{ number_format($lead->budget_min) }}₫ - {{ number_format($lead->budget_max) }}₫</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ghi chú về việc được chọn (tùy chọn)</label>
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
@endif

@endsection

@push('style')
<style>
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
    color: #059669;
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

.stat-item {
    font-size: 0.9rem;
}

.stat-item strong {
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

.alert h6 {
    font-size: 0.95rem;
    font-weight: 600;
}

.alert p {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.alert small {
    font-size: 0.8rem;
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

/* === RESPONSIVE === */
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
    
    .card-body h5 {
        font-size: 0.9rem;
    }
    
    .card-body h6 {
        font-size: 0.85rem;
    }
    
    .card-body p {
        font-size: 0.85rem;
    }
    
    .stat-item {
        font-size: 0.85rem;
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
    
    .card-body h5 {
        font-size: 0.85rem;
    }
    
    .card-body h6 {
        font-size: 0.8rem;
    }
    
    .card-body p {
        font-size: 0.8rem;
    }
    
    .stat-item {
        font-size: 0.8rem;
    }
    
    .alert h6 {
        font-size: 0.85rem;
    }
    
    .alert p {
        font-size: 0.8rem;
    }
    
    .btn {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.35rem 0.7rem;
    }
}
</style>
@endpush 