@extends($activeTemplate . 'layouts.master')

@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/templates/basic/css/auth-system.css') }}">
@endpush

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center min-vh-100 align-items-center">
            <div class="col-lg-6 col-xl-5">
                <div class="auth-card">
                    <!-- Header -->
                    <div class="auth-header text-center mb-4">
                        <a href="{{ route('home') }}" class="auth-logo">
                            <img src="{{ siteLogo() }}" alt="{{ gs('site_name') }}" height="60">
                        </a>
                        <h2 class="auth-title mt-3">Chào mừng trở lại!</h2>
                        <p class="auth-subtitle text-muted">Đăng nhập để tiếp tục</p>
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

                    <!-- Login Form -->
                    <form id="loginForm" class="auth-form" action="{{ route('user.login.v2.post') }}" method="POST">
                        @csrf
                        
                        <!-- Smart Login Mode Selector -->
                        <div class="login-mode-selector mb-4">
                            <div class="mode-tabs">
                                <button type="button" class="mode-tab active" data-mode="password">
                                    <i class="las la-key"></i>
                                    <span>Mật khẩu</span>
                                </button>
                                <button type="button" class="mode-tab" data-mode="magic">
                                    <i class="las la-magic"></i>
                                    <span>Magic Link</span>
                                </button>
                            </div>
                        </div>

                        <!-- Password Login Mode -->
                        <div class="login-mode active" data-mode="password">
                            <div class="form-group mb-3">
                                <label class="form-label">Email hoặc số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="las la-envelope"></i>
                                    </span>
                                    <input 
                                        type="text" 
                                        name="identifier" 
                                        class="form-control form-control-lg" 
                                        placeholder="email@example.com hoặc 0123456789"
                                        autocomplete="username"
                                        autocapitalize="none"
                                        spellcheck="false"
                                        required
                                    >
                                </div>
                                <div class="form-feedback"></div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Mật khẩu</label>
                                    <a href="{{ route('user.password.request') }}" class="forgot-link">
                                        Quên mật khẩu?
                                    </a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="las la-lock"></i>
                                    </span>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        class="form-control form-control-lg" 
                                        placeholder="Nhập mật khẩu"
                                        autocomplete="current-password"
                                        required
                                    >
                                    <button type="button" class="input-group-text password-toggle">
                                        <i class="las la-eye"></i>
                                    </button>
                                </div>
                                <div class="form-feedback"></div>
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                                <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                                Đăng nhập
                                <i class="las la-sign-in-alt ms-2"></i>
                            </button>
                        </div>

                        <!-- Magic Link Mode -->
                        <div class="login-mode" data-mode="magic">
                            <div class="magic-description mb-4">
                                <div class="magic-icon">
                                    <i class="las la-magic"></i>
                                </div>
                                <h5>Đăng nhập không mật khẩu</h5>
                                <p class="text-muted">
                                    Chúng tôi sẽ gửi một liên kết an toàn đến email của bạn. 
                                    Chỉ cần click vào liên kết để đăng nhập ngay lập tức.
                                </p>
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label">Email của bạn</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="las la-envelope"></i>
                                    </span>
                                    <input 
                                        type="email" 
                                        name="magic_email" 
                                        class="form-control form-control-lg" 
                                        placeholder="email@example.com"
                                        autocomplete="email"
                                    >
                                </div>
                                <div class="form-feedback"></div>
                            </div>

                            <button type="button" class="btn btn-primary btn-lg w-100 mb-3" id="sendMagicLink">
                                <span class="spinner-border spinner-border-sm me-2 d-none"></span>
                                Gửi Magic Link
                                <i class="las la-paper-plane ms-2"></i>
                            </button>

                            <div class="magic-sent d-none">
                                <div class="alert alert-success">
                                    <i class="las la-check-circle me-2"></i>
                                    Magic link đã được gửi! Kiểm tra email và click vào liên kết để đăng nhập.
                                </div>
                                <button type="button" class="btn btn-outline-primary w-100" id="resendMagicLink">
                                    Gửi lại Magic Link
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Security Info -->
                    <div class="security-info mt-4">
                        <div class="security-badge">
                            <i class="las la-shield-alt"></i>
                            <span>Được bảo mật bởi SSL 256-bit</span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="auth-footer text-center mt-4">
                        <p class="mb-0">
                            Chưa có tài khoản? 
                            <a href="{{ route('user.register.v2') }}" class="text-primary fw-medium">Đăng ký ngay</a>
                        </p>
                        <div class="mt-2">
                            <small class="text-muted">
                                <a href="{{ route('policy.pages', 'terms') }}">Điều khoản dịch vụ</a>
                                •
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
        <p class="mt-2">Đang đăng nhập...</p>
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

/* Enhanced Auth Styles - Mobile First */
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

/* Login Mode Selector - Mobile Optimized */
.login-mode-selector {
    background: var(--bg-light);
    border-radius: var(--radius-md);
    padding: 3px;
    margin-bottom: 1rem;
}

.mode-tabs {
    display: flex;
    gap: 2px;
}

.mode-tab {
    flex: 1;
    background: transparent;
    border: none;
    border-radius: var(--radius-sm);
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-weight: 500;
    font-size: 0.875rem;
    color: var(--text-muted);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    min-height: 44px; /* Touch target */
}

.mode-tab:hover {
    color: var(--text-dark);
    background: rgba(255, 255, 255, 0.7);
}

.mode-tab.active {
    background: white;
    color: var(--primary-color);
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.mode-tab i {
    font-size: 1.1rem;
}

/* Login Modes */
.login-mode {
    display: none;
}

.login-mode.active {
    display: block;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Magic Link Styles */
.magic-description {
    text-align: center;
    padding: 1rem 0;
}

.magic-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.magic-description h5 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.magic-description p {
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Social Login Buttons */
.btn-social {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    font-weight: 500;
    border: 2px solid #e2e8f0;
    background: white;
    color: #4a5568;
    transition: all 0.3s ease;
}

.btn-social:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border-color: #cbd5e0;
}

.btn-google:hover {
    border-color: #db4437;
}

.btn-facebook:hover {
    border-color: #4267b2;
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

/* Form Controls */
.form-control-lg {
    height: 52px;
    font-size: 1rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    padding: 0 16px;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.input-group-text {
    background: transparent;
    border: 2px solid #e2e8f0;
    border-right: none;
    border-radius: 12px 0 0 12px;
}

.input-group .form-control-lg {
    border-left: none;
    border-radius: 0 12px 12px 0;
}

.password-toggle {
    cursor: pointer;
    border-left: none !important;
}

/* Form Check */
.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 0;
    border-radius: 4px;
    border: 2px solid #e2e8f0;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-size: 0.9rem;
    color: #4a5568;
    margin-left: 0.5rem;
}

/* Forgot Link */
.forgot-link {
    color: #667eea;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
}

.forgot-link:hover {
    color: #5a67d8;
    text-decoration: underline;
}

/* Buttons */
.btn-primary {
    background: #667eea;
    border-color: #667eea;
    border-radius: 12px;
    font-weight: 600;
    padding: 14px 24px;
}

.btn-primary:hover {
    background: #5a67d8;
    border-color: #5a67d8;
    transform: translateY(-1px);
}

.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
    border-radius: 12px;
    font-weight: 500;
    padding: 12px 24px;
}

/* Security Info */
.security-info {
    text-align: center;
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.security-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #48bb78;
    font-size: 0.85rem;
    font-weight: 500;
}

.security-badge i {
    font-size: 1rem;
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

/* Alert */
.alert-success {
    background: rgba(72, 187, 120, 0.1);
    border: 1px solid rgba(72, 187, 120, 0.2);
    color: #276749;
    border-radius: 8px;
    padding: 12px 16px;
}

/* Responsive */
@media (max-width: 768px) {
    .auth-card {
        padding: 2rem 1.5rem;
        margin: 1rem;
        border-radius: 16px;
    }
    
    .auth-title {
        font-size: 1.5rem;
    }
    
    .mode-tabs {
        flex-direction: column;
        gap: 8px;
    }
    
    .mode-tab {
        justify-content: flex-start;
    }
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginFlow = new LoginFlow();
    loginFlow.init();
});

class LoginFlow {
    constructor() {
        this.currentMode = 'password';
        this.form = document.getElementById('loginForm');
        this.magicLinkTimer = null;
    }

    init() {
        this.bindEvents();
        this.initSocialLogin();
    }

    bindEvents() {
        // Mode switcher
        document.querySelectorAll('.mode-tab').forEach(tab => {
            tab.addEventListener('click', (e) => this.switchMode(e));
        });

        // Password toggle
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', this.togglePassword.bind(this));
        });

        // Form submission
        this.form.addEventListener('submit', this.handleLogin.bind(this));

        // Magic link
        document.getElementById('sendMagicLink')?.addEventListener('click', this.sendMagicLink.bind(this));
        document.getElementById('resendMagicLink')?.addEventListener('click', this.resendMagicLink.bind(this));

        // Real-time validation
        const identifierInput = document.querySelector('input[name="identifier"]');
        if (identifierInput) {
            identifierInput.addEventListener('blur', this.validateIdentifier.bind(this));
        }
    }

    switchMode(e) {
        e.preventDefault();
        
        const clickedTab = e.currentTarget;
        const newMode = clickedTab.dataset.mode;
        
        if (newMode === this.currentMode) return;

        // Update tabs
        document.querySelectorAll('.mode-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        clickedTab.classList.add('active');

        // Update modes
        document.querySelectorAll('.login-mode').forEach(mode => {
            mode.classList.remove('active');
        });
        document.querySelector(`[data-mode="${newMode}"]`).classList.add('active');

        this.currentMode = newMode;

        // Focus appropriate input
        setTimeout(() => {
            if (newMode === 'password') {
                document.querySelector('input[name="identifier"]')?.focus();
            } else {
                document.querySelector('input[name="magic_email"]')?.focus();
            }
        }, 300);
    }

    async handleLogin(e) {
        e.preventDefault();
        
        if (this.currentMode !== 'password') return;

        if (!this.validateForm()) return;

        this.showLoading(true);

        try {
            const formData = new FormData(this.form);
            
            const response = await fetch(this.form.action, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                if (data.requires_verification) {
                    this.showMessage('Login successful! Redirecting to verification...', 'success');
                } else {
                    this.showMessage('Login successful!', 'success');
                }
                
                setTimeout(() => {
                    window.location.href = data.redirect || '/user/dashboard';
                }, 1000);
            } else {
                this.showError(data.message || 'Login failed. Please try again.');
                this.handleLoginError(data);
            }
        } catch (error) {
            this.showError('An error occurred. Please try again.');
        } finally {
            this.showLoading(false);
        }
    }

    async sendMagicLink() {
        const emailInput = document.querySelector('input[name="magic_email"]');
        const email = emailInput.value.trim();

        if (!email || !this.isValidEmail(email)) {
            this.showInputError(emailInput, 'Please enter a valid email address');
            return;
        }

        this.setMagicLinkLoading(true);

        try {
            const response = await fetch('/user/magic-link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (data.success) {
                this.showMagicLinkSent();
                this.startResendTimer();
            } else {
                this.showInputError(emailInput, data.message || 'Failed to send magic link');
            }
        } catch (error) {
            this.showInputError(emailInput, 'An error occurred. Please try again.');
        } finally {
            this.setMagicLinkLoading(false);
        }
    }

    resendMagicLink() {
        this.hideMagicLinkSent();
        this.sendMagicLink();
    }

    validateForm() {
        const identifier = document.querySelector('input[name="identifier"]').value.trim();
        const password = document.querySelector('input[name="password"]').value;

        if (!identifier) {
            this.showError('Please enter your email or phone number');
            return false;
        }

        if (!password) {
            this.showError('Please enter your password');
            return false;
        }

        return true;
    }

    validateIdentifier(e) {
        const input = e.target;
        const value = input.value.trim();
        const feedback = input.parentElement.nextElementSibling;

        if (!value) {
            this.showFeedback(feedback, '', '');
            return;
        }

        const isEmail = this.isValidEmail(value);
        const isPhone = this.isValidPhone(value);

        if (!isEmail && !isPhone) {
            this.showFeedback(feedback, 'Please enter a valid email or phone number', 'invalid');
        } else {
            this.showFeedback(feedback, '✓ Valid format', 'valid');
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

    showMagicLinkSent() {
        document.getElementById('sendMagicLink').style.display = 'none';
        document.querySelector('.magic-sent').classList.remove('d-none');
    }

    hideMagicLinkSent() {
        document.getElementById('sendMagicLink').style.display = 'block';
        document.querySelector('.magic-sent').classList.add('d-none');
    }

    startResendTimer() {
        let countdown = 60;
        const resendBtn = document.getElementById('resendMagicLink');
        const originalText = resendBtn.textContent;

        const updateTimer = () => {
            if (countdown > 0) {
                resendBtn.textContent = `Resend in ${countdown}s`;
                resendBtn.disabled = true;
                countdown--;
                this.magicLinkTimer = setTimeout(updateTimer, 1000);
            } else {
                resendBtn.textContent = originalText;
                resendBtn.disabled = false;
            }
        };

        updateTimer();
    }

    setMagicLinkLoading(loading) {
        const btn = document.getElementById('sendMagicLink');
        const spinner = btn.querySelector('.spinner-border');

        if (loading) {
            btn.disabled = true;
            spinner.classList.remove('d-none');
        } else {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    }

    handleLoginError(data) {
        // Could implement specific error handling based on error type
        if (data.errors) {
            Object.keys(data.errors).forEach(field => {
                const input = document.querySelector(`input[name="${field}"]`);
                if (input) {
                    this.showInputError(input, data.errors[field][0]);
                }
            });
        }
    }

    initSocialLogin() {
        window.socialLogin = function(provider) {
            window.location.href = `/user/social-login/${provider}`;
        };
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

    showMessage(message, type = 'info') {
        // Could implement toast notification
        console.log(`${type}: ${message}`);
    }

    showError(message) {
        // Could implement toast notification
        alert(message);
    }

    showFeedback(element, message, type) {
        if (!element) return;
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

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isValidPhone(phone) {
        return /^[0-9+\-\s()]{10,15}$/.test(phone);
    }
}
</script>
@endpush 