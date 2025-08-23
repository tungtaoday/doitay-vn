<div class="companies-grid row g-4">
    <div class="results-count-data d-none">{{ $companies->total() }}</div>
    
    @forelse ($companies as $company)
        <div class="col-lg-6 col-xl-4">
            <div class="contractor-card">
                <div class="contractor-card-header">
                    <div class="contractor-avatar">
                        <img src="{{ getImage(getFilePath('company') . '/' . @$company->image, getFileSize('company')) }}" 
                             alt="{{ $company->name }}" class="avatar-img">
                        @if($company->featured)
                            <div class="featured-badge">
                                <i class="fas fa-crown"></i>
                            </div>
                        @endif
                    </div>
                    <div class="contractor-status">
                        <span class="status-badge status-online">
                            <i class="fas fa-circle"></i> Đang hoạt động
                        </span>
                    </div>
                </div>

                <div class="contractor-card-body">
                    <div class="contractor-info">
                        <h5 class="contractor-name">
                            <a href="{{ route('company.details', [$company->id, slug($company->name ?? 'company')]) }}">
                                {{ $company->name }}
                            </a>
                        </h5>
                        <div class="contractor-category">
                            <i class="fas fa-tools me-1"></i>
                            <span>{{ $company->category->name }}</span>
                        </div>
                        <div class="contractor-location">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <span>{{ $company->city ?? $company->address }}</span>
                        </div>
                    </div>

                    <div class="contractor-rating">
                        <div class="rating-stars">
                            @php
                                $rating = $company->ratings_avg_avg_rating ?? 0;
                                $fullStars = floor($rating);
                                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                            @endphp
                            
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $fullStars)
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i == $fullStars + 1 && $hasHalfStar)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-muted"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="rating-info">
                            <span class="rating-score">{{ number_format($rating, 1) }}</span>
                            <span class="rating-count">({{ $company->ratings_count }} đánh giá)</span>
                        </div>
                    </div>

                    @if($company->description)
                        <div class="contractor-description">
                            <p>{{ Str::limit($company->description, 100) }}</p>
                        </div>
                    @endif

                    <div class="contractor-stats">
                        <div class="stat-item">
                            <i class="fas fa-calendar-check text-success"></i>
                            <span>{{ \Carbon\Carbon::parse($company->created_at)->diffForHumans() }}</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-eye text-info"></i>
                            <span>{{ $company->total_click ?? 0 }} lượt xem</span>
                        </div>
                    </div>
                </div>

                <div class="contractor-card-footer">
                    <div class="action-buttons">
                        <a href="{{ route('company.details', [$company->id, slug($company->name ?? 'company')]) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> Xem chi tiết
                        </a>
                        <button class="btn btn-primary custom-primary-btn" onclick="quickContact({{ $company->id }})">
                            <i class="fas fa-phone me-1"></i> Liên hệ ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search-minus"></i>
                </div>
                <h4>Không tìm thấy thợ chuyên nghiệp nào</h4>
                <p class="text-muted">Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                <button class="btn btn-primary custom-primary-btn" onclick="clearAllFilters()">
                    <i class="fas fa-refresh me-1"></i> Xóa bộ lọc
                </button>
            </div>
        </div>
    @endforelse
</div>

@if ($companies->hasPages())
    <div class="pagination-wrapper">
        <nav aria-label="Contractors pagination">
            {{ $companies->appends(request()->query())->links('pagination::bootstrap-4') }}
        </nav>
    </div>
@endif

<style>
/* Custom Primary Button */
.custom-primary-btn {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
    border: none !important;
    color: white !important;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
}

.custom-primary-btn:hover {
    background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(30, 58, 138, 0.4);
    color: white !important;
}

.custom-primary-btn:active {
    transform: translateY(0);
    box-shadow: 0 4px 15px rgba(30, 58, 138, 0.3);
}

/* Contractor Cards */
.contractor-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.contractor-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.contractor-card-header {
    position: relative;
    padding: 1.5rem 1.5rem 1rem;
    background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
}

.contractor-avatar {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
}

.avatar-img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.featured-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #ffd700 0%, #ffb347 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    box-shadow: 0 3px 10px rgba(255, 215, 0, 0.3);
}

.contractor-status {
    text-align: center;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.status-online {
    background: #d4edda;
    color: #155724;
}

.status-online i {
    color: #28a745;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.contractor-card-body {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.contractor-info {
    margin-bottom: 1rem;
}

.contractor-name {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.contractor-name a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
}

.contractor-name a:hover {
    color: #667eea;
}

.contractor-category,
.contractor-location {
    display: flex;
    align-items: center;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.25rem;
}

.contractor-category i,
.contractor-location i {
    color: #667eea;
    width: 15px;
}

.contractor-rating {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-info {
    text-align: right;
}

.rating-score {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
}

.rating-count {
    display: block;
    font-size: 0.8rem;
    color: #666;
}

.contractor-description {
    margin-bottom: 1rem;
    flex-grow: 1;
}

.contractor-description p {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.5;
    margin: 0;
}

.contractor-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    color: #666;
}

.contractor-card-footer {
    padding: 1rem 1.5rem 1.5rem;
    background: #f8f9fa;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn {
    flex: 1;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.action-buttons .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.action-buttons .btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
}

.action-buttons .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.action-buttons .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

/* No Results */
.no-results {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.no-results-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.no-results h4 {
    color: #333;
    margin-bottom: 0.5rem;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.pagination {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.page-link {
    border: none;
    color: #667eea;
    font-weight: 500;
    margin: 0 2px;
    border-radius: 8px;
}

.page-link:hover {
    background-color: #667eea;
    color: white;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .contractor-rating {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .contractor-stats {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
function quickContact(companyId) {
    // Quick contact modal or redirect to contact
    window.location.href = `/companies/${companyId}/contact`;
}

function clearAllFilters() {
    // Trigger clear filters event
    $('#clearFilters').click();
}
</script> 