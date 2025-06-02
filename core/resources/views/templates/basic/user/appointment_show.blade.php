@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="container mt-4">
    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text--base">{{ $pageTitle ?? 'Chi tiết lịch hẹn' }}</h1>
        <a href="{{ $isCompany ? route('company.appointments.index') : route('appointments.index') }}" class="btn btn--base">
            <i class="las la-arrow-left"></i> @lang('Quay lại')
        </a>
    </div>

    <!-- Card thông tin lịch hẹn -->
    <div class="card custom--card">
        <div class="card-header bg--base text-white">
            <h5 class="mb-0">@lang('Lịch hẹn #'){{ $appointment->id }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Công ty')</h6>
                        <p class="mb-0">{{ $appointment->company ? $appointment->company->name : 'N/A' }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Ngày hẹn')</h6>
                        <p class="mb-0">{{ $appointment->appointment_date }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Giờ hẹn')</h6>
                        <p class="mb-0">{{ $appointment->appointment_time }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Tên người nhận')</h6>
                        <p class="mb-0">{{ $appointment->recipient_name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Số điện thoại')</h6>
                        <p class="mb-0">{{ $appointment->recipient_phone }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Địa chỉ')</h6>
                        <p class="mb-0">{{ $appointment->recipient_address }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Ghi chú')</h6>
                        <p class="mb-0">{{ $appointment->notes ?? 'N/A' }}</p>
                    </div>
                    <div class="info-item mb-3">
                        <h6 class="text-muted mb-1">@lang('Trạng thái')</h6>
                        <span class="badge {{ $appointment->status === 'pending' ? 'bg-warning' : ($appointment->status === 'confirmed' ? 'bg-success' : ($appointment->status === 'completed' ? 'bg-primary' : 'bg-danger')) }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Timeline -->
    <div class="mt-5">
        <h4 class="text--base mb-4">@lang('Quy trình')</h4>
        <div class="timeline-wrapper">
            <div class="timeline">
                <div class="timeline-item {{ $appointment->status === 'pending' || $appointment->status === 'confirmed' || $appointment->status === 'completed' ? 'active' : '' }}">
                    <div class="timeline-icon">
                        <i class="las la-clock"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h5>@lang('Đang chờ')</h5>
                            <span class="timeline-date">{{ $appointment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p>@lang('Lịch hẹn đã được tạo và đang chờ xác nhận')</p>
                    </div>
                </div>
                <div class="timeline-item {{ $appointment->status === 'confirmed' || $appointment->status === 'completed' ? 'active' : '' }}">
                    <div class="timeline-icon">
                        <i class="las la-check"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h5>@lang('Đã xác nhận')</h5>
                            @if($appointment->status === 'confirmed' || $appointment->status === 'completed')
                                <span class="timeline-date">{{ $appointment->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        <p>@lang('Lịch hẹn đã được xác nhận bởi') {{ $isCompany ? 'bạn' : 'công ty' }}</p>
                    </div>
                </div>
                <div class="timeline-item {{ $appointment->status === 'completed' ? 'active' : '' }}">
                    <div class="timeline-icon">
                        <i class="las la-flag-checkered"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h5>@lang('Hoàn thành')</h5>
                            @if($appointment->status === 'completed')
                                <span class="timeline-date">{{ $appointment->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        <p>@lang('Lịch hẹn đã hoàn thành thành công')</p>
                    </div>
                </div>
                <div class="timeline-item {{ $appointment->status === 'canceled' ? 'active' : '' }}">
                    <div class="timeline-icon">
                        <i class="las la-times"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-header">
                            <h5>@lang('Đã hủy')</h5>
                            @if($appointment->status === 'canceled')
                                <span class="timeline-date">{{ $appointment->updated_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        <p>@lang('Lịch hẹn đã bị hủy bởi') {{ $isCompany ? 'bạn' : 'công ty' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút hành động -->
    <div class="mt-4">
        @if ($isCompany)
            @if ($appointment->status === 'pending')
                <form action="{{ route('company.appointments.confirm', $appointment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn--success">
                        <i class="las la-check"></i> @lang('Xác nhận')
                    </button>
                </form>
                <form action="{{ route('company.appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn--danger">
                        <i class="las la-times"></i> @lang('Hủy')
                    </button>
                </form>
            @elseif ($appointment->status === 'confirmed')
                <form action="{{ route('company.appointments.complete', $appointment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn--primary">
                        <i class="las la-flag-checkered"></i> @lang('Hoàn thành')
                    </button>
                </form>
            @endif
        @else
            @if ($appointment->status === 'pending')
                <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn--danger">
                        <i class="las la-times"></i> @lang('Hủy')
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .custom--card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .timeline-wrapper {
        position: relative;
        padding: 20px 0;
    }
    .timeline {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    .timeline-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        position: relative;
        z-index: 2;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border: 2px solid #e9ecef;
        color: #6c757d;
        font-size: 18px;
        margin-right: 20px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }
    .timeline-item.active .timeline-icon {
        background: var(--base);
        border-color: var(--base);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(var(--base-rgb), 0.1);
    }
    .timeline-content {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        flex-grow: 1;
        position: relative;
    }
    .timeline-content::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 20px;
        width: 16px;
        height: 16px;
        background: #fff;
        transform: rotate(45deg);
        box-shadow: -2px 2px 5px rgba(0,0,0,0.05);
    }
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .timeline-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    .timeline-date {
        font-size: 13px;
        color: #6c757d;
    }
    .timeline-content p {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
        line-height: 1.5;
    }
    .timeline-item.active .timeline-content {
        background: var(--base);
    }
    .timeline-item.active .timeline-content::before {
        background: var(--base);
    }
    .timeline-item.active .timeline-content h5,
    .timeline-item.active .timeline-content p,
    .timeline-item.active .timeline-content .timeline-date {
        color: #fff;
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .btn--success {
        background: #28a745;
        color: white;
    }
    .btn--danger {
        background: #dc3545;
        color: white;
    }
    .btn--primary {
        background: var(--base);
        color: white;
    }
</style>
@endsection