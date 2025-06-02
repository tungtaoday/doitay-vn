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
                            <h5 class="card-title">@lang('Company Statistics Overview')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($companies as $company)
                                    <div class="col-lg-4 col-md-6 mb-4">
                                        <div class="statistics-card">
                                            <div class="statistics-card__header">
                                                <h6>{{ $company->name }}</h6>
                                                <span class="badge badge--{{ $company->status == 1 ? 'success' : 'warning' }}">
                                                    {{ $company->status == 1 ? 'Approved' : 'Pending' }}
                                                </span>
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
                                                            echo number_format($company->reviews_avg_rating ?? 0, 1);
                                                        @endphp
                                                        ({{ $company->reviews_count }} @lang('reviews'))
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="statistics-card__footer">
                                                <a href="{{ route('user.company.statistics.detail', $company->id) }}" class="btn btn--base btn-sm">
                                                    <i class="las la-chart-bar"></i> @lang('View Details')
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
    .statistics-card__footer {
        padding: 15px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
        text-align: center;
    }
</style>
@endpush 