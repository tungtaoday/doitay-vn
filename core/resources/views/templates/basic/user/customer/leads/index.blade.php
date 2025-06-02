@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="container-fluid px-3 px-sm-4 py-4">
    <!-- Header với Search & Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h2 class="mb-1">{{ $pageTitle }}</h2>
                    <p class="text-muted mb-0">Quản lý các yêu cầu dịch vụ của bạn</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('user.customer.leads.create') }}" class="btn btn--base">
                        <i class="las la-plus me-1"></i> Tạo Lead mới
                    </a>
                    <button class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="las la-filter"></i> Bộ lọc
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="las la-list"></i></div>
                <div class="stat-info">
                    <span class="stat-number">{{ $leads->total() }}</span>
                    <span class="stat-label">Tổng Leads</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stat-card bg-warning">
                <div class="stat-icon"><i class="las la-clock"></i></div>
                <div class="stat-info">
                    <span class="stat-number">{{ $leads->where('status', 'active')->count() }}</span>
                    <span class="stat-label">Đang mở</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="las la-check-circle"></i></div>
                <div class="stat-info">
                    <span class="stat-number">{{ $leads->where('status', 'closed')->count() }}</span>
                    <span class="stat-label">Đã đóng</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="las la-users"></i></div>
                <div class="stat-info">
                    <span class="stat-number">{{ $leads->sum('purchased_count') }}</span>
                    <span class="stat-label">Thợ quan tâm</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="collapse mb-4" id="filterCollapse">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang mở</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Mức độ khẩn cấp</label>
                        <select name="urgency" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Thấp</option>
                            <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Trung bình</option>
                            <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>Cao</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Áp dụng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Leads List -->
    <div class="row">
        @forelse($leads as $lead)
            <div class="col-12 mb-4">
                <div class="lead-card card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Lead Info -->
                            <div class="col-lg-6">
                                <div class="d-flex align-items-start">
                                    <div class="lead-icon me-3">
                                        <i class="las la-tools"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <a href="{{ route('user.customer.leads.show', $lead->id) }}" class="text-decoration-none">
                                                {{ $lead->title }}
                                            </a>
                                        </h6>
                                        <p class="text-muted mb-2 small">{{ Str::limit($lead->description, 100) }}</p>
                                        <div class="d-flex flex-wrap gap-2 small">
                                            <span class="badge bg-light text-dark">
                                                <i class="las la-map-marker me-1"></i>{{ $lead->location }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="las la-tag me-1"></i>{{ $lead->category->name }}
                                            </span>
                                            @if($lead->budget_min || $lead->budget_max)
                                                <span class="badge bg-light text-dark">
                                                    <i class="las la-dollar-sign me-1"></i>{{ $lead->getBudgetRange() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & Stats -->
                            <div class="col-lg-3">
                                <div class="text-center">
                                    <div class="mb-2">
                                        {!! $lead->getStatusBadge() !!}
                                        {!! $lead->getUrgencyBadge() !!}
                                    </div>
                                    <div class="stats-row">
                                        <div class="stat-item">
                                            <strong>{{ $lead->purchased_count }}</strong>
                                            <small class="d-block text-muted">Thợ quan tâm</small>
                                        </div>
                                        @if($lead->expires_at)
                                            <div class="stat-item">
                                                <strong>{{ $lead->expires_at->diffForHumans() }}</strong>
                                                <small class="d-block text-muted">Hết hạn</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-lg-3">
                                <div class="d-flex flex-column gap-2">
                                    <a href="{{ route('user.customer.leads.show', $lead->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="las la-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    @if($lead->status == 'active')
                                        <a href="{{ route('user.customer.leads.edit', $lead->id) }}" class="btn btn-outline-warning btn-sm">
                                            <i class="las la-edit me-1"></i>Chỉnh sửa
                                        </a>
                                        
                                        @if($lead->purchased_count > 0)
                                            <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#selectContractorModal" data-lead-id="{{ $lead->id }}">
                                                <i class="las la-user-check me-1"></i>Chọn thợ
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#closeLeadModal" data-lead-id="{{ $lead->id }}">
                                            <i class="las la-times me-1"></i>Đóng lead
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Progress Bar for active leads -->
                        @if($lead->status == 'active' && $lead->max_contractors > 0)
                            <div class="mt-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Tiến độ tìm thợ</span>
                                    <span>{{ $lead->purchased_count }}/{{ $lead->max_contractors }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($lead->purchased_count / $lead->max_contractors) * 100 }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-12">
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-3">
                        <i class="las la-clipboard-list"></i>
                    </div>
                    <h5>Chưa có lead nào</h5>
                    <p class="text-muted mb-4">Tạo lead đầu tiên để bắt đầu tìm thợ chuyên nghiệp</p>
                    <a href="{{ route('user.customer.leads.create') }}" class="btn btn--base">
                        <i class="las la-plus me-1"></i>Tạo Lead đầu tiên
                    </a>
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

<!-- Close Lead Modal -->
<div class="modal fade" id="closeLeadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đóng Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc muốn đóng lead này không?</p>
                <div class="alert alert-warning">
                    <i class="las la-exclamation-triangle me-1"></i>
                    <strong>Lưu ý:</strong> Sau khi đóng, các thợ sẽ không thể mua lead này nữa.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" class="d-inline" id="closeLeadForm">
                    @csrf
                    <button type="submit" class="btn btn-danger">Đóng lead</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .stat-card {
        background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
        color: white;
        border-radius: 12px;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-card.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); }
    .stat-card.bg-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
    .stat-card.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .lead-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .lead-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-left-color: var(--bs-primary);
    }

    .lead-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark, #0056b3) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .stats-row {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .stat-item {
        text-align: center;
    }

    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 4rem;
        color: var(--bs-gray-400);
    }

    .progress {
        background-color: rgba(0,0,0,0.1);
    }

    .badge {
        font-weight: normal;
    }
</style>
@endpush

@push('script')
<script>
    // Handle close lead modal
    document.addEventListener('DOMContentLoaded', function() {
        const closeModal = document.getElementById('closeLeadModal');
        const closeForm = document.getElementById('closeLeadForm');
        
        closeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const leadId = button.getAttribute('data-lead-id');
            closeForm.action = `{{ route('user.customer.leads.close', '') }}/${leadId}`;
        });
    });

    // Auto refresh notifications
    setInterval(function() {
        // Add notification check here if needed
    }, 30000); // Check every 30 seconds
</script>
@endpush 