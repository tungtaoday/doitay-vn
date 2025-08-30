<!-- Optimized Fast Preloader -->
<div class="fast-preloader" id="fastPreloader">
    <div class="preloader-content">
        <!-- Simple Logo -->
        <div class="logo-container">
            <img src="{{ getImage(getFilePath('logo_icon') . '/logo.png') }}" 
                 alt="{{ gs('site_name') }}" 
                 class="logo-img"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-fallback" style="display: none;">
                <span>{{ substr(gs('site_name'), 0, 2) }}</span>
            </div>
        </div>
        
        <!-- Simple Loading Bar -->
        <div class="loading-bar">
            <div class="loading-fill" id="loadingFill"></div>
        </div>
        
        <!-- Skip Button -->
        <button class="skip-btn" id="skipPreloader" onclick="skipPreloader()">
            Bỏ qua
        </button>
    </div>
</div>

<style>
/* Fast Preloader - Minimal CSS */
.fast-preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: #ffffff;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

.fast-preloader.fade-out {
    opacity: 0;
    pointer-events: none;
}

.preloader-content {
    text-align: center;
    max-width: 300px;
    padding: 2rem;
}

.logo-container {
    margin-bottom: 2rem;
}

.logo-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.logo-fallback {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #1e3a8a, #3b82f6);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
}

.loading-bar {
    width: 200px;
    height: 4px;
    background: #f3f4f6;
    border-radius: 2px;
    overflow: hidden;
    margin: 0 auto 1rem;
}

.loading-fill {
    height: 100%;
    background: linear-gradient(90deg, #1e3a8a, #3b82f6);
    width: 0%;
    transition: width 0.2s ease;
}

.skip-btn {
    background: transparent;
    border: 1px solid #d1d5db;
    color: #6b7280;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.skip-btn:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

/* Mobile Optimized */
@media (max-width: 768px) {
    .preloader-content {
        padding: 1rem;
        max-width: 250px;
    }
    
    .logo-img, .logo-fallback {
        width: 60px;
        height: 60px;
    }
    
    .loading-bar {
        width: 150px;
    }
}

/* Tablet Optimization */
@media (max-width: 1024px) and (min-width: 769px) {
    .preloader-content {
        padding: 1.5rem;
        max-width: 280px;
    }
    
    .logo-img, .logo-fallback {
        width: 70px;
        height: 70px;
    }
    
    .loading-bar {
        width: 180px;
    }
}

/* Small Mobile Optimization */
@media (max-width: 480px) {
    .preloader-content {
        padding: 0.75rem;
        max-width: 200px;
    }
    
    .logo-img, .logo-fallback {
        width: 50px;
        height: 50px;
    }
    
    .loading-bar {
        width: 120px;
        height: 3px;
    }
    
    .skip-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }
}

/* Ultra Small Mobile */
@media (max-width: 360px) {
    .preloader-content {
        padding: 0.5rem;
        max-width: 180px;
    }
    
    .logo-img, .logo-fallback {
        width: 45px;
        height: 45px;
    }
    
    .loading-bar {
        width: 100px;
        height: 2px;
    }
    
    .skip-btn {
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
    }
}

/* Landscape Mobile Optimization */
@media (max-height: 500px) and (orientation: landscape) {
    .preloader-content {
        padding: 0.5rem;
        max-width: 200px;
    }
    
    .logo-container {
        margin-bottom: 1rem;
    }
    
    .logo-img, .logo-fallback {
        width: 40px;
        height: 40px;
    }
    
    .loading-bar {
        width: 120px;
        margin: 0 auto 0.5rem;
    }
}

/* High DPI Mobile Devices */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .logo-img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}

/* Touch Device Optimization */
@media (hover: none) and (pointer: coarse) {
    .skip-btn {
        min-height: 44px; /* Apple's recommended touch target size */
        min-width: 44px;
    }
    
    .skip-btn:active {
        background: #e5e7eb;
        transform: scale(0.95);
    }
}

/* Reduced Motion for Accessibility */
@media (prefers-reduced-motion: reduce) {
    .loading-fill {
        transition: none;
    }
    
    .fast-preloader {
        transition: none;
    }
    
    .skip-btn {
        transition: none;
    }
}
</style>

<script>
// Ultra-Fast Preloader
(function() {
    'use strict';
    
    const preloader = document.getElementById('fastPreloader');
    const loadingFill = document.getElementById('loadingFill');
    const skipBtn = document.getElementById('skipPreloader');
    
    if (!preloader) return;
    
    // Mobile-optimized configuration
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 480;
    
    const CONFIG = {
        minDuration: isMobile ? 600 : 800,        // Faster on mobile
        maxDuration: isMobile ? 2000 : 3000,      // Faster on mobile
        showSkipAfter: isMobile ? 1000 : 1500,   // Faster on mobile
        progressInterval: isMobile ? 30 : 50      // Faster updates on mobile
    };
    
    let progress = 0;
    let isSkipped = false;
    let startTime = Date.now();
    
    // Fast progress simulation
    function startFastLoading() {
        const interval = setInterval(() => {
            if (isSkipped) {
                clearInterval(interval);
                return;
            }
            
            // Faster progress curve
            progress += Math.random() * 8 + 2; // 2-10% per update
            
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                completeLoading();
            }
            
            updateProgress(progress);
        }, CONFIG.progressInterval);
        
        // Safety timeout
        setTimeout(() => {
            clearInterval(interval);
            if (!isSkipped) completeLoading();
        }, CONFIG.maxDuration);
    }
    
    function updateProgress(newProgress) {
        if (loadingFill) {
            loadingFill.style.width = Math.min(newProgress, 100) + '%';
        }
    }
    
    function completeLoading() {
        updateProgress(100);
        
        setTimeout(() => {
            hidePreloader();
        }, 200); // Reduced from 400ms to 200ms
    }
    
    function hidePreloader() {
        if (!preloader) return;
        
        preloader.classList.add('fade-out');
        
        setTimeout(() => {
            if (preloader && preloader.parentNode) {
                preloader.parentNode.removeChild(preloader);
            }
        }, 300); // Reduced from 600ms to 300ms
    }
    
    // Global skip function
    window.skipPreloader = function() {
        isSkipped = true;
        hidePreloader();
    };
    
    // Show skip button after delay
    setTimeout(() => {
        if (skipBtn && !isSkipped) {
            skipBtn.style.opacity = '1';
        }
    }, CONFIG.showSkipAfter);
    
    // Start loading immediately
    startFastLoading();
    
    // Auto-hide if page loads very fast
    if (document.readyState === 'complete') {
        setTimeout(() => {
            if (!isSkipped) completeLoading();
        }, CONFIG.minDuration);
    }
    
    // Handle orientation change on mobile
    if (isMobile) {
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                // Recalculate mobile status after orientation change
                const newIsMobile = window.innerWidth <= 768;
                if (newIsMobile !== isMobile) {
                    location.reload(); // Reload for better mobile experience
                }
            }, 500);
        });
    }
    
})();
</script> 