@extends($activeTemplate . 'layouts.frontend')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Progress Header -->
                <div class="text-center mb-4">
                    <div class="d-inline-flex align-items-center bg-white rounded-pill px-4 py-2 shadow-sm">
                        <div class="step-indicator completed me-3">
                            <div class="step-circle">1</div>
                            <span class="step-label">Thông tin cá nhân</span>
                        </div>
                        <div class="step-divider"></div>
                                                    <div class="step-indicator active me-3">
                                <div class="step-circle">2</div>
                                <span class="step-label">Thông tin thợ</span>
                            </div>
                        <div class="step-divider"></div>
                        <div class="step-indicator">
                            <div class="step-circle">3</div>
                            <span class="step-label">Hoàn thành</span>
                        </div>
                    </div>
                </div>

                <!-- Main Form Card -->
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient text-white text-center p-4">
                        <h3 class="mb-2">🔧 Tạo Hồ Sơ Thợ Chuyên Nghiệp</h3>
                        <p class="mb-0 opacity-90">Hoàn thành thông tin để trở thành thợ cung cấp dịch vụ chuyên nghiệp</p>
                    </div>

                    <div class="card-body p-5">
                        <form id="companyForm" action="{{ route('user.company.store') }}" method="post" enctype="multipart/form-data">
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
                                            <div class="logo-preview">
                                                <img id="logoPreview" src="{{ getImage('', '150x150') }}" alt="Company Logo">
                                                <div class="upload-overlay">
                                                    <i class="fas fa-camera fa-2x"></i>
                                                    <p class="mt-2">Tải Ảnh đại diện</p>
                                                </div>
                                            </div>
                                            <input type="file" id="companyLogo" name="image" accept="image/*" hidden>
                                            <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="document.getElementById('companyLogo').click()">
                                                <i class="fas fa-upload"></i> Chọn Ảnh đại diện
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Company Details -->
                                    <div class="col-lg-8">
                                        <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                <label class="fw-semibold mb-2">Tên Thợ *</label>
                                <input type="text" name="name" class="form-control form-control-lg" 
                                       placeholder="VD: Thợ sửa chữa Minh, Thợ điện Tuấn..." 
                                       value="{{ old('name', auth()->user()->firstname . ' ' . auth()->user()->lastname) }}" required>
                                        </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Lĩnh Vực Chính *</label>
                                                <select name="category" class="form-select form-select-lg" required>
                                                    <option value="">Chọn lĩnh vực...</option>
                                                @foreach ($categories as $item)
                                                        <option value="{{ $item->id }}" {{ old('category') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                @endforeach
                                            </select>
                                            </div>
                                            
                                                                        <div class="col-md-6 mb-3">
                                <label class="fw-semibold mb-2">Email Liên Hệ *</label>
                                <input type="email" name="email" class="form-control form-control-lg" 
                                       placeholder="contact@email.com" value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Facebook</label>
                                                <input type="url" name="url" class="form-control form-control-lg" 
                                                       placeholder="https://www.company.com" value="{{ old('url') }}">
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="fw-semibold mb-2">Từ Khóa Dịch Vụ *</label>
                                                <input type="text" name="tags_input" class="form-control form-control-lg" 
                                                       placeholder="sửa chữa, thi công, bảo trì..." id="tagsInput">
                                                <small class="text-muted">Ngăn cách bằng dấu phẩy</small>
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
                                       placeholder="Số nhà, tên đường..." value="{{ old('address', auth()->user()->address) }}" required>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Tỉnh/Thành Phố *</label>
                                        <select name="city_code" class="form-select form-select-lg" required>
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                            @if(isset($cities) && count($cities) > 0)
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->city_code }}" 
                                                        {{ old('city_code') == $city->city_code || auth()->user()->city == $city->city ? 'selected' : '' }}>
                                                        {{ $city->city }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Quận/Huyện *</label>
                                        <select name="district_code" class="form-select form-select-lg" data-required="true">
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label class="fw-semibold mb-2">Phường/Xã *</label>
                                        <select name="ward_code" class="form-select form-select-lg" data-required="true">
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label class="fw-semibold mb-2">Số Năm Kinh Nghiệm *</label>
                                        <select name="experience" class="form-select form-select-lg" data-required="true">
                                            <option value="">Chọn số năm kinh nghiệm</option>
                                            <option value="0" {{ old('experience') == '0' ? 'selected' : '' }}>Mới vào nghề (0-1 năm)</option>
                                            <option value="2" {{ old('experience') == '2' ? 'selected' : '' }}>2-3 năm</option>
                                            <option value="5" {{ old('experience') == '5' ? 'selected' : '' }}>5-7 năm</option>
                                            <option value="8" {{ old('experience') == '8' ? 'selected' : '' }}>8-10 năm</option>
                                            <option value="10" {{ old('experience') == '10' ? 'selected' : '' }}>Trên 10 năm</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-4">
                                        <label class="fw-semibold mb-2">Mô Tả Kỹ Năng & Kinh Nghiệm *</label>
                                        <textarea name="description" class="form-control" rows="6" 
                                                  placeholder="Giới thiệu về kỹ năng, kinh nghiệm làm việc, thế mạnh, cam kết chất lượng..." data-required="true">{{ old('description') }}</textarea>
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

                            <!-- Step 3: Experience & Portfolio -->
                            <div class="form-step" id="step3">
                                <div class="text-center mb-4">
                                    <h4 class="step-title">🏆 Kinh Nghiệm & Dự Án</h4>
                                    <p class="text-muted">Thể hiện chuyên môn và thành tựu của bạn</p>
                                    </div>

                                    <!-- Certificates Section -->
                                <div class="experience-section mb-5">
                                    <h5 class="section-title">
                                        <i class="fas fa-certificate text-warning me-2"></i>
                                        Chứng Chỉ & Bằng Cấp
                                    </h5>
                                    <div class="certificates-container">
                                        <div class="certificate-item">
                                                <div class="row">
                                                <div class="col-md-7">
                                                    <input type="text" name="certificates[0][name]" class="form-control" 
                                                           placeholder="Tên chứng chỉ (VD: Chứng chỉ hành nghề xây dựng)">
                                                    </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="certificates[0][year]" class="form-control" 
                                                           placeholder="Năm cấp" min="1990" max="{{ date('Y') }}">
                                                    </div>
                                                    <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger remove-cert d-none">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-certificate">
                                        <i class="fas fa-plus me-2"></i>Thêm Chứng Chỉ
                                    </button>
                                    </div>

                                <!-- Services Section -->
                                <div class="services-section mb-4">
                                    <h5 class="section-title">
                                        <i class="fas fa-tools text-success me-2"></i>
                                        Dịch Vụ Cung Cấp
                                    </h5>
                                    <div class="services-container">
                                        <div class="service-item">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <input type="text" name="services[0][name]" class="form-control mb-3" 
                                                           placeholder="Tên dịch vụ (VD: Sửa chữa điện, Thi công xây dựng...)">
                                                    <textarea name="services[0][description]" class="form-control" rows="2"
                                                              placeholder="Mô tả ngắn về dịch vụ..."></textarea>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" name="services[0][price]" class="form-control" 
                                                           placeholder="Giá (VD: 500k)">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger remove-service d-none">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-success btn-sm mt-3 add-service">
                                        <i class="fas fa-plus me-2"></i>Thêm Dịch Vụ
                                    </button>
                                </div>

                                <!-- Business Hours Section -->
                                <div class="business-hours-section mb-4">
                                    <h5 class="section-title">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        Giờ Làm Việc
                                    </h5>
                                    <div class="hours-container">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-semibold mb-2">Thứ 2 - Thứ 6</label>
                                                <div class="input-group">
                                                    <input type="time" name="business_hours[weekdays][start]" class="form-control" value="08:00">
                                                    <span class="input-group-text">đến</span>
                                                    <input type="time" name="business_hours[weekdays][end]" class="form-control" value="18:00">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-semibold mb-2">Thứ 7</label>
                                                <div class="input-group">
                                                    <input type="time" name="business_hours[saturday][start]" class="form-control" value="08:00">
                                                    <span class="input-group-text">đến</span>
                                                    <input type="time" name="business_hours[saturday][end]" class="form-control" value="16:00">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="fw-semibold mb-2">Chủ nhật</label>
                                                <div class="input-group">
                                                    <select name="business_hours[sunday][status]" class="form-select">
                                                        <option value="closed">Nghỉ</option>
                                                        <option value="open">Có làm việc</option>
                                                    </select>
                                                    <div class="sunday-hours d-none">
                                                        <input type="time" name="business_hours[sunday][start]" class="form-control" value="09:00">
                                                        <span class="input-group-text">đến</span>
                                                        <input type="time" name="business_hours[sunday][end]" class="form-control" value="15:00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="business_hours[24_7]" id="24_7_service">
                                            <label class="form-check-label" for="24_7_service">
                                                Dịch vụ 24/7 (khẩn cấp)
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Portfolio Section -->
                                <div class="portfolio-section mb-4">
                                    <h5 class="section-title">
                                        <i class="fas fa-images text-info me-2"></i>
                                        Dự Án Tiêu Biểu
                                    </h5>
                                    <div class="projects-container">
                                        <div class="project-item">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                    <input type="text" name="projects[0][title]" class="form-control mb-3" 
                                                           placeholder="Tên dự án">
                                                    <textarea name="projects[0][description]" class="form-control" rows="3"
                                                              placeholder="Mô tả dự án, quy mô, thời gian thực hiện..."></textarea>
                                                    </div>
                                                <div class="col-md-4">
                                                    <div class="project-image-upload">
                                                        <div class="image-preview">
                                                            <img class="project-preview" src="{{ getImage('', '700x500') }}" alt="Project Image">
                                                            <div class="upload-overlay">
                                                                <i class="fas fa-camera"></i>
                                                                <p>Thêm Ảnh<br><small>700x500px</small></p>
                                                            </div>
                                                        </div>
                                                        <input type="file" name="projects[0][image]" class="project-file-input" accept="image/*" hidden>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger remove-project d-none">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3 add-project">
                                        <i class="fas fa-plus me-2"></i>Thêm Dự Án
                                    </button>
                                </div>

                                <div class="d-flex justify-content-between mt-5">
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="prevStep()">
                                        <i class="fas fa-arrow-left me-2"></i> Quay Lại
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-check me-2"></i> Hoàn Thành Đăng Ký
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
/* Modern gradient background */
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Progress Steps */
.step-indicator {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 0.9rem;
}

.step-indicator.completed {
    color: #28a745;
}

.step-indicator.active {
    color: #007bff;
    font-weight: 600;
}

.step-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.step-indicator.completed .step-circle {
    background: #28a745;
    color: white;
}

.step-indicator.active .step-circle {
    background: #007bff;
    color: white;
}

.step-divider {
    width: 50px;
    height: 2px;
    background: #dee2e6;
    margin: 0 15px;
}

.step-indicator.completed + .step-divider {
    background: #28a745;
}

/* Form Steps */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.step-title {
    color: #2c3e50;
    font-weight: 700;
    font-size: 1.4rem;
}

.card-body {
    font-size: 0.9rem;
}

label {
    font-size: 0.9rem !important;
}

.text-muted {
    font-size: 0.85rem !important;
}

/* Company Logo Upload */
.company-logo-upload {
    position: relative;
}

.logo-preview {
    position: relative;
    width: 150px;
    height: 150px;
    margin: 0 auto;
    border: 3px dashed #dee2e6;
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.logo-preview:hover {
    border-color: #007bff;
    transform: scale(1.05);
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
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    flex-direction: column;
            align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    font-size: 0.9rem;
}

.logo-preview:hover .upload-overlay {
    opacity: 1;
}

/* Form Controls */
.form-control-lg, .form-select-lg {
    padding: 10px 15px;
    font-size: 0.95rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    color: #495057;
    background-color: #fff;
}

.form-control-lg:focus, .form-select-lg:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    color: #495057;
}

/* Section Titles */
.section-title {
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #f8f9fa;
}

/* Certificate and Project Items */
.certificate-item, .project-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 15px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.certificate-item:hover, .project-item:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Project Image Upload */
.project-image-upload {
    position: relative;
}

.image-preview {
    position: relative;
    width: 100%;
    height: 300px;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.image-preview:hover {
    border-color: #007bff;
}

.image-preview img {
            width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview .upload-overlay {
    font-size: 0.8rem;
}

/* Buttons */
.btn {
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    color: #fff !important;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff !important;
}

.btn-outline-primary {
    color: #007bff !important;
    border-color: #007bff;
    background-color: transparent;
}

.btn-outline-primary:hover {
            background-color: #007bff;
    color: #fff !important;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    color: #fff !important;
}

.btn-outline-secondary {
    color: #6c757d !important;
    border-color: #6c757d;
    background-color: transparent;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #fff !important;
}

.btn-outline-danger {
    color: #dc3545 !important;
    border-color: #dc3545;
    background-color: transparent;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    color: #fff !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .step-indicator {
        flex-direction: column;
        text-align: center;
    }
    
    .step-circle {
        margin-right: 0;
        margin-bottom: 5px;
    }
    
    .step-divider {
        display: none;
    }
    
    .card-body {
        padding: 2rem 1rem;
    }
}

/* Loading Animation */
.btn-loading {
    position: relative;
    color: transparent;
}

.btn-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ffffff;
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Invalid State */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-invalid:focus {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

    let currentStep = 1;
    const totalSteps = 3;

    // Initialize
    $(document).ready(function() {
        loadCities();
        updateStepIndicators();
        
        // Pre-populate location from user data if available
        @if(auth()->user()->city)
            setTimeout(function() {
                loadDistrictsForUser('{{ auth()->user()->city }}', '{{ auth()->user()->district }}');
            }, 1000);
        @endif
    });

    // Step Navigation
    window.nextStep = function() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                updateStepIndicators();
            }
        }
    };

    window.prevStep = function() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
            updateStepIndicators();
        }
    };

    function showStep(step) {
        $('.form-step').removeClass('active');
        $(`#step${step}`).addClass('active');
    }

    function updateStepIndicators() {
        $('.step-indicator').removeClass('active completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < currentStep) {
                $(`.step-indicator:nth-child(${i*2-1})`).addClass('completed');
            } else if (i === currentStep) {
                $(`.step-indicator:nth-child(${i*2-1})`).addClass('active');
            }
        }
    }

         function validateCurrentStep() {
         const currentStepEl = $(`#step${currentStep}`);
         const requiredFields = currentStepEl.find('[required], [data-required="true"]');
         let isValid = true;

         // Temporarily add required attribute for validation
         requiredFields.each(function() {
             if ($(this).attr('data-required') === 'true') {
                 $(this).attr('required', true);
             }
         });

         requiredFields.each(function() {
             const value = $(this).val();
             if (!value || value === '') {
                 $(this).addClass('is-invalid');
                 isValid = false;
                 
                 // Show specific error message
                 const fieldName = $(this).attr('name') || 'field';
                 console.log(`Validation failed for: ${fieldName}`);
             } else {
                 $(this).removeClass('is-invalid');
             }
         });

         if (!isValid) {
             showNotification('Vui lòng điền đầy đủ thông tin bắt buộc', 'error');
             
             // Focus on first invalid field
             const firstInvalid = currentStepEl.find('.is-invalid').first();
             if (firstInvalid.length) {
                 firstInvalid.focus();
             }
         }

         return isValid;
     }

    // File Upload Handlers
    $('#companyLogo').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#logoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(document).on('change', '.project-file-input', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            const preview = $(this).siblings('.image-preview').find('.project-preview');
            reader.onload = function(e) {
                preview.attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(document).on('click', '.image-preview', function() {
        $(this).siblings('.project-file-input').click();
    });

    $('#logoPreview').parent().on('click', function() {
        $('#companyLogo').click();
    });

    // Dynamic Form Elements
    let certIndex = 1;
    let projectIndex = 1;

            $('.add-certificate').on('click', function() {
                const template = `
            <div class="certificate-item">
                        <div class="row">
                    <div class="col-md-7">
                        <input type="text" name="certificates[${certIndex}][name]" class="form-control" 
                               placeholder="Tên chứng chỉ">
                            </div>
                    <div class="col-md-3">
                        <input type="number" name="certificates[${certIndex}][year]" class="form-control" 
                               placeholder="Năm cấp" min="1990" max="${new Date().getFullYear()}">
                            </div>
                            <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger remove-cert">
                            <i class="fas fa-trash"></i>
                        </button>
                            </div>
                        </div>
                    </div>`;
        $('.certificates-container').append(template);
        certIndex++;
    });

            $('.add-project').on('click', function() {
                const template = `
            <div class="project-item">
                        <div class="row">
                            <div class="col-md-6">
                        <input type="text" name="projects[${projectIndex}][title]" class="form-control mb-3" 
                               placeholder="Tên dự án">
                        <textarea name="projects[${projectIndex}][description]" class="form-control" rows="3"
                                  placeholder="Mô tả dự án..."></textarea>
                            </div>
                    <div class="col-md-4">
                        <div class="project-image-upload">
                            <div class="image-preview">
                                <img class="project-preview" src="{{ getImage('', '700x500') }}" alt="Project Image">
                                <div class="upload-overlay">
                                    <i class="fas fa-camera"></i>
                                    <p>Thêm Ảnh<br><small>700x500px</small></p>
                                </div>
                            </div>
                            <input type="file" name="projects[${projectIndex}][image]" class="project-file-input" accept="image/*" hidden>
                        </div>
                            </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-danger remove-project">
                            <i class="fas fa-trash"></i>
                        </button>
                            </div>
                        </div>
                    </div>`;
        $('.projects-container').append(template);
                projectIndex++;
            });

            // Add service functionality
            $('.add-service').on('click', function() {
                const serviceCount = $('.service-item').length;
                const newService = `
                    <div class="service-item">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="services[${serviceCount}][name]" class="form-control mb-3" 
                                       placeholder="Tên dịch vụ (VD: Sửa chữa điện, Thi công xây dựng...)">
                                <textarea name="services[${serviceCount}][description]" class="form-control" rows="2"
                                          placeholder="Mô tả ngắn về dịch vụ..."></textarea>
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="services[${serviceCount}][price]" class="form-control" 
                                       placeholder="Giá (VD: 500k)">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger remove-service">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('.services-container').append(newService);
            });

    $(document).on('click', '.remove-cert', function() {
        $(this).closest('.certificate-item').fadeOut(function() {
            $(this).remove();
        });
    });

            $(document).on('click', '.remove-project', function() {
        $(this).closest('.project-item').fadeOut(function() {
            $(this).remove();
        });
    });

            $(document).on('click', '.remove-service', function() {
                $(this).closest('.service-item').fadeOut(function() {
                    $(this).remove();
                });
            });

            // Business hours functionality
            $('select[name="business_hours[sunday][status]"]').on('change', function() {
                if ($(this).val() === 'open') {
                    $('.sunday-hours').removeClass('d-none');
                } else {
                    $('.sunday-hours').addClass('d-none');
                }
            });

         // Location API handlers
     function loadCities() {
         // Use PHP data directly first as it's more reliable
         loadCitiesFromPHP();
     }

     function loadCitiesFromPHP() {
         const currentCity = '{{ auth()->user()->city ?? "" }}';
         $('select[name=city_code]').empty().append('<option value="">Chọn Tỉnh/Thành phố</option>');
         
         @if(isset($cities) && count($cities) > 0)
             @foreach($cities as $city)
                 const selected{{ $loop->index }} = '{{ $city->city }}' === currentCity ? 'selected' : '';
                 $('select[name=city_code]').append(
                     `<option value="{{ $city->city_code }}" data-name="{{ $city->city }}" ${selected{{ $loop->index }}}>{{ $city->city }}</option>`
                 );
             @endforeach
         @endif
     }

     function loadDistrictsFromDB(cityCode, cityName) {
         // Use existing route
         $.ajax({
             url: '{{ route("user.get.districts") }}',
             type: 'GET',
             data: { city_code: cityCode },
             success: function(districts) {
                 const currentDistrict = '{{ auth()->user()->district ?? "" }}';
                 
                 if (Array.isArray(districts)) {
                     districts.forEach(district => {
                         const selected = district.district === currentDistrict ? 'selected' : '';
                         $('select[name=district_code]').append(
                             `<option value="${district.district_code}" data-name="${district.district}" ${selected}>${district.district}</option>`
                         );
                     });
                 }
             },
             error: function() {
                 console.log('Không thể tải danh sách quận/huyện');
             }
         });
     }

     function loadWardsFromDB(districtCode, districtName) {
         // Use existing route
         $.ajax({
             url: '{{ route("user.get.wards") }}',
             type: 'GET',
             data: { district_code: districtCode },
             success: function(wards) {
                 const currentWard = '{{ auth()->user()->ward ?? "" }}';
                 
                 if (Array.isArray(wards)) {
                     wards.forEach(ward => {
                         const selected = ward.ward === currentWard ? 'selected' : '';
                         $('select[name=ward_code]').append(
                             `<option value="${ward.ward_code}" data-name="${ward.ward}" ${selected}>${ward.ward}</option>`
                         );
                     });
                 }
             },
             error: function() {
                 console.log('Không thể tải danh sách phường/xã');
             }
         });
     }

     function loadDistrictsForUser(cityName, selectedDistrict) {
         // Find city code first
         const cityOption = $('select[name=city_code] option').filter(function() {
             return $(this).data('name') === cityName;
         });
         
         if (cityOption.length > 0) {
             const cityCode = cityOption.val();
             $('select[name=city_code]').val(cityCode);
             
             // Load districts directly 
             loadDistrictsFromDB(cityCode, cityName);
             
             // Load wards after districts are loaded
             setTimeout(function() {
                 if (selectedDistrict) {
                     loadWardsForUser(selectedDistrict, '{{ auth()->user()->ward }}');
                 }
             }, 1000);
         }
     }

     function loadWardsForUser(districtName, selectedWard) {
         // Find and select district
         const districtOption = $('select[name=district_code] option').filter(function() {
             return $(this).data('name') === districtName;
         });
         
         if (districtOption.length > 0) {
             const districtCode = districtOption.val();
             $('select[name=district_code]').val(districtCode);
             
             // Load wards directly
             loadWardsFromDB(districtCode, districtName);
             
             // Set ward after a short delay
             setTimeout(function() {
                 if (selectedWard) {
                     const wardOption = $('select[name=ward_code] option').filter(function() {
                         return $(this).data('name') === selectedWard;
                     });
                     if (wardOption.length > 0) {
                         $('select[name=ward_code]').val(wardOption.val());
                     }
                 }
             }, 1000);
         }
     }

         $('select[name=city_code]').on('change', function() {
         const cityCode = $(this).val();
         const cityName = $(this).find('option:selected').data('name');
         
         $('select[name=district_code]').empty().append('<option value="">Chọn Quận/Huyện</option>');
         $('select[name=ward_code]').empty().append('<option value="">Chọn Phường/Xã</option>');

         if (cityCode) {
             loadDistrictsFromDB(cityCode, cityName);
         }
     });

         $('select[name=district_code]').on('change', function() {
         const districtCode = $(this).val();
         const districtName = $(this).find('option:selected').data('name');
         
         $('select[name=ward_code]').empty().append('<option value="">Chọn Phường/Xã</option>');

         if (districtCode) {
             loadWardsFromDB(districtCode, districtName);
         }
     });

         // Form Submission
     $('#companyForm').on('submit', function(e) {
         // Only allow submission on the last step
         if (currentStep < totalSteps) {
             e.preventDefault();
             return false;
         }

         // Validate all fields before final submission
         let allValid = true;
         for (let step = 1; step <= totalSteps; step++) {
             const stepEl = $(`#step${step}`);
             const requiredFields = stepEl.find('[required], [data-required="true"]');
             
             requiredFields.each(function() {
                 const value = $(this).val();
                 if (!value || value === '') {
                     allValid = false;
                     $(this).addClass('is-invalid');
                 }
             });
         }

         if (!allValid) {
             e.preventDefault();
             showNotification('Vui lòng hoàn thành tất cả thông tin bắt buộc', 'error');
             return false;
         }

         // Convert tags input to array
         const tagsInput = $('#tagsInput').val();
         if (tagsInput) {
             const tagsArray = tagsInput.split(',').map(tag => tag.trim()).filter(tag => tag);
             tagsArray.forEach((tag, index) => {
                 $('<input>').attr({
                     type: 'hidden',
                     name: `tags[${index}]`,
                     value: tag
                 }).appendTo(this);
             });
         }

         // Add location names
         const cityName = $('select[name=city_code] option:selected').text();
         const districtName = $('select[name=district_code] option:selected').text();
         const wardName = $('select[name=ward_code] option:selected').text();

         // Only add if valid selections
         if (cityName && cityName !== 'Chọn Tỉnh/Thành phố') {
             $('<input>').attr({type: 'hidden', name: 'city', value: cityName}).appendTo(this);
         }
         if (districtName && districtName !== 'Chọn Quận/Huyện') {
             $('<input>').attr({type: 'hidden', name: 'district', value: districtName}).appendTo(this);
         }
         if (wardName && wardName !== 'Chọn Phường/Xã') {
             $('<input>').attr({type: 'hidden', name: 'ward', value: wardName}).appendTo(this);
         }

         // Show loading
         const submitBtn = $(this).find('button[type=submit]');
         submitBtn.addClass('btn-loading').prop('disabled', true);
     });

    function showNotification(message, type = 'info') {
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-info';
        const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        $('body').append(notification);
        
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }

        })(jQuery);
    </script>
@endpush
