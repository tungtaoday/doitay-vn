@extends($activeTemplate . 'layouts.frontend')
@section('content')

<div class="container py-5">
    <div class="row">
        <!-- Back Navigation -->
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.customer.leads.index') }}">Yêu cầu của tôi</a></li>
                    <li class="breadcrumb-item active">Chi tiết yêu cầu</li>
                </ol>
            </nav>
        </div>

        <!-- Lead Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="las la-file-alt me-2"></i>{{ $lead->title }}
                        </h4>
                        <div class="lead-actions">
                            @if($lead->status == 'active')
                                <span class="badge bg-success">
                                    <i class="las la-check-circle me-1"></i>Đang hoạt động
                                </span>
                            @elseif($lead->status == 'closed')
                                <span class="badge bg-secondary">
                                    <i class="las la-times-circle me-1"></i>Đã đóng
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Lead Information -->
                    <div class="lead-info mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Danh mục:</label>
                                <p class="mb-0">
                                    <span class="badge bg-light text-dark">
                                        <i class="las la-tag me-1"></i>{{ $lead->category->name ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Mức độ ưu tiên:</label>
                                <p class="mb-0">
                                    @if($lead->urgency == 'high')
                                        <span class="badge bg-danger">
                                            <i class="las la-exclamation-circle me-1"></i>Khẩn cấp
                                        </span>
                                    @elseif($lead->urgency == 'medium')
                                        <span class="badge bg-warning">
                                            <i class="las la-clock me-1"></i>Bình thường
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            <i class="las la-hourglass me-1"></i>Không gấp
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Địa điểm:</label>
                                <p class="mb-0">
                                    <i class="las la-map-marker text-primary me-1"></i>
                                    {{ $lead->location }}
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Cần hoàn thành trước:</label>
                                <p class="mb-0">
                                    <i class="las la-calendar text-primary me-1"></i>
                                    {{ $lead->needed_by ? \Carbon\Carbon::parse($lead->needed_by)->format('d/m/Y') : 'Không xác định' }}
                                </p>
                            </div>
                        </div>

                        @if($lead->budget_min || $lead->budget_max)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Ngân sách:</label>
                                <p class="mb-0">
                                    <i class="las la-money-bill-wave text-success me-1"></i>
                                    @if($lead->budget_min && $lead->budget_max)
                                        {{ number_format($lead->budget_min) }}₫ - {{ number_format($lead->budget_max) }}₫
                                    @elseif($lead->budget_min)
                                        Từ {{ number_format($lead->budget_min) }}₫
                                    @elseif($lead->budget_max)
                                        Tối đa {{ number_format($lead->budget_max) }}₫
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Ngày tạo:</label>
                                <p class="mb-0">
                                    <i class="las la-calendar-plus text-primary me-1"></i>
                                    {{ $lead->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Lead Description -->
                    <div class="lead-description mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="las la-file-alt me-2"></i>Mô tả chi tiết
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $lead->description }}</p>
                        </div>
                    </div>

                    <!-- Address Details -->
                    @if(isset($lead->address['detail']))
                    <div class="address-details mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="las la-map-marked-alt me-2"></i>Địa chỉ cụ thể
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $lead->address['detail'] }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Requirements -->
                    @if(!empty($lead->requirements))
                    <div class="requirements mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="las la-clipboard-list me-2"></i>Yêu cầu đặc biệt
                        </h5>
                        <div class="bg-light p-3 rounded">
                            <ul class="mb-0">
                                @foreach($lead->requirements as $requirement)
                                    <li>{{ $requirement }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- Attachments -->
                    @if(!empty($lead->attachments))
                    <div class="attachments mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="las la-paperclip me-2"></i>Tệp đính kèm
                        </h5>
                        <div class="row">
                            @foreach($lead->attachments as $attachment)
                            <div class="col-md-6 mb-2">
                                <div class="attachment-item bg-light p-2 rounded">
                                    <i class="las la-file me-2"></i>
                                    <a href="{{ Storage::url($attachment['path']) }}" target="_blank">
                                        {{ $attachment['name'] }}
                                    </a>
                                    <small class="text-muted d-block">
                                        {{ formatBytes($attachment['size'] ?? 0) }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Lead Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="las la-info-circle text-primary me-2"></i>Trạng thái Lead
                    </h5>
                </div>
                <div class="card-body">
                    <div class="lead-stats">
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Thợ quan tâm:</span>
                                <strong class="text-primary">{{ $lead->purchases->count() }}</strong>
                            </div>
                        </div>
                        <div class="stat-item mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Hết hạn:</span>
                                <strong class="text-warning">
                                    {{ $lead->expires_at ? \Carbon\Carbon::parse($lead->expires_at)->format('d/m/Y') : 'N/A' }}
                                </strong>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="d-flex justify-content-between">
                                <span>ID Lead:</span>
                                <code>#{{ $lead->id }}</code>
                            </div>
                        </div>
                    </div>

                    @if($lead->status == 'active')
                    <div class="lead-actions mt-4">
                        <a href="{{ route('user.customer.leads.edit', $lead->id) }}" 
                           class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="las la-edit me-1"></i>Chỉnh sửa Lead
                        </a>
                        <form action="{{ route('user.customer.leads.close', $lead->id) }}" 
                              method="POST" onsubmit="return confirm('Bạn có chắc muốn đóng lead này?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="las la-times-circle me-1"></i>Đóng Lead
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Interested Contractors -->
            @if($lead->purchases->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="las la-users text-primary me-2"></i>Thợ quan tâm ({{ $lead->purchases->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($lead->purchases as $purchase)
                    <div class="contractor-item mb-3 p-3 border rounded">
                        <div class="d-flex align-items-center">
                            <div class="contractor-avatar me-3">
                                <img src="{{ getImage(getFilePath('company') . '/' . $purchase->company->image, getFileSize('company')) }}" 
                                     alt="{{ $purchase->company->name }}" 
                                     class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                            </div>
                            <div class="contractor-info flex-grow-1">
                                <h6 class="mb-1">{{ $purchase->company->name }}</h6>
                                <small class="text-muted">
                                    <i class="las la-clock me-1"></i>
                                    Quan tâm {{ $purchase->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="contractor-actions mt-3">
                            <a href="{{ route('company.details', [$purchase->company->id, slug($purchase->company->name)]) }}" 
                               class="btn btn-outline-primary btn-sm me-2" target="_blank">
                                <i class="las la-eye me-1"></i>Xem profile
                            </a>
                            
                            @if($lead->status == 'active')
                            <form action="{{ route('user.customer.leads.select-contractor', $lead->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="company_id" value="{{ $purchase->company->id }}">
                                <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn chọn thợ này?')">
                                    <i class="las la-handshake me-1"></i>Chọn thợ
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4">
                    <i class="las la-hourglass-half text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Chưa có thợ quan tâm</h5>
                    <p class="text-muted small">
                        Lead của bạn đang được hiển thị cho các thợ phù hợp. 
                        Hãy kiên nhẫn chờ đợi nhé!
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.lead-info .form-label {
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.attachment-item {
    transition: background-color 0.2s ease;
}

.attachment-item:hover {
    background-color: #e9ecef !important;
}

.contractor-item {
    transition: all 0.2s ease;
}

.contractor-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.stat-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.stat-item:last-child {
    border-bottom: none;
}

@media (max-width: 768px) {
    .lead-actions .btn {
        margin-bottom: 0.5rem;
    }
    
    .contractor-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .contractor-actions .btn:last-child {
        margin-bottom: 0;
    }
}
</style>

@endsection 