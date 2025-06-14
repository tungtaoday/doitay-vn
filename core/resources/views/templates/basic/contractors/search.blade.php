@extends($activeTemplate . 'layouts.frontend')
@section('content')

<div class="container py-5">
    <!-- Search Header -->
    <div class="search-header mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="page-title">🔍 Tìm Thợ Chuyên Nghiệp</h1>
                <p class="page-subtitle">
                    Tìm được {{ $contractors->total() }} thợ phù hợp với yêu cầu của bạn
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="result-stats">
                    <span class="text-muted">Sắp xếp theo:</span>
                    <select class="form-select form-select-sm d-inline-block w-auto ms-2" id="sortSelect">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá cao nhất</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Kinh nghiệm</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filters-sidebar">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0">🎯 Bộ Lọc Tìm Kiếm</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('contractors.search') }}" id="filterForm">
                            <!-- Search Input -->
                            <div class="filter-group mb-4">
                                <label class="form-label fw-semibold">Tìm kiếm</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Tên công ty, kỹ năng..." 
                                       value="{{ request('search') }}">
                            </div>

                            <!-- Category Filter -->
                            <div class="filter-group mb-4">
                                <label class="form-label fw-semibold">Loại công việc</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Tất cả danh mục</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Location Filter -->
                            <div class="filter-group mb-4">
                                <label class="form-label fw-semibold">Khu vực</label>
                                <select name="district" class="form-select">
                                    <option value="">Tất cả khu vực</option>
                                    <option value="Quận 1" {{ request('district') == 'Quận 1' ? 'selected' : '' }}>Quận 1</option>
                                    <option value="Quận 2" {{ request('district') == 'Quận 2' ? 'selected' : '' }}>Quận 2</option>
                                    <option value="Quận 3" {{ request('district') == 'Quận 3' ? 'selected' : '' }}>Quận 3</option>
                                    <option value="Quận 4" {{ request('district') == 'Quận 4' ? 'selected' : '' }}>Quận 4</option>
                                    <option value="Quận 5" {{ request('district') == 'Quận 5' ? 'selected' : '' }}>Quận 5</option>
                                    <!-- Add more districts as needed -->
                                </select>
                            </div>

                            <!-- Rating Filter -->
                            <div class="filter-group mb-4">
                                <label class="form-label fw-semibold">Đánh giá tối thiểu</label>
                                <div class="rating-filter">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check">
                                            <input type="radio" name="rating" value="{{ $i }}" 
                                                   class="form-check-input" id="rating{{ $i }}"
                                                   {{ request('rating') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="rating{{ $i }}">
                                                @for($j = 1; $j <= 5; $j++)
                                                    <i class="las la-star {{ $j <= $i ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ms-1">{{ $i }}+ sao</span>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Filter Actions -->
                            <div class="filter-actions">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="las la-search me-1"></i>Áp dụng lọc
                                </button>
                                <a href="{{ route('contractors.search') }}" class="btn btn-outline-secondary w-100">
                                    <i class="las la-redo me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body text-center">
                        <h6 class="mb-3">💡 Không tìm được thợ phù hợp?</h6>
                        <p class="small text-muted mb-3">Tạo lead để các thợ tự liên hệ với bạn</p>
                        <a href="{{ route('user.customer.leads.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="las la-plus me-1"></i>Tạo Lead
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contractors Grid -->
        <div class="col-lg-9">
            @if($contractors->count() > 0)
                <div class="contractors-grid">
                    <div class="row">
                        @foreach($contractors as $contractor)
                            <div class="col-lg-6 col-md-6 mb-4">
                                <div class="contractor-card">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <!-- Contractor Header -->
                                            <div class="contractor-header mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="contractor-avatar me-3">
                                                        <img src="{{ getImage(getFilePath('company') . '/' . $contractor->image, getFileSize('company')) }}" 
                                                             alt="{{ $contractor->name }}" 
                                                             class="rounded-circle">
                                                    </div>
                                                    <div class="contractor-info flex-grow-1">
                                                        <h5 class="contractor-name mb-1">
                                                            <a href="{{ route('contractors.profile', $contractor->id) }}" 
                                                               class="text-decoration-none">
                                                                {{ $contractor->name }}
                                                            </a>
                                                        </h5>
                                                        <div class="contractor-category mb-2">
                                                            <span class="badge bg-light text-dark">
                                                                <i class="las la-tag me-1"></i>
                                                                {{ $contractor->category->name ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="contractor-rating">
                                                            @php $avgRating = $contractor->ratings_avg_avg_rating ?? 0; @endphp
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="las la-star {{ $i <= $avgRating ? 'text-warning' : 'text-muted' }}"></i>
                                                            @endfor
                                                            <span class="rating-text ms-1">
                                                                {{ number_format($avgRating, 1) }} ({{ $contractor->ratings_count ?? 0 }} đánh giá)
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @if($contractor->is_premium ?? false)
                                                        <div class="premium-badge">
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="las la-crown"></i> Premium
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Contractor Description -->
                                            <div class="contractor-description mb-3">
                                                <p class="text-muted small mb-0">
                                                    {{ Str::limit($contractor->description ?? 'Chuyên gia trong lĩnh vực ' . ($contractor->category->name ?? 'N/A'), 100) }}
                                                </p>
                                            </div>

                                            <!-- Contractor Stats -->
                                            <div class="contractor-stats mb-3">
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="stat-item">
                                                            <div class="stat-number">{{ $contractor->completed_jobs ?? '50+' }}</div>
                                                            <div class="stat-label">Công việc</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="stat-item">
                                                            <div class="stat-number">{{ $contractor->years_experience ?? '5+' }}</div>
                                                            <div class="stat-label">Năm KN</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="stat-item">
                                                            <div class="stat-number">{{ $contractor->response_time ?? '24h' }}</div>
                                                            <div class="stat-label">Phản hồi</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Contractor Location -->
                                            <div class="contractor-location mb-3">
                                                <small class="text-muted">
                                                    <i class="las la-map-marker me-1"></i>
                                                    {{ $contractor->user->district ?? 'N/A' }}, {{ $contractor->user->city ?? 'TP.HCM' }}
                                                </small>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="contractor-actions">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <a href="{{ route('contractors.profile', $contractor->id) }}" 
                                                           class="btn btn-outline-primary btn-sm w-100">
                                                            <i class="las la-eye me-1"></i>Xem profile
                                                        </a>
                                                    </div>
                                                    <div class="col-6">
                                                        <button class="btn btn-primary btn-sm w-100" 
                                                                onclick="createLeadForContractor({{ $contractor->id }})">
                                                            <i class="las la-paper-plane me-1"></i>Thuê ngay
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($contractors->hasPages())
                        <div class="pagination-wrapper mt-4">
                            {{ $contractors->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="las la-search text-muted" style="font-size: 5rem;"></i>
                    </div>
                    <h4>Không tìm thấy thợ phù hợp</h4>
                    <p class="text-muted mb-4">
                        Thử điều chỉnh bộ lọc hoặc tạo lead để các thợ tự liên hệ với bạn
                    </p>
                    <div class="empty-actions">
                        <a href="{{ route('contractors.search') }}" class="btn btn-outline-primary me-2">
                            <i class="las la-redo me-1"></i>Xóa bộ lọc
                        </a>
                        <a href="{{ route('user.customer.leads.create') }}" class="btn btn-primary">
                            <i class="las la-plus me-1"></i>Tạo Lead
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Lead Modal -->
<div class="modal fade" id="quickLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-rocket text-primary me-2"></i>Tạo Lead Cho Thợ
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickLeadForm" method="POST" action="{{ route('user.customer.leads.store') }}">
                    @csrf
                    <input type="hidden" name="preferred_contractor_id" id="preferredContractorId">
                    
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề công việc</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết</label>
                        <textarea name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngân sách tối thiểu</label>
                            <input type="number" name="budget_min" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngân sách tối đa</label>
                            <input type="number" name="budget_max" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="quickLeadForm" class="btn btn-primary">
                    <i class="las la-paper-plane me-1"></i>Tạo Lead
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.contractor-card {
    transition: all 0.3s ease;
}

.contractor-card:hover {
    transform: translateY(-2px);
}

.contractor-card .card {
    border-radius: 16px;
    overflow: hidden;
}

.contractor-avatar img {
    width: 60px;
    height: 60px;
    object-fit: cover;
}

.contractor-name a {
    color: #374151;
    font-weight: 600;
}

.contractor-name a:hover {
    color: #0b92d4;
}

.rating-text {
    font-size: 0.875rem;
    color: #6b7280;
}

.stat-item {
    padding: 0.5rem 0;
}

.stat-number {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0b92d4;
}

.stat-label {
    font-size: 0.75rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.premium-badge {
    font-size: 0.75rem;
}

.filters-sidebar .card {
    border-radius: 16px;
    position: sticky;
    top: 100px;
}

.rating-filter .form-check {
    margin-bottom: 0.5rem;
}

.rating-filter .form-check-label {
    cursor: pointer;
    font-size: 0.875rem;
}

.empty-state {
    padding: 4rem 2rem;
}

@media (max-width: 991px) {
    .filters-sidebar {
        margin-bottom: 2rem;
    }
    
    .filters-sidebar .card {
        position: relative;
        top: auto;
    }
}
</style>

<script>
// Sort functionality
document.getElementById('sortSelect').addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});

// Create lead for specific contractor
function createLeadForContractor(contractorId) {
    @auth
        document.getElementById('preferredContractorId').value = contractorId;
        const modal = new bootstrap.Modal(document.getElementById('quickLeadModal'));
        modal.show();
    @else
                        window.location.href = '{{ route("user.login.v2") }}';
    @endauth
}

// Auto-submit filter form on change
document.querySelectorAll('#filterForm select, #filterForm input[type="radio"]').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>

@endsection 