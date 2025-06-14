@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Progress Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                        <div class="step-indicator active" id="progress-1">
                            <div class="step-circle">1</div>
                            <span class="step-label">Thông tin cơ bản</span>
                        </div>
                        <div class="step-divider"></div>
                        <div class="step-indicator" id="progress-2">
                            <div class="step-circle">2</div>
                            <span class="step-label">Địa chỉ & Mô tả</span>
                        </div>
                        <div class="step-divider"></div>
                        <div class="step-indicator" id="progress-3">
                            <div class="step-circle">3</div>
                            <span class="step-label">Dịch vụ & Giờ làm</span>
                        </div>
                        <div class="step-divider"></div>
                        <div class="step-indicator" id="progress-4">
                            <div class="step-circle">4</div>
                            <span class="step-label">Hình ảnh</span>
                        </div>
                        <div class="step-divider"></div>
                        <div class="step-indicator" id="progress-5">
                            <div class="step-circle">5</div>
                            <span class="step-label">Hoàn thành</span>
                        </div>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient text-white text-center p-4">
                        <h3 class="mb-2">🔧 Tạo Hồ Sơ Thợ V2</h3>
                        <p class="mb-0 opacity-90">Phiên bản cải tiến với wizard 5 bước và tính năng mới</p>
                    </div>

                    <div class="card-body p-5">
                        <form id="companyFormV2" action="{{ route('company.store.v2') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Step 1: Basic Information -->
                            <div class="form-step active" id="step1">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">📋 Thông Tin Cơ Bản</h4>
                                    <p class="text-muted">Tạo thương hiệu chuyên nghiệp của bạn</p>
                                </div>

                                <div class="row">
                                    <!-- Company Logo -->
                                    <div class="col-lg-4 text-center mb-4">
                                        <div class="company-logo-upload">
                                            <div class="logo-preview" onclick="document.getElementById('companyLogo').click()">
                                                <img id="logoPreview" src="{{ asset('assets/images/default-company.jpg') }}" alt="Company Logo">
                                                <div class="upload-overlay">
                                                    <i class="fas fa-camera fa-2x"></i>
                                                    <p class="mt-2">Tải Logo</p>
                                                </div>
                                            </div>
                                            <input type="file" id="companyLogo" name="image" accept="image/*" hidden>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('companyLogo').click()">
                                                <i class="fas fa-upload"></i> Chọn Logo
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Company Details -->
                                    <div class="col-lg-8">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="fw-semibold mb-2">Tên Thợ *</label>
                                                <input type="text" name="name" class="form-control form-control-lg" 
                                                       placeholder="VD: Thợ sửa chữa Minh, Thợ điện Tuấn..." required>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Lĩnh Vực Chính *</label>
                                                <select name="category_id" class="form-select form-select-lg" required>
                                                    <option value="">Chọn lĩnh vực...</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Email Liên Hệ *</label>
                                                <input type="email" name="email" class="form-control form-control-lg" 
                                                       placeholder="contact@email.com" required>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Số Điện Thoại *</label>
                                                <input type="tel" name="phone" class="form-control form-control-lg" 
                                                       placeholder="0123456789" required>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Kinh nghiệm (năm) *</label>
                                                <div class="experience-slider">
                                                    <input type="range" class="form-range" id="experience" name="experience" 
                                                           min="0" max="30" value="5" step="1">
                                                    <div class="experience-display text-center mt-2">
                                                        <span class="fw-bold text-primary" id="experience-value">5</span> năm kinh nghiệm
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="button" class="btn btn-primary btn-lg px-4" onclick="nextStep()">
                                        Tiếp Theo <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Location & Description -->
                            <div class="form-step" id="step2">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">📍 Địa Chỉ & Mô Tả</h4>
                                    <p class="text-muted">Thông tin liên hệ và giới thiệu kỹ năng</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-semibold mb-2">Địa Chỉ Cụ Thể *</label>
                                        <input type="text" name="address" class="form-control form-control-lg" 
                                               placeholder="Số nhà, tên đường..." required>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Tỉnh/Thành Phố *</label>
                                        <select name="city" class="form-select form-select-lg" required>
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                            <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                                            <option value="Hà Nội">Hà Nội</option>
                                            <option value="Đà Nẵng">Đà Nẵng</option>
                                            <option value="Cần Thơ">Cần Thơ</option>
                                            <option value="Hải Phòng">Hải Phòng</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Quận/Huyện</label>
                                        <input type="text" name="district" class="form-control form-control-lg" 
                                               placeholder="Nhập quận/huyện">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Phường/Xã</label>
                                        <input type="text" name="ward" class="form-control form-control-lg" 
                                               placeholder="Nhập phường/xã">
                                    </div>
                                    
                                    <div class="col-md-12 mb-4">
                                        <label class="fw-semibold mb-2">Mô Tả Kỹ Năng & Kinh Nghiệm *</label>
                                        <textarea name="description" class="form-control" rows="6" 
                                                  placeholder="Giới thiệu về kỹ năng, kinh nghiệm làm việc, thế mạnh, cam kết chất lượng..." required></textarea>
                                        <div class="character-counter text-end mt-1">
                                            <span class="current-chars">0</span>/1000 ký tự
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="fas fa-arrow-left me-2"></i> Quay Lại
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4" onclick="nextStep()">
                                        Tiếp Theo <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Services & Working Hours -->
                            <div class="form-step" id="step3">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">🔧 Dịch Vụ & Giờ Làm Việc</h4>
                                    <p class="text-muted">Chi tiết về các dịch vụ và thời gian làm việc</p>
                                </div>

                                <!-- Specialty Services -->
                                <div class="mb-4">
                                    <h5 class="subsection-title">
                                        <i class="fas fa-tools text-primary me-2"></i>
                                        Dịch Vụ Chuyên Môn Chính
                                    </h5>
                                    <small class="text-muted mb-3 d-block">Nhập tối đa 6 dịch vụ chính mà bạn cung cấp</small>
                                    <div class="specialty-services-container">
                                        <div class="row" id="specialty-services-list">
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="specialty_services[]" class="form-control" 
                                                       placeholder="VD: Sửa chữa điện nước">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <input type="text" name="specialty_services[]" class="form-control" 
                                                       placeholder="VD: Lắp đặt hệ thống">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-specialty-btn">
                                            <i class="fas fa-plus me-1"></i> Thêm dịch vụ
                                        </button>
                                    </div>
                                </div>

                                <!-- Working Hours -->
                                <div class="mb-4">
                                    <h5 class="subsection-title">
                                        <i class="fas fa-clock text-success me-2"></i>
                                        Giờ Làm Việc
                                    </h5>
                                    <div class="working-hours-container">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold">Thứ 2 - Thứ 6</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="time" name="weekday_start" class="form-control" value="08:00" required>
                                                    <span class="text-muted">đến</span>
                                                    <input type="time" name="weekday_end" class="form-control" value="18:00" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold">Thứ 7 - Chủ nhật</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <input type="time" name="weekend_start" class="form-control" value="09:00" required>
                                                    <span class="text-muted">đến</span>
                                                    <input type="time" name="weekend_end" class="form-control" value="17:00" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" name="available_247" class="form-check-input" id="available247">
                                            <label class="form-check-label" for="available247">
                                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                                Sẵn sàng 24/7 cho trường hợp khẩn cấp
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="fas fa-arrow-left me-2"></i> Quay Lại
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4" onclick="nextStep()">
                                        Tiếp Theo <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 4: Images & Portfolio -->
                            <div class="form-step" id="step4">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">📸 Hình Ảnh & Portfolio</h4>
                                    <p class="text-muted">Thể hiện chuyên môn qua hình ảnh</p>
                                </div>

                                <!-- Project Images -->
                                <div class="mb-4">
                                    <h5 class="subsection-title">
                                        <i class="fas fa-images text-info me-2"></i>
                                        Hình Ảnh Dự Án Đã Thực Hiện
                                    </h5>
                                    <div class="upload-area">
                                        <input type="file" name="project_images[]" id="project_images" 
                                               accept="image/*" multiple class="form-control">
                                        <small class="text-muted">Tối đa 10 ảnh, mỗi ảnh không quá 2MB</small>
                                        <div class="project-images-preview mt-3" id="project-preview"></div>
                                    </div>
                                </div>

                                <!-- Featured Project Description -->
                                <div class="mb-4">
                                    <h5 class="subsection-title">
                                        <i class="fas fa-star text-warning me-2"></i>
                                        Mô Tả Dự Án Tiêu Biểu
                                    </h5>
                                    <textarea name="featured_project_description" class="form-control" rows="4" 
                                              placeholder="Mô tả ngắn gọn về một dự án nổi bật mà bạn đã thực hiện. Bao gồm thách thức, giải pháp và kết quả..."></textarea>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="fas fa-arrow-left me-2"></i> Quay Lại
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg px-4" onclick="nextStep()">
                                        Tiếp Theo <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 5: Preview & Submit -->
                            <div class="form-step" id="step5">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">👁️ Xem Trước & Hoàn Thành</h4>
                                    <p class="text-muted">Kiểm tra lại thông tin trước khi gửi</p>
                                </div>

                                <div class="preview-container">
                                    <div class="preview-card">
                                        <div id="final-preview">
                                            <!-- Preview sẽ được tạo bằng JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mb-4 text-center">
                                    <input type="checkbox" class="form-check-input" id="agree-terms" required>
                                    <label class="form-check-label" for="agree-terms">
                                        Tôi đồng ý với <a href="#" target="_blank">Điều khoản sử dụng</a> và 
                                        <a href="#" target="_blank">Chính sách bảo mật</a>
                                    </label>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="fas fa-arrow-left me-2"></i> Quay Lại
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-check me-2"></i> Tạo Hồ Sơ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .form-step {
        display: none;
    }
    
    .form-step.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .step-indicator {
        display: flex;
        align-items: center;
        margin: 0 10px;
    }
    
    .step-indicator.active .step-circle {
        background: #667eea;
        color: white;
    }
    
    .step-indicator.completed .step-circle {
        background: #28a745;
        color: white;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .step-label {
        font-size: 14px;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .step-divider {
        width: 30px;
        height: 2px;
        background: #e9ecef;
        margin: 0 5px;
    }
    
    .step-title {
        color: #495057;
        margin-bottom: 10px;
        font-size: 1.5rem;
    }
    
    .subsection-title {
        color: #495057;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f8f9fa;
        font-size: 1.1rem;
    }
    
    .company-logo-upload {
        position: relative;
    }
    
    .logo-preview {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto;
        border: 3px dashed #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }
    
    .logo-preview:hover {
        border-color: #667eea;
    }
    
    .logo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .logo-preview:hover .upload-overlay {
        opacity: 1;
    }
    
    .character-counter {
        font-size: 12px;
        color: #6c757d;
    }
    
    .experience-slider {
        text-align: center;
    }
    
    .form-range {
        width: 100%;
    }
    
    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        transition: border-color 0.3s ease;
    }
    
    .upload-area:hover {
        border-color: #667eea;
    }
    
    .project-images-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
    }
    
    .project-images-preview img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .preview-container {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 25px;
        margin-top: 20px;
    }
    
    .preview-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .company-preview {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .company-preview .company-avatar img {
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .company-preview .badge {
        font-size: 0.8rem;
    }
    
    .company-preview .service-badge {
        background: linear-gradient(45deg, #667eea, #764ba2);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin: 3px;
        display: inline-block;
    }
    
    .working-hours-display {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-top: 10px;
    }
    
    .working-hours-display .time-slot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .working-hours-display .time-slot:last-child {
        border-bottom: none;
    }
    
    .emergency-badge {
        background: linear-gradient(45deg, #ff6b6b, #ffa500);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @media (max-width: 768px) {
        .step-indicator {
            margin: 0 5px;
        }
        
        .step-label {
            display: none;
        }
        
        .step-divider {
            width: 15px;
        }
    }
</style>
@endpush

@push('script')
<script>
let currentStep = 1;
const totalSteps = 5;

function nextStep() {
    if (validateCurrentStep()) {
        if (currentStep < totalSteps) {
            // Hide current step
            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.getElementById(`progress-${currentStep}`).classList.remove('active');
            document.getElementById(`progress-${currentStep}`).classList.add('completed');
            
            // Show next step
            currentStep++;
            document.getElementById(`step${currentStep}`).classList.add('active');
            document.getElementById(`progress-${currentStep}`).classList.add('active');
            
            // Generate preview if on step 5
            if (currentStep === 5) {
                generateFinalPreview();
            }
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        // Hide current step
        document.getElementById(`step${currentStep}`).classList.remove('active');
        document.getElementById(`progress-${currentStep}`).classList.remove('active');
        
        // Show previous step
        currentStep--;
        document.getElementById(`step${currentStep}`).classList.add('active');
        document.getElementById(`progress-${currentStep}`).classList.remove('completed');
        document.getElementById(`progress-${currentStep}`).classList.add('active');
    }
}

function validateCurrentStep() {
    const currentStepElement = document.getElementById(`step${currentStep}`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
    }
    
    return isValid;
}

function generateFinalPreview() {
    const formData = new FormData(document.getElementById('companyFormV2'));
    const previewContainer = document.getElementById('final-preview');
    
    const name = formData.get('name') || 'Tên thợ';
    const category = document.querySelector('[name="category_id"] option:checked')?.text || 'Lĩnh vực chuyên môn';
    const experience = formData.get('experience') || '5';
    const phone = formData.get('phone') || 'Số điện thoại';
    const email = formData.get('email') || 'Email';
    const address = formData.get('address') || 'Địa chỉ';
    const city = formData.get('city') || 'Thành phố';
    const district = formData.get('district') || '';
    const ward = formData.get('ward') || '';
    const description = formData.get('description') || 'Mô tả dịch vụ';
    const featuredProject = formData.get('featured_project_description') || '';
    
    // Get specialty services
    const specialties = Array.from(document.querySelectorAll('[name="specialty_services[]"]'))
        .map(input => input.value)
        .filter(value => value.trim())
        .slice(0, 6);
    
    // Get working hours
    const weekdayStart = formData.get('weekday_start') || '08:00';
    const weekdayEnd = formData.get('weekday_end') || '18:00';
    const weekendStart = formData.get('weekend_start') || '09:00';
    const weekendEnd = formData.get('weekend_end') || '17:00';
    const available247 = formData.get('available_247');
    
    // Build full address
    let fullAddress = address;
    if (district) fullAddress += ', ' + district;
    if (ward) fullAddress += ', ' + ward;
    fullAddress += ', ' + city;
    
    previewContainer.innerHTML = `
        <div class="company-preview">
            <div class="d-flex align-items-center mb-4">
                <div class="company-avatar me-4">
                    <img src="${document.getElementById('logoPreview').src}" alt="Logo" class="rounded-circle" width="100" height="100">
                </div>
                <div class="flex-grow-1">
                    <h2 class="mb-2 text-primary">${name}</h2>
                    <p class="text-muted mb-2 fs-5">${category}</p>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <span class="badge bg-primary fs-6">${experience} năm kinh nghiệm</span>
                        <span class="text-muted">★★★★★ 5.0 (Chưa có đánh giá)</span>
                        ${available247 ? '<span class="emergency-badge">🚨 Hỗ trợ 24/7</span>' : ''}
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="fas fa-address-card me-2"></i>Thông tin liên hệ</h5>
                    <div class="contact-info">
                        <p class="mb-2"><i class="fas fa-phone text-success me-2"></i><strong>${phone}</strong></p>
                        <p class="mb-2"><i class="fas fa-envelope text-info me-2"></i>${email}</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i>${fullAddress}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3"><i class="fas fa-clock me-2"></i>Giờ làm việc</h5>
                    <div class="working-hours-display">
                        <div class="time-slot">
                            <span><strong>Thứ 2 - Thứ 6:</strong></span>
                            <span class="text-primary fw-bold">${weekdayStart} - ${weekdayEnd}</span>
                        </div>
                        <div class="time-slot">
                            <span><strong>Thứ 7 - Chủ nhật:</strong></span>
                            <span class="text-primary fw-bold">${weekendStart} - ${weekendEnd}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="fas fa-user-tie me-2"></i>Giới thiệu</h5>
                <div class="description-box p-3 bg-light rounded">
                    <p class="mb-0">${description}</p>
                </div>
            </div>
            
            ${specialties.length > 0 ? `
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="fas fa-tools me-2"></i>Dịch vụ chuyên môn</h5>
                <div class="services-list">
                    ${specialties.map(service => `<span class="service-badge">${service}</span>`).join('')}
                </div>
            </div>
            ` : ''}
            
            ${featuredProject ? `
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="fas fa-star me-2"></i>Dự án tiêu biểu</h5>
                <div class="featured-project p-3 bg-light rounded">
                    <p class="mb-0">${featuredProject}</p>
                </div>
            </div>
            ` : ''}
            
            <div class="text-center mt-4 pt-4 border-top">
                <p class="text-muted mb-2">Hồ sơ được tạo bằng <strong>Hệ thống V2</strong></p>
                <small class="text-muted">Liên hệ ngay để được tư vấn và báo giá miễn phí!</small>
            </div>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', function() {
    // Experience slider
    const experienceSlider = document.getElementById('experience');
    const experienceValue = document.getElementById('experience-value');
    
    experienceSlider.addEventListener('input', function() {
        experienceValue.textContent = this.value;
    });
    
    // Character counter
    const descriptionField = document.querySelector('[name="description"]');
    const charCounter = document.querySelector('.current-chars');
    
    descriptionField.addEventListener('input', function() {
        charCounter.textContent = this.value.length;
        if (this.value.length > 1000) {
            charCounter.style.color = '#dc3545';
        } else {
            charCounter.style.color = '#6c757d';
        }
    });
    
    // Logo upload preview
    document.getElementById('companyLogo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Add specialty service
    let specialtyCount = 2;
    const maxSpecialties = 6;
    
    document.getElementById('add-specialty-btn').addEventListener('click', function() {
        if (specialtyCount < maxSpecialties) {
            const container = document.getElementById('specialty-services-list');
            const newDiv = document.createElement('div');
            newDiv.className = 'col-md-6 mb-2';
            newDiv.innerHTML = `
                <div class="d-flex">
                    <input type="text" name="specialty_services[]" class="form-control" placeholder="VD: Bảo trì định kỳ">
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2 remove-specialty">×</button>
                </div>
            `;
            container.appendChild(newDiv);
            specialtyCount++;
            
            // Add remove functionality
            newDiv.querySelector('.remove-specialty').addEventListener('click', function() {
                newDiv.remove();
                specialtyCount--;
                updateSpecialtyButton();
            });
            
            updateSpecialtyButton();
        }
    });
    
    function updateSpecialtyButton() {
        const btn = document.getElementById('add-specialty-btn');
        btn.style.display = specialtyCount >= maxSpecialties ? 'none' : 'inline-block';
    }
    
    // Project images preview
    document.getElementById('project_images').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const preview = document.getElementById('project-preview');
        preview.innerHTML = '';
        
        files.slice(0, 10).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
});
</script>
@endpush 
 