@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title">{{ $pageTitle }}</h4>
                <p class="text-muted">Phân tích hiệu suất và xu hướng leads</p>
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

<!-- Revenue Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($revenueStats['total_revenue']) }}₫</h4>
                        <p class="mb-0">Tổng Doanh Thu</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($revenueStats['avg_lead_price']) }}₫</h4>
                        <p class="mb-0">Giá Lead Trung Bình</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-tag"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ number_format($revenueStats['total_purchases']) }}</h4>
                        <p class="mb-0">Tổng Lượt Mua</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
        <div class="card bg--primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="text-white">{{ $revenueStats['unique_buyers'] }}</h4>
                        <p class="mb-0">Công Ty Mua</p>
                    </div>
                    <div class="dashboard-icon">
                        <i class="las la-building"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Lead Creation Trend -->
    <div class="col-lg-8">
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-chart-line text-primary"></i>
                    Xu hướng tạo Leads (30 ngày gần đây)
                </h5>
            </div>
            <div class="card-body">
                <canvas id="leadTrendChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Lead Status Distribution -->
    <div class="col-lg-4">
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-chart-pie text-success"></i>
                    Phân bố Trạng thái
                </h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" height="180"></canvas>
                <div class="mt-3">
                    @foreach($statusDistribution as $status)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-capitalize">{{ $status->status }}</span>
                        <strong>{{ $status->count }}</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Category Performance -->
    <div class="col-lg-6">
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-chart-bar text-info"></i>
                    Hiệu suất theo Danh mục
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Danh mục</th>
                                <th>Leads</th>
                                <th>Đã mua</th>
                                <th>Tỷ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryStats as $category)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td><span class="badge badge--primary">{{ $category['total_leads'] }}</span></td>
                                <td><span class="badge badge--success">{{ $category['purchased_leads'] }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $category['purchase_rate'] }}%"></div>
                                        </div>
                                        <small>{{ $category['purchase_rate'] }}%</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Districts -->
    <div class="col-lg-6">
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-map-marker-alt text-warning"></i>
                    Top Khu vực (có >= 5 leads)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Quận/Huyện</th>
                                <th>Tổng Leads</th>
                                <th>Có người mua</th>
                                <th>Tỷ lệ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($districtStats as $district)
                            @php
                                $rate = $district->total_leads > 0 
                                    ? round(($district->leads_with_purchases / $district->total_leads) * 100, 1) 
                                    : 0;
                            @endphp
                            <tr>
                                <td>{{ $district->district }}</td>
                                <td><span class="badge badge--primary">{{ $district->total_leads }}</span></td>
                                <td><span class="badge badge--success">{{ $district->leads_with_purchases }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $rate }}%"></div>
                                        </div>
                                        <small>{{ $rate }}%</small>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Time Analysis -->
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-stopwatch text-danger"></i>
                    Phân tích Thời gian Phản hồi
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded">
                            <h3 class="text-primary">{{ $avgResponseTime ? round($avgResponseTime) : 0 }}</h3>
                            <p class="text-muted mb-0">Phút trung bình</p>
                            <small class="text-muted">Từ thông báo đến mua lead đầu tiên</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded">
                            @php
                                $hours = $avgResponseTime ? round($avgResponseTime / 60, 1) : 0;
                            @endphp
                            <h3 class="text-success">{{ $hours }}</h3>
                            <p class="text-muted mb-0">Giờ trung bình</p>
                            <small class="text-muted">Thời gian phản hồi nhanh</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4 border rounded">
                            @php
                                $quality = 'Tốt';
                                $qualityClass = 'text-success';
                                if ($avgResponseTime > 1440) { // > 24h
                                    $quality = 'Cần cải thiện';
                                    $qualityClass = 'text-danger';
                                } elseif ($avgResponseTime > 240) { // > 4h
                                    $quality = 'Bình thường';
                                    $qualityClass = 'text-warning';
                                }
                            @endphp
                            <h3 class="{{ $qualityClass }}">{{ $quality }}</h3>
                            <p class="text-muted mb-0">Đánh giá</p>
                            <small class="text-muted">Dựa trên thời gian phản hồi</small>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h6><i class="las la-info-circle"></i> Thông tin phân tích:</h6>
                            <ul class="mb-0">
                                <li><strong>Thời gian phản hồi nhanh (< 4 giờ):</strong> Tốt cho trải nghiệm khách hàng</li>
                                <li><strong>Thời gian phản hồi trung bình (4-24 giờ):</strong> Có thể chấp nhận được</li>
                                <li><strong>Thời gian phản hồi chậm (> 24 giờ):</strong> Cần cải thiện hệ thống thông báo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Lead Trend Chart
    const leadTrendData = @json($leadTrends);
    const dates = leadTrendData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('vi-VN', { month: 'short', day: 'numeric' });
    });
    const counts = leadTrendData.map(item => item.count);

    const leadTrendCtx = document.getElementById('leadTrendChart').getContext('2d');
    new Chart(leadTrendCtx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Leads được tạo',
                data: counts,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Status Distribution Chart
    const statusData = @json($statusDistribution);
    const statusLabels = statusData.map(item => {
        const labels = {
            'active': 'Đang mở',
            'closed': 'Đã đóng', 
            'expired': 'Hết hạn',
            'draft': 'Nháp'
        };
        return labels[item.status] || item.status;
    });
    const statusCounts = statusData.map(item => item.count);
    const statusColors = ['#28a745', '#dc3545', '#ffc107', '#6c757d'];

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });
});
</script>
@endpush 