@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="contractors-page">
    <!-- Hero Search Section -->
    <section class="hero-search-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="text-center text-white mb-4">
                        <h1 class="display-5 fw-bold mb-3">{{ $pageTitle }}</h1>
                        <p class="lead">Khám phá {{ $companies->total() }} thợ chuyên nghiệp được xác minh trên toàn quốc</p>
                    </div>
                    
                    <!-- Enhanced Search Bar -->
                    <div class="search-wrapper">
                        <form id="mainSearchForm" class="search-form-enhanced">
                            <div class="row g-2">
                                <div class="col-md-5">
                                    <div class="search-input-group">
                                        <i class="fas fa-search search-icon"></i>
                                        <input type="text" name="search" class="form-control search-input" 
                                               placeholder="Tìm kiếm thợ hoặc dịch vụ..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="category_id" class="form-select category-select">
                                        <option value="">Tất cả chuyên ngành</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->company_count ?? 0 }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="location" class="form-select location-select">
                                        <option value="">Tất cả khu vực</option>
                                        @if(isset($locations))
                                            @foreach($locations as $location)
                                                <option value="{{ $location }}" 
                                                    {{ request('location') == $location ? 'selected' : '' }}>
                                                    {{ $location }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-search w-100">
                                        <i class="fas fa-search me-1"></i> Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="contractors-listing py-5">
        <div class="container">
            <div class="row">
                <!-- Advanced Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="filters-sidebar">
                        <div class="filters-header">
                            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ lọc nâng cao</h5>
                            <button class="btn btn-link btn-sm" id="clearFilters">Xóa bộ lọc</button>
                        </div>

                        <!-- Rating Filter -->
                        <div class="filter-section">
                            <h6 class="filter-title">Đánh giá</h6>
                            <div class="rating-filters">
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rating" value="{{ $i }}" 
                                               id="rating{{ $i }}" {{ request('rating') == $i ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rating{{ $i }}">
                                            <div class="rating-display">
                                                @for($j = 1; $j <= 5; $j++)
                                                    <i class="fas fa-star {{ $j <= $i ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ms-2">{{ $i }} sao trở lên</span>
                                            </div>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Experience Filter -->
                        <div class="filter-section">
                            <h6 class="filter-title">Kinh nghiệm</h6>
                            @if(isset($experienceLevels))
                                @foreach($experienceLevels as $value => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="experience" value="{{ $value }}" 
                                               id="exp{{ $value }}" {{ request('experience') == $value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exp{{ $value }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Quick Filters -->
                        <div class="filter-section">
                            <h6 class="filter-title">Lọc nhanh</h6>
                            <div class="quick-filters">
                                <button class="btn btn-outline-primary btn-sm quick-filter" data-filter="top-rated">
                                    <i class="fas fa-star me-1"></i>Đánh giá cao
                                </button>
                                <button class="btn btn-outline-success btn-sm quick-filter" data-filter="newest">
                                    <i class="fas fa-clock me-1"></i>Mới tham gia
                                </button>
                                <button class="btn btn-outline-info btn-sm quick-filter" data-filter="experienced">
                                    <i class="fas fa-medal me-1"></i>Kinh nghiệm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Area -->
                <div class="col-lg-9">
                    <!-- Results Header -->
                    <div class="results-header">
                        <div class="results-info">
                            <h5 class="mb-0">
                                <span class="results-count">{{ $companies->total() }}</span> thợ chuyên nghiệp được tìm thấy
                            </h5>
                            <p class="text-muted mb-0">Hiển thị {{ $companies->firstItem() ?? 0 }}-{{ $companies->lastItem() ?? 0 }} trong {{ $companies->total() }} kết quả</p>
                        </div>
                        <div class="results-controls">
                            <div class="view-toggle">
                                <button class="btn btn-outline-secondary btn-sm active" data-view="grid">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                            <select name="sort_by" class="form-select form-select-sm ms-2" style="width: auto;">
                                @if(isset($sortOptions))
                                    @foreach($sortOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('sort_by') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="latest">Mới nhất</option>
                                    <option value="rating">Đánh giá cao nhất</option>
                                    <option value="name">Tên A-Z</option>
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Companies Grid -->
                    <div id="companiesContainer" class="companies-container">
                        @include($activeTemplate . 'company.companies')
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('style')
<style>
/* Hero Search Section */
.hero-search-section {
    min-height: 300px;
    display: flex;
    align-items: center;
}

.search-wrapper {
    background: rgba(255,255,255,0.95);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
}

.search-input-group {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    z-index: 5;
}

.search-input {
    padding-left: 45px;
    height: 50px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.category-select, .location-select {
    height: 50px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 16px;
}

.btn-search {
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Filters Sidebar */
.filters-sidebar {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    height: fit-content;
    position: sticky;
    top: 100px;
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f8f9fa;
}

.filter-section {
    margin-bottom: 2rem;
}

.filter-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.rating-display {
    display: flex;
    align-items: center;
}

.quick-filters {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.quick-filter {
    text-align: left;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.quick-filter:hover {
    transform: translateX(5px);
}

/* Results Header */
.results-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.results-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.view-toggle {
    display: flex;
    border-radius: 8px;
    overflow: hidden;
}

.view-toggle .btn {
    border-radius: 0;
    border-color: #dee2e6;
}

.view-toggle .btn.active {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

/* Companies Container */
.companies-container {
    min-height: 400px;
}

/* Loading State */
.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 15px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .search-wrapper {
        padding: 1rem;
    }
    
    .results-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .results-controls {
        justify-content: space-between;
    }
    
    .filters-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
}

/* === FIX BTN-OUTLINE-SUCCESS === */
.btn.btn-outline-success {
    color: #10b981 !important;
    border-color: #10b981 !important;
    background-color: transparent !important;
}

.btn.btn-outline-success:hover,
.btn.btn-outline-success:focus {
    background-color: #10b981 !important;
    color: white !important;
    border-color: #10b981 !important;
}

.btn.btn-outline-success:active {
    background-color: #10b981 !important;
    color: white !important;
    border-color: #10b981 !important;
}

/* Fix for small size variants */
.btn.btn-outline-success.btn-sm {
    color: #10b981 !important;
    border-color: #10b981 !important;
    background-color: transparent !important;
}

.btn.btn-outline-success.btn-sm:hover,
.btn.btn-outline-success.btn-sm:focus {
    background-color: #10b981 !important;
    color: white !important;
    border-color: #10b981 !important;
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    let isLoading = false;
    
    // Main search form
    $('#mainSearchForm').on('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    // Filter changes
    $('input[name="rating"], input[name="experience"], select[name="sort_by"]').on('change', function() {
        applyFilters();
    });
    
    // Quick filters
    $('.quick-filter').on('click', function() {
        const filter = $(this).data('filter');
        
        // Remove active class from all quick filters
        $('.quick-filter').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        
        switch(filter) {
            case 'top-rated':
                $('select[name="sort_by"]').val('rating');
                $('input[name="rating"][value="4"]').prop('checked', true);
                break;
            case 'newest':
                $('select[name="sort_by"]').val('latest');
                $('input[name="experience"][value="0-1"]').prop('checked', true);
                break;
            case 'experienced':
                $('select[name="sort_by"]').val('experience');
                $('input[name="experience"][value="5-10"]').prop('checked', true);
                break;
        }
        
        applyFilters();
    });
    
    // Clear filters
    $('#clearFilters').on('click', function() {
        $('input[name="search"]').val('');
        $('select[name="category_id"]').val('');
        $('select[name="location"]').val('');
        $('input[name="rating"]').prop('checked', false);
        $('input[name="experience"]').prop('checked', false);
        $('select[name="sort_by"]').val('latest');
        $('.quick-filter').removeClass('btn-primary').addClass('btn-outline-primary');
        applyFilters();
    });
    
    // View toggle
    $('.view-toggle .btn').on('click', function() {
        $('.view-toggle .btn').removeClass('active');
        $(this).addClass('active');
        
        const view = $(this).data('view');
        if (view === 'list') {
            $('.companies-grid').removeClass('row').addClass('companies-list');
        } else {
            $('.companies-list').removeClass('companies-list').addClass('row');
        }
    });
    
    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const page = new URL(url).searchParams.get('page');
        applyFilters(page);
    });
    
    function applyFilters(page = 1) {
        if (isLoading) return;
        
        isLoading = true;
        
        // Show loading
        const $container = $('#companiesContainer');
        $container.css('position', 'relative');
        $container.append('<div class="loading-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Đang tải...</span></div></div>');
        
        const formData = {
            search: $('input[name="search"]').val(),
            category_id: $('select[name="category_id"]').val(),
            location: $('select[name="location"]').val(),
            rating: $('input[name="rating"]:checked').val(),
            experience: $('input[name="experience"]:checked').val(),
            sort_by: $('select[name="sort_by"]').val(),
            page: page
        };
        
        $.ajax({
            url: '{{ route("company.filter") }}',
            method: 'GET',
            data: formData,
            success: function(response) {
                $container.html(response);
                
                // Update results count
                const total = $(response).find('.results-count-data').text() || '0';
                $('.results-count').text(total);
                
                // Scroll to results
                if (page > 1) {
                    $('html, body').animate({
                        scrollTop: $container.offset().top - 100
                    }, 500);
                }
            },
            error: function() {
                $container.html('<div class="alert alert-danger">Có lỗi xảy ra. Vui lòng thử lại.</div>');
            },
            complete: function() {
                isLoading = false;
                $('.loading-overlay').remove();
            }
        });
    }
});
</script>
@endpush 