<!-- Quick Stats -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-card__icon">
                <i class="las la-building"></i>
            </div>
            <div class="stat-card__content">
                <h6 class="stat-card__title">Số lượng người thợ</h6>
                <h2 class="stat-card__value">{{ auth()->user()->companies()->count() }}</h2>
            </div>
        </div>  
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-card__icon">
                <i class="las la-eye"></i>
            </div>
            <div class="stat-card__content">
                <h6 class="stat-card__title">Số lượt xem</h6>
                <h2 class="stat-card__value">{{ auth()->user()->companies()->with('statistics')->get()->sum('statistics.views') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-card__icon">
                <i class="las la-handshake"></i>
            </div>
            <div class="stat-card__content">
                <h6 class="stat-card__title">Số lượng được thuê</h6>
                <h2 class="stat-card__value">{{ auth()->user()->companies()->with('statistics')->get()->sum('statistics.hires') }}</h2>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-card__icon">
                <i class="las la-star"></i>
            </div>
            <div class="stat-card__content">
                <h6 class="stat-card__title">Trung bình đánh giá</h6>
                <h2 class="stat-card__value">
                    @php
                        $companies = auth()->user()->companies()->with('ratings')->get();
                        $totalRating = 0;
                        $totalReviews = 0;
                        foreach($companies as $company) {
                            $companyRatings = $company->ratings()->where('status', 1)->get();
                            $totalRating += $companyRatings->sum('avg_rating');
                            $totalReviews += $companyRatings->count();
                        }
                        $avgRating = $totalReviews > 0 ? $totalRating / $totalReviews : 0;
                        echo number_format($avgRating, 1);
                    @endphp
                </h2>
            </div>
        </div>
    </div>
</div>

<!-- Company Statistics -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="custom--card">
            <div class="card-header">
                <h5 class="card-title">Thống kê người thợ</h5>
                <a href="{{ route('user.company.statistics') }}" class="btn btn--base btn-sm">
                    <i class="las la-chart-bar"></i> Xem toàn bộ thống kê
                </a>
            </div>
            <div class="card-body p-0">
                <div class="row g-4 p-4">
                    @foreach(auth()->user()->companies()->with('statistics', 'ratings')->latest()->take(3)->get() as $company)
                        <div class="col-xl-4 col-md-6">
                            <div class="company-stats-card">
                                <div class="company-stats-card__header">
                                    <div class="company-info">
                                        <h6 class="company-name">{{ $company->name }}</h6>
                                        <span class="company-status badge badge--{{ $company->status == 1 ? 'success' : 'warning' }}">
                                            {{ $company->status == 1 ? 'Approved' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="company-stats-card__body">
                                    <div class="stats-grid">
                                        <div class="stats-item">
                                            <div class="stats-icon">
                                                <i class="las la-eye"></i>
                                            </div>
                                            <div class="stats-content">
                                                <span class="stats-label">Views</span>
                                                <span class="stats-value">{{ $company->statistics->views ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <div class="stats-item">
                                            <div class="stats-icon">
                                                <i class="las la-handshake"></i>
                                            </div>
                                            <div class="stats-content">
                                                <span class="stats-label">Hires</span>
                                                <span class="stats-value">{{ $company->statistics->hires ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <div class="stats-item">
                                            <div class="stats-icon">
                                                <i class="las la-star"></i>
                                            </div>
                                            <div class="stats-content">
                                                <span class="stats-label">Rating</span>
                                                <span class="stats-value">
                                                    @php
                                                        $companyRatings = $company->ratings()->where('status', 1)->get();
                                                        $totalRating = $companyRatings->sum('avg_rating');
                                                        $totalReviews = $companyRatings->count();
                                                        $avgRating = $totalReviews > 0 ? $totalRating / $totalReviews : 0;
                                                        echo number_format($avgRating, 1);
                                                    @endphp
                                                    <small>({{ $totalReviews }})</small>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="company-stats-card__footer">
                                    <a href="{{ route('user.company.statistics.detail', $company->id) }}" class="btn btn--base btn-sm w-100">
                                        <i class="las la-chart-bar"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
    /* Quick Stats Cards */
    .stat-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        display: flex;
        align-items: center;
        height: 100%;
    }
    .stat-card__icon {
        width: 50px;
        height: 50px;
        background: var(--base);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    .stat-card__icon i {
        font-size: 24px;
        color: #fff;
    }
    .stat-card__content {
        flex: 1;
    }
    .stat-card__title {
        font-size: 14px;
        color: #666;
        margin-bottom: 5px;
    }
    .stat-card__value {
        font-size: 24px;
        font-weight: 600;
        color: var(--base);
        margin: 0;
    }

    /* Company Stats Cards */
    .company-stats-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .company-stats-card__header {
        padding: 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }
    .company-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .company-name {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    .company-status {
        font-size: 12px;
        padding: 4px 8px;
    }
    .company-stats-card__body {
        padding: 15px;
        flex: 1;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
    }
    .stats-item {
        text-align: center;
    }
    .stats-icon {
        width: 40px;
        height: 40px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
    }
    .stats-icon i {
        font-size: 18px;
        color: var(--base);
    }
    .stats-label {
        display: block;
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
    }
    .stats-value {
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }
    .stats-value small {
        font-size: 12px;
        color: #666;
    }
    .company-stats-card__footer {
        padding: 15px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }
</style>
@endpush 