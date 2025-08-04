@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card b-radius--10">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ $pageTitle }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.deposits.settings.update', $setting->id) }}" method="POST" enctype="multipart/form-data" id="editDepositSettingForm">
                    @csrf
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Loại phương thức <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-control" required>
                                    <option value="">Chọn loại phương thức</option>
                                    <option value="bank_transfer" {{ old('payment_method', $setting->payment_method) == 'bank_transfer' ? 'selected' : '' }}>
                                        Chuyển khoản ngân hàng
                                    </option>
                                    <option value="momo" {{ old('payment_method', $setting->payment_method) == 'momo' ? 'selected' : '' }}>
                                        Ví MoMo
                                    </option>
                                    <option value="zalopay" {{ old('payment_method', $setting->payment_method) == 'zalopay' ? 'selected' : '' }}>
                                        Ví ZaloPay
                                    </option>
                                    <option value="other" {{ old('payment_method', $setting->payment_method) == 'other' ? 'selected' : '' }}>
                                        Khác
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $setting->name) }}" 
                                       placeholder="VD: Ngân hàng Vietcombank - Chi nhánh Hà Nội" required>
                                <small class="text-muted">Tên hiển thị cho người dùng</small>
                            </div>
                        </div>

                        <!-- Current QR Code Display -->
                        @if($setting->qr_code_image)
                        <div class="col-12 mb-3">
                            <label class="form-label">QR Code hiện tại</label>
                            <div class="current-qr-display">
                                <img src="{{ $setting->getQrCodeUrl() }}" 
                                     alt="Current QR Code" class="current-qr-image">
                                <div class="current-qr-info">
                                    <small class="text-success">
                                        <i class="las la-check-circle"></i> QR Code đang hoạt động
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- QR Code Upload -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    {{ $setting->qr_code_image ? 'Cập nhật QR Code' : 'Upload QR Code' }}
                                </label>
                                <div class="qr-upload-container">
                                    <div class="upload-area" id="uploadArea">
                                        <div class="upload-content">
                                            <i class="las la-qrcode fs-40 text-muted"></i>
                                            <h6 class="mt-2">
                                                {{ $setting->qr_code_image ? 'Kéo thả QR Code mới vào đây' : 'Kéo thả QR Code vào đây' }}
                                            </h6>
                                            <p class="text-muted">hoặc</p>
                                            <button type="button" class="btn btn-sm btn--primary" onclick="$('#qrCodeInput').click()">
                                                {{ $setting->qr_code_image ? 'Chọn file mới' : 'Chọn file' }}
                                            </button>
                                            <input type="file" name="qr_code_image" id="qrCodeInput" class="d-none" 
                                                   accept="image/jpeg,image/png,image/jpg">
                                        </div>
                                        <div class="upload-preview" id="uploadPreview" style="display: none;">
                                            <img id="previewImage" src="" alt="QR Preview">
                                            <button type="button" class="btn btn-sm btn--danger remove-image" onclick="removeQrImage()">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        Định dạng: JPG, JPEG, PNG. Kích thước tối đa: 2MB
                                        @if($setting->qr_code_image)
                                        <br><span class="text-warning">Chọn file mới sẽ thay thế QR Code hiện tại</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bankFields" style="display: none;">
                            <div class="col-12"><hr><h6>Thông tin ngân hàng</h6></div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Tên ngân hàng</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $setting->bank_name) }}" 
                                           placeholder="VD: Ngân hàng TMCP Ngoại thương Việt Nam">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Chi nhánh</label>
                                    <input type="text" name="bank_branch" class="form-control" value="{{ old('bank_branch', $setting->bank_branch) }}" 
                                           placeholder="VD: Chi nhánh Hà Nội">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Số tài khoản</label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $setting->account_number) }}" 
                                           placeholder="VD: 1234567890">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Tên chủ tài khoản</label>
                                    <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $setting->account_name) }}" 
                                           placeholder="VD: NGUYEN VAN A">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Mã SWIFT (nếu có)</label>
                                    <input type="text" name="swift_code" class="form-control" value="{{ old('swift_code', $setting->swift_code) }}" 
                                           placeholder="VD: BFTVVNVX">
                                </div>
                            </div>
                        </div>

                        <!-- E-Wallet Fields -->
                        <div id="ewalletFields" style="display: none;">
                            <div class="col-12"><hr><h6>Thông tin ví điện tử</h6></div>
                            
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="wallet_phone" class="form-control" value="{{ old('wallet_phone', $setting->wallet_phone) }}" 
                                           placeholder="VD: 0987654321">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">Tên chủ ví</label>
                                    <input type="text" name="wallet_name" class="form-control" value="{{ old('wallet_name', $setting->wallet_name) }}" 
                                           placeholder="VD: Nguyen Van A">
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="col-12"><hr><h6>Cấu hình</h6></div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Số tiền tối thiểu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="min_amount" class="form-control" value="{{ old('min_amount', $setting->min_amount) }}" 
                                           min="1000" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Số tiền tối đa <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="max_amount" class="form-control" value="{{ old('max_amount', $setting->max_amount) }}" 
                                           min="1000" required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label">Thời gian xử lý <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="processing_hours" class="form-control" value="{{ old('processing_hours', $setting->processing_hours) }}" 
                                           min="1" max="168" required>
                                    <span class="input-group-text">giờ</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Thứ tự sắp xếp</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $setting->sort_order) }}" 
                                       min="0" placeholder="0">
                                <small class="text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active', $setting->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label">Kích hoạt</label>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Hướng dẫn chuyển khoản</label>
                                <textarea name="instructions" class="form-control" rows="4" 
                                          placeholder="Nhập hướng dẫn chi tiết cho người dùng...">{{ old('instructions', $setting->instructions) }}</textarea>
                                <small class="text-muted">Hướng dẫn này sẽ hiển thị cho người dùng khi chọn phương thức thanh toán</small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Template ghi chú</label>
                                <input type="text" name="note_template" class="form-control" value="{{ old('note_template', $setting->note_template) }}" 
                                       placeholder="VD: NAP [USER_ID] [AMOUNT]">
                                <small class="text-muted">Template ghi chú cho người dùng. Sử dụng [USER_ID] và [AMOUNT] để thay thế tự động</small>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn--primary">
                                    <i class="las la-save"></i> Cập nhật phương thức
                                </button>
                                <a href="{{ route('admin.deposits.settings') }}" class="btn btn--secondary">
                                    <i class="las la-arrow-left"></i> Quay lại
                                </a>
                                @if($setting->qr_code_image)
                                <button type="button" class="btn btn--info" onclick="showCurrentQr()">
                                    <i class="las la-qrcode"></i> Xem QR hiện tại
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Current QR Modal -->
@if($setting->qr_code_image)
<div class="modal fade" id="currentQrModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">QR Code hiện tại - {{ $setting->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ $setting->getQrCodeUrl() }}" 
                     alt="Current QR Code" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Payment method change handler
    $('select[name="payment_method"]').on('change', function() {
        var method = $(this).val();
        
        // Hide all conditional fields
        $('#bankFields, #ewalletFields').hide();
        
        // Show relevant fields
        if (method === 'bank_transfer') {
            $('#bankFields').show();
        } else if (method === 'momo' || method === 'zalopay') {
            $('#ewalletFields').show();
        }
    });

    // Trigger change event on page load
    $('select[name="payment_method"]').trigger('change');

    // QR Code upload handling
    $('#qrCodeInput').on('change', function() {
        handleQrUpload(this.files[0]);
    });

    // Drag and drop for QR upload
    var uploadArea = $('#uploadArea');
    
    uploadArea.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('drag-over');
    });

    uploadArea.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
    });

    uploadArea.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('drag-over');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleQrUpload(files[0]);
        }
    });

    // Form validation
    $('#editDepositSettingForm').on('submit', function(e) {
        var minAmount = parseInt($('input[name="min_amount"]').val());
        var maxAmount = parseInt($('input[name="max_amount"]').val());
        
        if (maxAmount <= minAmount) {
            e.preventDefault();
            notify('error', 'Số tiền tối đa phải lớn hơn số tiền tối thiểu');
            return false;
        }
    });
});

function handleQrUpload(file) {
    if (!file) return;
    
    // Validate file type
    if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
        notify('error', 'Chỉ chấp nhận file JPG, JPEG, PNG');
        return;
    }
    
    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        notify('error', 'Kích thước file không được vượt quá 2MB');
        return;
    }
    
    // Show preview
    var reader = new FileReader();
    reader.onload = function(e) {
        $('#previewImage').attr('src', e.target.result);
        $('.upload-content').hide();
        $('#uploadPreview').show();
    };
    reader.readAsDataURL(file);
}

function removeQrImage() {
    $('#qrCodeInput').val('');
    $('#previewImage').attr('src', '');
    $('#uploadPreview').hide();
    $('.upload-content').show();
}

function showCurrentQr() {
    $('#currentQrModal').modal('show');
}
</script>
@endpush

@push('style')
<style>
.current-qr-display {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
}

.current-qr-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.qr-upload-container {
    margin-bottom: 10px;
}

.upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    background: #fafafa;
    transition: all 0.3s ease;
    position: relative;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #007bff;
    background: #f8f9ff;
}

.upload-area.drag-over {
    border-color: #007bff;
    background: #e3f2fd;
}

.upload-preview {
    position: relative;
    width: 100%;
}

.upload-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.remove-image {
    position: absolute;
    top: -10px;
    right: calc(50% - 110px);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

#bankFields, #ewalletFields {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
}
</style>
@endpush 