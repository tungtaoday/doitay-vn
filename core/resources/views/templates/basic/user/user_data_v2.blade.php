@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-100 pb-100 section--bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="user-data-wrapper">
                        <!-- Progress Steps -->
                        <div class="progress-steps">
                            <div class="step active" data-step="1">
                                <div class="step-number">1</div>
                                <div class="step-label">Thông tin cơ bản</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-number">2</div>
                                <div class="step-label">Địa chỉ</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-number">3</div>
                                <div class="step-label">Hoàn tất</div>
                            </div>
                        </div>

                        <!-- Form Container -->
                        <div class="form-container">
                            <form id="userDataForm" method="POST" action="{{ route('user.data.submit') }}" class="multi-step-form">
                                @csrf
                                
                                <!-- Step 1: Basic Information -->
                                <div class="form-step active" data-step="1">
                                    <div class="step-header">
                                        <h3>Thông tin cơ bản</h3>
                                        <p>Vui lòng điền thông tin cá nhân của bạn</p>
                                    </div>
                                    
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="las la-user"></i>
                                                @lang('Username')
                                            </label>
                                            <input type="text" 
                                                   class="form-control modern-input checkUser" 
                                                   name="username" 
                                                   value="{{ old('username', auth()->user()->username ?? '') }}"
                                                   placeholder="Nhập username của bạn"
                                                   required>
                                            <small class="text-danger usernameExist"></small>
                                            <div class="input-feedback"></div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="las la-phone"></i>
                                                @lang('Số điện thoại')
                                            </label>
                                            <input type="tel" 
                                                   class="form-control modern-input checkUser" 
                                                   name="mobile" 
                                                   value="{{ old('mobile', auth()->user()->mobile ?? '') }}"
                                                   placeholder="Nhập số điện thoại"
                                                   pattern="[0-9]{10,11}">
                                            <small class="text-danger mobileExist"></small>
                                            <div class="input-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: Address Information -->
                                <div class="form-step" data-step="2">
                                    <div class="step-header">
                                        <h3>Thông tin địa chỉ</h3>
                                        <p>Chọn địa chỉ của bạn để chúng tôi hỗ trợ tốt hơn</p>
                                    </div>
                                    
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="las la-map-marker-alt"></i>
                                                @lang('Thành phố')
                                            </label>
                                            <select id="city" class="form-control modern-select" name="city" required>
                                                <option value="">@lang('Chọn Thành phố')</option>
                                            </select>
                                            <div class="select-arrow">
                                                <i class="las la-chevron-down"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="las la-building"></i>
                                                @lang('Quận/Huyện')
                                            </label>
                                            <select id="district" class="form-control modern-select" name="district" disabled required>
                                                <option value="">@lang('Chọn Quận/Huyện')</option>
                                            </select>
                                            <div class="select-arrow">
                                                <i class="las la-chevron-down"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="las la-home"></i>
                                                @lang('Phường/Xã')
                                            </label>
                                            <select id="ward" class="form-control modern-select" name="ward" disabled required>
                                                <option value="">@lang('Chọn Phường/Xã')</option>
                                            </select>
                                            <div class="select-arrow">
                                                <i class="las la-chevron-down"></i>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group full-width">
                                            <label class="form-label">
                                                <i class="las la-map"></i>
                                                @lang('Địa chỉ chi tiết')
                                            </label>
                                            <input type="text" 
                                                   class="form-control modern-input" 
                                                   name="address"
                                                   value="{{ old('address') }}"
                                                   placeholder="Số nhà, tên đường...">
                                            <div class="input-feedback"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: Completion -->
                                <div class="form-step" data-step="3">
                                    <div class="step-header">
                                        <h3>Hoàn tất đăng ký</h3>
                                        <p>Xác nhận thông tin và hoàn tất quá trình</p>
                                    </div>
                                    
                                    <div class="completion-content">
                                        @if(auth()->user()->profile_complete != 1)
                                            <div class="expert-option-container mt-4 mb-4">
                                                <div class="expert-option-card">
                                                    <div class="d-flex align-items-center">
                                                        <div class="expert-icon me-3">
                                                            <i class="fas fa-briefcase"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1">🚀 Trở thành nhà cung cấp dịch vụ</h5>
                                                            <p class="text-muted mb-0">Tạo hồ sơ chuyên gia và kiếm thêm thu nhập từ kỹ năng của bạn</p>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="register_as_expert" id="registerAsExpert" value="1">
                                                            <label class="form-check-label" for="registerAsExpert"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <div class="summary-info">
                                            <h5>Thông tin của bạn:</h5>
                                            <div class="info-summary">
                                                <div class="info-item">
                                                    <span class="label">Username:</span>
                                                    <span class="value" id="summary-username">-</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">Điện thoại:</span>
                                                    <span class="value" id="summary-mobile">-</span>
                                                </div>
                                                <div class="info-item">
                                                    <span class="label">Địa chỉ:</span>
                                                    <span class="value" id="summary-address">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Navigation Buttons -->
                                <div class="form-navigation">
                                    <button type="button" class="btn btn-secondary btn-prev" style="display: none;">
                                        <i class="las la-arrow-left"></i>
                                        Quay lại
                                    </button>
                                    
                                    <button type="button" class="btn btn-primary btn-next">
                                        Tiếp tục
                                        <i class="las la-arrow-right"></i>
                                    </button>
                                    
                                    <button type="submit" class="btn btn-success btn-submit" style="display: none;">
                                        <i class="las la-check"></i>
                                        Hoàn tất đăng ký
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
<style>
:root {
    --primary-color: #667eea;
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-color: #48bb78;
    --danger-color: #f56565;
    --warning-color: #ed8936;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
}

.user-data-wrapper {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

.user-data-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

/* Progress Steps */
.progress-steps {
    display: flex;
    justify-content: center;
    padding: 2rem 2rem 0;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 25%;
    right: 25%;
    height: 2px;
    background: var(--gray-200);
    transform: translateY(-50%);
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
    max-width: 200px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gray-200);
    color: var(--gray-500);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
}

.step-label {
    font-size: 0.875rem;
    color: var(--gray-500);
    text-align: center;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background: var(--primary-gradient);
    color: white;
    transform: scale(1.1);
}

.step.active .step-label {
    color: var(--primary-color);
    font-weight: 600;
}

.step.completed .step-number {
    background: var(--success-color);
    color: white;
}

.step.completed .step-label {
    color: var(--success-color);
}

/* Form Container */
.form-container {
    padding: 2rem;
}

.multi-step-form {
    position: relative;
}

.form-step {
    display: none;
    animation: fadeInUp 0.5s ease;
}

.form-step.active {
    display: block;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.step-header {
    text-align: center;
    margin-bottom: 2rem;
}

.step-header h3 {
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
}

.step-header p {
    color: var(--gray-600);
    margin: 0;
}

/* Form Grid */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group {
    position: relative;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-label i {
    color: var(--primary-color);
    font-size: 1rem;
}

/* Modern Inputs */
.modern-input, .modern-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    position: relative;
}

.modern-input:focus, .modern-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    transform: translateY(-1px);
}

.modern-input.valid {
    border-color: var(--success-color);
}

.modern-input.invalid {
    border-color: var(--danger-color);
}

/* Select Styling */
.form-group {
    position: relative;
}

.select-arrow {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none;
    color: var(--gray-400);
    margin-top: 1.25rem;
}

.modern-select {
    appearance: none;
    background-image: none;
    cursor: pointer;
}

.modern-select:disabled {
    background-color: var(--gray-50);
    color: var(--gray-400);
    cursor: not-allowed;
}

/* Input Feedback */
.input-feedback {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    margin-top: 1.25rem;
    opacity: 0;
    transition: all 0.3s ease;
}

.modern-input.valid + .input-feedback::before {
    content: '✓';
    color: var(--success-color);
    font-weight: bold;
    opacity: 1;
}

.modern-input.invalid + .input-feedback::before {
    content: '✗';
    color: var(--danger-color);
    font-weight: bold;
    opacity: 1;
}

/* Expert Option */
.expert-option-container {
    margin-bottom: 2rem;
}

.expert-option-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6;
    border-radius: 15px;
    padding: 20px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.expert-option-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
}

.expert-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.expert-option-card h5 {
    color: #2c3e50;
    font-weight: 600;
    margin: 0;
}

.expert-option-card p {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

.option-content h4 {
    color: var(--gray-800);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.option-content p {
    color: var(--gray-600);
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

/* Modern Checkbox */
.modern-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.modern-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--gray-300);
    border-radius: 4px;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.3s ease;
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.modern-checkbox input[type="checkbox"]:checked + .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.75rem;
    font-weight: bold;
}

.checkbox-text {
    color: var(--gray-700);
    font-weight: 500;
}

/* Summary Info */
.summary-info {
    background: var(--gray-50);
    border-radius: 12px;
    padding: 1.5rem;
}

.summary-info h5 {
    color: var(--gray-800);
    margin-bottom: 1rem;
    font-weight: 600;
}

.info-summary {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-item .label {
    color: var(--gray-600);
    font-weight: 500;
}

.info-item .value {
    color: var(--gray-800);
    font-weight: 600;
}

/* Navigation Buttons */
.form-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid var(--gray-200);
    margin-top: 2rem;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background: var(--primary-gradient);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
    border: 1px solid var(--gray-300);
}

.btn-secondary:hover {
    background: var(--gray-200);
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, var(--success-color) 0%, #38a169 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
}

/* Loading State */
.btn.loading {
    pointer-events: none;
    opacity: 0.7;
}

.btn.loading::after {
    content: '';
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 0.5rem;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

/* Error Messages */
.text-danger {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

/* Responsive Design */
@media (max-width: 768px) {
    .progress-steps {
        padding: 1rem 1rem 0;
    }
    
    .progress-steps::before {
        left: 15%;
        right: 15%;
    }
    
    .step-label {
        font-size: 0.75rem;
    }
    
    .form-container {
        padding: 1rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .form-navigation {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .step-number {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }
    
    .step-header h3 {
        font-size: 1.25rem;
    }
    
    .option-card {
        padding: 1rem;
    }
    
    .option-icon {
        width: 48px;
        height: 48px;
    }
    
    .option-icon i {
        font-size: 1.25rem;
    }
}
</style>
@endpush

@push('script')
<script>
class UserDataFormV2 {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 3;
        this.form = document.getElementById('userDataForm');
        this.init();
    }

    init() {
        console.log('🚀 UserDataFormV2 initializing...');
        this.bindEvents();
        // Delay location loading to ensure DOM is ready
        setTimeout(() => {
            this.loadLocationData();
        }, 500);
        this.updateSummary();
    }

    bindEvents() {
        console.log('📎 Binding events...');
        
        // Navigation buttons
        const btnNext = document.querySelector('.btn-next');
        const btnPrev = document.querySelector('.btn-prev');
        
        if (btnNext) {
            btnNext.addEventListener('click', () => this.nextStep());
            console.log('✅ Next button bound');
        } else {
            console.warn('❌ .btn-next not found');
        }
        
        if (btnPrev) {
            btnPrev.addEventListener('click', () => this.prevStep());
            console.log('✅ Prev button bound');
        } else {
            console.warn('❌ .btn-prev not found');
        }
        
        // Form validation
        const checkUserInputs = document.querySelectorAll('.checkUser');
        console.log('📝 Found checkUser inputs:', checkUserInputs.length);
        
        checkUserInputs.forEach(input => {
            input.addEventListener('blur', (e) => this.validateField(e.target));
            input.addEventListener('input', (e) => this.clearValidation(e.target));
        });

        // Location selects
        const citySelect = document.getElementById('city');
        const districtSelect = document.getElementById('district');
        
        if (citySelect) {
            citySelect.addEventListener('change', () => this.loadDistricts());
            console.log('✅ City select bound');
        } else {
            console.warn('❌ #city not found');
        }
        
        if (districtSelect) {
            districtSelect.addEventListener('change', () => this.loadWards());
            console.log('✅ District select bound');
        } else {
            console.warn('❌ #district not found');
        }
        
        // Expert option
        const optionCard = document.querySelector('.option-card');
        if (optionCard) {
            optionCard.addEventListener('click', () => this.toggleExpertOption());
            console.log('✅ Option card bound');
        } else {
            console.warn('❌ .option-card not found');
        }
        
        // Form inputs for summary
        if (this.form) {
            this.form.addEventListener('input', () => this.updateSummary());
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
            console.log('✅ Form events bound');
        } else {
            console.error('❌ Form not found!');
        }
        
        console.log('📎 Events binding completed');
    }

    nextStep() {
        if (this.validateCurrentStep()) {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                this.updateStepDisplay();
            }
        }
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.currentStep--;
            this.updateStepDisplay();
        }
    }

    updateStepDisplay() {
        console.log('📊 Updating step display to:', this.currentStep);
        
        // Update progress steps
        const steps = document.querySelectorAll('.step');
        console.log('📊 Found steps:', steps.length);
        
        steps.forEach((step, index) => {
            const stepNumber = index + 1;
            step.classList.remove('active', 'completed');
            
            if (stepNumber < this.currentStep) {
                step.classList.add('completed');
            } else if (stepNumber === this.currentStep) {
                step.classList.add('active');
            }
        });

        // Update form steps
        const formSteps = document.querySelectorAll('.form-step');
        console.log('📊 Found form steps:', formSteps.length);
        
        formSteps.forEach((step, index) => {
            step.classList.remove('active');
            if (index + 1 === this.currentStep) {
                step.classList.add('active');
            }
        });

        // Update navigation buttons
        const prevBtn = document.querySelector('.btn-prev');
        const nextBtn = document.querySelector('.btn-next');
        const submitBtn = document.querySelector('.btn-submit');

        if (prevBtn) {
            prevBtn.style.display = this.currentStep > 1 ? 'inline-flex' : 'none';
        }
        
        if (this.currentStep === this.totalSteps) {
            if (nextBtn) nextBtn.style.display = 'none';
            if (submitBtn) submitBtn.style.display = 'inline-flex';
        } else {
            if (nextBtn) nextBtn.style.display = 'inline-flex';
            if (submitBtn) submitBtn.style.display = 'none';
        }

        // Update summary on last step
        if (this.currentStep === this.totalSteps) {
            this.updateSummary();
        }
    }

    validateCurrentStep() {
        const currentStepEl = document.querySelector(`.form-step[data-step="${this.currentStep}"]`);
        
        if (!currentStepEl) {
            console.warn(`❌ Current step element not found: ${this.currentStep}`);
            return false;
        }
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        console.log(`🔍 Validating step ${this.currentStep}, required fields:`, requiredFields.length);
        
        let isValid = true;

        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        console.log(`✅ Step ${this.currentStep} validation result:`, isValid);
        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';

        // Clear previous validation
        this.clearValidation(field);

        // Required validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'Trường này là bắt buộc';
        }

        // Specific validations
        if (value && field.name === 'mobile') {
            const phoneRegex = /^[0-9]{10,11}$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                message = 'Số điện thoại không hợp lệ';
            }
        }

        // Apply validation styling
        if (isValid) {
            field.classList.add('valid');
            field.classList.remove('invalid');
        } else {
            field.classList.add('invalid');
            field.classList.remove('valid');
            this.showFieldError(field, message);
        }

        // Check for existing username/mobile
        if (isValid && (field.name === 'username' || field.name === 'mobile')) {
            this.checkUserExists(field);
        }

        return isValid;
    }

    clearValidation(field) {
        field.classList.remove('valid', 'invalid');
        const errorEl = field.parentNode.querySelector('.text-danger');
        if (errorEl) {
            errorEl.textContent = '';
        }
    }

    showFieldError(field, message) {
        let errorEl = field.parentNode.querySelector('.text-danger');
        if (!errorEl) {
            errorEl = document.createElement('small');
            errorEl.className = 'text-danger';
            field.parentNode.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }

    async checkUserExists(field) {
        const value = field.value.trim();
        if (!value) return;

        try {
            const formData = new FormData();
            formData.append('_token', document.querySelector('[name="_token"]').value);
            formData.append(field.name, value);

            const response = await fetch('{{ route("user.checkUser") }}', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.data !== false) {
                field.classList.add('invalid');
                field.classList.remove('valid');
                this.showFieldError(field, `${data.field} đã tồn tại`);
            } else {
                field.classList.add('valid');
                field.classList.remove('invalid');
            }
        } catch (error) {
            console.error('Error checking user:', error);
        }
    }

    async loadLocationData() {
        console.log('🏙️ Starting cities load...');
        
        const citySelect = document.getElementById('city');
        if (!citySelect) {
            console.error('❌ City select element not found!');
            return;
        }
        
        citySelect.innerHTML = '<option value="">Đang tải thành phố...</option>';
        
        try {
            const response = await fetch('/localtion/api/cities', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const text = await response.text();
            console.log('📡 Cities API response length:', text.length);
            
            const cleanResponse = text.replace(/<!--|-->/g, '').trim();
            
            const cities = JSON.parse(cleanResponse);
            console.log('🏙️ Cities loaded:', cities.length);
            
            citySelect.innerHTML = '<option value="">Chọn Thành phố</option>';
            cities.forEach(city => {
                citySelect.innerHTML += `<option value="${city.City_code}" data-name="${city.City}">${city.City}</option>`;
            });
            
            console.log('✅ Cities populated successfully');
            
        } catch (error) {
            console.error('❌ Cities load error:', error);
            
            // Fallback cities
            const fallbackCities = [
                { City_code: '01', City: 'Hà Nội' },
                { City_code: '79', City: 'TP. Hồ Chí Minh' }, 
                { City_code: '48', City: 'Đà Nẵng' },
                { City_code: '31', City: 'Hải Phòng' },
                { City_code: '92', City: 'Cần Thơ' }
            ];
            
            citySelect.innerHTML = '<option value="">Chọn Thành phố</option>';
            fallbackCities.forEach(city => {
                citySelect.innerHTML += `<option value="${city.City_code}" data-name="${city.City}">${city.City}</option>`;
            });
            
            this.showNotification('Lỗi tải danh sách thành phố. Sử dụng danh sách cơ bản.', 'warning');
        }
    }

    async loadDistricts() {
        const cityCode = document.getElementById('city').value;
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        console.log('🏢 Loading districts for city:', cityCode);

        // Reset dependent selects
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        districtSelect.disabled = !cityCode;
        wardSelect.disabled = true;

        if (!cityCode) {
            return;
        }

        districtSelect.innerHTML = '<option value="">Đang tải quận/huyện...</option>';

        try {
            const response = await fetch(`/localtion/api/districts/${cityCode}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const text = await response.text();
            const cleanResponse = text.replace(/<!--|-->/g, '').trim();
            const districts = JSON.parse(cleanResponse);
            
            console.log('🏢 Districts loaded:', districts.length);

            districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            districts.forEach(district => {
                districtSelect.innerHTML += `<option value="${district.District_code}" data-name="${district.District}">${district.District}</option>`;
            });
            
            districtSelect.disabled = false;
            console.log('✅ Districts populated successfully');
            
        } catch (error) {
            console.error('❌ Districts load error:', error);
            districtSelect.innerHTML = '<option value="">Lỗi tải quận/huyện</option>';
            this.showNotification('Không thể tải danh sách quận/huyện', 'error');
        }
    }

    async loadWards() {
        const districtCode = document.getElementById('district').value;
        const wardSelect = document.getElementById('ward');

        console.log('🏘️ Loading wards for district:', districtCode);

        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wardSelect.disabled = !districtCode;

        if (!districtCode) {
            return;
        }

        wardSelect.innerHTML = '<option value="">Đang tải phường/xã...</option>';

        try {
            const response = await fetch(`/localtion/api/wards/${districtCode}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json, text/plain, */*',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const text = await response.text();
            const cleanResponse = text.replace(/<!--|-->/g, '').trim();
            const wards = JSON.parse(cleanResponse);
            
            console.log('🏘️ Wards loaded:', wards.length);

            wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
            wards.forEach(ward => {
                wardSelect.innerHTML += `<option value="${ward.Ward_code}" data-name="${ward.Ward}">${ward.Ward}</option>`;
            });
            
            wardSelect.disabled = false;
            console.log('✅ Wards populated successfully');
            
        } catch (error) {
            console.error('❌ Wards load error:', error);
            wardSelect.innerHTML = '<option value="">Lỗi tải phường/xã</option>';
            this.showNotification('Không thể tải danh sách phường/xã', 'error');
        }
    }

    toggleExpertOption() {
        const card = document.querySelector('.option-card');
        const checkbox = document.getElementById('registerAsExpert');
        
        card.classList.toggle('selected');
        checkbox.checked = !checkbox.checked;
    }

    updateSummary() {
        console.log('📝 Updating summary...');
        
        const usernameInput = document.querySelector('[name="username"]');
        const mobileInput = document.querySelector('[name="mobile"]');
        const addressInput = document.querySelector('[name="address"]');
        
        const username = usernameInput ? usernameInput.value || '-' : '-';
        const mobile = mobileInput ? mobileInput.value || '-' : '-';
        
        const cityOption = document.querySelector('#city option:checked');
        const districtOption = document.querySelector('#district option:checked');
        const wardOption = document.querySelector('#ward option:checked');
        
        const cityName = cityOption?.dataset.name || '';
        const districtName = districtOption?.dataset.name || '';
        const wardName = wardOption?.dataset.name || '';
        const address = addressInput ? addressInput.value || '' : '';
        
        let fullAddress = [wardName, districtName, cityName, address].filter(Boolean).join(', ') || '-';

        const summaryUsername = document.getElementById('summary-username');
        const summaryMobile = document.getElementById('summary-mobile');
        const summaryAddress = document.getElementById('summary-address');
        
        if (summaryUsername) summaryUsername.textContent = username;
        if (summaryMobile) summaryMobile.textContent = mobile;
        if (summaryAddress) summaryAddress.textContent = fullAddress;
        
        console.log('📝 Summary updated');
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        if (!this.validateCurrentStep()) {
            return;
        }

        const submitBtn = document.querySelector('.btn-submit');
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        // Add hidden location name fields
        const cityName = document.querySelector('#city option:checked')?.dataset.name;
        const districtName = document.querySelector('#district option:checked')?.dataset.name;
        const wardName = document.querySelector('#ward option:checked')?.dataset.name;

        if (cityName) this.addHiddenField('City', cityName);
        if (districtName) this.addHiddenField('District', districtName);
        if (wardName) this.addHiddenField('Ward', wardName);

        // Submit form
        this.form.submit();
    }

    addHiddenField(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        this.form.appendChild(input);
    }

    showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.location-notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `location-notification alert alert-${type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999;
            min-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        notification.innerHTML = `
            <i class="las la-${type === 'error' ? 'exclamation-triangle' : type === 'warning' ? 'exclamation' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 150);
            }
        }, 3000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new UserDataFormV2();
});
</script>
@endpush 