@extends($activeTemplate . 'layouts.auth')

@section('content')
<div class="deposit-create-page">
    <!-- Page Header - matching index page style -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">
                        <i class="las la-plus-circle me-3"></i>
                        Nạp tiền vào ví
                    </h2>
                    <p class="text-muted mb-0">{{ $wallet->company->name }} • Số dư: {{ number_format($wallet->balance, 0, '.', ',') }} VNĐ</p>
                </div>
                <div>
                    <a href="{{ route('user.deposit.index') }}" class="btn btn-outline-secondary">
                        <i class="las la-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('user.deposit.store') }}" method="POST" enctype="multipart/form-data" id="depositForm">
                @csrf
                <input type="hidden" name="wallet_id" value="{{ $wallet->id }}">

                    <!-- Wallet Info Card -->
                    <div class="wallet-info-section mb-4">
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">
                                    <i class="las la-info-circle me-2"></i>
                                    Thông tin ví
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center">
                                            <div class="wallet-avatar">
                                                <i class="las la-building"></i>
                                            </div>
                                            <div class="wallet-details">
                                                <h6 class="mb-1">{{ $wallet->company->name }}</h6>
                                                <p class="text-muted mb-0">{{ $wallet->company->category->name ?? 'Chưa phân loại' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <div class="balance-display">
                                            <span class="balance-label">Số dư hiện tại</span>
                                            <h5 class="balance-amount mb-0">{{ number_format($wallet->balance, 0, '.', ',') }} VNĐ</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="payment-methods-section mb-4">
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">
                                    <i class="las la-credit-card me-2"></i>
                                    Chọn phương thức thanh toán
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($depositSettings->count() > 0)
                                <div class="payment-methods-grid">
                                    @foreach($depositSettings as $setting)
                                    <div class="payment-method" data-setting-id="{{ $setting->id }}">
                                        <input type="radio" name="deposit_setting_id" value="{{ $setting->id }}" 
                                               id="method_{{ $setting->id }}" class="payment-method-input" required>
                                        <label for="method_{{ $setting->id }}" class="payment-method-card">
                                            <div class="payment-method-header">
                                                <div class="payment-method-info">
                                                    <h6 class="payment-method-name">{{ $setting->name }}</h6>
                                                    <p class="payment-method-description">{{ $setting->description }}</p>
                                                </div>
                                                <div class="payment-method-check">
                                                    <i class="las la-check-circle"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="payment-method-details">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="detail-item">
                                                            <span class="detail-label">Số tiền tối thiểu:</span>
                                                            <span class="detail-value">{{ number_format($setting->min_amount, 0, '.', ',') }} VNĐ</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="detail-item">
                                                            <span class="detail-label">Số tiền tối đa:</span>
                                                            <span class="detail-value">{{ number_format($setting->max_amount, 0, '.', ',') }} VNĐ</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="processing-time mt-2">
                                                    <i class="las la-clock text-muted me-1"></i>
                                                    <span class="text-muted">Thời gian xử lý: {{ $setting->processing_time }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="text-center py-4">
                                    <i class="las la-exclamation-triangle text-warning fs-1 mb-3"></i>
                                    <h6 class="text-muted">Hiện tại chưa có phương thức thanh toán nào</h6>
                                    <p class="text-muted">Vui lòng liên hệ admin để kích hoạt phương thức thanh toán</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($depositSettings->count() > 0)
                    <!-- Payment Details (Initially Hidden) -->
                    <div class="payment-details-section mb-4" id="paymentDetails" style="display: none;">
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">
                                    <i class="las la-qrcode me-2"></i>
                                    Thông tin chuyển khoản
                                </h5>
                            </div>
                            <div class="card-body" id="paymentDetailsContent">
                                <!-- Content will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Deposit Form -->
                    <div class="deposit-form-section mb-4">
                        <div class="custom--card">
                            <div class="card-header bg--dark">
                                <h5 class="text-white">
                                    <i class="las la-edit me-2"></i>
                                    Thông tin nạp tiền
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label">Số tiền nạp <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control form--control" name="amount" id="amount" 
                                                   placeholder="Nhập số tiền" min="10000" step="1000" required>
                                            <span class="input-group-text">VNĐ</span>
                                        </div>
                                        <div class="form-text">
                                            <span id="amountRange" class="text-muted">Chọn phương thức để xem giới hạn</span>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="payment_proof" class="form-label">Ảnh xác nhận chuyển khoản (tùy chọn)</label>
                                        <input type="file" class="form-control form--control" name="payment_proof" id="payment_proof" 
                                               accept="image/*">
                                        <div class="form-text text-muted">
                                            Tải lên ảnh chụp màn hình xác nhận chuyển khoản
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Ghi chú (tùy chọn)</label>
                                    <textarea class="form-control form--control" name="notes" id="notes" rows="3" 
                                              placeholder="Thêm ghi chú về giao dịch của bạn..."></textarea>
                                </div>

                                <!-- User ID Display -->
                                <div class="user-id-display mb-4">
                                    <div class="alert alert-info border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="alert-icon me-3">
                                                <i class="las la-user-tag fs-2"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="alert-heading mb-1">Mã người dùng của bạn</h6>
                                                <p class="mb-2">Khi chuyển khoản, vui lòng ghi rõ mã này trong nội dung:</p>
                                                <div class="user-id-code">
                                                    <span class="badge badge--primary fs-5 px-3 py-2">{{ auth()->id() }}</span>
                                                    <button type="button" class="btn btn-link p-0 ms-2" onclick="copyUserId()">
                                                        <i class="las la-copy"></i> Sao chép
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="form-actions">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('user.deposit.index') }}" class="btn btn-secondary">
                                            <i class="las la-arrow-left me-2"></i>Quay lại
                                        </a>
                                        <button type="submit" class="btn btn--base" id="submitBtn">
                                            <i class="las la-paper-plane me-2"></i>Tạo yêu cầu nạp tiền
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Sticky Payment Info (Initially Hidden) -->
            <div class="col-lg-4" id="stickyPaymentInfo" style="display: none;">
                <div class="sticky-top" style="top: 2rem;">
                    <div class="custom--card">
                        <div class="card-header bg--dark">
                            <h6 class="text-white mb-0">
                                <i class="las la-mobile me-2"></i>
                                Hướng dẫn nhanh
                            </h6>
                        </div>
                        <div class="card-body" id="stickyPaymentContent">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    const depositSettings = @json($depositSettings);
    
    // Handle payment method selection
    $('input[name="deposit_setting_id"]').on('change', function() {
        const settingId = $(this).val();
        const setting = depositSettings.find(s => s.id == settingId);
        
        if (setting) {
            showPaymentDetails(setting);
            updateAmountRange(setting);
            showStickyInfo(setting);
        }
    });
    
    function showPaymentDetails(setting) {
        let content = `
            <div class="payment-details-content">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="mb-3">Thông tin chuyển khoản</h6>
                        <div class="bank-info">
                            ${setting.bank_account ? `<div class="info-item mb-2">
                                <span class="info-label">Số tài khoản:</span>
                                <span class="info-value fw-bold">${setting.bank_account}</span>
                                <button type="button" class="btn btn-link p-0 ms-1" onclick="copyToClipboard('${setting.bank_account}')">
                                    <i class="las la-copy"></i>
                                </button>
                            </div>` : ''}
                            
                            ${setting.bank_name ? `<div class="info-item mb-2">
                                <span class="info-label">Ngân hàng:</span>
                                <span class="info-value">${setting.bank_name}</span>
                            </div>` : ''}
                            
                            ${setting.account_holder ? `<div class="info-item mb-2">
                                <span class="info-label">Chủ tài khoản:</span>
                                <span class="info-value">${setting.account_holder}</span>
                            </div>` : ''}
                            
                            ${setting.phone_number ? `<div class="info-item mb-2">
                                <span class="info-label">Số điện thoại:</span>
                                <span class="info-value fw-bold">${setting.phone_number}</span>
                                <button type="button" class="btn btn-link p-0 ms-1" onclick="copyToClipboard('${setting.phone_number}')">
                                    <i class="las la-copy"></i>
                                </button>
                            </div>` : ''}
                        </div>
                        
                        ${setting.note_template ? `<div class="note-template mt-3">
                            <h6 class="mb-2">Mẫu nội dung chuyển khoản:</h6>
                            <div class="template-box">
                                <code>${setting.note_template.replace('[USER_ID]', '{{ auth()->id() }}')}</code>
                                <button type="button" class="btn btn-link p-0 float-end" onclick="copyToClipboard('${setting.note_template.replace('[USER_ID]', '{{ auth()->id() }}')}')">
                                    <i class="las la-copy"></i>
                                </button>
                            </div>
                        </div>` : ''}
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <h6 class="mb-3">Mã QR thanh toán</h6>
                        <div class="qr-code-container">
                            ${setting.qr_code_path ? 
                                `<img src="${setting.qr_code_path}" alt="QR Code" class="qr-code-image">` :
                                `<div class="qr-placeholder">
                                    <i class="las la-qrcode fs-1 text-muted"></i>
                                    <p class="text-muted mt-2">QR Code sẽ sớm được cập nhật</p>
                                </div>`
                            }
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#paymentDetailsContent').html(content);
        $('#paymentDetails').slideDown();
    }
    
    function updateAmountRange(setting) {
        const minAmount = parseInt(setting.min_amount).toLocaleString('vi-VN');
        const maxAmount = parseInt(setting.max_amount).toLocaleString('vi-VN');
        $('#amountRange').text(`Từ ${minAmount} VNĐ đến ${maxAmount} VNĐ`);
        
        $('#amount').attr('min', setting.min_amount);
        $('#amount').attr('max', setting.max_amount);
    }
    
    function showStickyInfo(setting) {
        let content = `
            <div class="sticky-payment-content">
                <div class="steps-guide">
                    <h6 class="mb-3">Các bước thực hiện:</h6>
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">Quét mã QR hoặc chuyển khoản theo thông tin</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">Ghi đúng mã người dùng: <strong>{{ auth()->id() }}</strong></div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">Nhập số tiền đã chuyển vào form</div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">Gửi yêu cầu và chờ xác nhận</div>
                    </div>
                </div>
                
                <div class="quick-copy mt-4">
                    <h6 class="mb-2">Sao chép nhanh:</h6>
                    ${setting.bank_account ? `<div class="copy-item" onclick="copyToClipboard('${setting.bank_account}')">
                        <span>STK: ${setting.bank_account}</span>
                        <i class="las la-copy"></i>
                    </div>` : ''}
                    
                    ${setting.phone_number ? `<div class="copy-item" onclick="copyToClipboard('${setting.phone_number}')">
                        <span>SĐT: ${setting.phone_number}</span>
                        <i class="las la-copy"></i>
                    </div>` : ''}
                    
                    <div class="copy-item" onclick="copyUserId()">
                        <span>Mã User: {{ auth()->id() }}</span>
                        <i class="las la-copy"></i>
                    </div>
                </div>
            </div>
        `;
        
        $('#stickyPaymentContent').html(content);
        $('#stickyPaymentInfo').slideDown();
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showToast('Đã sao chép: ' + text, 'success');
    });
}

function copyUserId() {
    copyToClipboard('{{ auth()->id() }}');
}

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = $(`
        <div class="toast-notification ${type}">
            <i class="las la-check-circle me-2"></i>
            ${message}
        </div>
    `);
    
    $('body').append(toast);
    toast.addClass('show');
    
    setTimeout(() => {
        toast.removeClass('show');
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}
</script>
@endpush

@push('style')
<style>
/* Deposit Create Page Styles - Adjusted for Auth Layout */
.deposit-create-page {
    background: var(--section-bg, #f8f9fa);
    padding: 0; /* Remove padding since auth layout handles spacing */
}

/* Custom Card Enhancements */
.custom--card {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: none;
}

.card-header.bg--dark {
    background: linear-gradient(135deg, #374151 0%, #1f2937 100%) !important;
    border-bottom: none;
}

/* Wallet Info */
.wallet-avatar {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #3b82f6, #1e40af);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    font-size: 1.5rem;
}

.balance-label {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-bottom: 0.25rem;
}

.balance-amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #059669;
}

/* Payment Methods */
.payment-methods-grid {
    display: grid;
    gap: 1rem;
}

.payment-method-input {
    display: none;
}

.payment-method-card {
    display: block;
    padding: 1.5rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 0;
}

.payment-method-card:hover {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.payment-method-input:checked + .payment-method-card {
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.05);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.payment-method-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.payment-method-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1f2937;
}

.payment-method-description {
    color: #6b7280;
    margin-bottom: 0;
    font-size: 0.875rem;
}

.payment-method-check {
    color: #3b82f6;
    font-size: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.payment-method-input:checked + .payment-method-card .payment-method-check {
    opacity: 1;
}

.payment-method-details {
    border-top: 1px solid #f3f4f6;
    padding-top: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.detail-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.detail-value {
    font-weight: 600;
    color: #1f2937;
}

/* Payment Details */
.bank-info .info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f3f4f6;
}

.info-label {
    font-size: 0.875rem;
    color: #6b7280;
}

.info-value {
    color: #1f2937;
}

.template-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 0.75rem;
    position: relative;
}

.template-box code {
    background: none;
    color: #e83e8c;
    font-size: 0.875rem;
}

.qr-code-container {
    text-align: center;
}

.qr-code-image {
    max-width: 200px;
    width: 100%;
    height: auto;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.qr-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 2px dashed #d1d5db;
}

/* User ID Display */
.user-id-display .alert {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
}

.alert-icon {
    color: #3b82f6;
}

.user-id-code {
    display: flex;
    align-items: center;
}

/* Sticky Payment Info */
.steps-guide .step-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.step-number {
    width: 24px;
    height: 24px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 600;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

.step-content {
    font-size: 0.875rem;
    color: #4b5563;
    line-height: 1.4;
}

.quick-copy .copy-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.quick-copy .copy-item:hover {
    background: #e9ecef;
    transform: translateX(2px);
}

/* Form Controls */
.form--control {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form--control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Toast Notifications */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #10b981;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 9999;
}

.toast-notification.show {
    transform: translateX(0);
}

.toast-notification.success {
    background: #10b981;
}

.toast-notification.info {
    background: #3b82f6;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .payment-method-card {
        padding: 1rem;
    }
    
    .wallet-avatar {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
    
    .qr-code-image {
        max-width: 150px;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .form-actions .d-flex {
        flex-direction: column-reverse;
        gap: 0.75rem !important;
    }
}
</style>
@endpush 