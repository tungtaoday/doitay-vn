@extends($activeTemplate . 'layouts.auth')
@section('content')
    <div class="notice"></div>
    
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $pageTitle }}</h4>
                </div>
                <div class="card-body">
                    <div class="welcome-message">
                        <h5>Chào mừng, {{ $user->fullname }}!</h5>
                        <p>Đây là dashboard của bạn.</p>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['total_appointments'] }}</h5>
                                    <p class="text-muted mb-0">Tổng lịch hẹn</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['pending_appointments'] }}</h5>
                                    <p class="text-muted mb-0">Đang chờ</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['completed_appointments'] }}</h5>
                                    <p class="text-muted mb-0">Hoàn thành</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h5>{{ number_format($stats['loyalty_points']) }}</h5>
                                    <p class="text-muted mb-0">Điểm thưởng</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Appointments List -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6>Lịch hẹn gần đây</h6>
                        </div>
                        <div class="card-body">
                            @if($appointments->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Ngày</th>
                                                <th>Công ty</th>
                                                <th>Trạng thái</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($appointments as $appointment)
                                            <tr>
                                                <td>{{ $appointment->created_at->format('d/m/Y') }}</td>
                                                <td>{{ $appointment->company->name ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $appointment->status }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">Chưa có lịch hẹn nào.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h6>Thao tác nhanh</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('company.all') }}" class="btn btn-outline-primary w-100">
                                        Tìm thợ
                                    </a>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="{{ route('user.profile.setting') }}" class="btn btn-outline-info w-100">
                                        Cập nhật hồ sơ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 