// Home Page JavaScript Functions

// Review Slider Navigation
document.addEventListener('DOMContentLoaded', function() {
    // Initialize review slider
    initReviewSlider();
    
    // Auto-rotate reviews every 5 seconds
    setInterval(function() {
        nextReview();
    }, 5000);
});

function initReviewSlider() {
    const reviewDots = document.querySelectorAll('.review-dot');
    const reviewItems = document.querySelectorAll('.review-item');
    
    // Add click event to review dots
    reviewDots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            showReview(index);
        });
    });
}

function showReview(index) {
    const reviewItems = document.querySelectorAll('.review-item');
    const reviewDots = document.querySelectorAll('.review-dot');
    
    // Hide all reviews
    reviewItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Remove active class from all dots
    reviewDots.forEach(dot => {
        dot.classList.remove('active');
    });
    
    // Show selected review
    if (reviewItems[index]) {
        reviewItems[index].classList.add('active');
    }
    
    // Activate selected dot
    if (reviewDots[index]) {
        reviewDots[index].classList.add('active');
    }
}

function nextReview() {
    const reviewItems = document.querySelectorAll('.review-item');
    const currentIndex = Array.from(reviewItems).findIndex(item => item.classList.contains('active'));
    const nextIndex = (currentIndex + 1) % reviewItems.length;
    
    showReview(nextIndex);
} 