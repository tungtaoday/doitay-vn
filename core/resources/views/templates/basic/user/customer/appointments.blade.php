<!-- Appointments List -->
<div class="row">
    <div class="col-12">
        @if($appointments->count() > 0)
            <div class="appointments-timeline">
                @foreach($appointments as $appointment)
                    <div class="appointment-card card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <div class="appointment-date">
                                        <div class="date-display bg-primary text-white rounded p-3">
                                            <div class="day fs-4 fw-bold">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}
                                            </div>
                                            <div class="month">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            {{ $appointment->appointment_time }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="appointment-info">
                                        <h5 class="mb-2">
                                            <a href="{{ route('company.details', [$appointment->company->id, slug($appointment->company->name)]) }}" 
                                               class="text-decoration-none">
                                                {{ $appointment->company->name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted mb-2">
                                            <i class="las la-map-marker text-primary me-1"></i>
                                            {{ $appointment->company->address }}
                                        </p>
                                        <div class="service-details">
                                            <strong>Dịch vụ:</strong> {{ $appointment->service_type ?? 'Tư vấn chung' }}
                                        </div>
                                        @if($appointment->message)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Ghi chú:</strong> {{ Str::limit($appointment->message, 100) }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <div class="appointment-status">
                                        @if($appointment->status === 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="las la-clock me-1"></i>
                                                Chờ xác nhận
                                            </span>
                                        @elseif($appointment->status === 'confirmed')
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="las la-check me-1"></i>
                                                Đã xác nhận
                                            </span>
                                        @elseif($appointment->status === 'completed')
                                            <span class="badge bg-primary px-3 py-2">
                                                <i class="las la-check-circle me-1"></i>
                                                Hoàn thành
                                            </span>
                                            @if(!$appointment->reviewed_by_customer)
                                                <div class="mt-2">
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            onclick="showReviewModal({{ $appointment->id }})">
                                                        <i class="las la-star me-1"></i>
                                                        Đánh giá
                                                    </button>
                                                </div>
                                            @endif
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="las la-times me-1"></i>
                                                Đã hủy
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <div class="appointment-actions">
                                        <a href="{{ route('user.appointments.show', $appointment->id) }}" 
                                           class="btn btn-outline-primary btn-sm mb-2">
                                            <i class="las la-eye me-1"></i>
                                            Chi tiết
                                        </a>
                                        
                                        @if($appointment->status === 'pending')
                                            <button class="btn btn-outline-danger btn-sm" 
                                                    onclick="cancelAppointment({{ $appointment->id }})">
                                                <i class="las la-times me-1"></i>
                                                Hủy
                                            </button>
                                        @endif
                                        
                                        @if($appointment->status === 'confirmed')
                                            <div class="mt-2">
                                                <small class="text-success">
                                                    <i class="las la-info-circle me-1"></i>
                                                    Sẵn sàng phục vụ
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            @if($appointment->status !== 'cancelled')
                                <div class="appointment-progress mt-3">
                                    <div class="progress" style="height: 6px;">
                                        @php
                                            $progress = 25; // pending
                                            if($appointment->status === 'confirmed') $progress = 75;
                                            if($appointment->status === 'completed') $progress = 100;
                                        @endphp
                                        <div class="progress-bar bg-success" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">Đặt lịch</small>
                                        <small class="text-muted">Xác nhận</small>
                                        <small class="text-muted">Hoàn thành</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Load More Button -->
            @if($appointments->count() >= 10)
                <div class="text-center mt-4">
                    <a href="{{ route('user.appointments.index') }}" class="btn btn-outline-primary">
                        <i class="las la-plus me-2"></i>
                        Xem tất cả lịch hẹn
                    </a>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="las la-calendar-times text-muted" style="font-size: 4rem;"></i>
                </div>
                <h5 class="text-muted mb-3">Chưa có lịch hẹn nào</h5>
                <p class="text-muted mb-4">Hãy tìm và đặt lịch với thợ chuyên nghiệp ngay hôm nay!</p>
                <a href="{{ route('company.all') }}" class="btn btn-primary">
                    <i class="las la-search me-2"></i>
                    Tìm thợ ngay
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-star text-warning me-2"></i>
                    Đánh giá dịch vụ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="reviewForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="company-info mb-4">
                        <h6 id="companyName"></h6>
                        <small class="text-muted" id="appointmentDate"></small>
                    </div>
                    
                    <div class="rating-section mb-3">
                        <label class="form-label">Đánh giá tổng thể</label>
                        <div class="star-rating">
                            <input type="radio" name="rating" value="5" id="star5">
                            <label for="star5" class="star">★</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4" class="star">★</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3" class="star">★</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2" class="star">★</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1" class="star">★</label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nhận xét của bạn</label>
                        <textarea name="comment" class="form-control" rows="4" 
                                  placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-paper-plane me-1"></i>
                        Gửi đánh giá
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.appointment-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.appointment-card:hover {
    transform: translateY(-2px);
    border-left-color: var(--base);
}

.date-display {
    width: 80px;
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: center;
    gap: 5px;
}

.star-rating input {
    display: none;
}

.star-rating label {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.3s ease;
}

.star-rating input:checked ~ label,
.star-rating label:hover,
.star-rating label:hover ~ label {
    color: #ffc107;
}
</style>

<script>
function showReviewModal(appointmentId) {
    // You would fetch appointment details here
    document.getElementById('reviewForm').action = `/user/appointments/${appointmentId}/review`;
    
    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
}

function cancelAppointment(appointmentId) {
    if(confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?')) {
        // Submit cancel form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/user/appointments/${appointmentId}/cancel`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script> 