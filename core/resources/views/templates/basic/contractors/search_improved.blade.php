@extends($activeTemplate . 'layouts.frontend')
@section('content')

<div class="search-hero-section">
    <div class="container">
        <div class="search-hero-content">
            <h1>🔍 Tìm Thợ Chuyên Nghiệp</h1>
            <p>{{ $contractors->total() }} thợ sẵn sàng phục vụ bạn</p>
            
            <!-- Smart Search Bar -->
            <div class="smart-search-bar">
                <form action="{{ route('contractors.search') }}" method="GET" class="search-form">
                    <div class="search-input-group">
                        <div class="search-field">
                            <i class="las la-search"></i>
                            <input type="text" name="search" 
                                   placeholder="Điện nước, sửa máy lạnh, thi công nhà..." 
                                   value="{{ request('search') }}"
                                   id="smartSearch"
                                   autocomplete="off">
                            <div class="search-suggestions" id="searchSuggestions"></div>
                        </div>
                        
                        <div class="location-field">
                            <i class="las la-map-marker"></i>
                            <select name="district" class="form-select">
                                <option value="">Chọn khu vực</option>
                                @foreach(config('vietnam.districts') as $district)
                                    <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>
                                        {{ $district }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <button type="submit" class="search-btn">
                            <i class="las la-search"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Quick Category Pills -->
            <div class="quick-categories">
                <span class="quick-label">Phổ biến:</span>
                @foreach(['Điện', 'Nước', 'Điều hòa', 'Sửa chữa', 'Thi công'] as $tag)
                    <a href="{{ route('contractors.search', ['search' => $tag]) }}" class="category-pill">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row">
        <!-- Smart Filters Sidebar -->
        <div class="col-lg-3">
            <div class="filters-panel">
                <div class="filter-header">
                    <h5>🎯 Bộ Lọc Thông Minh</h5>
                    <button class="clear-filters" onclick="clearAllFilters()">Xóa tất cả</button>
                </div>
                
                <form method="GET" action="{{ route('contractors.search') }}" id="filterForm">
                    <!-- Active Filters Display -->
                    <div class="active-filters" id="activeFilters" style="display: none;">
                        <h6>Đã chọn:</h6>
                        <div class="filter-tags"></div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-group">
                        <h6>💰 Mức giá</h6>
                        <div class="price-range-options">
                            <label class="price-option">
                                <input type="radio" name="price_range" value="budget" {{ request('price_range') == 'budget' ? 'checked' : '' }}>
                                <span>👍 Tiết kiệm (< 500k)</span>
                            </label>
                            <label class="price-option">
                                <input type="radio" name="price_range" value="standard" {{ request('price_range') == 'standard' ? 'checked' : '' }}>
                                <span>⭐ Chuẩn (500k - 2M)</span>
                            </label>
                            <label class="price-option">
                                <input type="radio" name="price_range" value="premium" {{ request('price_range') == 'premium' ? 'checked' : '' }}>
                                <span>💎 Cao cấp (> 2M)</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Rating Filter -->
                    <div class="filter-group">
                        <h6>⭐ Đánh giá</h6>
                        <div class="rating-options">
                            @for($i = 5; $i >= 3; $i--)
                                <label class="rating-option">
                                    <input type="radio" name="min_rating" value="{{ $i }}" {{ request('min_rating') == $i ? 'checked' : '' }}>
                                    <span>
                                        @for($j = 1; $j <= $i; $j++)
                                            ⭐
                                        @endfor
                                        & lên ({{ $i }}+)
                                    </span>
                                </label>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Experience Filter -->
                    <div class="filter-group">
                        <h6>🏆 Kinh nghiệm</h6>
                        <div class="experience-options">
                            <label class="exp-option">
                                <input type="radio" name="experience" value="new" {{ request('experience') == 'new' ? 'checked' : '' }}>
                                <span>🆕 Mới (< 1 năm)</span>
                            </label>
                            <label class="exp-option">
                                <input type="radio" name="experience" value="experienced" {{ request('experience') == 'experienced' ? 'checked' : '' }}>
                                <span>👨‍🔧 Có kinh nghiệm (1-5 năm)</span>
                            </label>
                            <label class="exp-option">
                                <input type="radio" name="experience" value="expert" {{ request('experience') == 'expert' ? 'checked' : '' }}>
                                <span>🎖️ Chuyên gia (5+ năm)</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Availability Filter -->
                    <div class="filter-group">
                        <h6>⏰ Tình trạng</h6>
                        <div class="availability-options">
                            <label class="availability-option">
                                <input type="checkbox" name="available_now" {{ request('available_now') ? 'checked' : '' }}>
                                <span>🟢 Sẵn sàng ngay</span>
                            </label>
                            <label class="availability-option">
                                <input type="checkbox" name="verified" {{ request('verified') ? 'checked' : '' }}>
                                <span>✅ Đã xác minh</span>
                            </label>
                            <label class="availability-option">
                                <input type="checkbox" name="premium" {{ request('premium') ? 'checked' : '' }}>
                                <span>👑 Premium</span>
                            </label>
                        </div>
                    </div>
                </form>
                
                <!-- CTA for Lead Creation -->
                <div class="lead-cta-card">
                    <div class="lead-cta-content">
                        <h6>💡 Không tìm thấy thợ phù hợp?</h6>
                        <p>Tạo lead để thợ tự liên hệ bạn</p>
                        <a href="{{ route('user.customer.leads.create') }}" class="btn-create-lead">
                            <i class="las la-rocket"></i>
                            Tạo Lead Ngay
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contractors Grid -->
        <div class="col-lg-9">
            <!-- Results Header -->
            <div class="results-header">
                <div class="results-info">
                    <h4>{{ $contractors->total() }} thợ được tìm thấy</h4>
                    <p class="text-muted">
                        @if(request('search'))
                            Kết quả cho "{{ request('search') }}"
                        @endif
                        @if(request('district'))
                            tại {{ request('district') }}
                        @endif
                    </p>
                </div>
                
                <div class="sort-options">
                    <select name="sort" id="sortSelect" class="form-select">
                        <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>📈 Phù hợp nhất</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>⭐ Đánh giá cao</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Giá thấp</option>
                        <option value="distance" {{ request('sort') == 'distance' ? 'selected' : '' }}>📍 Gần nhất</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>🆕 Mới nhất</option>
                    </select>
                </div>
            </div>
            
            <!-- Smart Results Grid -->
            @if($contractors->count() > 0)
                <div class="contractors-grid" id="contractorsGrid">
                    @foreach($contractors as $contractor)
                        <div class="contractor-card-enhanced" data-contractor-id="{{ $contractor->id }}">
                            <!-- Quick Action Buttons -->
                            <div class="quick-actions">
                                <button class="quick-action save-contractor" data-id="{{ $contractor->id }}" title="Lưu lại">
                                    <i class="las la-heart"></i>
                                </button>
                                <button class="quick-action share-contractor" data-id="{{ $contractor->id }}" title="Chia sẻ">
                                    <i class="las la-share"></i>
                                </button>
                            </div>
                            
                            <!-- Contractor Header -->
                            <div class="contractor-header">
                                <div class="contractor-avatar">
                                    <img src="{{ getImage(getFilePath('company') . '/' . $contractor->image, getFileSize('company')) }}" 
                                         alt="{{ $contractor->name }}">
                                    
                                    <!-- Status Badges -->
                                    <div class="status-badges">
                                        @if($contractor->is_verified ?? false)
                                            <span class="badge badge-verified">✅</span>
                                        @endif
                                        @if($contractor->is_premium ?? false)
                                            <span class="badge badge-premium">👑</span>
                                        @endif
                                        @if($contractor->is_online ?? false)
                                            <span class="badge badge-online">🟢</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="contractor-info">
                                    <h5 class="contractor-name">
                                        <a href="{{ route('contractors.profile', $contractor->id) }}">
                                            {{ $contractor->name }}
                                        </a>
                                    </h5>
                                    
                                    <div class="contractor-meta">
                                        <span class="category">
                                            <i class="las la-tag"></i>
                                            {{ $contractor->category->name ?? 'N/A' }}
                                        </span>
                                        <span class="location">
                                            <i class="las la-map-marker"></i>
                                            {{ $contractor->user->district ?? 'N/A' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Rating & Reviews -->
                                    <div class="rating-info">
                                        @php $avgRating = $contractor->ratings_avg_avg_rating ?? 0; @endphp
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="las la-star {{ $i <= $avgRating ? 'filled' : 'empty' }}"></i>
                                            @endfor
                                        </div>
                                        <span class="rating-text">
                                            {{ number_format($avgRating, 1) }}
                                            <small>({{ $contractor->ratings_count ?? 0 }} đánh giá)</small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contractor Description -->
                            <div class="contractor-description">
                                <p>{{ Str::limit($contractor->description ?? 'Chuyên gia trong lĩnh vực ' . ($contractor->category->name ?? 'N/A'), 120) }}</p>
                            </div>
                            
                            <!-- Key Stats -->
                            <div class="contractor-stats">
                                <div class="stat">
                                    <i class="las la-briefcase"></i>
                                    <span>{{ $contractor->completed_projects ?? rand(10, 100) }} dự án</span>
                                </div>
                                <div class="stat">
                                    <i class="las la-clock"></i>
                                    <span>{{ $contractor->response_time ?? 'Trong ngày' }}</span>
                                </div>
                                <div class="stat">
                                    <i class="las la-dollar-sign"></i>
                                    <span>{{ $contractor->price_range ?? '500k - 2M' }}</span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="contractor-actions">
                                <button class="btn btn-outline-primary" onclick="viewProfile({{ $contractor->id }})">
                                    <i class="las la-eye"></i>
                                    Xem Profile
                                </button>
                                <button class="btn btn-primary" onclick="quickHire({{ $contractor->id }})">
                                    <i class="las la-rocket"></i>
                                    Thuê Ngay
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Smart Pagination -->
                <div class="smart-pagination">
                    {{ $contractors->appends(request()->query())->links() }}
                    
                    <!-- Load More Button for better UX -->
                    @if($contractors->hasMorePages())
                        <button class="btn-load-more" onclick="loadMoreContractors()">
                            <i class="las la-plus"></i>
                            Xem thêm thợ
                        </button>
                    @endif
                </div>
            @else
                <!-- Enhanced Empty State -->
                <div class="empty-state-enhanced">
                    <div class="empty-icon">
                        <i class="las la-search-minus"></i>
                    </div>
                    <h4>Không tìm thấy thợ phù hợp</h4>
                    <p>Hãy thử:</p>
                    <ul class="suggestions">
                        <li>Điều chỉnh bộ lọc tìm kiếm</li>
                        <li>Mở rộng khu vực tìm kiếm</li>
                        <li>Thử từ khóa khác</li>
                        <li>Tạo lead để thợ tự liên hệ</li>
                    </ul>
                    
                    <div class="empty-actions">
                        <button class="btn btn-outline-primary" onclick="clearAllFilters()">
                            <i class="las la-redo"></i>
                            Xóa bộ lọc
                        </button>
                        <a href="{{ route('user.customer.leads.create') }}" class="btn btn-primary">
                            <i class="las la-rocket"></i>
                            Tạo Lead
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Hire Modal -->
<div class="modal fade" id="quickHireModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="las la-rocket text-primary"></i>
                    Thuê Thợ Nhanh
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="contractor-preview" id="selectedContractor"></div>
                
                <form id="quickHireForm" action="{{ route('user.customer.leads.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="preferred_contractor_id" id="contractorId">
                    
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="title" id="jobTitle" required>
                        <label for="jobTitle">Tiêu đề công việc</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="description" id="jobDescription" style="height: 120px" required></textarea>
                        <label for="jobDescription">Mô tả chi tiết công việc</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="budget_min" id="budgetMin">
                                <label for="budgetMin">Ngân sách tối thiểu (VNĐ)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="budget_max" id="budgetMax">
                                <label for="budgetMax">Ngân sách tối đa (VNĐ)</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" name="deadline" id="deadline" min="{{ date('Y-m-d') }}">
                        <label for="deadline">Thời hạn hoàn thành</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="quickHireForm" class="btn btn-primary">
                    <i class="las la-paper-plane"></i>
                    Gửi Yêu Cầu
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* Enhanced Search UX Styles */
.search-hero-section {
    background: #102f4b;
    padding: 3rem 0;
    color: white;
}

.smart-search-bar {
    max-width: 800px;
    margin: 2rem auto;
}

.search-input-group {
    display: flex;
    background: white;
    border-radius: 12px;
    padding: 8px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.search-field, .location-field {
    position: relative;
    flex: 1;
    margin-right: 8px;
}

.search-field input, .location-field select {
    border: none;
    padding: 12px 12px 12px 40px;
    border-radius: 8px;
    width: 100%;
    font-size: 16px;
}

.search-field i, .location-field i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    z-index: 2;
}

.search-btn {
    background: #48bbe2;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.search-btn:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.quick-categories {
    text-align: center;
    margin-top: 1rem;
}

.category-pill {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    color: white;
    padding: 6px 12px;
    margin: 0 4px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.category-pill:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    transform: translateY(-1px);
}

/* Enhanced Contractor Cards */
.contractor-card-enhanced {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
}

.contractor-card-enhanced:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}

.quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    gap: 8px;
}

.quick-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.9);
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.quick-action:hover {
    background: #48bbe2;
    color: white;
    transform: scale(1.1);
}

.contractor-header {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.contractor-avatar {
    position: relative;
    margin-right: 1rem;
    flex-shrink: 0;
}

.contractor-avatar img {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    object-fit: cover;
}

.status-badges {
    position: absolute;
    top: -4px;
    right: -4px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.badge {
    font-size: 12px;
    padding: 2px 4px;
    border-radius: 6px;
    border: 1px solid white;
}

.contractor-stats {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    flex-wrap: wrap;
}

.stat {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
    color: #666;
}

.contractor-actions {
    display: flex;
    gap: 12px;
    margin-top: 1rem;
}

.contractor-actions .btn {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Smart Filters */
.filters-panel {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
}

.filter-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.filter-group {
    margin-bottom: 2rem;
}

.filter-group h6 {
    margin-bottom: 1rem;
    color: #2d3748;
    font-weight: 600;
}

.price-option, .rating-option, .exp-option, .availability-option {
    display: block;
    padding: 8px 12px;
    margin-bottom: 8px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.price-option:hover, .rating-option:hover, .exp-option:hover, .availability-option:hover {
    background: #f7fafc;
}

.price-option input, .rating-option input, .exp-option input, .availability-option input {
    margin-right: 8px;
}

/* Lead CTA Card */
.lead-cta-card {
    background: linear-gradient(135deg, #48bbe2, #764ba2);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    color: white;
    margin-top: 2rem;
}

.btn-create-lead {
    background: white;
    color: #48bbe2;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-create-lead:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    color: #48bbe2;
}

/* Results Header */
.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

/* Enhanced Empty State */
.empty-state-enhanced {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.empty-icon i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1rem;
}

.suggestions {
    text-align: left;
    display: inline-block;
    margin: 1rem 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .search-input-group {
        flex-direction: column;
        gap: 8px;
    }
    
    .contractor-header {
        flex-direction: column;
        text-align: center;
    }
    
    .contractor-stats {
        justify-content: center;
    }
    
    .results-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
}
</style>
@endpush

@push('script')
<script>
// Smart Search with Auto-suggestions
document.getElementById('smartSearch').addEventListener('input', function(e) {
    const query = e.target.value;
    if (query.length > 2) {
        // Show search suggestions
        fetchSearchSuggestions(query);
    }
});

function fetchSearchSuggestions(query) {
    // AJAX call to get suggestions
    fetch(`/api/search-suggestions?q=${query}`)
        .then(response => response.json())
        .then(data => {
            showSuggestions(data);
        });
}

// Filter Management
function clearAllFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = '{{ route("contractors.search") }}';
}

// Quick Actions
function viewProfile(contractorId) {
    window.open(`/contractors/${contractorId}`, '_blank');
}

function quickHire(contractorId) {
    @auth
        // Load contractor data into modal
        loadContractorPreview(contractorId);
        document.getElementById('contractorId').value = contractorId;
        new bootstrap.Modal(document.getElementById('quickHireModal')).show();
    @else
        window.location.href = '{{ route("user.login.v2") }}';
    @endauth
}

function loadContractorPreview(contractorId) {
    fetch(`/api/contractors/${contractorId}/preview`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('selectedContractor').innerHTML = `
                <div class="contractor-preview">
                    <img src="${data.image}" alt="${data.name}">
                    <div>
                        <h6>${data.name}</h6>
                        <p class="text-muted">${data.category}</p>
                        <div class="rating">
                            ${generateStars(data.rating)}
                            <span>(${data.reviews_count} đánh giá)</span>
                        </div>
                    </div>
                </div>
            `;
        });
}

// Save/Favorite Contractor
function saveContractor(contractorId) {
    @auth
        fetch('/api/contractors/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ contractor_id: contractorId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Đã lưu thợ vào danh sách yêu thích!');
            }
        });
    @else
        window.location.href = '{{ route("user.login.v2") }}';
    @endauth
}

// Auto-submit filters
document.querySelectorAll('#filterForm input[type="radio"], #filterForm input[type="checkbox"]').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});

// Sort change handler
document.getElementById('sortSelect').addEventListener('change', function() {
    const url = new URL(window.location);
    url.searchParams.set('sort', this.value);
    window.location.href = url.toString();
});

// Load more contractors (infinite scroll alternative)
function loadMoreContractors() {
    const nextPage = {{ $contractors->currentPage() + 1 }};
    const url = new URL(window.location);
    url.searchParams.set('page', nextPage);
    
    fetch(url.toString())
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContractors = doc.querySelector('.contractors-grid').innerHTML;
            
            document.querySelector('.contractors-grid').innerHTML += newContractors;
            
            // Hide load more button if last page
            @if(!$contractors->hasMorePages())
                document.querySelector('.btn-load-more').style.display = 'none';
            @endif
        });
}

// Utility functions
function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += `<i class="las la-star ${i <= rating ? 'text-warning' : 'text-muted'}"></i>`;
    }
    return stars;
}

function showToast(message) {
    // Toast notification implementation
    console.log(message);
}
</script>
@endpush 