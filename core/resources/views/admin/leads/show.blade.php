@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $pageTitle }}</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.leads.index') }}">Leads</a></li>
                        <li class="breadcrumb-item active">Chi tiết #{{ $lead->id }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leads.index') }}" class="btn btn--secondary">
                    <i class="las la-arrow-left"></i> Quay lại
                </a>
                @if($lead->status === 'active')
                    <button class="btn btn--warning update-status" data-id="{{ $lead->id }}" data-status="closed">
                        <i class="las la-times-circle"></i> Đóng Lead
                    </button>
                @elseif($lead->status === 'closed')
                    <button class="btn btn--success update-status" data-id="{{ $lead->id }}" data-status="active">
                        <i class="las la-redo"></i> Mở lại Lead
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Lead Information -->
    <div class="col-lg-8">
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-info-circle text-primary"></i>
                    Thông tin Lead
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Tiêu đề</label>
                            <h6 class="form-control-plaintext">{{ $lead->title }}</h6>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Mô tả</label>
                            <p class="form-control-plaintext">{{ $lead->description }}</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Danh mục</label>
                            <span class="badge badge--primary">{{ $lead->category->name ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Địa điểm</label>
                            <p class="form-control-plaintext">
                                <i class="las la-map-marker-alt text-primary"></i>
                                {{ $lead->location }}
                                @if($lead->address && isset($lead->address['detail']))
                                    <br><small class="text-muted">{{ $lead->address['detail'] }}</small>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Ngân sách</label>
                            <h6 class="form-control-plaintext text-success">{{ $lead->getBudgetRange() }}</h6>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Mức độ ưu tiên</label>
                            <div>{!! $lead->getUrgencyBadge() !!}</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Trạng thái</label>
                            <div>{!! $lead->getStatusBadge() !!}</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Ngày cần hoàn thành</label>
                            <p class="form-control-plaintext">
                                {{ $lead->needed_by ? $lead->needed_by->format('d/m/Y') : 'Không xác định' }}
                            </p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Hết hạn</label>
                            <p class="form-control-plaintext">
                                {{ $lead->expires_at ? $lead->expires_at->format('d/m/Y H:i') : 'Không giới hạn' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                @if($lead->requirements && count($lead->requirements) > 0)
                <div class="form-group">
                    <label class="form-label">Yêu cầu đặc biệt</label>
                    <ul class="list-unstyled">
                        @foreach($lead->requirements as $requirement)
                            <li><i class="las la-check text-success"></i> {{ $requirement }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        @if($lead->customer)
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-user text-info"></i>
                    Thông tin Khách hàng
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="user">
                            <div class="user-info">
                                <span class="fw-bold">{{ $lead->customer->firstname }} {{ $lead->customer->lastname }}</span>
                                <br>
                                <small class="text-muted">ID: {{ $lead->customer->id }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1">
                            <i class="las la-phone text-primary"></i>
                            <strong>SĐT:</strong> {{ $lead->customer->mobile }}
                        </p>
                        <p class="mb-1">
                            <i class="las la-envelope text-primary"></i>
                            <strong>Email:</strong> {{ $lead->customer->email }}
                        </p>
                        <p class="mb-0">
                            <i class="las la-calendar text-primary"></i>
                            <strong>Tham gia:</strong> {{ $lead->customer->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Timeline -->
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-history text-warning"></i>
                    Timeline Lead
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($timeline as $event)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-{{ $event['color'] }}">
                            <i class="{{ $event['icon'] }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">{{ $event['title'] }}</h6>
                            <p class="timeline-description">{{ $event['description'] }}</p>
                            <small class="timeline-date text-muted">
                                <i class="las la-clock"></i>
                                {{ $event['timestamp']->format('d/m/Y H:i') }}
                                ({{ $event['timestamp']->diffForHumans() }})
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Lead Statistics -->
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-chart-bar text-success"></i>
                    Thống kê
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-info">{{ $lead->visibilities->count() }}</h4>
                            <small class="text-muted">Thông báo gửi</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-success">{{ $lead->purchases->count() }}</h4>
                            <small class="text-muted">Lượt mua</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-warning">{{ $lead->purchases->where('contacted_at', '!=', null)->count() }}</h4>
                            <small class="text-muted">Đã liên hệ</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h4 class="text-primary">{{ $lead->purchases->where('outcome', 'won')->count() }}</h4>
                            <small class="text-muted">Thắng thầu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notified Companies -->
        @if($lead->visibilities->count() > 0)
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-bell text-info"></i>
                    Thợ được thông báo ({{ $lead->visibilities->count() }})
                </h5>
            </div>
            <div class="card-body">
                @foreach($lead->visibilities as $visibility)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="user">
                        <div class="user-info">
                            <span class="fw-bold">{{ $visibility->company->name }}</span>
                            <br>
                            <small class="text-muted">
                                Điểm ưu tiên: {{ number_format($visibility->priority_score, 1) }}/5.0
                            </small>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">{{ $visibility->notified_at->format('H:i d/m') }}</small>
                        @if($visibility->expires_at && $visibility->expires_at->isFuture())
                            <br><small class="text-warning">Độc quyền</small>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Purchase History -->
        @if($lead->purchases->count() > 0)
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-shopping-cart text-success"></i>
                    Lịch sử mua ({{ $lead->purchases->count() }})
                </h5>
            </div>
            <div class="card-body">
                @foreach($lead->purchases as $purchase)
                <div class="purchase-item mb-3 p-3 border rounded">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="user">
                            <div class="user-info">
                                <span class="fw-bold">{{ $purchase->company->name }}</span>
                                <br>
                                <small class="text-muted">
                                    {{ number_format($purchase->price_paid) }}₫ • 
                                    {{ $purchase->created_at->format('H:i d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            @switch($purchase->outcome)
                                @case('won')
                                    <span class="badge badge--success">Thắng thầu</span>
                                    @break
                                @case('lost')
                                    <span class="badge badge--danger">Thua thầu</span>
                                    @break
                                @default
                                    <span class="badge badge--warning">Chờ kết quả</span>
                            @endswitch
                        </div>
                    </div>
                    
                    @if($purchase->contacted_at || $purchase->quoted_at)
                    <div class="mt-2">
                        @if($purchase->contacted_at)
                            <small class="text-success">
                                <i class="las la-phone"></i> Đã liên hệ: {{ $purchase->contacted_at->format('H:i d/m') }}
                            </small>
                        @endif
                        @if($purchase->quoted_at)
                            <br><small class="text-info">
                                <i class="las la-dollar-sign"></i> Báo giá: {{ number_format($purchase->quote_amount) }}₫
                            </small>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Related Notifications -->
        @if($notifications->count() > 0)
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-bell text-warning"></i>
                    Notifications ({{ $notifications->count() }})
                </h5>
            </div>
            <div class="card-body">
                @foreach($notifications as $notification)
                <div class="notification-item mb-3 p-2 border-start border-3 border-primary">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">{{ $notification->title }}</span>
                        <small class="text-muted">{{ $notification->created_at->format('H:i d/m') }}</small>
                    </div>
                    <p class="mb-1 small">{{ $notification->message }}</p>
                    <small class="text-muted">
                        Gửi cho: {{ $notification->user->firstname }} {{ $notification->user->lastname }}
                        @if($notification->is_read)
                            <span class="badge badge--success ms-1">Đã đọc</span>
                        @else
                            <span class="badge badge--warning ms-1">Chưa đọc</span>
                        @endif
                    </small>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Bạn có chắc muốn thay đổi trạng thái lead này?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn--primary">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    border: 3px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-description {
    margin-bottom: 10px;
    color: #6c757d;
}

.timeline-date {
    font-size: 12px;
}

.purchase-item {
    transition: all 0.2s ease;
}

.purchase-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.notification-item {
    transition: all 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Update status
    $('.update-status').click(function() {
        const leadId = $(this).data('id');
        const status = $(this).data('status');
        
        $('#statusForm').attr('action', `{{ route('admin.leads.status.update', '') }}/${leadId}`);
        $('#statusForm').find('input[name="status"]').remove();
        $('#statusForm').append(`<input type="hidden" name="status" value="${status}">`);
        $('#statusModal').modal('show');
    });
});
</script>
@endpush 