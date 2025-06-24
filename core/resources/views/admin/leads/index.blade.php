@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $pageTitle }}</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.leads.analytics') }}" class="btn btn--primary btn-sm">
                        <i class="las la-chart-line"></i> Phân tích
                    </a>
                    <a href="{{ route('admin.leads.notifications') }}" class="btn btn--info btn-sm">
                        <i class="las la-bell"></i> Thông báo
                    </a>
                    <a href="{{ route('admin.leads.companies') }}" class="btn btn--success btn-sm">
                        <i class="las la-building"></i> Công ty
                    </a>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                        <div class="card bg--primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="text-white">{{ $stats['total'] }}</h4>
                                        <p class="mb-0">Tổng Leads</p>
                                    </div>
                                    <div class="dashboard-icon">
                                        <i class="las la-briefcase"></i>
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
                                        <h4 class="text-white">{{ $stats['active'] }}</h4>
                                        <p class="mb-0">Leads Đang Mở</p>
                                    </div>
                                    <div class="dashboard-icon">
                                        <i class="las la-check-circle"></i>
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
                                        <h4 class="text-white">{{ $stats['today'] }}</h4>
                                        <p class="mb-0">Hôm Nay</p>
                                    </div>
                                    <div class="dashboard-icon">
                                        <i class="las la-calendar-day"></i>
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
                                        <h4 class="text-white">{{ number_format($stats['total_revenue']) }}₫</h4>
                                        <p class="mb-0">Doanh Thu</p>
                                    </div>
                                    <div class="dashboard-icon">
                                        <i class="las la-dollar-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <form action="{{ route('admin.leads.index') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang mở</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
                                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Hết hạn</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="category_id" class="form-control">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="urgency" class="form-control">
                                        <option value="">Tất cả mức độ</option>
                                        <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Không khẩn</option>
                                        <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Bình thường</option>
                                        <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>Khẩn cấp</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Từ ngày">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Đến ngày">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn--primary w-100">
                                        <i class="las la-search"></i> Lọc
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Leads Table -->
                <div class="table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Khách hàng</th>
                                <th>Danh mục</th>
                                <th>Địa điểm</th>
                                <th>Ngân sách</th>
                                <th>Thông báo</th>
                                <th>Lượt mua</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                            <tr>
                                <td>
                                    <input type="checkbox" name="leads[]" value="{{ $lead->id }}" class="form-check-input lead-checkbox">
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $lead->id }}</span>
                                </td>
                                <td>
                                    <div class="user">
                                        <div class="user-info">
                                            <a href="{{ route('admin.leads.show', $lead->id) }}" class="fw-bold">
                                                {{ Str::limit($lead->title, 30) }}
                                            </a>
                                            <br>
                                            <small class="text-muted">
                                                {!! $lead->getUrgencyBadge() !!}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($lead->customer)
                                        <div class="user">
                                            <div class="user-info">
                                                <span class="fw-bold">{{ $lead->customer->firstname }} {{ $lead->customer->lastname }}</span>
                                                <br>
                                                <small class="text-muted">{{ $lead->customer->mobile }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">Đã xóa</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $lead->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <i class="las la-map-marker-alt text-primary"></i>
                                    {{ $lead->location }}
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ $lead->getBudgetRange() }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--info">{{ $lead->visibilities_count }}</span>
                                </td>
                                <td>
                                    @if($lead->purchases_count > 0)
                                        <span class="badge badge--success">{{ $lead->purchases_count }}</span>
                                    @else
                                        <span class="badge badge--secondary">0</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $lead->getStatusBadge() !!}
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $lead->created_at->format('d/m/Y') }}</span>
                                    <br>
                                    <small class="text-muted">{{ $lead->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn--secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.leads.show', $lead->id) }}">
                                                    <i class="las la-eye"></i> Xem chi tiết
                                                </a>
                                            </li>
                                            @if($lead->status === 'active')
                                                <li>
                                                    <button class="dropdown-item update-status" data-id="{{ $lead->id }}" data-status="closed">
                                                        <i class="las la-times-circle"></i> Đóng lead
                                                    </button>
                                                </li>
                                            @endif
                                            @if($lead->status === 'closed')
                                                <li>
                                                    <button class="dropdown-item update-status" data-id="{{ $lead->id }}" data-status="active">
                                                        <i class="las la-redo"></i> Mở lại
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">
                                    <i class="las la-frown fs-2 text-muted"></i>
                                    <p class="text-muted">Không có lead nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions -->
                @if($leads->count() > 0)
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <select id="bulkAction" class="form-control me-2" style="width: auto;">
                                <option value="">Chọn thao tác</option>
                                <option value="close">Đóng leads</option>
                                <option value="expire">Hết hạn</option>
                                <option value="delete">Xóa leads</option>
                            </select>
                            <button type="button" id="executeBulkAction" class="btn btn--warning">
                                <i class="las la-cogs"></i> Thực hiện
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{ $leads->appends(request()->all())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p>Bạn có chắc muốn thay đổi trạng thái lead này?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn--primary">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').change(function() {
        $('.lead-checkbox').prop('checked', this.checked);
    });

    // Update status
    $('.update-status').click(function() {
        const leadId = $(this).data('id');
        const status = $(this).data('status');
        
        $('#statusForm').attr('action', `{{ route('admin.leads.status.update', '') }}/${leadId}`);
        $('#statusForm').append(`<input type="hidden" name="status" value="${status}">`);
        $('#statusModal').modal('show');
    });

    // Bulk action
    $('#executeBulkAction').click(function() {
        const action = $('#bulkAction').val();
        const selectedLeads = $('.lead-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (!action) {
            alert('Vui lòng chọn thao tác');
            return;
        }

        if (selectedLeads.length === 0) {
            alert('Vui lòng chọn ít nhất một lead');
            return;
        }

        if (confirm(`Bạn có chắc muốn ${action} ${selectedLeads.length} leads?`)) {
            $.post('{{ route("admin.leads.bulk.action") }}', {
                _token: '{{ csrf_token() }}',
                leads: selectedLeads,
                action: action
            }).done(function(response) {
                location.reload();
            }).fail(function() {
                alert('Có lỗi xảy ra');
            });
        }
    });
});
</script>
@endpush 