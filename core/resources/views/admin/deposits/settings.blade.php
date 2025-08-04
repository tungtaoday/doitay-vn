@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">{{ $pageTitle }}</h6>
                <a href="{{ route('admin.deposits.settings.create') }}" class="btn btn-sm btn--primary">
                    <i class="las la-plus"></i> Thêm phương thức mới
                </a>
            </div>

            <div class="card-body">
                @if($settings->count() > 0)
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên phương thức</th>
                                <th>Loại</th>
                                <th>QR Code</th>
                                <th>Số tiền</th>
                                <th>Thời gian xử lý</th>
                                <th>Trạng thái</th>
                                <th>Thứ tự</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settings as $index => $setting)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @switch($setting->payment_method)
                                                @case('bank_transfer')
                                                    <i class="las la-university text-primary fs-20 me-2"></i>
                                                    @break
                                                @case('momo')
                                                    <i class="las la-mobile text-info fs-20 me-2"></i>
                                                    @break
                                                @case('zalopay')
                                                    <i class="las la-wallet text-warning fs-20 me-2"></i>
                                                    @break
                                                @default
                                                    <i class="las la-credit-card text-secondary fs-20 me-2"></i>
                                            @endswitch
                                            <div>
                                                <div class="fw-bold">{{ $setting->name }}</div>
                                                @if($setting->bank_name)
                                                    <small class="text-muted">{{ $setting->bank_name }}</small>
                                                @endif
                                                @if($setting->account_number)
                                                    <br><small class="text-muted">STK: {{ $setting->account_number }}</small>
                                                @endif
                                                @if($setting->wallet_phone)
                                                    <br><small class="text-muted">SĐT: {{ $setting->wallet_phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($setting->payment_method)
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
                                                <span class="badge badge--secondary">{{ ucfirst($setting->payment_method) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        @if($setting->qr_code_image)
                                            <img src="{{ $setting->getQrCodeUrl() }}" 
                                                 alt="QR Code" class="qr-preview"
                                                 onclick="showQrModal('{{ $setting->getQrCodeUrl() }}', '{{ $setting->name }}')">
                                            <br><small class="text-success">Có QR</small>
                                        @else
                                            <i class="las la-times text-danger fs-20"></i>
                                            <br><small class="text-muted">Chưa có</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><strong>Min:</strong> {{ number_format($setting->min_amount, 0, ',', '.') }} VNĐ</div>
                                            <div><strong>Max:</strong> {{ number_format($setting->max_amount, 0, ',', '.') }} VNĐ</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge--dark">{{ $setting->processing_hours }}h</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   {{ $setting->is_active ? 'checked' : '' }}
                                                   data-id="{{ $setting->id }}"
                                                   data-url="{{ route('admin.deposits.settings.toggle', $setting->id) }}">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge--info">{{ $setting->sort_order }}</span>
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.deposits.settings.edit', $setting->id) }}" 
                                               class="btn btn-sm btn-outline--primary" title="Chỉnh sửa">
                                                <i class="las la-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline--danger" 
                                                    onclick="deleteSettingModal({{ $setting->id }}, '{{ $setting->name }}')" 
                                                    title="Xóa">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="empty-thumb">
                        <img src="{{ getImage('assets/images/extra_images/empty.png') }}" alt="empty">
                        <p class="fs-14 mt-3">Chưa có phương thức thanh toán nào</p>
                        <a href="{{ route('admin.deposits.settings.create') }}" class="btn btn--primary">
                            <i class="las la-plus"></i> Thêm phương thức đầu tiên
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="qrModalImage" src="" alt="QR Code" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa phương thức thanh toán "<span id="deleteSettingName"></span>"?</p>
                <div class="alert alert-warning">
                    <i class="las la-exclamation-triangle"></i>
                    Hành động này không thể hoàn tác!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<x-search-form placeholder="Tìm kiếm phương thức..." />
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Status toggle
    $('.status-toggle').on('change', function() {
        var checkbox = $(this);
        var url = checkbox.data('url');
        var isActive = checkbox.is(':checked');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    notify('success', response.message || 'Cập nhật trạng thái thành công');
                } else {
                    // Revert checkbox state
                    checkbox.prop('checked', !isActive);
                    notify('error', response.message || 'Có lỗi xảy ra');
                }
            },
            error: function() {
                // Revert checkbox state
                checkbox.prop('checked', !isActive);
                notify('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
            }
        });
    });
});

function showQrModal(imageSrc, settingName) {
    $('#qrModalImage').attr('src', imageSrc);
    $('#qrModalTitle').text('QR Code - ' + settingName);
    $('#qrModal').modal('show');
}

function deleteSettingModal(id, name) {
    $('#deleteSettingName').text(name);
    $('#deleteForm').attr('action', '{{ route("admin.deposits.settings.destroy", ":id") }}'.replace(':id', id));
    $('#deleteModal').modal('show');
}
</script>
@endpush

@push('style')
<style>
.qr-preview {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 4px;
    cursor: pointer;
    transition: transform 0.2s;
}

.qr-preview:hover {
    transform: scale(1.1);
}

.status-toggle {
    cursor: pointer;
}

.button--group .btn {
    margin-right: 5px;
}

.button--group .btn:last-child {
    margin-right: 0;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}
</style>
@endpush 