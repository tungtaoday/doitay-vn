<!-- Modern Menu CSS -->
<style>
/* Reset any existing styles */
* {
    box-sizing: border-box;
}

/* Hide old header completely */
.header, .header__bottom, .navbar, .navbar-nav, .navbar-toggler {
    display: none !important;
}

/* ===== MODERN HEADER STYLES ===== */
.modern-header {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 9999 !important;
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px) !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.header-container {
    max-width: 1200px !important;
    margin: 0 auto !important;
    padding: 0 24px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    height: 80px !important;
}

/* Logo */
.modern-header .logo {
    text-decoration: none !important;
}

.modern-header .logo img {
    height: 50px !important;
    width: auto !important;
    transition: all 0.3s ease !important;
}

.modern-header .logo:hover img {
    transform: scale(1.05) !important;
}

/* Desktop Navigation */
.modern-header .desktop-nav {
    display: flex !important;
    align-items: center !important;
}

.modern-header .nav-menu {
    display: flex !important;
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    gap: 8px !important;
}

.modern-header .nav-menu li {
    margin: 0 !important;
    padding: 0 !important;
}

.modern-header .nav-menu li a {
    display: block !important;
    padding: 12px 20px !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    color: #374151 !important;
    text-decoration: none !important;
    border-radius: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
}

.modern-header .nav-menu li:hover a,
.modern-header .nav-menu li.active a {
    color: #0b92d4 !important;
    background: rgba(11, 146, 212, 0.1) !important;
    transform: translateY(-2px) !important;
}

/* Header Actions */
.modern-header .header-actions {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
}

.modern-header .btn-login,
.modern-header .btn-register {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding: 10px 20px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    border-radius: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.modern-header .btn-login {
    color: #0b92d4 !important;
    background: transparent !important;
    border: 1px solid rgba(11, 146, 212, 0.3) !important;
}

.modern-header .btn-login:hover {
    background: rgba(11, 146, 212, 0.1) !important;
    transform: translateY(-2px) !important;
}

.modern-header .btn-register {
    color: white !important;
    background: linear-gradient(135deg, #0b92d4, #0d84c1) !important;
    border: none !important;
    box-shadow: 0 4px 15px rgba(11, 146, 212, 0.3) !important;
}

.modern-header .btn-register:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(11, 146, 212, 0.4) !important;
}

/* User Dropdown */
.modern-header .user-dropdown {
    position: relative !important;
}

.modern-header .user-toggle {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 8px 16px !important;
    background: transparent !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
    border-radius: 12px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}

.modern-header .user-toggle:hover {
    background: rgba(0, 0, 0, 0.05) !important;
}

.modern-header .user-avatar {
    width: 32px !important;
    height: 32px !important;
    background: linear-gradient(135deg, #0b92d4, #0d84c1) !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
}

.modern-header .user-name {
    font-weight: 500 !important;
    color: #374151 !important;
}

.modern-header .user-menu {
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    width: 240px !important;
    background: white !important;
    border-radius: 16px !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
    padding: 12px !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transform: translateY(10px) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    margin-top: 8px !important;
}

.modern-header .user-dropdown:hover .user-menu {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}

.modern-header .user-menu a {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 12px 16px !important;
    color: #374151 !important;
    text-decoration: none !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
    font-size: 14px !important;
}

.modern-header .user-menu a:hover {
    background: rgba(11, 146, 212, 0.1) !important;
    color: #0b92d4 !important;
}

.modern-header .user-menu a.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #dc2626 !important;
}

.modern-header .menu-divider {
    height: 1px !important;
    background: rgba(0, 0, 0, 0.1) !important;
    margin: 8px 0 !important;
}

/* Mobile Menu Trigger */
.modern-header .mobile-menu-trigger {
    display: none !important;
    flex-direction: column !important;
    justify-content: space-around !important;
    width: 32px !important;
    height: 32px !important;
    background: transparent !important;
    border: none !important;
    cursor: pointer !important;
    padding: 6px !important;
}

.modern-header .hamburger-line {
    width: 100% !important;
    height: 2px !important;
    background: #374151 !important;
    border-radius: 2px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    transform-origin: center !important;
}

.modern-header .mobile-menu-trigger.active .hamburger-line:nth-child(1) {
    transform: rotate(45deg) translate(6px, 6px) !important;
}

.modern-header .mobile-menu-trigger.active .hamburger-line:nth-child(2) {
    opacity: 0 !important;
}

.modern-header .mobile-menu-trigger.active .hamburger-line:nth-child(3) {
    transform: rotate(-45deg) translate(6px, -6px) !important;
}

/* Mobile Menu Overlay */
.mobile-menu-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100vh !important;
    background: rgba(255, 255, 255, 0.98) !important;
    backdrop-filter: blur(20px) !important;
    z-index: 99999 !important;
    opacity: 0 !important;
    visibility: hidden !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.mobile-menu-overlay.active {
    opacity: 1 !important;
    visibility: visible !important;
}

.mobile-menu-content {
    height: 100% !important;
    overflow-y: auto !important;
    padding: 24px !important;
}

.mobile-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 48px !important;
}

.mobile-logo img {
    height: 40px !important;
}

.mobile-close {
    width: 48px !important;
    height: 48px !important;
    background: rgba(0, 0, 0, 0.05) !important;
    border: none !important;
    border-radius: 12px !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 24px !important;
    color: #374151 !important;
    transition: all 0.3s ease !important;
}

.mobile-close:hover {
    background: rgba(0, 0, 0, 0.1) !important;
    transform: scale(1.05) !important;
}

/* Mobile Navigation */
.mobile-menu-list {
    list-style: none !important;
    margin: 0 !important;
    padding: 0 !important;
    margin-bottom: 48px !important;
}

.mobile-menu-list li {
    margin-bottom: 8px !important;
}

.mobile-menu-list li a {
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    padding: 20px 24px !important;
    font-size: 18px !important;
    font-weight: 500 !important;
    color: #374151 !important;
    text-decoration: none !important;
    background: rgba(0, 0, 0, 0.02) !important;
    border-radius: 16px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.mobile-menu-list li a:hover,
.mobile-menu-list li.active a {
    background: rgba(11, 146, 212, 0.1) !important;
    color: #0b92d4 !important;
    transform: translateX(8px) !important;
}

.mobile-menu-list li a i {
    font-size: 24px !important;
    width: 32px !important;
    text-align: center !important;
}

/* Mobile Auth Actions */
.mobile-auth {
    border-top: 1px solid rgba(0, 0, 0, 0.1) !important;
    padding-top: 32px !important;
}

.mobile-btn {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 12px !important;
    width: 100% !important;
    padding: 16px 24px !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    border-radius: 16px !important;
    margin-bottom: 12px !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.mobile-btn-primary {
    color: white !important;
    background: linear-gradient(135deg, #0b92d4, #0d84c1) !important;
    box-shadow: 0 8px 32px rgba(11, 146, 212, 0.3) !important;
}

.mobile-btn-primary:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 12px 40px rgba(11, 146, 212, 0.4) !important;
}

.mobile-btn-secondary {
    color: #0b92d4 !important;
    background: rgba(11, 146, 212, 0.1) !important;
    border: 1px solid rgba(11, 146, 212, 0.3) !important;
}

.mobile-btn-secondary:hover {
    background: rgba(11, 146, 212, 0.2) !important;
    transform: translateY(-2px) !important;
}

/* Mobile User Info */
.mobile-user-info {
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    padding: 24px !important;
    background: rgba(11, 146, 212, 0.05) !important;
    border-radius: 16px !important;
    margin-bottom: 24px !important;
}

.mobile-user-avatar {
    width: 56px !important;
    height: 56px !important;
    background: linear-gradient(135deg, #0b92d4, #0d84c1) !important;
    border-radius: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: white !important;
    font-size: 24px !important;
}

.mobile-user-details h4 {
    margin: 0 !important;
    font-size: 18px !important;
    font-weight: 600 !important;
    color: #374151 !important;
}

.mobile-user-details p {
    margin: 4px 0 0 0 !important;
    font-size: 14px !important;
    color: #6b7280 !important;
}

.mobile-action-btn {
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    padding: 16px 20px !important;
    font-size: 16px !important;
    color: #374151 !important;
    text-decoration: none !important;
    background: rgba(0, 0, 0, 0.02) !important;
    border-radius: 12px !important;
    margin-bottom: 8px !important;
    transition: all 0.3s ease !important;
}

.mobile-action-btn:hover {
    background: rgba(11, 146, 212, 0.1) !important;
    color: #0b92d4 !important;
    transform: translateX(8px) !important;
}

.mobile-action-btn.logout:hover {
    background: rgba(239, 68, 68, 0.1) !important;
    color: #dc2626 !important;
}

/* Responsive Behavior */
@media (max-width: 1024px) {
    .modern-header .desktop-nav,
    .modern-header .header-actions .btn-login,
    .modern-header .header-actions .btn-register,
    .modern-header .user-dropdown {
        display: none !important;
    }
    
    .modern-header .mobile-menu-trigger {
        display: flex !important;
    }
}

@media (max-width: 768px) {
    .modern-header .header-container {
        padding: 0 16px !important;
        height: 70px !important;
    }
    
    .modern-header .logo img {
        height: 40px !important;
    }
    
    .mobile-menu-content {
        padding: 16px !important;
    }
    
    .mobile-menu-list li a {
        padding: 16px 20px !important;
        font-size: 16px !important;
    }
}

/* Body padding for fixed header */
body {
    padding-top: 80px !important;
}

@media (max-width: 768px) {
    body {
        padding-top: 70px !important;
    }
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth !important;
}

/* Override any conflicting styles */
.hero.bg_img {
    margin-top: 0 !important;
    padding-top: 120px !important;
}

.banner {
    margin-top: 0 !important;
    padding-top: 120px !important;
}

/* Ensure main content has proper spacing */
.main-wrapper {
    margin-top: 0 !important;
    padding-top: 0 !important;
}
</style>

<!-- Modern Menu JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Đảm bảo script chỉ chạy sau khi DOM loaded
    setTimeout(function() {
        const mobileMenuTrigger = document.getElementById('mobileMenuTrigger');
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        
        if (!mobileMenuTrigger || !mobileMenuOverlay || !mobileMenuClose) {
            console.log('Modern menu elements not found, retrying...');
            return;
        }
        
        // Open mobile menu
        mobileMenuTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            mobileMenuTrigger.classList.add('active');
            mobileMenuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
        
        // Close mobile menu
        function closeMobileMenu() {
            mobileMenuTrigger.classList.remove('active');
            mobileMenuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        mobileMenuClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });
        
        // Close on overlay click
        mobileMenuOverlay.addEventListener('click', function(e) {
            if (e.target === mobileMenuOverlay) {
                closeMobileMenu();
            }
        });
        
        // Close on menu item click (mobile)
        const mobileMenuLinks = document.querySelectorAll('.mobile-menu-list a, .mobile-action-btn');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Delay to allow navigation
                setTimeout(closeMobileMenu, 100);
            });
        });
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('active')) {
                closeMobileMenu();
            }
        });
        
        // Header scroll effect
        let lastScrollTop = 0;
        const header = document.querySelector('.modern-header');
        
        if (header) {
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up
                    header.style.transform = 'translateY(0)';
                }
                
                // Change background opacity on scroll
                if (scrollTop > 50) {
                    header.style.background = 'rgba(255, 255, 255, 0.98)';
                    header.style.borderBottomColor = 'rgba(0, 0, 0, 0.1)';
                } else {
                    header.style.background = 'rgba(255, 255, 255, 0.95)';
                    header.style.borderBottomColor = 'rgba(0, 0, 0, 0.05)';
                }
                
                lastScrollTop = scrollTop;
            });
        }
        
        console.log('Modern menu initialized successfully!');
    }, 100);
});

// Fallback initialization
window.addEventListener('load', function() {
    // Force initialization if needed
    if (!document.querySelector('.modern-header')) {
        console.log('Modern header not found after load');
    }
});
</script> 