@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">{{ $pageTitle }}</h6>
                <div class="d-flex gap-2">
                    <select class="form-control form-control-sm" id="statusFilter" style="width: 150px;">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="completed">Đã hoàn thành</option>
                        <option value="rejected">Đã từ chối</option>
                    </select>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-3 col-sm-6 mb-30">
                        <div class="widget-two style--two bg--warning">
                            <div class="widget-two__icon">
                                <i class="las la-clock"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ $stats['pending'] }}</h3>
                                <p class="text-white">Chờ xử lý</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 mb-30">
                        <div class="widget-two style--two bg--info">
                            <div class="widget-two__icon">
                                <i class="las la-spinner"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ $stats['processing'] }}</h3>
                                <p class="text-white">Đang xử lý</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 mb-30">
                        <div class="widget-two style--two bg--success">
                            <div class="widget-two__icon">
                                <i class="las la-check-circle"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ $stats['completed'] }}</h3>
                                <p class="text-white">Đã hoàn thành</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 mb-30">
                        <div class="widget-two style--two bg--danger">
                            <div class="widget-two__icon">
                                <i class="las la-times-circle"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ $stats['rejected'] }}</h3>
                                <p class="text-white">Đã từ chối</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requests Table -->
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã nạp tiền</th>
                                <th>Người dùng</th>
                                <th>Ví công ty</th>
                                <th>Số tiền</th>
                                <th>Phương thức</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td><span class="fw-bold text-primary">{{ $request->deposit_code }}</span></td>
                                    <td>
                                        <span class="small">
                                            {{ $request->user->fullname ?? $request->user->username }}
                                            <br>
                                            <span class="text-muted">{{ $request->user->email }}</span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="small">
                                            {{ $request->wallet->company->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ number_format($request->amount, 0, ',', '.') }} VNĐ
                                        </span>
                                    </td>
                                    <td>
                                        @switch($request->payment_method)
                                            @case('bank_transfer')
                                                <span class="badge badge--primary">Chuyển khoản</span>
                                                @break
                                            @case('momo')
                                                <span class="badge badge--info">MoMo</span>
                                                @break
                                            @case('zalopay')
                                                <span class="badge badge--warning">ZaloPay</span>
                                                @break
                                            @default
                                                <span class="badge badge--secondary">{{ ucfirst($request->payment_method) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($request->status)
                                            @case('pending')
                                                <span class="badge badge--warning">Chờ xử lý</span>
                                                @break
                                            @case('processing')
                                                <span class="badge badge--info">Đang xử lý</span>
                                                @break
                                            @case('completed')
                                                <span class="badge badge--success">Hoàn thành</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge badge--danger">Từ chối</span>
                                                @break
                                            @default
                                                <span class="badge badge--secondary">{{ ucfirst($request->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="small">
                                            {{ $request->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.deposits.show', $request->id) }}" 
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="las la-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="empty-thumb">
                                            <img src="{{ getImage('assets/images/extra_images/empty.png') }}" alt="empty">
                                            <p class="fs-14">{{ __('Chưa có yêu cầu nạp tiền nào') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($requests->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($requests) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Tìm kiếm theo mã, tên người dùng..." />
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Auto refresh every 30 seconds
    setInterval(function() {
        if ($('#statusFilter').val() === '') {
            location.reload();
        }
    }, 30000);

    // Status filter
    $('#statusFilter').on('change', function() {
        var status = $(this).val();
        var url = new URL(window.location.href);
        
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        
        window.location.href = url.toString();
    });

    // Set current filter value
    var urlParams = new URLSearchParams(window.location.search);
    var currentStatus = urlParams.get('status');
    if (currentStatus) {
        $('#statusFilter').val(currentStatus);
    }
});
</script>
@endpush 