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
                        <h1 class="h3 mb-1">Chi tiết Lead #{{ $lead->id }}</h1>
                        <p class="page-subtitle mb-0">{{ $lead->title }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="service-nav">
                    <a href="{{ route('user.leads.index') }}" class="btn btn-light btn-sm me-2">
                        <i class="las la-list me-1"></i>Tất cả Leads
                    </a>
                    <a href="{{ route('user.leads.my-purchases') }}" class="btn btn-outline-light btn-sm">
                        <i class="las la-shopping-bag me-1"></i>Đã mua
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Lead Details Card -->
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
                                <strong>Tên:</strong> {{ $lead->customer->name ?? 'Chưa cập nhật' }}<br>
                                <strong>Email:</strong> {{ $lead->customer->email ?? 'Chưa cập nhật' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Điện thoại:</strong> {{ $lead->customer->phone ?? 'Chưa cập nhật' }}
                            </div>
                        </div>
                    </div>

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
                    <h6 class="mb-0"><i class="las la-shopping-cart me-2"></i>Mua Lead này</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ number_format($lead->lead_price ?? 50000) }}₫</h4>
                        <small class="text-muted">Giá mua lead</small>
                    </div>
                    
                    @if(Auth::user()->companies->count() > 0)
                        <form action="{{ route('user.leads.purchase', $lead->id) }}" method="POST">
                            @csrf
                            @if(Auth::user()->companies->count() > 1)
                                <div class="mb-3">
                                    <label class="form-label">Chọn công ty:</label>
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
                            <small>Bạn cần tạo hồ sơ công ty trước khi mua lead</small>
                        </div>
                        <a href="{{ route('user.company.create') }}" class="btn btn-outline-primary w-100">
                            <i class="las la-plus me-2"></i>Tạo công ty
                        </a>
                    @endif
                </div>
            </div>
            @elseif($hasPurchased)
            <div class="alert alert-success">
                <i class="las la-check-circle me-2"></i>
                <strong>Đã mua lead này</strong><br>
                <small>Mua lúc: {{ $userPurchase->created_at->format('d/m/Y H:i') }}</small>
            </div>
            @else
            <div class="alert alert-secondary">
                <i class="las la-info-circle me-2"></i>
                Lead này không còn khả dụng
            </div>
            @endif

            <!-- Lead Stats -->
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

            <!-- Related Leads -->
            <div class="card">
                <div class="card-header">
                    <h6><i class="las la-list me-2"></i>Leads tương tự</h6>
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

@endsection 