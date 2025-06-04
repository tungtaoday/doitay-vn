<!-- Professional Preloader -->
<div class="professional-preloader" id="professionalPreloader" role="dialog" aria-labelledby="preloader-title" aria-describedby="preloader-status">
    <div class="preloader-background"></div>
    <div class="preloader-container">
        <!-- Logo Section -->
        <div class="logo-section">
            <div class="logo-frame">
                <img src="{{ getImage(getFilePath('logo_icon') . '/logo.png') }}" 
                     alt="{{ gs('site_name') }}" 
                     class="brand-logo"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                     loading="eager">
                <div class="logo-fallback" style="display: none;">
                    <span class="fallback-text">{{ substr(gs('site_name'), 0, 2) }}</span>
                </div>
            </div>
            <div class="brand-subtitle">
                <h1 id="preloader-title" class="subtitle-text">Tìm thợ chuyên nghiệp nhanh & tin cậy</h1>
                <!-- <p class="subtitle-text">Nền tảng dịch vụ chuyên nghiệp</p> -->
            </div>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-container">
                <div class="progress-track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <div class="progress-info">
                    <span class="progress-percentage" id="progressPercentage" aria-live="polite">0%</span>
                    <span class="progress-status" id="progressStatus" aria-live="polite" aria-describedby="preloader-status">Đang khởi tạo</span>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section" role="list">
            <div class="feature-item" role="listitem">
                <div class="feature-icon verified" aria-hidden="true">
                    <i class="las la-shield-alt"></i>
                </div>
                <span>Đã xác minh</span>
            </div>
            <div class="feature-item" role="listitem">
                <div class="feature-icon professional" aria-hidden="true">
                    <i class="las la-tools"></i>
                </div>
                <span>Chuyên nghiệp</span>
            </div>
            <div class="feature-item" role="listitem">
                <div class="feature-icon responsive" aria-hidden="true">
                    <i class="las la-clock"></i>
                </div>
                <span>Phản hồi nhanh</span>
            </div>
        </div>

        <!-- Skip button for accessibility -->
        <button class="skip-preloader" id="skipPreloader" aria-label="Bỏ qua màn hình tải">
            <i class="las la-times"></i>
        </button>
    </div>
</div>

<style>
/* Professional Preloader Styles */
.professional-preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    visibility: visible;
    transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.professional-preloader.fade-out {
    opacity: 0;
    visibility: hidden;
}

.preloader-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f6f0 0%, #ffffff 100%);
    /* Cream/Off-White background */
}

.preloader-container {
    position: relative;
    text-align: center;
    max-width: 480px;
    padding: 2rem 2rem;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.04);
    backdrop-filter: blur(20px);
    /* Fallback for browsers without backdrop-filter */
    background: rgba(255, 255, 255, 0.95);
}

/* Modern browsers with backdrop-filter support */
@supports (backdrop-filter: blur(20px)) {
    .preloader-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
    }
}

/* Logo Section */
.logo-section {
    margin-bottom: 1rem;
    margin-top: 0rem;
}

.logo-frame {
    width: 160px;
    height: 160px;
    margin: 0 auto 0.5rem;
    background: transparent;
    /* No background */
    border-radius: 0;
    /* No border radius */
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    /* Remove all animations and effects */
}

.logo-frame::before {
    display: none;
    /* Hide the gradient border effect */
}

.brand-logo {
    width: 100px;
    height: 100px;
    object-fit: contain;
    /* Show original logo as-is */
}

/* Logo Fallback */
.logo-fallback {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #1e3a8a, #10b981);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.fallback-text {
    font-size: 2rem;
    font-weight: 800;
    color: white;
    text-transform: uppercase;
    letter-spacing: -0.05em;
}

/* Brand Subtitle */
.brand-subtitle {
    margin-top: 0rem;
    text-align: center;
}

.subtitle-text {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e3a8a;
    /* Dark Blue */
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.025em;
}

.subtitle-desc {
    font-size: 1rem;
    color: #6b7280;
    /* Light Gray */
    margin: 0;
    font-weight: 500;
    opacity: 0.9;
}

/* Skip Button */
.skip-preloader {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: #6b7280;
    font-size: 1.25rem;
    opacity: 0.7;
}

.skip-preloader:hover,
.skip-preloader:focus {
    background: rgba(0, 0, 0, 0.15);
    opacity: 1;
    transform: scale(1.05);
}

.skip-preloader:focus {
    outline: 2px solid #1e3a8a;
    outline-offset: 2px;
}

/* Progress Section */
.progress-section {
    margin-bottom: 2.5rem;
}

.progress-container {
    max-width: 300px;
    margin: 0 auto;
}

.progress-track {
    width: 100%;
    height: 8px;
    background: #f3f4f6;
    /* Light Gray */
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 1rem;
    position: relative;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #1e3a8a, #10b981);
    /* Dark Blue to Green */
    border-radius: 4px;
    width: 0%;
    transition: width 0.3s ease;
    position: relative;
}

.progress-fill::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: shimmer 2s infinite;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.progress-percentage {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1e3a8a;
    /* Dark Blue */
}

.progress-status {
    font-size: 0.95rem;
    color: #6b7280;
    /* Light Gray */
    font-weight: 500;
}

/* Features Section */
.features-section {
    display: flex;
    justify-content: space-around;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 0 0.5rem;
}

.feature-item {
    flex: 1;
    min-width: 70px;
    max-width: 90px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    opacity: 0;
    animation: slideInUp 0.6s ease forwards;
}

.feature-item:nth-child(1) { animation-delay: 0.2s; }
.feature-item:nth-child(2) { animation-delay: 0.4s; }
.feature-item:nth-child(3) { animation-delay: 0.6s; }

.feature-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: white;
    position: relative;
}

.feature-icon.verified {
    background: linear-gradient(135deg, #10b981, #059669);
    /* Green */
}

.feature-icon.professional {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
    /* Dark Blue */
}

.feature-icon.responsive {
    background: linear-gradient(135deg, #ea580c, #dc2626);
    /* Terracotta/Coral accent */
}

.feature-item span {
    font-size: 0.7rem;
    color: #374151;
    font-weight: 500;
    text-align: center;
    line-height: 1.1;
}

/* Animations */
@keyframes logoGlow {
    0% {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transform: translateY(0);
    }
    100% {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
}

@keyframes borderGlow {
    0% {
        opacity: 0.1;
    }
    100% {
        opacity: 0.2;
    }
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .preloader-container {
        margin: 1rem;
        padding: 1.5rem 1.5rem;
        max-width: none;
    }
    
    .logo-section {
        margin-top: 1rem;
        margin-bottom: 0.75rem;
    }
    
    .logo-frame {
        width: 140px;
        height: 140px;
    }
    
    .brand-logo, .logo-fallback {
        width: 90px;
        height: 90px;
    }
    
    .fallback-text {
        font-size: 1.8rem;
    }
    
    .subtitle-text {
        font-size: 1.25rem;
    }
    
    .features-section {
        gap: 1rem;
        padding: 0 0.5rem;
    }
    
    .feature-item {
        flex: 1;
        min-width: 70px;
        max-width: 90px;
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
    
    .feature-item span {
        font-size: 0.75rem;
        line-height: 1.1;
    }
    
    .skip-preloader {
        width: 40px;
        height: 40px;
        font-size: 1.1rem;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .professional-preloader,
    .professional-preloader * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Smooth page transition */
body.loading {
    overflow: hidden;
}

/* Multiple Responsive Breakpoints */

/* Ultra-wide screens (1440px+) */
@media (min-width: 1440px) {
    .preloader-container {
        max-width: 520px;
        padding: 2.5rem;
    }
    
    .logo-frame {
        width: 280px;
        height: 280px;
    }
    
    .brand-logo, .logo-fallback {
        width: 180px;
        height: 180px;
    }
    
    .subtitle-text {
        font-size: 1.75rem;
    }
}

/* Large tablets and small desktops (1024px to 1439px) */
@media (max-width: 1439px) and (min-width: 1024px) {
    .logo-frame {
        width: 220px;
        height: 220px;
    }
    
    .brand-logo, .logo-fallback {
        width: 140px;
        height: 140px;
    }
}

/* Standard tablets (768px to 1023px) */
@media (max-width: 1023px) and (min-width: 769px) {
    .preloader-container {
        padding: 1.75rem;
        margin: 1.5rem;
    }
    
    .logo-frame {
        width: 200px;
        height: 200px;
    }
    
    .brand-logo, .logo-fallback {
        width: 120px;
        height: 120px;
    }
    
    .subtitle-text {
        font-size: 1.4rem;
    }
    
    .features-section {
        gap: 1.25rem;
    }
}

/* Small mobile devices (max-width: 480px) */
@media (max-width: 480px) {
    .preloader-container {
        margin: 0.75rem;
        padding: 1.25rem 1rem;
    }
    
    .logo-frame {
        width: 160px;
        height: 160px;
    }
    
    .brand-logo, .logo-fallback {
        width: 100px;
        height: 100px;
    }
    
    .fallback-text {
        font-size: 2rem;
    }
    
    .subtitle-text {
        font-size: 1.1rem;
        line-height: 1.3;
    }
    
    .progress-container {
        max-width: 240px;
    }
    
    .feature-item {
        min-width: 60px;
        max-width: 80px;
    }
    
    .feature-icon {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .feature-item span {
        font-size: 0.7rem;
    }
}

/* Very small devices (max-width: 320px) */
@media (max-width: 320px) {
    .preloader-container {
        margin: 0.5rem;
        padding: 1rem 0.75rem;
    }
    
    .logo-frame {
        width: 120px;
        height: 120px;
    }
    
    .brand-logo, .logo-fallback {
        width: 80px;
        height: 80px;
    }
    
    .fallback-text {
        font-size: 1.5rem;
    }
    
    .subtitle-text {
        font-size: 1rem;
    }
    
    .features-section {
        gap: 0.75rem;
    }
    
    .feature-item {
        min-width: 50px;
        max-width: 70px;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .preloader-background {
        background: #000000;
    }
    
    .preloader-container {
        background: #ffffff;
        border: 2px solid #000000;
        box-shadow: none;
    }
    
    .subtitle-text {
        color: #000000;
    }
    
    .progress-track {
        background: #cccccc;
        border: 1px solid #000000;
    }
    
    .progress-fill {
        background: #000000;
    }
    
    .feature-icon {
        border: 2px solid #000000;
    }
    
    .skip-preloader {
        background: #ffffff;
        border: 2px solid #000000;
        color: #000000;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .preloader-background {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    }
    
    .preloader-container {
        background: rgba(40, 40, 40, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .subtitle-text {
        color: #ffffff;
    }
    
    .progress-track {
        background: #404040;
    }
    
    .progress-status {
        color: #cccccc;
    }
    
    .feature-item span {
        color: #cccccc;
    }
}
</style>

<script>
// Enhanced Professional Preloader with Modern APIs
(function() {
    'use strict';
    
    // Feature detection
    const hasIntersectionObserver = 'IntersectionObserver' in window;
    const hasRequestAnimationFrame = 'requestAnimationFrame' in window;
    const hasPerformanceAPI = 'performance' in window && 'navigation' in performance;
    
    // Configuration
    const CONFIG = {
        minDuration: 1500, // Minimum loading time
        maxDuration: 8000, // Maximum loading time
        adaptiveTiming: true, // Adjust timing based on connection
        showSkipAfter: 3000, // Show skip button after 3 seconds
    };
    
    // Get connection speed estimate
    function getConnectionSpeed() {
        if ('connection' in navigator) {
            const connection = navigator.connection;
            if (connection.effectiveType) {
                const speedMap = {
                    'slow-2g': 0.3,
                    '2g': 0.5,
                    '3g': 0.7,
                    '4g': 1.0
                };
                return speedMap[connection.effectiveType] || 1.0;
            }
        }
        return 1.0; // Default speed multiplier
    }
    
    // Adaptive timing based on connection and page load
    function calculateTiming() {
        const speedMultiplier = getConnectionSpeed();
        const baseTime = CONFIG.minDuration;
        
        // Adjust based on performance if available
        if (hasPerformanceAPI) {
            const loadTime = performance.navigation.type === 1 ? 
                performance.timing.loadEventEnd - performance.timing.navigationStart : baseTime;
            return Math.min(Math.max(loadTime * speedMultiplier, CONFIG.minDuration), CONFIG.maxDuration);
        }
        
        return Math.min(baseTime / speedMultiplier, CONFIG.maxDuration);
    }
    
    // Enhanced progress animation using RAF
    function animateProgress(element, targetWidth, duration, onUpdate) {
        if (!hasRequestAnimationFrame) {
            element.style.width = targetWidth + '%';
            if (onUpdate) onUpdate(targetWidth);
            return;
        }
        
        const startTime = performance.now();
        const startWidth = parseFloat(element.style.width) || 0;
        const widthDiff = targetWidth - startWidth;
        
        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Smooth easing function
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const currentWidth = startWidth + (widthDiff * easeOut);
            
            element.style.width = currentWidth + '%';
            if (onUpdate) onUpdate(Math.round(currentWidth));
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
        
        requestAnimationFrame(animate);
    }
    
    // Preloader manager class
    class PreloaderManager {
        constructor() {
            this.preloader = document.getElementById('professionalPreloader');
            this.progressFill = document.getElementById('progressFill');
            this.progressPercentage = document.getElementById('progressPercentage');
            this.progressStatus = document.getElementById('progressStatus');
            this.progressTrack = document.querySelector('.progress-track');
            this.skipButton = document.getElementById('skipPreloader');
            
            this.progress = 0;
            this.currentStateIndex = 0;
            this.isSkipped = false;
            this.startTime = Date.now();
            this.estimatedDuration = calculateTiming();
            
            this.loadingStates = [
                { message: 'Đang khởi tạo', minProgress: 0 },
                { message: 'Đang tải dữ liệu', minProgress: 25 },
                { message: 'Đang kết nối', minProgress: 50 },
                { message: 'Chuẩn bị giao diện', minProgress: 75 },
                { message: 'Hoàn tất', minProgress: 95 }
            ];
            
            this.init();
        }
        
        init() {
            if (!this.preloader) return;
            
            // Add loading class to body
            document.body.classList.add('loading');
            
            // Update progress bar ARIA attributes
            this.updateProgressBar(0);
            
            // Setup skip button
            this.setupSkipButton();
            
            // Start loading simulation
            this.startLoading();
            
            // Show skip button after delay
            setTimeout(() => {
                if (!this.isSkipped && this.skipButton) {
                    this.skipButton.style.opacity = '0.7';
                    this.skipButton.style.pointerEvents = 'auto';
                }
            }, CONFIG.showSkipAfter);
        }
        
        setupSkipButton() {
            if (!this.skipButton) return;
            
            // Initially hide skip button
            this.skipButton.style.opacity = '0';
            this.skipButton.style.pointerEvents = 'none';
            
            // Add event listeners
            this.skipButton.addEventListener('click', () => this.skipPreloader());
            this.skipButton.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.skipPreloader();
                }
            });
        }
        
        updateProgressBar(progress) {
            if (this.progressTrack) {
                this.progressTrack.setAttribute('aria-valuenow', Math.round(progress));
            }
        }
        
        updateProgress(newProgress, immediate = false) {
            const clampedProgress = Math.min(Math.max(newProgress, 0), 100);
            
            if (immediate) {
                this.progressFill.style.width = clampedProgress + '%';
                this.progressPercentage.textContent = Math.round(clampedProgress) + '%';
                this.updateProgressBar(clampedProgress);
            } else {
                animateProgress(this.progressFill, clampedProgress, 300, (currentProgress) => {
                    this.progressPercentage.textContent = currentProgress + '%';
                    this.updateProgressBar(currentProgress);
                });
            }
            
            this.progress = clampedProgress;
            this.updateLoadingState();
        }
        
        updateLoadingState() {
            for (let i = this.loadingStates.length - 1; i >= 0; i--) {
                if (this.progress >= this.loadingStates[i].minProgress && i > this.currentStateIndex) {
                    this.currentStateIndex = i;
                    this.progressStatus.textContent = this.loadingStates[i].message;
                    break;
                }
            }
        }
        
        startLoading() {
            const updateInterval = 100; // Update every 100ms
            const totalUpdates = this.estimatedDuration / updateInterval;
            let updateCount = 0;
            
            const progressInterval = setInterval(() => {
                if (this.isSkipped) {
                    clearInterval(progressInterval);
                    return;
                }
                
                updateCount++;
                
                // Realistic progress curve - faster at start, slower at end
                const normalizedTime = updateCount / totalUpdates;
                const progressCurve = 1 - Math.pow(1 - normalizedTime, 2);
                const targetProgress = Math.min(progressCurve * 100, 99);
                
                // Add some randomness for realism
                const jitter = (Math.random() - 0.5) * 2;
                const newProgress = Math.min(targetProgress + jitter, 100);
                
                this.updateProgress(newProgress);
                
                // Complete loading
                if (this.progress >= 99 || updateCount >= totalUpdates) {
                    clearInterval(progressInterval);
                    this.completeLoading();
                }
            }, updateInterval);
            
            // Safety timeout
            setTimeout(() => {
                clearInterval(progressInterval);
                if (!this.isSkipped) {
                    this.completeLoading();
                }
            }, CONFIG.maxDuration);
        }
        
        completeLoading() {
            this.updateProgress(100, true);
            this.progressStatus.textContent = 'Hoàn tất';
            
            setTimeout(() => {
                this.hidePreloader();
            }, 400);
        }
        
        skipPreloader() {
            this.isSkipped = true;
            this.hidePreloader();
        }
        
        hidePreloader() {
            if (!this.preloader) return;
            
            // Add fade-out class
            this.preloader.classList.add('fade-out');
            document.body.classList.remove('loading');
            
            // Remove from DOM after animation
            setTimeout(() => {
                if (this.preloader && this.preloader.parentNode) {
                    this.preloader.parentNode.removeChild(this.preloader);
                }
            }, 600);
            
            // Trigger custom event for page scripts
            if ('CustomEvent' in window) {
                window.dispatchEvent(new CustomEvent('preloaderComplete', {
                    detail: { 
                        skipped: this.isSkipped,
                        duration: Date.now() - this.startTime
                    }
                }));
            }
        }
    }
    
    // Initialize when DOM is ready
    function initPreloader() {
        // Check if preloader exists
        if (!document.getElementById('professionalPreloader')) return;
        
        // Initialize preloader manager
        new PreloaderManager();
    }
    
    // Multiple initialization strategies for better compatibility
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPreloader);
    } else {
        initPreloader();
    }
    
    // Fallback for very slow loads
    if (document.readyState === 'loading') {
        window.addEventListener('load', function() {
            setTimeout(initPreloader, 100);
        });
    }
    
})();
</script>
