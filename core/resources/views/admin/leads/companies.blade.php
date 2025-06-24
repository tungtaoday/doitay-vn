@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $pageTitle }}</h4>
                <p class="text-muted">Hiệu suất công ty trong hệ thống leads</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.leads.index') }}" class="btn btn--secondary">
                    <i class="las la-arrow-left"></i> Quay lại
                </a>
                <button class="btn btn--primary" onclick="window.print()">
                    <i class="las la-print"></i> In báo cáo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Company Performance Overview -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ $overviewStats['total_companies'] }}</h4>
                        <p class="mb-0">Tổng Công Ty</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ $overviewStats['active_companies'] }}</h4>
                        <p class="mb-0">Đang Hoạt Động</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-check-circle"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        {{ $overviewStats['total_companies'] > 0 ? round(($overviewStats['active_companies'] / $overviewStats['total_companies']) * 100, 1) : 0 }}% tổng số
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ $overviewStats['companies_with_purchases'] }}</h4>
                        <p class="mb-0">Đã Mua Lead</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-shopping-cart"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        {{ $overviewStats['active_companies'] > 0 ? round(($overviewStats['companies_with_purchases'] / $overviewStats['active_companies']) * 100, 1) : 0 }}% công ty hoạt động
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($overviewStats['avg_response_time']) }}</h4>
                        <p class="mb-0">Phút TB Phản Hồi</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-clock"></i>
                    </div>
                </div>
                <div class="mt-2">
                    <small class="text-white-50">
                        Từ thông báo đến mua lead
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Search -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('admin.leads.companies') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm tên công ty...">
                        </div>
                        <div class="col-md-2">
                            <select name="sort_by" class="form-control">
                                <option value="purchases_desc" {{ request('sort_by') == 'purchases_desc' ? 'selected' : '' }}>Mua nhiều nhất</option>
                                <option value="purchases_asc" {{ request('sort_by') == 'purchases_asc' ? 'selected' : '' }}>Mua ít nhất</option>
                                <option value="rating_desc" {{ request('sort_by') == 'rating_desc' ? 'selected' : '' }}>Điểm cao nhất</option>
                                <option value="rating_asc" {{ request('sort_by') == 'rating_asc' ? 'selected' : '' }}>Điểm thấp nhất</option>
                                <option value="response_time_asc" {{ request('sort_by') == 'response_time_asc' ? 'selected' : '' }}>Phản hồi nhanh</option>
                                <option value="response_time_desc" {{ request('sort_by') == 'response_time_desc' ? 'selected' : '' }}>Phản hồi chậm</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="min_purchases" class="form-control">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('min_purchases') == '1' ? 'selected' : '' }}>≥ 1 lead</option>
                                <option value="5" {{ request('min_purchases') == '5' ? 'selected' : '' }}>≥ 5 leads</option>
                                <option value="10" {{ request('min_purchases') == '10' ? 'selected' : '' }}>≥ 10 leads</option>
                                <option value="20" {{ request('min_purchases') == '20' ? 'selected' : '' }}>≥ 20 leads</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="time_period" class="form-control">
                                <option value="all" {{ request('time_period') == 'all' ? 'selected' : '' }}>Tất cả thời gian</option>
                                <option value="7" {{ request('time_period') == '7' ? 'selected' : '' }}>7 ngày qua</option>
                                <option value="30" {{ request('time_period') == '30' ? 'selected' : '' }}>30 ngày qua</option>
                                <option value="90" {{ request('time_period') == '90' ? 'selected' : '' }}>90 ngày qua</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Bị cấm</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn--primary w-100">
                                <i class="las la-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Companies Performance Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-chart-line text-primary"></i>
                    Hiệu suất các Công ty
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Công ty</th>
                                <th>Điểm đánh giá</th>
                                <th>Thông báo nhận</th>
                                <th>Leads đã mua</th>
                                <th>Tỷ lệ chuyển đổi</th>
                                <th>Tổng chi tiêu</th>
                                <th>TB phản hồi</th>
                                <th>Thắng thầu</th>
                                <th>Tỷ lệ thành công</th>
                                <th>Hoạt động gần đây</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($companies as $index => $company)
                            <tr>
                                <td>
                                    <span class="fw-bold">
                                        @if($index < 3)
                                            <i class="las la-medal text-warning"></i>
                                        @endif
                                        {{ $index + 1 + (($companies->currentPage() - 1) * $companies->perPage()) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="user">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('company') . '/' . @$company->image, getFileSize('company')) }}" alt="company">
                                        </div>
                                        <div class="user-info">
                                            <a href="{{ route('admin.companies.detail', $company->id) }}" class="fw-bold">
                                                {{ $company->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                ID: {{ $company->id }} • 
                                                Thành viên từ {{ $company->created_at->format('m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="rating-display">
                                        @php
                                            $rating = $company->avgRating ?? 0;
                                            $fullStars = floor($rating);
                                            $halfStar = $rating - $fullStars >= 0.5;
                                        @endphp
                                        
                                        <div class="star-rating mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $fullStars)
                                                    <i class="las la-star text-warning"></i>
                                                @elseif($i == $fullStars + 1 && $halfStar)
                                                    <i class="las la-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="las la-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ number_format($rating, 1) }}/5.0 ({{ $company->reviews_count }} đánh giá)</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge--info">{{ $company->notifications_received ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--success">{{ $company->leads_purchased ?? 0 }}</span>
                                </td>
                                <td>
                                    @php
                                        $conversionRate = ($company->notifications_received > 0) 
                                            ? round(($company->leads_purchased / $company->notifications_received) * 100, 1) 
                                            : 0;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar bg-success" style="width: {{ min($conversionRate, 100) }}%"></div>
                                        </div>
                                        <small>{{ $conversionRate }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ number_format($company->total_spent ?? 0) }}₫</span>
                                </td>
                                <td>
                                    @if($company->avg_response_minutes)
                                        @php
                                            $minutes = $company->avg_response_minutes;
                                            $hours = floor($minutes / 60);
                                            $remainingMinutes = $minutes % 60;
                                        @endphp
                                        @if($hours > 0)
                                            <span class="text-{{ $hours > 24 ? 'danger' : ($hours > 4 ? 'warning' : 'success') }}">
                                                {{ $hours }}h {{ $remainingMinutes }}m
                                            </span>
                                        @else
                                            <span class="text-success">{{ $remainingMinutes }}m</span>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $company->won_leads ?? 0 }}</span>
                                </td>
                                <td>
                                    @php
                                        $winRate = ($company->leads_purchased > 0) 
                                            ? round(($company->won_leads / $company->leads_purchased) * 100, 1) 
                                            : 0;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar bg-primary" style="width: {{ $winRate }}%"></div>
                                        </div>
                                        <small>{{ $winRate }}%</small>
                                    </div>
                                </td>
                                <td>
                                    @if($company->last_activity)
                                        <span class="text-{{ $company->last_activity->diffInDays() > 7 ? 'danger' : 'success' }}">
                                            {{ $company->last_activity->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-muted">Chưa có</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn--secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.companies.detail', $company->id) }}">
                                                    <i class="las la-eye"></i> Xem chi tiết
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item view-lead-history" href="#" data-company-id="{{ $company->id }}">
                                                    <i class="las la-history"></i> Lịch sử leads
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.login', $company->user_id) }}" target="_blank">
                                                    <i class="las la-sign-in-alt"></i> Đăng nhập với tư cách
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">
                                    <i class="las la-building fs-2 text-muted"></i>
                                    <p class="text-muted">Không có công ty nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($companies->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p class="text-muted">
                            Hiển thị {{ $companies->firstItem() }}-{{ $companies->lastItem() }} 
                            trong tổng số {{ $companies->total() }} công ty
                        </p>
                    </div>
                    <div class="col-md-6">
                        {{ $companies->appends(request()->all())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Company Lead History Modal -->
<div class="modal fade" id="leadHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lịch sử Leads</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="leadHistoryContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
.star-rating {
    display: inline-block;
}

.star-rating i {
    font-size: 14px;
}

.rating-display {
    min-width: 120px;
}

.dashboard-icon {
    font-size: 2rem;
    opacity: 0.8;
}

.progress {
    background-color: #e9ecef;
}

.thumb {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}

.thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

@media print {
    .btn, .dropdown, .pagination, .modal {
        display: none !important;
    }
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    // View lead history
    $('.view-lead-history').click(function(e) {
        e.preventDefault();
        const companyId = $(this).data('company-id');
        
        $('#leadHistoryModal').modal('show');
        
        // Load lead history via AJAX
        $.get(`{{ route('admin.leads.companies') }}/${companyId}/history`)
            .done(function(response) {
                $('#leadHistoryContent').html(response);
            })
            .fail(function() {
                $('#leadHistoryContent').html(`
                    <div class="alert alert-danger">
                        <i class="las la-exclamation-triangle"></i>
                        Không thể tải lịch sử leads. Vui lòng thử lại.
                    </div>
                `);
            });
    });
});
</script>
@endpush 