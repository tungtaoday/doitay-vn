@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="las la-comments"></i>
                                    Cài đặt Zalo Chat Widget
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.settings.zalo.chat.update') }}" method="POST">
                                    @csrf
                                    
                                    <div class="row">
                                        <!-- Zalo Phone Number -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-phone"></i>
                                                    Số điện thoại Zalo
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-lg" 
                                                       name="zalo_phone" 
                                                       value="{{ gs('zalo_phone') ?? '0901234567' }}"
                                                       placeholder="0901234567"
                                                       required>
                                                <small class="form-text text-muted">
                                                    Số điện thoại Zalo của bạn để khách hàng liên hệ
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Zalo Name -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-user"></i>
                                                    Tên hiển thị
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-lg" 
                                                       name="zalo_name" 
                                                       value="{{ gs('zalo_name') ?? 'Tư vấn viên' }}"
                                                       placeholder="Tư vấn viên"
                                                       required>
                                                <small class="form-text text-muted">
                                                    Tên hiển thị trong widget chat
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Zalo Avatar -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-image"></i>
                                                    Avatar Zalo
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" 
                                                           class="form-control form-control-lg" 
                                                           name="zalo_avatar" 
                                                           value="{{ gs('zalo_avatar') ?? asset('assets/images/zalo-avatar.jpg') }}"
                                                           placeholder="URL ảnh avatar hoặc để trống để dùng mặc định"
                                                           id="zalo_avatar_input">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary" 
                                                            onclick="openFileManager('zalo_avatar_input')">
                                                        <i class="las la-folder-open"></i>
                                                    </button>
                                                </div>
                                                <small class="form-text text-muted">
                                                    URL ảnh avatar cho widget chat. Để trống sẽ sử dụng file mặc định từ public/assets/images/zalo-avatar.jpg
                                                </small>
                                                <div class="mt-2">
                                                    <small class="text-info">
                                                        <i class="las la-info-circle"></i>
                                                        File mặc định: <code>public/assets/images/zalo-avatar.jpg</code>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Zalo Status -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-circle"></i>
                                                    Trạng thái hoạt động
                                                </label>
                                                <select class="form-control form-control-lg" name="zalo_online">
                                                    <option value="1" {{ (gs('zalo_online') ?? true) ? 'selected' : '' }}>
                                                        Đang hoạt động
                                                    </option>
                                                    <option value="0" {{ !(gs('zalo_online') ?? true) ? 'selected' : '' }}>
                                                        Không hoạt động
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    Hiển thị trạng thái online/offline
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Default Message -->
                                    <div class="form-group">
                                        <label class="form-control-label">
                                            <i class="las la-comment"></i>
                                            Tin nhắn mặc định
                                        </label>
                                        <textarea class="form-control form-control-lg" 
                                                  name="zalo_message" 
                                                  rows="3"
                                                  placeholder="Xin chào! Tôi có thể giúp gì cho bạn?"
                                                  required>{{ gs('zalo_message') ?? 'Xin chào! Tôi có thể giúp gì cho bạn?' }}</textarea>
                                        <small class="form-text text-muted">
                                            Tin nhắn chào mừng mặc định khi khách hàng mở chat
                                        </small>
                                    </div>

                                    <!-- Widget Position -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-arrows-alt"></i>
                                                    Vị trí hiển thị
                                                </label>
                                                <select class="form-control form-control-lg" name="zalo_position">
                                                    <option value="bottom-right" {{ (gs('zalo_position') ?? 'bottom-right') === 'bottom-right' ? 'selected' : '' }}>
                                                        Góc phải dưới
                                                    </option>
                                                    <option value="bottom-left" {{ (gs('zalo_position') ?? 'bottom-right') === 'bottom-left' ? 'selected' : '' }}>
                                                        Góc trái dưới
                                                    </option>
                                                    <option value="top-right" {{ (gs('zalo_position') ?? 'bottom-right') === 'top-right' ? 'selected' : '' }}>
                                                        Góc phải trên
                                                    </option>
                                                    <option value="top-left" {{ (gs('zalo_position') ?? 'bottom-right') === 'top-left' ? 'selected' : '' }}>
                                                        Góc trái trên
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    Vị trí hiển thị của widget chat
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Widget Size -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-expand-arrows-alt"></i>
                                                    Kích thước nút chat
                                                </label>
                                                <select class="form-control form-control-lg" name="zalo_button_size">
                                                    <option value="small" {{ (gs('zalo_button_size') ?? 'medium') === 'small' ? 'selected' : '' }}>
                                                        Nhỏ (50px)
                                                    </option>
                                                    <option value="medium" {{ (gs('zalo_button_size') ?? 'medium') === 'medium' ? 'selected' : '' }}>
                                                        Vừa (60px)
                                                    </option>
                                                    <option value="large" {{ (gs('zalo_button_size') ?? 'medium') === 'large' ? 'selected' : '' }}>
                                                        Lớn (70px)
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    Kích thước của nút chat floating
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Advanced Settings -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="las la-cog"></i>
                                                Cài đặt nâng cao
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <!-- Auto-hide timeout -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">
                                                            <i class="las la-clock"></i>
                                                            Tự động ẩn sau (phút)
                                                        </label>
                                                        <input type="number" 
                                                               class="form-control form-control-lg" 
                                                               name="zalo_auto_hide" 
                                                               value="{{ gs('zalo_auto_hide') ?? 5 }}"
                                                               min="1" 
                                                               max="60"
                                                               placeholder="5">
                                                        <small class="form-text text-muted">
                                                            Tự động ẩn chat sau khi không hoạt động (0 = không tự ẩn)
                                                        </small>
                                                    </div>
                                                </div>

                                                <!-- Show on mobile -->
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">
                                                            <i class="las la-mobile-alt"></i>
                                                            Hiển thị trên mobile
                                                        </label>
                                                        <select class="form-control form-control-lg" name="zalo_show_mobile">
                                                            <option value="1" {{ (gs('zalo_show_mobile') ?? 1) ? 'selected' : '' }}>
                                                                Có
                                                            </option>
                                                            <option value="0" {{ !(gs('zalo_show_mobile') ?? 1) ? 'selected' : '' }}>
                                                                Không
                                                            </option>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            Hiển thị widget chat trên thiết bị di động
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Custom CSS -->
                                            <div class="form-group">
                                                <label class="form-control-label">
                                                    <i class="las la-code"></i>
                                                    CSS tùy chỉnh
                                                </label>
                                                <textarea class="form-control form-control-lg" 
                                                          name="zalo_custom_css" 
                                                          rows="4"
                                                          placeholder="/* CSS tùy chỉnh cho Zalo chat widget */">{{ gs('zalo_custom_css') ?? '' }}</textarea>
                                                <small class="form-text text-muted">
                                                    CSS tùy chỉnh để thay đổi giao diện widget (tùy chọn)
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="las la-eye"></i>
                                                Xem trước
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="zalo-preview">
                                                <div class="zalo-preview-button" style="
                                                    width: {{ gs('zalo_button_size') === 'small' ? '50px' : (gs('zalo_button_size') === 'large' ? '70px' : '60px') }};
                                                    height: {{ gs('zalo_button_size') === 'small' ? '50px' : (gs('zalo_button_size') === 'large' ? '70px' : '60px') }};
                                                    background: linear-gradient(135deg, #00A6FF 0%, #0088CC 100%);
                                                    border-radius: 50%;
                                                    display: inline-flex;
                                                    align-items: center;
                                                    justify-content: center;
                                                    color: white;
                                                    font-size: {{ gs('zalo_button_size') === 'small' ? '20px' : (gs('zalo_button_size') === 'large' ? '28px' : '24px') }};
                                                    box-shadow: 0 4px 20px rgba(0, 166, 255, 0.3);
                                                ">
                                                    <i class="las la-comments"></i>
                                                </div>
                                                <p class="mt-3 text-muted">
                                                    Nút chat sẽ hiển thị ở vị trí: 
                                                    <strong>{{ gs('zalo_position') ?? 'bottom-right' }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn--primary btn-lg">
                                            <i class="las la-save"></i>
                                            Lưu cài đặt
                                        </button>
                                        
                                        <button type="button" class="btn btn--info btn-lg ml-2" onclick="testZaloChat()">
                                            <i class="las la-play"></i>
                                            Test Widget
                                        </button>
                                        
                                        <button type="button" class="btn btn--dark btn-lg ml-2" onclick="resetZaloSettings()">
                                            <i class="las la-undo"></i>
                                            Khôi phục mặc định
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Zalo Chat Modal -->
    <div class="modal fade" id="testZaloModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="las la-comments"></i>
                        Test Zalo Chat Widget
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i>
                        Widget chat sẽ hiển thị ở góc màn hình. Bạn có thể test các tính năng chat.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Thông tin cấu hình:</h6>
                            <ul class="list-unstyled">
                                <li><strong>Số điện thoại:</strong> {{ gs('zalo_phone') ?? '0901234567' }}</li>
                                <li><strong>Tên:</strong> {{ gs('zalo_name') ?? 'Tư vấn viên' }}</li>
                                <li><strong>Vị trí:</strong> {{ gs('zalo_position') ?? 'bottom-right' }}</li>
                                <li><strong>Kích thước:</strong> {{ gs('zalo_button_size') ?? 'medium' }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Hướng dẫn test:</h6>
                            <ol>
                                <li>Click vào nút chat để mở widget</li>
                                <li>Gửi tin nhắn test</li>
                                <li>Kiểm tra responsive trên mobile</li>
                                <li>Test các nút quick action</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="showZaloWidget()">Hiển thị Widget</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
.zalo-preview {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.zalo-preview-button {
    transition: all 0.3s ease;
}

.zalo-preview-button:hover {
    transform: scale(1.1);
}

.form-control-label {
    font-weight: 600;
    color: #333;
}

.form-control-label i {
    color: #00A6FF;
    margin-right: 8px;
}

.card-header h6 {
    color: #00A6FF;
}

.card-header h6 i {
    margin-right: 8px;
}
</style>
@endpush

@push('script')
<script>
// Test Zalo Chat Widget
function testZaloChat() {
    $('#testZaloModal').modal('show');
}

// Show Zalo Widget
function showZaloWidget() {
    // Create temporary widget for testing
    if (!document.getElementById('temp-zalo-widget')) {
        const widget = document.createElement('div');
        widget.id = 'temp-zalo-widget';
        widget.innerHTML = `
            <div style="
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 9999;
                background: #00A6FF;
                color: white;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                cursor: pointer;
                animation: slideIn 0.3s ease;
            " onclick="this.remove()">
                <i class="las la-comments" style="font-size: 24px;"></i>
                <div style="margin-top: 8px; font-size: 12px;">Click để đóng</div>
            </div>
        `;
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(widget);
        
        // Auto remove after 10 seconds
        setTimeout(() => {
            if (widget.parentNode) {
                widget.remove();
            }
        }, 10000);
    }
}

// Reset Zalo Settings
function resetZaloSettings() {
    if (confirm('Bạn có chắc muốn khôi phục cài đặt mặc định?')) {
        // Reset form fields
        document.querySelector('input[name="zalo_phone"]').value = '0901234567';
        document.querySelector('input[name="zalo_name"]').value = 'Tư vấn viên';
        document.querySelector('input[name="zalo_avatar"]').value = '{{ asset("assets/images/zalo-avatar.jpg") }}';
        document.querySelector('select[name="zalo_online"]').value = '1';
        document.querySelector('textarea[name="zalo_message"]').value = 'Xin chào! Tôi có thể giúp gì cho bạn?';
        document.querySelector('select[name="zalo_position"]').value = 'bottom-right';
        document.querySelector('select[name="zalo_button_size"]').value = 'medium';
        document.querySelector('input[name="zalo_auto_hide"]').value = '5';
        document.querySelector('select[name="zalo_show_mobile"]').value = '1';
        document.querySelector('textarea[name="zalo_custom_css"]').value = '';
        
        // Update preview
        updatePreview();
    }
}

// Update preview when form changes
document.addEventListener('DOMContentLoaded', function() {
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', updatePreview);
    });
});

function updatePreview() {
    const buttonSize = document.querySelector('select[name="zalo_button_size"]').value;
    const position = document.querySelector('select[name="zalo_position"]').value;
    
    const previewButton = document.querySelector('.zalo-preview-button');
    if (previewButton) {
        const size = buttonSize === 'small' ? '50px' : (buttonSize === 'large' ? '70px' : '60px');
        const fontSize = buttonSize === 'small' ? '20px' : (buttonSize === 'large' ? '28px' : '24px');
        
        previewButton.style.width = size;
        previewButton.style.height = size;
        previewButton.style.fontSize = fontSize;
    }
    
    const positionText = document.querySelector('.zalo-preview p strong');
    if (positionText) {
        positionText.textContent = position;
    }
}

// File manager integration
function openFileManager(inputId) {
    // This should integrate with your existing file manager
    // For now, we'll use a simple prompt
    const url = prompt('Nhập URL ảnh avatar:');
    if (url) {
        document.getElementById(inputId).value = url;
    }
}
</script>
@endpush 