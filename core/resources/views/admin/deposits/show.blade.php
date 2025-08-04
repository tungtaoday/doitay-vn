@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Request Details Card -->
        <div class="card b-radius--10 mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ $pageTitle }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Mã nạp tiền</label>
                                <div class="form-control-static">
                                    <span class="badge badge--primary fs-16">{{ $request->deposit_code }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Trạng thái</label>
                                <div class="form-control-static">
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
                                    @endswitch
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Người dùng</label>
                                <div class="form-control-static">
                                    {{ $request->user->fullname ?? $request->user->username }}
                                    <br><small class="text-muted">{{ $request->user->email }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Ví công ty</label>
                                <div class="form-control-static">
                                    {{ $request->wallet->company->name ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Số tiền</label>
                                <div class="form-control-static">
                                    <span class="fs-18 fw-bold text-success">
                                        {{ number_format($request->amount, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Phương thức thanh toán</label>
                                <div class="form-control-static">
                                    @switch($request->payment_method)
                                        @case('bank_transfer')
                                            <span class="badge badge--primary">Chuyển khoản ngân hàng</span>
                                            @break
                                        @case('momo')
                                            <span class="badge badge--info">Ví MoMo</span>
                                            @break
                                        @case('zalopay')
                                            <span class="badge badge--warning">Ví ZaloPay</span>
                                            @break
                                        @default
                                            <span class="badge badge--secondary">{{ ucfirst($request->payment_method) }}</span>
                                    @endswitch
                                </div>
                            </div>
                            
                            @if($request->bank_account_name)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Tên tài khoản</label>
                                <div class="form-control-static">{{ $request->bank_account_name }}</div>
                            </div>
                            @endif
                            
                            @if($request->bank_account_number)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Số tài khoản</label>
                                <div class="form-control-static">{{ $request->bank_account_number }}</div>
                            </div>
                            @endif
                            
                            @if($request->bank_name)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Ngân hàng</label>
                                <div class="form-control-static">{{ $request->bank_name }}</div>
                            </div>
                            @endif
                            
                            @if($request->transaction_reference)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Mã giao dịch</label>
                                <div class="form-control-static">{{ $request->transaction_reference }}</div>
                            </div>
                            @endif
                            
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Ngày tạo</label>
                                <div class="form-control-static">{{ $request->created_at->format('d/m/Y H:i:s') }}</div>
                            </div>
                            
                            @if($request->payment_date)
                            <div class="col-sm-6 mb-3">
                                <label class="form-label fw-bold">Ngày thanh toán</label>
                                <div class="form-control-static">{{ $request->payment_date->format('d/m/Y H:i:s') }}</div>
                            </div>
                            @endif
                            
                            @if($request->user_notes)
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Ghi chú từ người dùng</label>
                                <div class="form-control-static">
                                    <div class="bg-light p-3 rounded">{{ $request->user_notes }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        @if($request->payment_proof)
                        <div class="text-center mb-4">
                            <label class="form-label fw-bold">Ảnh minh chứng</label>
                            <div class="payment-proof-container">
                                <img src="{{ asset('storage/deposit_proofs/' . $request->payment_proof) }}" 
                                     alt="Payment Proof" class="img-fluid rounded border"
                                     style="max-height: 300px; cursor: pointer;"
                                     onclick="showImageModal(this.src)">
                                <br>
                                <small class="text-muted">Click để xem ảnh lớn</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->processedBy)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Được xử lý bởi</label>
                            <div class="form-control-static">
                                {{ $request->processedBy->name }}
                                <br><small class="text-muted">{{ $request->processed_at->format('d/m/Y H:i:s') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->admin_notes)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú admin</label>
                            <div class="form-control-static">
                                <div class="bg-light p-3 rounded">{{ $request->admin_notes }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($request->rejection_reason)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lý do từ chối</label>
                            <div class="form-control-static">
                                <div class="bg-danger-light p-3 rounded text-danger">{{ $request->rejection_reason }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Process Request Card -->
        @if(in_array($request->status, ['pending', 'processing']))
        <div class="card b-radius--10">
            <div class="card-header">
                <h6 class="card-title mb-0">Xử lý yêu cầu</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.deposits.process', $request->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thao tác <span class="text-danger">*</span></label>
                            <select name="action" class="form-control" required>
                                <option value="">Chọn thao tác</option>
                                @if($request->status === 'pending')
                                    <option value="processing">Chuyển sang đang xử lý</option>
                                @endif
                                <option value="approve">Duyệt yêu cầu</option>
                                <option value="reject">Từ chối yêu cầu</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3" id="rejection-reason-container" style="display: none;">
                            <label class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                            <select name="rejection_reason" class="form-control">
                                <option value="">Chọn lý do</option>
                                <option value="Thông tin chuyển khoản không đúng">Thông tin chuyển khoản không đúng</option>
                                <option value="Ảnh minh chứng không rõ ràng">Ảnh minh chứng không rõ ràng</option>
                                <option value="Số tiền không khớp">Số tiền không khớp</option>
                                <option value="Giao dịch không tồn tại">Giao dịch không tồn tại</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label class="form-label">Ghi chú admin</label>
                            <textarea name="admin_notes" class="form-control" rows="3" 
                                      placeholder="Thêm ghi chú cho yêu cầu này..."></textarea>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn--primary">
                                <i class="las la-check-circle"></i> Xử lý yêu cầu
                            </button>
                            <a href="{{ route('admin.deposits.requests') }}" class="btn btn--secondary">
                                <i class="las la-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @else
        <div class="text-center">
            <a href="{{ route('admin.deposits.requests') }}" class="btn btn--primary">
                <i class="las la-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ảnh minh chứng thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Payment Proof" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Show/hide rejection reason based on action
    $('select[name="action"]').on('change', function() {
        var action = $(this).val();
        var rejectionContainer = $('#rejection-reason-container');
        var rejectionSelect = $('select[name="rejection_reason"]');
        
        if (action === 'reject') {
            rejectionContainer.show();
            rejectionSelect.attr('required', true);
        } else {
            rejectionContainer.hide();
            rejectionSelect.attr('required', false);
            rejectionSelect.val('');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        var action = $('select[name="action"]').val();
        
        if (!action) {
            e.preventDefault();
            notify('error', 'Vui lòng chọn thao tác');
            return false;
        }
        
        if (action === 'reject' && !$('select[name="rejection_reason"]').val()) {
            e.preventDefault();
            notify('error', 'Vui lòng chọn lý do từ chối');
            return false;
        }
        
        // Confirm action
        var confirmMessage = '';
        switch(action) {
            case 'processing':
                confirmMessage = 'Chuyển yêu cầu sang trạng thái đang xử lý?';
                break;
            case 'approve':
                confirmMessage = 'Duyệt yêu cầu nạp tiền này? Số tiền sẽ được cộng vào ví công ty.';
                break;
            case 'reject':
                confirmMessage = 'Từ chối yêu cầu nạp tiền này?';
                break;
        }
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return false;
        }
    });
});

function showImageModal(src) {
    $('#modalImage').attr('src', src);
    $('#imageModal').modal('show');
}
</script>
@endpush

@push('style')
<style>
.form-control-static {
    padding: 8px 0;
    font-size: 14px;
}

.payment-proof-container img {
    transition: transform 0.2s;
}

.payment-proof-container img:hover {
    transform: scale(1.05);
}

.bg-danger-light {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
</style>
@endpush 