@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 contact-section overflow-hidden section--bg">
        <div class="shape-one"></div>
        <div class="shape-two"></div>
        <div class="shape-three"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="custom--card">
                        <div class="card-header">
                            <h5 class="card-title">@lang('Detailed Statistics for') {{ $company->name }}</h5>
                            <a href="{{ route('user.company.statistics') }}" class="btn btn--base btn-sm">
                                <i class="las la-arrow-left"></i> @lang('Back to Overview')
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="statistics-card">
                                        <div class="statistics-card__header">
                                            <h6>@lang('Basic Statistics')</h6>
                                        </div>
                                        <div class="statistics-card__body">
                                            <div class="stat-item">
                                                <span class="stat-label">@lang('Total Views')</span>
                                                <span class="stat-value">{{ $company->statistics->views ?? 0 }}</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">@lang('Total Hires')</span>
                                                <span class="stat-value">{{ $company->statistics->hires ?? 0 }}</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-label">@lang('Average Rating')</span>
                                                <span class="stat-value">
                                                    @php
                                                        $ratings = $company->ratings()->where('status', 1)->get();
                                                        $totalRating = $ratings->sum('avg_rating');
                                                        $totalReviews = $ratings->count();
                                                        $avgRating = $totalReviews > 0 ? $totalRating / $totalReviews : 0;
                                                        echo number_format($avgRating, 1);
                                                    @endphp
                                                    ({{ $totalReviews }} @lang('reviews'))
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="statistics-card">
                                        <div class="statistics-card__header">
                                            <h6>@lang('Recent Reviews')</h6>
                                        </div>
                                        <div class="statistics-card__body">
                                            @forelse($company->ratings()->with('user')->where('status', 1)->latest()->take(5)->get() as $rating)
                                                <div class="review-item">
                                                    <div class="review-header">
                                                        <div class="reviewer-info">
                                                            <h6>{{ $rating->user->fullname }}</h6>
                                                            <div class="rating">
                                                                @php
                                                                    $ratingValue = round($rating->avg_rating);
                                                                @endphp
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="las la-star {{ $i <= $ratingValue ? 'text-warning' : 'text-muted' }}"></i>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                        <span class="review-date">{{ $rating->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="review-content">{{ $rating->suggest }}</p>
                                                </div>
                                            @empty
                                                <p class="text-muted text-center">@lang('No reviews yet')</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
<style>
    .statistics-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        overflow: hidden;
        height: 100%;
    }
    .statistics-card__header {
        padding: 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .statistics-card__body {
        padding: 15px;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .stat-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .stat-label {
        color: #666;
    }
    .stat-value {
        font-weight: 600;
        color: #333;
    }
    .review-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    .review-item:last-child {
        border-bottom: none;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .reviewer-info h6 {
        margin-bottom: 5px;
    }
    .rating {
        color: #ffc107;
    }
    .review-date {
        color: #666;
        font-size: 12px;
    }
    .review-content {
        color: #333;
        margin-bottom: 10px;
    }
</style>
@endpush 