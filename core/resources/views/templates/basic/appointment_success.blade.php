@extends($activeTemplate . 'layouts.frontend')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <!-- Success Icon -->
                    <div class="success-icon mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                            <i class="las la-check-circle text-success" style="font-size: 60px;"></i>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="text-success mb-3">Đặt lịch thành công!</h2>
                    <p class="lead text-muted mb-4">
                        Cảm ơn bạn đã đặt lịch hẹn. Chúng tôi đã ghi nhận yêu cầu của bạn.
                    </p>
                    
                    <!-- Appointment Details -->
                    @if(isset($appointment))
                    <div class="appointment-summary bg-light rounded p-4 mb-4">
                        <h5 class="mb-3">Thông tin lịch hẹn</h5>
                        <div class="row text-start">
                            <div class="col-md-6 mb-3">
                                <strong>Người thợ:</strong><br>
                                <span class="text-muted">{{ $appointment->company->name }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Ngày hẹn:</strong><br>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Giờ hẹn:</strong><br>
                                <span class="text-muted">{{ $appointment->appointment_time }}</span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Trạng thái:</strong><br>
                                <span class="badge bg-warning">Chờ xác nhận</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Next Steps -->
                    <div class="next-steps bg-info bg-opacity-10 rounded p-4 mb-4">
                        <h6 class="text-info mb-3">
                            <i class="las la-info-circle me-2"></i>
                            Bước tiếp theo
                        </h6>
                        <ul class="list-unstyled text-start mb-0">
                            <li class="mb-2">
                                <i class="las la-check text-success me-2"></i>
                                Thợ sẽ xem xét và xác nhận lịch hẹn trong vòng 2-4 giờ
                            </li>
                            <li class="mb-2">
                                <i class="las la-check text-success me-2"></i>
                                Bạn sẽ nhận được email thông báo khi lịch hẹn được xác nhận
                            </li>
                            <li class="mb-2">
                                <i class="las la-check text-success me-2"></i>
                                Thợ sẽ liên hệ trực tiếp với bạn để thống nhất chi tiết
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        @auth
                            <a href="{{ route('appointments.index') }}" class="btn btn--base px-4">
                                <i class="las la-calendar-check me-2"></i>
                                Xem lịch hẹn của tôi
                            </a>
                        @endauth
                        
                        <a href="{{ route('company.all') }}" class="btn btn-outline-primary px-4">
                            <i class="las la-search me-2"></i>
                            Tìm thợ khác
                        </a>
                        
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary px-4">
                            <i class="las la-home me-2"></i>
                            Về trang chủ
                        </a>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted mb-2">Cần hỗ trợ?</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="tel:{{ gs('contact_number') }}" class="text-decoration-none">
                                <i class="las la-phone text-success me-1"></i>
                                {{ gs('contact_number') }}
                            </a>
                            <a href="mailto:{{ gs('email_from') }}" class="text-decoration-none">
                                <i class="las la-envelope text-primary me-1"></i>
                                {{ gs('email_from') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Auto redirect for guests -->
@guest
<script>
    // Redirect guests to home page after 10 seconds
    setTimeout(function() {
        window.location.href = "{{ route('home') }}";
    }, 10000);
    
    // Show countdown
    let countdown = 10;
    const countdownElement = document.createElement('div');
    countdownElement.className = 'alert alert-info mt-3';
    countdownElement.innerHTML = `<i class="las la-clock me-2"></i>Tự động chuyển về trang chủ sau <span id="countdown">${countdown}</span> giây`;
    document.querySelector('.card-body').appendChild(countdownElement);
    
    const interval = setInterval(function() {
        countdown--;
        document.getElementById('countdown').textContent = countdown;
        if (countdown <= 0) {
            clearInterval(interval);
        }
    }, 1000);
</script>
@endguest
@endsection

@push('style')
<style>
    .success-icon {
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .appointment-summary {
        border-left: 4px solid var(--base);
    }
    
    .next-steps {
        border-left: 4px solid #0dcaf0;
    }
    
    .btn--base {
        background: var(--base);
        border-color: var(--base);
        color: white;
    }
    
    .btn--base:hover {
        background: var(--base);
        border-color: var(--base);
        color: white;
        opacity: 0.9;
    }
</style>
@endpush