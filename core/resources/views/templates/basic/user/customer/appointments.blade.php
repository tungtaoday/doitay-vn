<!-- Enhanced Appointments List -->
<div class="row">
    <div class="col-12">
        <!-- Appointment Status Filter -->
        <div class="appointment-filters mb-4">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-outline-primary filter-btn active" data-status="all">
                    Tất cả <span class="badge bg-primary ms-1">{{ $appointments->count() }}</span>
                </button>
                <button class="btn btn-outline-warning filter-btn" data-status="pending">
                    Chờ xác nhận <span class="badge bg-warning ms-1">{{ $appointments->where('status', 'pending')->count() }}</span>
                </button>
                <button class="btn btn-outline-success filter-btn" data-status="confirmed">
                    Đã xác nhận <span class="badge bg-success ms-1">{{ $appointments->where('status', 'confirmed')->count() }}</span>
                </button>
                <button class="btn btn-outline-info filter-btn" data-status="completed">
                    Hoàn thành <span class="badge bg-info ms-1">{{ $appointments->where('status', 'completed')->count() }}</span>
                </button>
            </div>
        </div>

        @if($appointments->count() > 0)
            <div class="appointments-timeline">
                @foreach($appointments as $appointment)
                    <div class="appointment-card card border-0 shadow-sm mb-3" data-status="{{ $appointment->status }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <div class="appointment-date">
                                        <div class="date-display bg-primary text-white rounded p-3 position-relative">
                                            <div class="day fs-4 fw-bold">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}
                                            </div>
                                            <div class="month">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}
                                            </div>
                                            @if($appointment->status === 'confirmed')
                                                <div class="status-indicator confirmed"></div>
                                            @elseif($appointment->status === 'pending')
                                                <div class="status-indicator pending"></div>
                                            @endif
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="las la-clock me-1"></i>{{ $appointment->appointment_time }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="appointment-info">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="mb-0">
                                                <a href="{{ route('company.details', [$appointment->company->id, slug($appointment->company->name)]) }}" 
                                                   class="text-decoration-none">
                                                    {{ $appointment->company->name }}
                                                </a>
                                            </h5>
                                            <div class="appointment-actions dropdown">
                                                <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                                                    <i class="las la-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('user.appointments.show', $appointment->id) }}">
                                                        <i class="las la-eye me-2"></i>Xem chi tiết
                                                    </a></li>
                                                    @if($appointment->status === 'pending')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="cancelAppointment({{ $appointment->id }})">
                                                        <i class="las la-times me-2"></i>Hủy lịch hẹn
                                                    </a></li>
                                                    @endif
                                                    @if($appointment->status === 'completed')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reviewModal" 
                                                           onclick="openReviewModal({{ $appointment->id }}, '{{ $appointment->company->name }}')">
                                                        <i class="las la-star me-2"></i>Đánh giá
                                                    </a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-2">
                                            <i class="las la-map-marker text-primary me-1"></i>
                                            {{ $appointment->company->address }}
                                        </p>
                                        <div class="service-details mb-2">
                                            <strong>Dịch vụ:</strong> {{ $appointment->service_type ?? 'Tư vấn chung' }}
                                        </div>
                                        @if($appointment->message)
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <strong>Ghi chú:</strong> {{ Str::limit($appointment->message, 100) }}
                                                </small>
                                            </div>
                                        @endif
                                        
                                        <!-- Real-time Status Update -->
                                        <div class="status-update-area">
                                            @if($appointment->status === 'confirmed')
                                                <div class="alert alert-success alert-sm mb-0">
                                                    <i class="las la-check-circle me-2"></i>
                                                    Thợ đã xác nhận và sẽ liên hệ với bạn sớm
                                                </div>
                                            @elseif($appointment->status === 'pending')
                                                <div class="alert alert-warning alert-sm mb-0">
                                                    <i class="las la-clock me-2"></i>
                                                    Đang chờ thợ xác nhận lịch hẹn
                                                </div>
                                            @endif
                                        </div>
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
                                        @elseif($appointment->status === 'cancelled')
                                            <span class="badge bg-secondary px-3 py-2">
                                                <i class="las la-times me-1"></i>
                                                Đã hủy
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Estimated Time -->
                                    @if($appointment->status === 'confirmed')
                                        <div class="estimated-time mt-2">
                                            <small class="text-muted">
                                                <i class="las la-calendar-day me-1"></i>
                                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-2 text-center">
                                    <div class="appointment-actions">
                                        <a href="{{ route('user.appointments.show', $appointment->id) }}" 
                                           class="btn btn-outline-primary btn-sm mb-2">
                                            <i class="las la-eye me-1"></i> Chi tiết
                                        </a>
                                        
                                        @if($appointment->status === 'confirmed')
                                            <button class="btn btn-success btn-sm mb-2" disabled>
                                                <i class="las la-phone me-1"></i> Chờ liên hệ
                                            </button>
                                        @endif
                                        
                                        @if($appointment->status === 'completed')
                                            <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                    onclick="openReviewModal({{ $appointment->id }}, '{{ $appointment->company->name }}')">
                                                <i class="las la-star me-1"></i> Đánh giá
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Indicator -->
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
    <div class="modal-dialog modal-lg">
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
                    
                    <div id="featuresRating" class="features-rating mb-3">
                        <!-- Features will be loaded dynamically -->
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nhận xét của bạn</label>
                        <textarea name="comment" class="form-control" rows="4" 
                                  placeholder="Chia sẻ trải nghiệm của bạn..." required></textarea>
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
function openReviewModal(appointmentId, companyName) {
    // Set form action and company info
    document.getElementById('reviewForm').action = `/appointments/${appointmentId}/review`;
    document.getElementById('companyName').textContent = companyName;
    
    // Load features for this company's category
    fetch(`/appointments/${appointmentId}/company-category`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.category_id) {
                return fetch(`/api/categories/${data.category_id}/features`);
            } else {
                throw new Error('No category found');
            }
        })
        .then(response => response.json())
        .then(features => {
            loadFeaturesRating(features);
        })
        .catch(error => {
            console.error('Error loading features:', error);
            // Fallback to simple rating
            loadSimpleRating();
        });
    
    const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
    modal.show();
    
    // Handle form submission with AJAX
    const form = document.getElementById('reviewForm');
    form.onsubmit = function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modal.hide();
                alert(data.message);
                location.reload(); // Refresh to update UI
            } else {
                alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại');
        });
    };
}

function loadFeaturesRating(features) {
    const container = document.getElementById('featuresRating');
    
    if (features && features.length > 0) {
        const featuresHtml = features.map(feature => `
            <div class="feature-rating mb-3">
                <label class="form-label">${feature.name}</label>
                <div class="star-rating" data-feature-id="${feature.id}">
                    <input type="radio" name="rating[${feature.id}]" value="5" id="cust-feature${feature.id}-star5">
                    <label for="cust-feature${feature.id}-star5" class="star">★</label>
                    <input type="radio" name="rating[${feature.id}]" value="4" id="cust-feature${feature.id}-star4">
                    <label for="cust-feature${feature.id}-star4" class="star">★</label>
                    <input type="radio" name="rating[${feature.id}]" value="3" id="cust-feature${feature.id}-star3">
                    <label for="cust-feature${feature.id}-star3" class="star">★</label>
                    <input type="radio" name="rating[${feature.id}]" value="2" id="cust-feature${feature.id}-star2">
                    <label for="cust-feature${feature.id}-star2" class="star">★</label>
                    <input type="radio" name="rating[${feature.id}]" value="1" id="cust-feature${feature.id}-star1">
                    <label for="cust-feature${feature.id}-star1" class="star">★</label>
                </div>
            </div>
        `).join('');
        
        container.innerHTML = featuresHtml;
        addFeatureRatingStyles();
    } else {
        loadSimpleRating();
    }
}

function loadSimpleRating() {
    const container = document.getElementById('featuresRating');
    container.innerHTML = `
        <div class="rating-section mb-3">
            <label class="form-label">Đánh giá tổng thể</label>
            <div class="star-rating">
                <input type="radio" name="rating[general]" value="5" id="cust-star5">
                <label for="cust-star5" class="star">★</label>
                <input type="radio" name="rating[general]" value="4" id="cust-star4">
                <label for="cust-star4" class="star">★</label>
                <input type="radio" name="rating[general]" value="3" id="cust-star3">
                <label for="cust-star3" class="star">★</label>
                <input type="radio" name="rating[general]" value="2" id="cust-star2">
                <label for="cust-star2" class="star">★</label>
                <input type="radio" name="rating[general]" value="1" id="cust-star1">
                <label for="cust-star1" class="star">★</label>
            </div>
        </div>
    `;
    addFeatureRatingStyles();
}

function addFeatureRatingStyles() {
    if (!document.getElementById('featureRatingStyles')) {
        const style = document.createElement('style');
        style.id = 'featureRatingStyles';
        style.textContent = `
            .star-rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: center;
                gap: 5px;
                margin-bottom: 10px;
            }
            .star-rating input {
                display: none;
            }
            .star-rating label {
                font-size: 1.5rem;
                color: #ddd;
                cursor: pointer;
                transition: color 0.3s ease;
            }
            .star-rating input:checked ~ label,
            .star-rating label:hover,
            .star-rating label:hover ~ label {
                color: #ffc107;
            }
            .feature-rating {
                border: 1px solid #e9ecef;
                border-radius: 8px;
                padding: 15px;
                background-color: #f8f9fa;
            }
            .feature-rating .form-label {
                font-weight: 600;
                margin-bottom: 8px;
                color: #495057;
            }
        `;
        document.head.appendChild(style);
    }
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