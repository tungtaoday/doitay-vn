// ================================
// HOME PAGE COMPLETE FUNCTIONALITY
// ================================

// ================================
// MOBILE BACKGROUND IMAGE HANDLER
// ================================
function handleMobileBackground() {
    const heroSection = document.querySelector('.hero-section.bg_img');
    if (!heroSection) return;
    
    const mobileImage = heroSection.getAttribute('data-mobile-image');
    const desktopImage = heroSection.style.backgroundImage;
    
    function updateBackground() {
        if (window.innerWidth <= 768 && mobileImage) {
            heroSection.style.backgroundImage = `url('${mobileImage}')`;
        } else if (desktopImage) {
            heroSection.style.backgroundImage = desktopImage;
        }
    }
    
    updateBackground();
    window.addEventListener('resize', updateBackground);
}

// ================================
// VIETNAM LOCATION API
// ================================
class VietnamLocationAPI {
    constructor() {
        this.baseURL = 'https://provinces.open-api.vn/api';
        this.cities = [];
        this.districts = [];
        this.wards = [];
        this.init();
    }
    
    async init() {
        try {
            await this.loadCities();
            this.setupEventListeners();
        } catch (error) {
            console.error('Error initializing location API:', error);
        }
    }
    
    async loadCities() {
        try {
            const response = await fetch(`${this.baseURL}/p/`);
            this.cities = await response.json();
            this.populateCitySelects();
        } catch (error) {
            console.error('Error loading cities:', error);
        }
    }
    
    populateCitySelects() {
        const citySelects = document.querySelectorAll('[name="city_code"]');
        citySelects.forEach(select => {
            select.innerHTML = '<option value="">Chọn thành phố</option>';
            this.cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city.code;
                option.textContent = city.name;
                select.appendChild(option);
            });
        });
    }
    
    async loadDistricts(cityCode) {
        try {
            const response = await fetch(`${this.baseURL}/p/${cityCode}?depth=2`);
            const cityData = await response.json();
            this.districts = cityData.districts || [];
            return this.districts;
        } catch (error) {
            console.error('Error loading districts:', error);
            return [];
        }
    }
    
    async loadWards(districtCode) {
        try {
            const response = await fetch(`${this.baseURL}/d/${districtCode}?depth=2`);
            const districtData = await response.json();
            this.wards = districtData.wards || [];
            return this.wards;
        } catch (error) {
            console.error('Error loading wards:', error);
            return [];
        }
    }
    
    setupEventListeners() {
        // Handle city change for all city selects
        document.addEventListener('change', async (e) => {
            if (e.target.name === 'city_code') {
                const cityCode = e.target.value;
                const formContainer = e.target.closest('form') || e.target.closest('.tab-content');
                const districtSelect = formContainer.querySelector('[name="district_code"]');
                const wardSelect = formContainer.querySelector('[name="ward_code"]');
                
                if (districtSelect) {
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    districtSelect.disabled = !cityCode;
                    
                    if (wardSelect) {
                        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                        wardSelect.disabled = true;
                    }
                    
                    if (cityCode) {
                        const districts = await this.loadDistricts(cityCode);
                        districts.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.code;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                        districtSelect.disabled = false;
                    }
                }
            }
            
            if (e.target.name === 'district_code') {
                const districtCode = e.target.value;
                const formContainer = e.target.closest('form') || e.target.closest('.tab-content');
                const wardSelect = formContainer.querySelector('[name="ward_code"]');
                
                if (wardSelect) {
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    wardSelect.disabled = !districtCode;
                    
                    if (districtCode) {
                        const wards = await this.loadWards(districtCode);
                        wards.forEach(ward => {
                            const option = document.createElement('option');
                            option.value = ward.code;
                            option.textContent = ward.name;
                            wardSelect.appendChild(option);
                        });
                        wardSelect.disabled = false;
                    }
                }
            }
        });
    }
}

// ================================
// TAB SYSTEM
// ================================
class TabSystem {
    constructor() {
        this.currentTab = 'guest';
        this.init();
    }
    
    init() {
        this.setupTabButtons();
        this.setupInitialState();
    }
    
    setupTabButtons() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const tabName = button.getAttribute('data-tab');
                this.switchTab(tabName);
            });
        });
    }
    
    setupInitialState() {
        // Show guest tab by default for non-authenticated users
        if (document.getElementById('guestTab')) {
            this.switchTab('guest');
        }
    }
    
    switchTab(tabName) {
        console.log('Switching to tab:', tabName);
        
        // Update button states
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            if (btn.getAttribute('data-tab') === tabName) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Update tab content visibility
        const tabContents = document.querySelectorAll('.tab-content');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        
        const targetTab = document.getElementById(tabName + 'Tab');
        if (targetTab) {
            targetTab.classList.add('active');
        }
        
        this.currentTab = tabName;
    }
}

// ================================
// GUEST FORM STEP NAVIGATION
// ================================
class GuestFormNavigation {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 3;
        this.init();
    }
    
    init() {
        this.setupStepNavigation();
        this.setupFormValidation();
    }
    
    setupStepNavigation() {
        // Next step buttons with multiple event handling
        document.addEventListener('click', (e) => {
            if (e.target.closest('.next-step')) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Next step clicked');
                
                if (this.validateCurrentStep()) {
                    this.goToStep(this.currentStep + 1);
                }
            }
            
            if (e.target.closest('.prev-step')) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Previous step clicked');
                this.goToStep(this.currentStep - 1);
            }
        });
        
        // Additional touch events for mobile
        document.addEventListener('touchend', (e) => {
            if (e.target.closest('.next-step')) {
                e.preventDefault();
                console.log('Next step touch');
                if (this.validateCurrentStep()) {
                    this.goToStep(this.currentStep + 1);
                }
            }
            
            if (e.target.closest('.prev-step')) {
                e.preventDefault();
                console.log('Previous step touch');
                this.goToStep(this.currentStep - 1);
            }
        });
    }
    
    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) {
            return;
        }
        
        console.log(`Going to step ${stepNumber} from step ${this.currentStep}`);
        
        // Hide all steps first
        for (let i = 1; i <= this.totalSteps; i++) {
            const stepEl = document.getElementById(`step${i}`);
            if (stepEl) {
                stepEl.style.display = 'none';
            }
        }
        
        // Show target step
        const targetStepEl = document.getElementById(`step${stepNumber}`);
        if (targetStepEl) {
            targetStepEl.style.display = 'block';
            this.currentStep = stepNumber;
            
            // Trigger animation
            targetStepEl.style.opacity = '0';
            targetStepEl.style.transform = 'translateX(20px)';
            setTimeout(() => {
                targetStepEl.style.opacity = '1';
                targetStepEl.style.transform = 'translateX(0)';
            }, 50);
        }
    }
    
    validateCurrentStep() {
        const currentStepEl = document.getElementById(`step${this.currentStep}`);
        if (!currentStepEl) return true;
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
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
            this.showValidationMessage('Vui lòng điền đầy đủ thông tin bắt buộc');
        }
        
        return isValid;
    }
    
    showValidationMessage(message) {
        // Remove existing messages
        const existingMessage = document.querySelector('.validation-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message
        const messageEl = document.createElement('div');
        messageEl.className = 'validation-message alert alert-warning mt-3';
        messageEl.innerHTML = `<i class="las la-exclamation-triangle me-2"></i>${message}`;
        
        const currentStepEl = document.getElementById(`step${this.currentStep}`);
        if (currentStepEl) {
            currentStepEl.appendChild(messageEl);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                messageEl.remove();
            }, 5000);
        }
    }
    
    setupFormValidation() {
        // Real-time validation
        document.addEventListener('input', (e) => {
            if (e.target.hasAttribute('required')) {
                if (e.target.value.trim()) {
                    e.target.classList.remove('is-invalid');
                }
            }
        });
    }
}

// ================================
// AUTHENTICATED FORM NAVIGATION
// ================================
class AuthenticatedFormNavigation {
    constructor() {
        this.currentStep = 1;
        this.totalSteps = 2;
        this.init();
    }
    
    init() {
        this.setupStepNavigation();
    }
    
    setupStepNavigation() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.next-step-auth')) {
                e.preventDefault();
                console.log('Auth next step clicked');
                
                if (this.validateCurrentStep()) {
                    this.goToStep(this.currentStep + 1);
                }
            }
            
            if (e.target.closest('.prev-step-auth')) {
                e.preventDefault();
                console.log('Auth previous step clicked');
                this.goToStep(this.currentStep - 1);
            }
        });
    }
    
    goToStep(stepNumber) {
        if (stepNumber < 1 || stepNumber > this.totalSteps) {
            return;
        }
        
        console.log(`Auth going to step ${stepNumber} from step ${this.currentStep}`);
        
        // Hide current step
        const currentStepEl = document.getElementById(`lead-step${this.currentStep}`);
        if (currentStepEl) {
            currentStepEl.style.display = 'none';
        }
        
        // Show target step
        const targetStepEl = document.getElementById(`lead-step${stepNumber}`);
        if (targetStepEl) {
            targetStepEl.style.display = 'block';
            this.currentStep = stepNumber;
        }
    }
    
    validateCurrentStep() {
        const currentStepEl = document.getElementById(`lead-step${this.currentStep}`);
        if (!currentStepEl) return true;
        
        const requiredFields = currentStepEl.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        return isValid;
    }
}

// ================================
// FORM SUBMISSIONS
// ================================
class FormSubmissions {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupGuestFormSubmission();
        this.setupAuthenticatedFormSubmission();
        this.setupLoginFormSubmission();
        this.setupRegisterFormSubmission();
    }
    
    setupGuestFormSubmission() {
        const guestForm = document.getElementById('guestLeadForm');
        if (guestForm) {
            guestForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                console.log('Guest form submission');
                
                const submitBtn = guestForm.querySelector('.submit-lead');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
                submitBtn.disabled = true;
                
                const formData = new FormData(guestForm);
                const data = Object.fromEntries(formData);
                
                try {
                    // Simulate API call - replace with actual endpoint
                    const response = await fetch('/guest-lead-submit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        this.showSuccessMessage('Lead đã được tạo thành công! Các thợ sẽ liên hệ với bạn sớm.');
                        guestForm.reset();
                        // Reset to step 1
                        window.guestFormNav?.goToStep(1);
                    } else {
                        this.showErrorMessage(result.message || 'Có lỗi xảy ra, vui lòng thử lại');
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    this.showErrorMessage('Có lỗi kết nối, vui lòng thử lại');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    }
    
    setupAuthenticatedFormSubmission() {
        const authForm = document.getElementById('authenticatedLeadForm');
        if (authForm) {
            authForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                console.log('Authenticated form submission');
                
                const submitBtn = authForm.querySelector('.submit-lead-auth');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang xử lý...';
                submitBtn.disabled = true;
                
                const formData = new FormData(authForm);
                const data = Object.fromEntries(formData);
                
                try {
                    // Simulate API call - replace with actual endpoint
                    const response = await fetch('/user/leads/store', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        this.showSuccessMessage('Lead đã được tạo thành công! Các thợ sẽ liên hệ với bạn sớm.');
                        authForm.reset();
                        // Reset to step 1
                        window.authFormNav?.goToStep(1);
                    } else {
                        this.showErrorMessage(result.message || 'Có lỗi xảy ra, vui lòng thử lại');
                    }
                } catch (error) {
                    console.error('Form submission error:', error);
                    this.showErrorMessage('Có lỗi kết nối, vui lòng thử lại');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    }
    
    setupLoginFormSubmission() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = loginForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang đăng nhập...';
                submitBtn.disabled = true;
                
                const formData = new FormData(loginForm);
                const data = Object.fromEntries(formData);
                
                try {
                    // Simulate API call - replace with actual endpoint
                    const response = await fetch('/user/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        this.showSuccessMessage('Đăng nhập thành công!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showErrorMessage(result.message || 'Thông tin đăng nhập không chính xác');
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    this.showErrorMessage('Có lỗi kết nối, vui lòng thử lại');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    }
    
    setupRegisterFormSubmission() {
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const submitBtn = registerForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="las la-spinner la-spin me-2"></i>Đang đăng ký...';
                submitBtn.disabled = true;
                
                const formData = new FormData(registerForm);
                const data = Object.fromEntries(formData);
                
                // Validate password confirmation
                if (data.password !== data.password_confirmation) {
                    this.showErrorMessage('Mật khẩu xác nhận không khớp');
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    return;
                }
                
                try {
                    // Simulate API call - replace with actual endpoint
                    const response = await fetch('/user/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok && result.success) {
                        this.showSuccessMessage('Đăng ký thành công!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        this.showErrorMessage(result.message || 'Có lỗi xảy ra, vui lòng thử lại');
                    }
                } catch (error) {
                    console.error('Register error:', error);
                    this.showErrorMessage('Có lỗi kết nối, vui lòng thử lại');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
    }
    
    showSuccessMessage(message) {
        this.showMessage(message, 'success');
    }
    
    showErrorMessage(message) {
        this.showMessage(message, 'danger');
    }
    
    showMessage(message, type) {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.form-message');
        existingMessages.forEach(msg => msg.remove());
        
        // Create new message
        const messageEl = document.createElement('div');
        messageEl.className = `form-message alert alert-${type} position-fixed`;
        messageEl.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 400px;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        const icon = type === 'success' ? 'las la-check-circle' : 'las la-exclamation-triangle';
        messageEl.innerHTML = `<i class="${icon} me-2"></i>${message}`;
        
        document.body.appendChild(messageEl);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            messageEl.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                messageEl.remove();
            }, 300);
        }, 5000);
    }
}

// ================================
// CUSTOMER REVIEWS SLIDER
// ================================
class ReviewsSlider {
    constructor() {
        this.currentIndex = 0;
        this.reviews = document.querySelectorAll('.review-item');
        this.init();
    }
    
    init() {
        if (this.reviews.length > 1) {
            this.startAutoSlide();
        }
    }
    
    startAutoSlide() {
        setInterval(() => {
            this.nextReview();
        }, 4000);
    }
    
    nextReview() {
        this.reviews[this.currentIndex].classList.remove('active');
        this.currentIndex = (this.currentIndex + 1) % this.reviews.length;
        this.reviews[this.currentIndex].classList.add('active');
    }
}

// ================================
// SMOOTH SCROLLING
// ================================
class SmoothScrolling {
    constructor() {
        this.init();
    }
    
    init() {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (link) {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    }
}

// ================================
// STATISTICS COUNTER ANIMATION
// ================================
class StatsCounter {
    constructor() {
        this.init();
    }
    
    init() {
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCounters(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        const statNumbers = document.querySelectorAll('.stat-number[data-count]');
        statNumbers.forEach(stat => {
            observer.observe(stat);
        });
    }
    
    animateCounters(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current);
        }, 16);
    }
}

// ================================
// CATEGORY CARD HOVER EFFECTS
// ================================
class CategoryHoverEffects {
    constructor() {
        this.init();
    }
    
    init() {
        const categoryCards = document.querySelectorAll('.category-card-modern');
        
        categoryCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                this.addHoverEffect(card);
            });
            
            card.addEventListener('mouseleave', () => {
                this.removeHoverEffect(card);
            });
        });
    }
    
    addHoverEffect(card) {
        const icon = card.querySelector('.category-icon-modern');
        if (icon) {
            icon.style.transform = 'scale(1.1) rotate(5deg)';
            icon.style.transition = 'all 0.3s ease';
        }
    }
    
    removeHoverEffect(card) {
        const icon = card.querySelector('.category-icon-modern');
        if (icon) {
            icon.style.transform = 'scale(1) rotate(0deg)';
        }
    }
}

// ================================
// GUEST BUTTON FIX - ULTIMATE SOLUTION
// ================================
function ultimateGuestButtonFix() {
    console.log('🔧 Ultimate guest button fix...');
    
    // Fix next-step buttons with aggressive approach
    const nextBtns = document.querySelectorAll('.next-step');
    nextBtns.forEach((btn, i) => {
        // Clone to remove all existing listeners
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all properties
        newBtn.style.cssText += `
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 9999 !important;
            position: relative !important;
        `;
        newBtn.disabled = false;
        
        // Simple click handler
        newBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Ultimate next click!');
            
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            
            // Determine current step by visibility
            const step1Visible = step1 && window.getComputedStyle(step1).display !== 'none';
            const step2Visible = step2 && window.getComputedStyle(step2).display !== 'none';
            
            if (step1Visible) {
                step1.style.display = 'none';
                step2.style.display = 'block';
                console.log('✅ Ultimate: Step 1 -> 2');
            } else if (step2Visible) {
                step2.style.display = 'none';
                step3.style.display = 'block';
                console.log('✅ Ultimate: Step 2 -> 3');
            }
        };
    });
    
    // Fix prev-step buttons
    const prevBtns = document.querySelectorAll('.prev-step');
    prevBtns.forEach((btn, i) => {
        // Clone to remove all existing listeners
        const parent = btn.parentNode;
        const newBtn = btn.cloneNode(true);
        parent.replaceChild(newBtn, btn);
        
        // Force all properties
        newBtn.style.cssText += `
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 9999 !important;
            position: relative !important;
        `;
        newBtn.disabled = false;
        
        // Simple click handler
        newBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Ultimate prev click!');
            
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const step3 = document.getElementById('step3');
            
            // Determine current step by visibility
            const step2Visible = step2 && window.getComputedStyle(step2).display !== 'none';
            const step3Visible = step3 && window.getComputedStyle(step3).display !== 'none';
            
            if (step3Visible) {
                step3.style.display = 'none';
                step2.style.display = 'block';
                console.log('✅ Ultimate: Step 3 -> 2');
            } else if (step2Visible) {
                step2.style.display = 'none';
                step1.style.display = 'block';
                console.log('✅ Ultimate: Step 2 -> 1');
            }
        };
    });
}

// ================================
// MAIN INITIALIZATION
// ================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing complete home page functionality...');
    
    // Initialize mobile background handler
    handleMobileBackground();
    
    // Initialize all components
    const locationAPI = new VietnamLocationAPI();
    const tabSystem = new TabSystem();
    window.guestFormNav = new GuestFormNavigation();
    window.authFormNav = new AuthenticatedFormNavigation();
    const formSubmissions = new FormSubmissions();
    const reviewsSlider = new ReviewsSlider();
    const smoothScrolling = new SmoothScrolling();
    const statsCounter = new StatsCounter();
    const categoryHoverEffects = new CategoryHoverEffects();
    
    // Apply ultimate guest button fix
    setTimeout(ultimateGuestButtonFix, 100);
    setTimeout(ultimateGuestButtonFix, 500);
    setTimeout(ultimateGuestButtonFix, 1000);
    
    console.log('✅ All components initialized successfully');
    
    // Additional form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid && !form.id.includes('leadForm')) {
                e.preventDefault();
                console.log('Form validation failed');
            }
        });
    });
    
    // Real-time field validation
    document.addEventListener('input', (e) => {
        if (e.target.hasAttribute('required')) {
            if (e.target.value.trim()) {
                e.target.classList.remove('is-invalid');
            }
        }
    });
});

// ================================
// UTILITY FUNCTIONS
// ================================
window.goToStep1 = function() {
    if (window.guestFormNav) {
        window.guestFormNav.goToStep(1);
    } else {
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
        if (step3) step3.style.display = 'none';
    }
};

window.goToStep2 = function() {
    if (window.guestFormNav) {
        window.guestFormNav.goToStep(2);
    } else {
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';
        if (step3) step3.style.display = 'none';
    }
};

window.goToStep3 = function() {
    if (window.guestFormNav) {
        window.guestFormNav.goToStep(3);
    } else {
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'none';
        if (step3) step3.style.display = 'block';
    }
};

window.debugFormStatus = function() {
    console.log('=== FORM DEBUG STATUS ===');
    console.log('Guest form nav:', window.guestFormNav);
    console.log('Auth form nav:', window.authFormNav);
    
    const steps = ['step1', 'step2', 'step3'];
    steps.forEach(stepId => {
        const el = document.getElementById(stepId);
        if (el) {
            console.log(`${stepId}:`, {
                exists: true,
                display: getComputedStyle(el).display,
                visible: el.offsetParent !== null
            });
        } else {
            console.log(`${stepId}: Not found`);
        }
    });
    
    const nextBtns = document.querySelectorAll('.next-step');
    const prevBtns = document.querySelectorAll('.prev-step');
    console.log('Next buttons:', nextBtns.length);
    console.log('Prev buttons:', prevBtns.length);
};

window.forceFixButtons = function() {
    ultimateGuestButtonFix();
};

console.log('🎉 Complete home functionality loaded successfully!'); 