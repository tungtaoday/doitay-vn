@extends($activeTemplate . 'layouts.master')

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/templates/basic/css/auth-system.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/jquery.validate.min.js') }}"></script>
@endpush

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.auth_seo')
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-lg-8 col-xl-6">
                <div class="auth-card">
                    <!-- Header -->
                    <div class="auth-header text-center mb-4">
                        <a href="{{ route('home') }}" class="auth-logo">
                            <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}" height="60">
                        </a>
                        <h2 class="auth-title mt-3">Tạo tài khoản mới</h2>
                        <p class="auth-subtitle text-muted">Tham gia cộng đồng thợ chuyên nghiệp hàng đầu Việt Nam</p>
                    </div>

                    <!-- Social Login (Prominent) -->
                    <div class="social-auth-section mb-4">
                        <button type="button" class="btn btn-google btn-social w-100 mb-3" onclick="socialLogin('google')">
                            <img src="{{ asset($activeTemplateTrue . 'images/google.svg') }}" alt="Google" width="20" height="20">
                            <span>Tiếp tục với Google</span>
                        </button>
                        
                        <button type="button" class="btn btn-facebook btn-social w-100 mb-3" onclick="socialLogin('facebook')">
                            <img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt="Facebook" width="20" height="20">
                            <span>Tiếp tục với Facebook</span>
                        </button>
                        
                        <div class="divider">
                            <span>hoặc</span>
                        </div>
                    </div>

                    <!-- Progressive Form -->
                    <form id="registrationForm" class="auth-form" action="{{ route('user.register.v2.post') }}" method="POST">
                        @csrf
                        
                        <!-- Step 1: Email/Phone -->
                        <div class="form-step active" data-step="1">
                            <div class="form-group mb-4">
                                <label class="form-label">Email hoặc số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="las la-envelope"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="email_or_phone" 
                                        class="form-control form-control-lg" 
                                        placeholder="Nhập email hoặc số điện thoại"
                                        autocomplete="username"
                                        required
                                    >
                                </div>
                                <div class="form-feedback"></div>
                            </div>
                            
                            <button type="button" class="btn btn-primary btn-lg w-100 btn-next">
                                Tiếp tục
                                <i class="las la-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <!-- Step 2: Password -->
                        <div class="form-step" data-step="2">
                            <div class="step-back mb-3">
                                <button type="button" class="btn btn-link btn-back">
                                    <i class="las la-arrow-left me-2"></i>Quay lại
                                </button>
                                <div class="user-identifier text-muted"></div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label class="form-label">Tạo mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="las la-lock"></i>
                                    </span>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        class="form-control form-control-lg" 
                                        placeholder="Tạo mật khẩu mạnh"
                                        autocomplete="new-password"
                                    >
                                    <button type="button" class="input-group-text password-toggle">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                                <div class="password-strength">
                                    <div class="strength-meter">
                                        <div class="strength-fill"></div>
                                    </div>
                                    <div class="strength-text">Mật khẩu mạnh</div>
                                </div>
                                <div class="password-requirements">
                                    <small class="text-muted">
                                        <span class="req" data-req="length">✓ Ít nhất 8 ký tự</span>
                                        <span class="req" data-req="uppercase">✓ Chữ hoa</span>
                                        <span class="req" data-req="number">✓ Số</span>
                                    </small>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary btn-lg w-100 btn-next">
                                Tiếp tục
                                <i class="las la-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <!-- Step 3: Personal Info -->
                        <div class="form-step" data-step="3">
                            <div class="step-back mb-3">
                                <button type="button" class="btn btn-link btn-back">
                                    <i class="las la-arrow-left me-2"></i>Quay lại
                                </button>
                            </div>
                            
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="las la-user"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            name="fullname" 
                                            class="form-control form-control-lg" 
                                            placeholder="Nguyễn Văn A"
                                            autocomplete="name"
                                        >
                                    </div>
                                    <small class="text-muted">Chúng tôi sẽ tách thành họ và tên</small>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary btn-lg w-100 btn-next">
                                Tiếp tục
                                <i class="las la-arrow-right ms-2"></i>
                            </button>
                        </div>

                        <!-- Step 4: Role Selection -->
                        <div class="form-step" data-step="4">
                            <div class="step-back mb-3">
                                <button type="button" class="btn btn-link btn-back">
                                    <i class="las la-arrow-left me-2"></i>Quay lại
                                </button>
                            </div>
                            
                            <div class="role-selection mb-4">
                                <h5 class="mb-3">Bạn muốn sử dụng nền tảng như thế nào?</h5>
                                
                                <div class="role-option" data-role="customer">
                                    <div class="role-card">
                                        <div class="role-icon">
                                            <i class="las la-search"></i>
                                        </div>
                                        <div class="role-content">
                                            <h6>Tôi cần tìm thợ</h6>
                                            <p>Tìm kiếm và thuê thợ chuyên nghiệp cho công việc của tôi</p>
                                        </div>
                                        <div class="role-check">
                                            <i class="las la-check"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="role-option" data-role="contractor">
                                    <div class="role-card">
                                        <div class="role-icon">
                                            <i class="las la-tools"></i>
                                        </div>
                                        <div class="role-content">
                                            <h6>Tôi là thợ chuyên nghiệp</h6>
                                            <p>Nhận việc và kết nối với khách hàng cần dịch vụ</p>
                                        </div>
                                        <div class="role-check">
                                            <i class="las la-check"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="role-option" data-role="both">
                                    <div class="role-card">
                                        <div class="role-icon">
                                            <i class="las la-users"></i>
                                        </div>
                                        <div class="role-content">
                                            <h6>Cả hai</h6>
                                            <p>Vừa tìm thợ vừa nhận việc làm thợ</p>
                                        </div>
                                        <div class="role-check">
                                            <i class="las la-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" name="user_role" value="">
                            
                            <button type="submit" class="btn btn-success btn-lg w-100" disabled>
                                <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                                Tạo tài khoản
                                <i class="las la-check ms-2"></i>
                            </button>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="firstname" value="">
                        <input type="hidden" name="lastname" value="">
                        <input type="hidden" name="email" value="">
                        <input type="hidden" name="mobile" value="">
                        <input type="hidden" name="password_confirmation" value="">
                    </form>

                    <!-- Footer -->
                    <div class="auth-footer text-center mt-4">
                        <p class="mb-0">
                            Đã có tài khoản? 
                            <a href="{{ route('user.login.v2') }}" class="text-primary fw-medium">Đăng nhập ngay</a>
                        </p>
                        <div class="mt-2">
                            <small class="text-muted">
                                Bằng cách đăng ký, bạn đồng ý với 
                                <a href="{{ route('policy.pages', 'terms') }}">Điều khoản</a> và 
                                <a href="{{ route('policy.pages', 'privacy') }}">Chính sách bảo mật</a>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Đang tạo tài khoản...</p>
    </div>
</div>
@endsection

@push('style')
<style>
/* Mobile-first CSS Variables */
:root {
    --primary-color: #7367f0;
    --primary-hover: #5e50ee;
    --secondary-color: #0b92d4;
    --secondary-hover: #0878b8;
    --accent-gradient: linear-gradient(45deg, #ffd700, #ffa500);
    --success-color: #28c76f;
    --danger-color: #ea5455;
    --warning-color: #ff9f43;
    --text-dark: #2d3748;
    --text-muted: #718096;
    --border-color: #e2e8f0;
    --border-focus: #cbd5e0;
    --bg-light: #f7fafc;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 16px;
    --radius-xl: 20px;
}

/* Progressive Auth Form Styles - Mobile First */
.auth-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    min-height: 100vh;
    padding: 1rem 0;
    position: relative;
    overflow: hidden;
}

.auth-section::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(255, 215, 0, 0.1) 0%, transparent 50%);
    animation: float 20s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(1deg); }
}

.auth-card {
    background: white;
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-lg);
    padding: 1.5rem;
    max-width: 400px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.auth-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-align: center;
}

.auth-subtitle {
    font-size: 0.875rem;
    line-height: 1.5;
    color: var(--text-muted);
    text-align: center;
    margin-bottom: 1.5rem;
}

/* Social Login Buttons - Mobile Optimized */
.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: var(--radius-md);
    font-weight: 500;
    font-size: 0.875rem;
    border: 1.5px solid var(--border-color);
    background: white;
    color: var(--text-dark);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 48px; /* Touch target size */
}

.btn-social::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s;
}

.btn-social:hover::before {
    left: 100%;
}

.btn-social:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
    border-color: var(--border-focus);
}

.btn-social:active {
    transform: translateY(0);
}

.btn-google:hover {
    border-color: #db4437;
    color: #db4437;
}

.btn-facebook:hover {
    border-color: #4267b2;
    color: #4267b2;
}

.divider {
    position: relative;
    text-align: center;
    margin: 1.5rem 0;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e2e8f0;
}

.divider span {
    background: white;
    padding: 0 1rem;
    color: #718096;
    font-size: 0.9rem;
}

/* Progressive Form Steps */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.form-control-lg {
    height: 48px;
    font-size: 16px; /* Prevents zoom on iOS */
    border-radius: var(--radius-md);
    border: 1.5px solid var(--border-color);
    padding: 0 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
}

.form-control-lg:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(115, 103, 240, 0.1);
    background: white;
    outline: none;
}

.form-control-lg::placeholder {
    color: var(--text-muted);
    opacity: 0.7;
}

.input-group-text {
    background: rgba(255, 255, 255, 0.8);
    border: 1.5px solid var(--border-color);
    border-right: none;
    border-radius: var(--radius-md) 0 0 var(--radius-md);
    color: var(--text-muted);
    backdrop-filter: blur(10px);
}

.input-group .form-control-lg {
    border-left: none;
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
}

.input-group:focus-within .input-group-text {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Password Strength */
.password-strength {
    margin-top: 8px;
}

.strength-meter {
    height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-text {
    font-size: 0.85rem;
    margin-top: 4px;
    font-weight: 500;
}

.password-requirements {
    margin-top: 8px;
}

.password-requirements .req {
    display: block;
    font-size: 0.8rem;
    color: #718096;
    margin-bottom: 2px;
}

.password-requirements .req.valid {
    color: #48bb78;
}

/* Role Selection */
.role-option {
    margin-bottom: 1rem;
    cursor: pointer;
}

.role-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
}

.role-option.selected .role-card {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.05);
}

.role-card:hover {
    border-color: #cbd5e0;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.role-icon {
    width: 50px;
    height: 50px;
    background: #667eea;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.role-content h6 {
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    color: #2d3748;
}

.role-content p {
    margin: 0;
    color: #718096;
    font-size: 0.9rem;
}

.role-check {
    margin-left: auto;
    width: 24px;
    height: 24px;
    border: 2px solid #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: transparent;
    transition: all 0.3s ease;
}

.role-option.selected .role-check {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

/* Buttons - Mobile Optimized */
.btn-primary {
    background: var(--primary-color);
    border-color: var(--primary-color);
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 12px 20px;
    min-height: 48px; /* Touch target */
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    background: var(--primary-hover);
    border-color: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-success {
    background: var(--success-color);
    border-color: var(--success-color);
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 12px 20px;
    min-height: 48px;
    position: relative;
    overflow: hidden;
}

.btn-back {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    padding: 0;
}

.btn-back:hover {
    color: #5a67d8;
}

/* Form Feedback */
.form-feedback {
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.form-feedback.valid {
    color: #48bb78;
}

.form-feedback.invalid {
    color: #f56565;
}

/* Loading */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loading-content {
    text-align: center;
    color: white;
}

/* Desktop Improvements */
@media (min-width: 768px) {
    .auth-card {
        padding: 2.5rem;
        max-width: 460px;
    }
    
    .auth-title {
        font-size: 1.75rem;
    }
    
    .auth-subtitle {
        font-size: 1rem;
    }
    
    .btn-social {
        padding: 14px 20px;
        font-size: 1rem;
    }
    
    .form-control-lg {
        height: 52px;
        padding: 0 16px;
    }
    
    .btn-primary, .btn-success {
        padding: 14px 24px;
        font-size: 1rem;
    }
}

/* Large Desktop */
@media (min-width: 1200px) {
    .auth-card {
        max-width: 500px;
        padding: 3rem;
    }
}

/* Touch Improvements */
@media (hover: none) and (pointer: coarse) {
    .btn-social, .btn-primary, .btn-success {
        min-height: 52px;
    }
    
    .form-control-lg {
        min-height: 52px;
    }
}

/* Reduce animations for users who prefer reduced motion */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
    
    .auth-section::before {
        animation: none;
    }
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registrationFlow = new RegistrationFlow();
    registrationFlow.init();
});

class RegistrationFlow {
    constructor() {
        this.currentStep = 1;
        this.maxStep = 4;
        this.formData = {};
        this.form = document.getElementById('registrationForm');
    }

    init() {
        this.bindEvents();
        this.initSocialLogin();
    }

    bindEvents() {
        // Next buttons
        document.querySelectorAll('.btn-next').forEach(btn => {
            btn.addEventListener('click', (e) => this.nextStep(e));
        });

        // Back buttons
        document.querySelectorAll('.btn-back').forEach(btn => {
            btn.addEventListener('click', (e) => this.prevStep(e));
        });

        // Email/phone validation
        const emailPhoneInput = document.querySelector('input[name="email_or_phone"]');
        if (emailPhoneInput) {
            emailPhoneInput.addEventListener('input', this.debounce(this.validateEmailOrPhone.bind(this), 500));
        }

        // Password strength
        const passwordInput = document.querySelector('input[name="password"]');
        if (passwordInput) {
            passwordInput.addEventListener('input', this.checkPasswordStrength.bind(this));
        }

        // Password toggle
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', this.togglePassword.bind(this));
        });

        // Role selection
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', this.selectRole.bind(this));
        });

        // Form submission
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
    }

    nextStep(e) {
        e.preventDefault();
        
        if (this.validateCurrentStep()) {
            this.saveCurrentStepData();
            this.currentStep++;
            this.showStep(this.currentStep);
        }
    }

    prevStep(e) {
        e.preventDefault();
        this.currentStep--;
        this.showStep(this.currentStep);
    }

    showStep(step) {
        document.querySelectorAll('.form-step').forEach(stepEl => {
            stepEl.classList.remove('active');
        });
        
        const targetStep = document.querySelector(`[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.add('active');
        }

        // Focus first input in new step
        setTimeout(() => {
            const firstInput = targetStep?.querySelector('input:not([type="hidden"])');
            if (firstInput && !firstInput.disabled) {
                firstInput.focus();
            }
        }, 300);
    }

    validateCurrentStep() {
        const currentStepEl = document.querySelector(`[data-step="${this.currentStep}"]`);
        
        switch(this.currentStep) {
            case 1:
                return this.validateEmailOrPhone();
            case 2:
                return this.validatePassword();
            case 3:
                return this.validatePersonalInfo();
            case 4:
                return this.validateRole();
            default:
                return true;
        }
    }

    async validateEmailOrPhone() {
        const input = document.querySelector('input[name="email_or_phone"]');
        const feedback = input.parentElement.nextElementSibling;
        const value = input.value.trim();

        if (!value) {
            this.showFeedback(feedback, 'Vui lòng nhập email hoặc số điện thoại', 'invalid');
            return false;
        }

        // Check format
        const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        const isPhone = /^[0-9]{10,11}$/.test(value.replace(/\D/g, ''));

        if (!isEmail && !isPhone) {
            this.showFeedback(feedback, 'Email hoặc số điện thoại không hợp lệ', 'invalid');
            return false;
        }

        // Check if exists
        try {
            const response = await fetch('{{ route("user.check.user.v2") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    [isEmail ? 'email' : 'mobile']: value
                })
            });

            const data = await response.json();
            
            if (data.exists) {
                this.showFeedback(feedback, 'Tài khoản đã tồn tại. Vui lòng đăng nhập.', 'invalid');
                return false;
            }

            this.showFeedback(feedback, '✓ Có thể sử dụng', 'valid');
            return true;
        } catch (error) {
            this.showFeedback(feedback, 'Có lỗi xảy ra. Vui lòng thử lại.', 'invalid');
            return false;
        }
    }

    validatePassword() {
        const input = document.querySelector('input[name="password"]');
        const value = input.value;

        const requirements = {
            length: value.length >= 8,
            uppercase: /[A-Z]/.test(value),
            number: /[0-9]/.test(value)
        };

        const allValid = Object.values(requirements).every(Boolean);
        
        if (!allValid) {
            return false;
        }

        return true;
    }

    validatePersonalInfo() {
        const input = document.querySelector('input[name="fullname"]');
        const value = input.value.trim();

        if (!value || value.length < 2) {
            this.showInputError(input, 'Vui lòng nhập họ tên đầy đủ');
            return false;
        }

        this.showInputSuccess(input);
        return true;
    }

    validateRole() {
        const selectedRole = document.querySelector('.role-option.selected');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (!selectedRole) {
            return false;
        }

        submitBtn.disabled = false;
        return true;
    }

    checkPasswordStrength(e) {
        const password = e.target.value;
        const strengthFill = document.querySelector('.strength-fill');
        const strengthText = document.querySelector('.strength-text');
        const requirements = document.querySelectorAll('.req');

        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password)
        };

        // Update requirement indicators
        requirements.forEach(req => {
            const reqType = req.dataset.req;
            if (checks[reqType]) {
                req.classList.add('valid');
            } else {
                req.classList.remove('valid');
            }
        });

        // Calculate strength
        const score = Object.values(checks).filter(Boolean).length;
        const colors = ['#f56565', '#ed8936', '#48bb78'];
        const texts = ['Yếu', 'Trung bình', 'Mạnh'];
        const widths = ['33%', '66%', '100%'];

        if (password.length > 0) {
            strengthFill.style.width = widths[score - 1] || '20%';
            strengthFill.style.background = colors[score - 1] || '#f56565';
            strengthText.textContent = texts[score - 1] || 'Quá yếu';
            strengthText.style.color = colors[score - 1] || '#f56565';
        } else {
            strengthFill.style.width = '0%';
            strengthText.textContent = 'Mật khẩu mạnh';
            strengthText.style.color = '#718096';
        }
    }

    togglePassword(e) {
        const button = e.target.closest('.password-toggle');
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'las la-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'las la-eye';
        }
    }

    selectRole(e) {
        const option = e.currentTarget;
        const role = option.dataset.role;

        // Remove previous selection
        document.querySelectorAll('.role-option').forEach(opt => {
            opt.classList.remove('selected');
        });

        // Add current selection
        option.classList.add('selected');
        
        // Update hidden input
        document.querySelector('input[name="user_role"]').value = role;

        // Enable submit button
        document.querySelector('button[type="submit"]').disabled = false;
    }

    saveCurrentStepData() {
        const currentStepEl = document.querySelector(`[data-step="${this.currentStep}"]`);
        
        switch(this.currentStep) {
            case 1:
                const emailPhone = document.querySelector('input[name="email_or_phone"]').value;
                const isEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailPhone);
                
                console.log('Step 1 - Processing:', { emailPhone, isEmail });
                
                if (isEmail) {
                    // Email registration
                    document.querySelector('input[name="email"]').value = emailPhone;
                    document.querySelector('input[name="mobile"]').value = '';
                    this.formData.email = emailPhone;
                    this.formData.mobile = '';
                    console.log('Email registration - Set email:', emailPhone, 'Clear mobile');
                } else {
                    // Mobile registration
                    document.querySelector('input[name="mobile"]').value = emailPhone;
                    document.querySelector('input[name="email"]').value = '';
                    this.formData.mobile = emailPhone;
                    this.formData.email = '';
                    console.log('Mobile registration - Set mobile:', emailPhone, 'Clear email');
                }
                
                // Show identifier in next step
                document.querySelector('.user-identifier').textContent = emailPhone;
                break;
                
            case 2:
                const password = document.querySelector('input[name="password"]').value;
                document.querySelector('input[name="password_confirmation"]').value = password;
                this.formData.password = password;
                break;
                
            case 3:
                const fullname = document.querySelector('input[name="fullname"]').value;
                const nameParts = fullname.trim().split(' ');
                const lastname = nameParts.pop();
                const firstname = nameParts.join(' ');
                
                document.querySelector('input[name="firstname"]').value = firstname;
                document.querySelector('input[name="lastname"]').value = lastname;
                
                this.formData.firstname = firstname;
                this.formData.lastname = lastname;
                break;
        }
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateCurrentStep()) {
            return;
        }

        this.saveCurrentStepData();
        this.showLoading(true);

        try {
            // Debug: Log form data before submission
            console.log('Form data before submission:', {
                email: document.querySelector('input[name="email"]').value,
                mobile: document.querySelector('input[name="mobile"]').value,
                firstname: document.querySelector('input[name="firstname"]').value,
                lastname: document.querySelector('input[name="lastname"]').value,
                user_role: document.querySelector('input[name="user_role"]').value
            });
            
            const formData = new FormData(this.form);
            
            // Debug: Log all form data entries
            console.log('=== FORM DATA ENTRIES ===');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            console.log('=== END FORM DATA ===');
            
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                // Registration successful
                window.location.href = data.redirect || '/user/dashboard';
            } else {
                this.showError(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
            }
        } catch (error) {
            this.showError('Có lỗi xảy ra. Vui lòng thử lại.');
        } finally {
            this.showLoading(false);
        }
    }

    initSocialLogin() {
        window.socialLogin = function(provider) {
            window.location.href = `/user/social-login/${provider}`;
        };
    }

    showFeedback(element, message, type) {
        element.textContent = message;
        element.className = `form-feedback ${type}`;
    }

    showInputError(input, message) {
        input.classList.add('is-invalid');
        let feedback = input.parentElement.nextElementSibling;
        if (!feedback || !feedback.classList.contains('form-feedback')) {
            feedback = document.createElement('div');
            feedback.className = 'form-feedback';
            input.parentElement.after(feedback);
        }
        this.showFeedback(feedback, message, 'invalid');
    }

    showInputSuccess(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }

    showLoading(show) {
        const overlay = document.getElementById('loadingOverlay');
        const submitBtn = document.querySelector('button[type="submit"]');
        const spinner = submitBtn.querySelector('.spinner-border');

        if (show) {
            overlay.style.display = 'flex';
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
        } else {
            overlay.style.display = 'none';
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    }

    showError(message) {
        // Could implement toast notification here
        alert(message);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Fix viewport height for mobile browsers
function setViewportHeight() {
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

// Set initial viewport height
setViewportHeight();

// Update viewport height on resize
window.addEventListener('resize', setViewportHeight);
window.addEventListener('orientationchange', () => {
    setTimeout(setViewportHeight, 100);
});
</script>
@endpush 