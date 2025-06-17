@extends('templates.basic.layouts.auth')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>{{ $pageTitle ?? 'Dashboard' }}</h4>
            </div>
            <div class="card-body">
                <h5>Chào mừng, {{ $user->fullname ?? 'User' }}!</h5>
                <p>Dashboard đang hoạt động!</p>
                
                @if(isset($stats))
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h6>{{ $stats['total_appointments'] }}</h6>
                                <small>Tổng lịch hẹn</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h6>{{ $stats['pending_appointments'] }}</h6>
                                <small>Đang chờ</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h6>{{ $stats['completed_appointments'] }}</h6>
                                <small>Hoàn thành</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h6>{{ $stats['loyalty_points'] }}</h6>
                                <small>Điểm thưởng</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <!-- Lead Creation Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light border-0">
                            <div class="card-body text-center">
                                <i class="las la-bullhorn fs-1 text-primary mb-3"></i>
                                <h5>Cần tìm thợ chuyên nghiệp?</h5>
                                <p class="text-muted mb-4">Tạo yêu cầu dịch vụ để các thợ uy tín liên hệ với bạn</p>
                                <a href="{{ route('user.customer.leads.create') }}" class="btn btn--base btn-lg">
                                    <i class="las la-plus me-2"></i>Tạo yêu cầu dịch vụ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Thao tác nhanh:</h6>
                    <a href="/company/all" class="btn btn-primary me-2">
                        <i class="las la-search me-1"></i>Tìm thợ
                    </a>
                    <a href="{{ route('user.customer.leads.index') }}" class="btn btn-info me-2">
                        <i class="las la-list me-1"></i>Leads của tôi
                    </a>
                    <a href="{{ route('user.data') }}" class="btn btn-secondary">
                        <i class="las la-user-edit me-1"></i>Xem thông tin cá nhân
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    .card.bg-light {
        background: linear-gradient(45deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .fs-1 {
        font-size: 3rem;
    }
    
    .btn--base {
        background: var(--base-color, #007bff);
        border-color: var(--base-color, #007bff);
        color: white;
    }
    
    .btn--base:hover {
        background: var(--base-hover-color, #0056b3);
        border-color: var(--base-hover-color, #0056b3);
        color: white;
    }
</style>
@endpush
@endsection 