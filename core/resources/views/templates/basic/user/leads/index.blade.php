@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="container-fluid px-3 px-sm-4 py-4">
    <!-- Header với Smart Stats -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h2 class="mb-1">{{ $pageTitle }}</h2>
                    <p class="text-muted mb-0">🎯 Leads được chọn riêng cho bạn dựa trên rating và khu vực</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.leads.my-purchases') }}" class="btn btn-outline-primary">
                        <i class="las la-shopping-bag me-1"></i> Leads đã mua
                    </a>
                    <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="las la-filter"></i> Bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon mb-2">
                        <i class="las la-star text-warning" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1 text-primary">{{ $stats['exclusive_leads'] }}</h4>
                    <p class="text-muted mb-0">Leads độc quyền</p>
                    <small class="text-success">Chỉ dành cho bạn</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon mb-2">
                        <i class="las la-trophy text-success" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1 text-success">{{ $stats['high_priority'] }}</h4>
                    <p class="text-muted mb-0">Ưu tiên cao</p>
                    <small class="text-warning">Rating 4.0+</small>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="stat-icon mb-2">
                        <i class="las la-clock text-danger" style="font-size: 2rem;"></i>
                    </div>
                    <h4 class="mb-1 text-danger">{{ $stats['expiring_soon'] }}</h4>
                    <p class="text-muted mb-0">Sắp hết hạn</p>
                    <small class="text-danger">< 6 giờ</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="collapse mb-4" id="filterCollapse">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Danh mục</label>
                            <select name="category_id" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Mức độ ưu tiên</label>
                            <select name="urgency" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>🚨 Khẩn cấp</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>⚡ Bình thường</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>📅 Không gấp</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Ngân sách tối thiểu</label>
                            <input type="number" name="budget_min" class="form-control" value="{{ request('budget_min') }}" placeholder="VNĐ">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Ngân sách tối đa</label>
                            <input type="number" name="budget_max" class="form-control" value="{{ request('budget_max') }}" placeholder="VNĐ">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-search me-1"></i>Lọc
                        </button>
                        <a href="{{ route('user.leads.index') }}" class="btn btn-outline-secondary">
                            <i class="las la-redo me-1"></i>Đặt lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Smart Leads List -->
    <div class="row">
        @forelse($leads as $lead)
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm smart-lead-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Lead Info -->
                            <div class="col-lg-8">
                                <div class="d-flex align-items-start">
                                    <!-- Priority Badge -->
                                    <div class="priority-badge me-3">
                                        @if($lead->visibility && $lead->visibility->priority_score >= 4.5)
                                            <span class="badge bg-success">⭐ Ưu tiên cao</span>
                                        @elseif($lead->visibility && $lead->visibility->priority_score >= 4.0)
                                            <span class="badge bg-primary">🎯 Ưu tiên</span>
                                        @else
                                            <span class="badge bg-secondary">📋 Phù hợp</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <h5 class="mb-0 me-2">
                                                <a href="{{ route('user.leads.show', $lead->id) }}" class="text-decoration-none">
                                                    {{ $lead->title }}
                                                </a>
                                            </h5>
                                            
                                            <!-- Urgency Badge -->
                                            @if($lead->urgency == 'high')
                                                <span class="badge bg-danger">🚨 Khẩn cấp</span>
                                            @elseif($lead->urgency == 'medium')
                                                <span class="badge bg-warning">⚡ Bình thường</span>
                                            @else
                                                <span class="badge bg-info">📅 Không gấp</span>
                                            @endif
                                        </div>
                                        
                                        <p class="text-muted mb-2">{{ Str::limit($lead->description, 120) }}</p>
                                        
                                        <div class="d-flex flex-wrap gap-2 small mb-2">
                                            <span class="badge bg-light text-dark">
                                                <i class="las la-map-marker me-1"></i>{{ $lead->location }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="las la-tag me-1"></i>{{ $lead->category->name }}
                                            </span>
                                            @if($lead->budget_min || $lead->budget_max)
                                                <span class="badge bg-light text-dark">
                                                    <i class="las la-money-bill-wave me-1"></i>{{ $lead->getBudgetRange() }}
                                                </span>
                                            @endif
                                            <span class="badge bg-light text-dark">
                                                <i class="las la-coins me-1"></i>{{ number_format($lead->lead_price) }}₫
                                            </span>
                                        </div>

                                        <!-- Exclusive Access Info -->
                                        @if($lead->visibility)
                                            <div class="exclusive-info">
                                                <small class="text-primary">
                                                    <i class="las la-lock me-1"></i>
                                                    <strong>Độc quyền:</strong> Chỉ {{ $lead->visibility->isActive() ? '3 thợ' : 'bạn' }} được xem lead này
                                                    @if($lead->visibility->getTimeRemaining())
                                                        • Còn {{ $lead->visibility->getTimeRemaining() }}
                                                    @endif
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions & Timer -->
                            <div class="col-lg-4">
                                <div class="text-end">
                                    <!-- Countdown Timer -->
                                    @if($lead->visibility && $lead->visibility->isActive())
                                        <div class="countdown-timer mb-3" data-expires="{{ $lead->visibility->expires_at->toISOString() }}">
                                            <div class="timer-display">
                                                <span class="hours">00</span>:
                                                <span class="minutes">00</span>:
                                                <span class="seconds">00</span>
                                            </div>
                                            <small class="text-muted">Thời gian độc quyền</small>
                                        </div>
                                    @endif
                                    
                                    <!-- Priority Score -->
                                    @if($lead->visibility)
                                        <div class="priority-score mb-3">
                                            <small class="text-muted">Điểm ưu tiên của bạn:</small>
                                            <div class="score-display">
                                                <strong class="text-primary">{{ number_format($lead->visibility->priority_score, 1) }}/5.0</strong>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Action Buttons -->
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('user.leads.show', $lead->id) }}" class="btn btn-primary">
                                            <i class="las la-eye me-1"></i>Xem chi tiết
                                        </a>
                                        
                                        @if($lead->canBePurchasedBy(auth()->user()->companies->first()->id ?? 0))
                                            <button class="btn btn-success" onclick="purchaseLead({{ $lead->id }})">
                                                <i class="las la-shopping-cart me-1"></i>Mua Lead ({{ number_format($lead->lead_price) }}₫)
                                            </button>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                <i class="las la-check me-1"></i>Đã mua
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-3">
                        <i class="las la-search" style="font-size: 4rem; color: #ddd;"></i>
                    </div>
                    <h5>Chưa có leads phù hợp</h5>
                    <p class="text-muted mb-4">
                        Hiện tại chưa có leads nào được chọn riêng cho bạn.<br>
                        Hãy cải thiện rating và mở rộng khu vực hoạt động để nhận thêm leads.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('user.company.index') }}" class="btn btn-outline-primary">
                            <i class="las la-cog me-1"></i>Cập nhật hồ sơ
                        </a>
                        <a href="{{ route('user.wallet.index') }}" class="btn btn-outline-success">
                            <i class="las la-wallet me-1"></i>Nạp tiền ví
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($leads->hasPages())
        <div class="row">
            <div class="col-12">
                <nav aria-label="Leads pagination">
                    {{ $leads->links() }}
                </nav>
            </div>
        </div>
    @endif
</div>

@endsection

@push('script')
<script>
// Countdown Timer
function updateCountdowns() {
    document.querySelectorAll('.countdown-timer').forEach(timer => {
        const expiresAt = new Date(timer.dataset.expires);
        const now = new Date();
        const diff = expiresAt - now;
        
        if (diff <= 0) {
            timer.innerHTML = '<span class="text-danger">Đã hết hạn</span>';
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        timer.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
        timer.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
        timer.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
        
        // Add urgency styling
        if (diff < 3600000) { // Less than 1 hour
            timer.classList.add('urgent');
        }
    });
}

// Update every second
setInterval(updateCountdowns, 1000);
updateCountdowns();

// Purchase Lead Function
function purchaseLead(leadId) {
    if (confirm('Bạn có chắc muốn mua lead này?')) {
        // Implementation for purchase
        console.log('Purchasing lead:', leadId);
    }
}

// Auto-submit filter form
document.querySelectorAll('#filterForm select, #filterForm input').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>
@endpush

@push('style')
<style>
.smart-lead-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.smart-lead-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.smart-lead-card .badge.bg-success {
    border-left-color: #28a745;
}

.smart-lead-card .badge.bg-primary {
    border-left-color: #007bff;
}

.countdown-timer {
    text-align: center;
    padding: 10px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.countdown-timer.urgent {
    background: linear-gradient(135deg, #fff5f5, #fed7d7);
    border-color: #fc8181;
    animation: pulse 2s infinite;
}

.timer-display {
    font-family: 'Courier New', monospace;
    font-size: 1.2rem;
    font-weight: bold;
    color: #495057;
}

.countdown-timer.urgent .timer-display {
    color: #e53e3e;
}

.priority-score {
    text-align: center;
}

.score-display {
    font-size: 1.1rem;
}

.exclusive-info {
    background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
    padding: 8px 12px;
    border-radius: 6px;
    border-left: 3px solid #2196f3;
    margin-top: 10px;
}

.empty-state {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    border-radius: 12px;
    border: 2px dashed #dee2e6;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0,123,255,0.1);
    border-radius: 50%;
    margin: 0 auto;
}
</style>
@endpush 