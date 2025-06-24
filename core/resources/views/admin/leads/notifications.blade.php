@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $pageTitle }}</h4>
                <p class="text-muted">Giám sát thông báo lead và tỷ lệ tương tác</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leads.index') }}" class="btn btn--secondary">
                    <i class="las la-arrow-left"></i> Quay lại
                </a>
                <button class="btn btn--primary refresh-notifications">
                    <i class="las la-sync"></i> Làm mới
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($stats['total_notifications']) }}</h4>
                        <p class="mb-0">Tổng Thông Báo</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-bell"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($stats['read_notifications']) }}</h4>
                        <p class="mb-0">Đã Đọc</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        Tỷ lệ: {{ $stats['total_notifications'] > 0 ? round(($stats['read_notifications'] / $stats['total_notifications']) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($stats['converted_notifications']) }}</h4>
                        <p class="mb-0">Chuyển Đổi</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-shopping-cart"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        Tỷ lệ: {{ $stats['total_notifications'] > 0 ? round(($stats['converted_notifications'] / $stats['total_notifications']) * 100, 1) : 0 }}%
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ $stats['avg_read_time'] ?? '0' }}</h4>
                        <p class="mb-0">Phút TB Đọc</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-clock"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        Thời gian trung bình từ gửi đến đọc
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('admin.leads.notifications') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <select name="type" class="form-control">
                                <option value="">Tất cả loại</option>
                                <option value="smart_lead" {{ request('type') == 'smart_lead' ? 'selected' : '' }}>Smart Lead</option>
                                <option value="new_lead" {{ request('type') == 'new_lead' ? 'selected' : '' }}>Lead mới</option>
                                <option value="lead_created" {{ request('type') == 'lead_created' ? 'selected' : '' }}>Lead đã tạo</option>
                                <option value="lead_purchased" {{ request('type') == 'lead_purchased' ? 'selected' : '' }}>Lead đã mua</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Đã đọc</option>
                                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="priority" class="form-control">
                                <option value="">Tất cả mức độ</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>Cao</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Thấp</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Từ ngày">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Đến ngày">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn--primary w-100">
                                <i class="las la-search"></i> Lọc
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Notifications Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-list text-primary"></i>
                    Danh sách Thông báo Lead
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lead</th>
                                <th>Người nhận</th>
                                <th>Loại</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Ưu tiên</th>
                                <th>Thời gian</th>
                                <th>Phản hồi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                            <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                <td>
                                    <span class="fw-bold">#{{ $notification->id }}</span>
                                </td>
                                <td>
                                    @if($notification->data && isset($notification->data['lead_id']))
                                        <a href="{{ route('admin.leads.show', $notification->data['lead_id']) }}" class="text-primary">
                                            Lead #{{ $notification->data['lead_id'] }}
                                        </a>
                                        @if(isset($notification->data['lead_title']))
                                            <br><small class="text-muted">{{ Str::limit($notification->data['lead_title'], 25) }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="user">
                                        <div class="user-info">
                                            <span class="fw-bold">{{ $notification->user->firstname }} {{ $notification->user->lastname }}</span>
                                            <br>
                                            <small class="text-muted">{{ $notification->user->company->name ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @switch($notification->type)
                                        @case('smart_lead')
                                            <span class="badge badge--success">Smart Lead</span>
                                            @break
                                        @case('new_lead')
                                            <span class="badge badge--primary">Lead Mới</span>
                                            @break
                                        @case('lead_created')
                                            <span class="badge badge--info">Đã Tạo</span>
                                            @break
                                        @case('lead_purchased')
                                            <span class="badge badge--warning">Đã Mua</span>
                                            @break
                                        @default
                                            <span class="badge badge--secondary">{{ $notification->type }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="notification-title">
                                        {{ Str::limit($notification->title, 40) }}
                                    </div>
                                    @if($notification->message)
                                        <small class="text-muted">{{ Str::limit($notification->message, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($notification->is_read)
                                        <span class="badge badge--success">
                                            <i class="las la-check-circle"></i> Đã đọc
                                        </span>
                                        @if($notification->read_at)
                                            <br><small class="text-muted">{{ $notification->read_at->format('H:i d/m') }}</small>
                                        @endif
                                    @else
                                        <span class="badge badge--warning">
                                            <i class="las la-clock"></i> Chưa đọc
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @switch($notification->priority)
                                        @case('high')
                                            <span class="badge badge--danger">Cao</span>
                                            @break
                                        @case('medium')
                                            <span class="badge badge--warning">TB</span>
                                            @break
                                        @case('low')
                                            <span class="badge badge--secondary">Thấp</span>
                                            @break
                                        @default
                                            <span class="badge badge--secondary">{{ $notification->priority }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="timeline-info">
                                        <strong>Gửi:</strong> {{ $notification->created_at->format('H:i d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        @if($notification->expires_at)
                                            <br><small class="text-danger">Hết hạn: {{ $notification->expires_at->format('H:i d/m') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($notification->data && isset($notification->data['lead_id']))
                                        @php
                                            $leadId = $notification->data['lead_id'];
                                            $hasPurchase = \App\Models\LeadPurchase::where('lead_id', $leadId)
                                                ->where('company_id', $notification->user->company_id)
                                                ->exists();
                                        @endphp
                                        @if($hasPurchase)
                                            <span class="badge badge--success">
                                                <i class="las la-shopping-cart"></i> Đã mua
                                            </span>
                                        @else
                                            <span class="badge badge--secondary">
                                                <i class="las la-eye"></i> Chỉ xem
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn--secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($notification->data && isset($notification->data['lead_id']))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.leads.show', $notification->data['lead_id']) }}">
                                                        <i class="las la-eye"></i> Xem Lead
                                                    </a>
                                                </li>
                                            @endif
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.detail', $notification->user_id) }}">
                                                    <i class="las la-user"></i> Xem User
                                                </a>
                                            </li>
                                            @if(!$notification->is_read)
                                                <li>
                                                    <button class="dropdown-item mark-read" data-id="{{ $notification->id }}">
                                                        <i class="las la-check"></i> Đánh dấu đã đọc
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="las la-bell-slash fs-2 text-muted"></i>
                                    <p class="text-muted">Không có thông báo nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($notifications->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Hiển thị {{ $notifications->firstItem() }}-{{ $notifications->lastItem() }} 
                            trong tổng số {{ $notifications->total() }} thông báo
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $notifications->appends(request()->all())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.notification-title {
    font-weight: 600;
    color: #333;
}

.timeline-info {
    font-size: 0.85rem;
}

.user-info {
    max-width: 150px;
}

.dashboard-icon {
    font-size: 2rem;
    opacity: 0.8;
}

@media print {
    .btn, .dropdown, .pagination {
        display: none !important;
    }
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Refresh notifications
    $('.refresh-notifications').click(function() {
        location.reload();
    });

    // Mark as read
    $('.mark-read').click(function() {
        const notificationId = $(this).data('id');
        const button = $(this);
        
        $.post('{{ route("admin.user.notifications.mark.read") }}', {
            _token: '{{ csrf_token() }}',
            notification_id: notificationId
        }).done(function(response) {
            if(response.success) {
                button.closest('tr').removeClass('table-warning');
                button.closest('.dropdown-menu').find('.mark-read').remove();
                location.reload();
            }
        }).fail(function() {
            alert('Có lỗi xảy ra khi đánh dấu đã đọc');
        });
    });

    // Auto refresh every 30 seconds
    setInterval(function() {
        const url = new URL(window.location);
        url.searchParams.set('auto_refresh', '1');
        
        $.get(url.toString())
            .done(function(data) {
                // Update notification count if needed
                const newCount = $(data).find('.table tbody tr').length;
                // Could update some indicator here
            });
    }, 30000);
});
</script>
@endpush 