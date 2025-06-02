<!-- Appointments Stats -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="las la-clock text-warning fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ $appointmentStats['pending'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Chờ xác nhận</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="las la-check-circle text-success fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ $appointmentStats['confirmed'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Đã xác nhận</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="las la-flag-checkered text-primary fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ $appointmentStats['completed'] ?? 0 }}</h3>
                        <p class="text-muted mb-0">Hoàn thành</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="las la-money-bill text-info fs-2"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-1">{{ number_format($appointmentStats['total_earned'] ?? 0) }} VNĐ</h3>
                        <p class="text-muted mb-0">Tổng thu nhập</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Appointments -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lịch hẹn gần đây</h5>
                    <a href="{{ route('company.appointments.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentAppointments && $recentAppointments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Khách hàng</th>
                                    <th>Công ty</th>
                                    <th>Ngày hẹn</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAppointments as $appointment)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-1">{{ $appointment->recipient_name }}</h6>
                                            @if($appointment->customer_info_unlocked)
                                                <small class="text-muted">{{ $appointment->recipient_phone }}</small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="las la-lock"></i> Thông tin bị khóa
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $appointment->company->name }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning">Chờ xác nhận</span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge bg-success">Đã xác nhận</span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-primary">Hoàn thành</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($appointment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('company.appointments.show', $appointment->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="las la-eye"></i>
                                            </a>
                                            @if($appointment->status === 'pending')
                                                <button class="btn btn-sm btn-success confirm-appointment" 
                                                        data-id="{{ $appointment->id }}"
                                                        data-customer="{{ $appointment->recipient_name }}">
                                                    <i class="las la-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="las la-calendar-times fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Chưa có lịch hẹn nào</p>
                        <a href="{{ route('company.all') }}" class="btn btn--base">
                            Xem thợ khác
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="col-lg-4 mb-4">
        <!-- Revenue Summary -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="las la-chart-line text-success fs-2"></i>
                </div>
                <h4 class="mb-2">{{ number_format($appointmentStats['this_month_earned'] ?? 0) }} VNĐ</h4>
                <p class="text-muted mb-3">Thu nhập tháng này</p>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h6 class="mb-1">{{ $appointmentStats['this_month_count'] ?? 0 }}</h6>
                            <small class="text-muted">Lịch hẹn</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h6 class="mb-1">{{ $appointmentStats['success_rate'] ?? 0 }}%</h6>
                            <small class="text-muted">Tỷ lệ thành công</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('company.appointments.index') }}" class="btn btn-outline-primary">
                        <i class="las la-calendar-check me-2"></i>Quản lý lịch hẹn
                    </a>
                    <a href="{{ route('user.wallet.index') }}" class="btn btn-outline-success">
                        <i class="las la-wallet me-2"></i>Nạp tiền ví
                    </a>
                    <a href="{{ route('user.company.index') }}" class="btn btn-outline-info">
                        <i class="las la-building me-2"></i>Quản lý công ty
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Payment Modal -->
<div class="modal fade" id="confirmPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="las la-info-circle"></i>
                    <strong>Phí truy cập thông tin khách hàng: 50,000 VNĐ</strong>
                </div>
                <p>Bạn có chắc muốn xác nhận lịch hẹn với khách hàng <strong id="customerName"></strong>?</p>
                <p class="text-muted small">Sau khi xác nhận, bạn sẽ có thể xem thông tin liên hệ của khách hàng.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn--base" id="confirmPaymentBtn">
                    <i class="las la-credit-card me-2"></i>Xác nhận & Thanh toán
                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle confirm appointment with payment
    document.querySelectorAll('.confirm-appointment').forEach(button => {
        button.addEventListener('click', function() {
            const appointmentId = this.dataset.id;
            const customerName = this.dataset.customer;
            
            document.getElementById('customerName').textContent = customerName;
            
            const modal = new bootstrap.Modal(document.getElementById('confirmPaymentModal'));
            modal.show();
            
            document.getElementById('confirmPaymentBtn').onclick = function() {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/user/company/appointments/${appointmentId}/confirm`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            };
        });
    });
});
</script>
@endpush 