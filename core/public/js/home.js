/**
 * Home Page JavaScript
 * Handles home page specific functionality
 */

$(document).ready(function() {
    console.log('🏠 Home page JavaScript loaded');
    
    // Initialize home page features
    initHomePage();
    
    // Initialize statistics counter animation
    initStatsCounter();
    
    // Initialize smooth scrolling for anchor links
    initSmoothScrolling();
    
    // Initialize form validation
    initFormValidation();
});

/**
 * Initialize home page features
 */
function initHomePage() {
    console.log('🎯 Initializing home page features');
    
    // Add loading animation
    $('.loading').fadeIn();
    
    // Hide loading after page load
    $(window).on('load', function() {
        $('.loading').fadeOut();
    });
}

/**
 * Initialize statistics counter animation
 */
function initStatsCounter() {
    $('.stat-number').each(function() {
        const $this = $(this);
        const countTo = $this.attr('data-count');
        
        if (countTo) {
            $({ countNum: $this.text() }).animate({
                countNum: countTo
            }, {
                duration: 2000,
                easing: 'linear',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(this.countNum);
                }
            });
        }
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initSmoothScrolling() {
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
                return false;
            }
        }
    });
}

/**
 * Initialize form validation
 */
function initFormValidation() {
    // Contact form validation
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();
        
        const name = $('#name').val().trim();
        const email = $('#email').val().trim();
        const message = $('#message').val().trim();
        
        let isValid = true;
        
        // Clear previous errors
        $('.error-message').remove();
        
        // Validate name
        if (name === '') {
            $('#name').after('<div class="error-message text-danger">Vui lòng nhập tên</div>');
            isValid = false;
        }
        
        // Validate email
        if (email === '') {
            $('#email').after('<div class="error-message text-danger">Vui lòng nhập email</div>');
            isValid = false;
        } else if (!isValidEmail(email)) {
            $('#email').after('<div class="error-message text-danger">Email không hợp lệ</div>');
            isValid = false;
        }
        
        // Validate message
        if (message === '') {
            $('#message').after('<div class="error-message text-danger">Vui lòng nhập tin nhắn</div>');
            isValid = false;
        }
        
        if (isValid) {
            // Submit form
            submitContactForm(name, email, message);
        }
    });
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Submit contact form
 */
function submitContactForm(name, email, message) {
    // Show loading
    $('#contactForm button[type="submit"]').prop('disabled', true).text('Đang gửi...');
    
    // Simulate form submission (replace with actual AJAX call)
    setTimeout(function() {
        // Show success message
        showNotification('Tin nhắn đã được gửi thành công!', 'success');
        
        // Reset form
        $('#contactForm')[0].reset();
        
        // Reset button
        $('#contactForm button[type="submit"]').prop('disabled', false).text('Gửi tin nhắn');
    }, 2000);
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').prepend(notification);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

/**
 * Initialize lazy loading for images
 */
function initLazyLoading() {
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

/**
 * Initialize search functionality
 */
function initSearch() {
    $('#searchInput').on('input', function() {
        const query = $(this).val().toLowerCase();
        
        if (query.length > 2) {
            // Perform search (implement actual search logic)
            performSearch(query);
        } else {
            // Clear search results
            $('#searchResults').empty();
        }
    });
}

/**
 * Perform search
 */
function performSearch(query) {
    // Implement actual search logic here
    console.log('Searching for:', query);
}

// Export functions for global access
window.HomePage = {
    initHomePage,
    initStatsCounter,
    initSmoothScrolling,
    initFormValidation,
    showNotification,
    initLazyLoading,
    initSearch
};
