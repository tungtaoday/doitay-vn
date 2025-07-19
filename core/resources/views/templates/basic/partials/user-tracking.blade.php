<!-- User Authentication & Registration Tracking -->
<script>
// Track user registration
function trackUserRegistration(userType, registrationMethod = 'form') {
  trackEvent('user_registration', {
    category: 'user',
    label: 'registered',
    user_type: userType,
    registration_method: registrationMethod,
    actionType: 'registration'
  });
}

// Track user login
function trackUserLogin(userType, loginMethod = 'form') {
  trackEvent('user_login', {
    category: 'user',
    label: 'logged_in',
    user_type: userType,
    login_method: loginMethod,
    actionType: 'login'
  });
}

// Track user logout
function trackUserLogout(userType) {
  trackEvent('user_logout', {
    category: 'user',
    label: 'logged_out',
    user_type: userType,
    actionType: 'logout'
  });
}

// Track profile updates
function trackProfileUpdate(userType, updateType) {
  trackEvent('profile_update', {
    category: 'user',
    label: updateType,
    user_type: userType,
    update_type: updateType,
    actionType: 'update'
  });
}

// Track password changes
function trackPasswordChange(userType) {
  trackEvent('password_change', {
    category: 'user',
    label: 'changed',
    user_type: userType,
    actionType: 'password_change'
  });
}

// Track email verification
function trackEmailVerification(userType, success = true) {
  trackEvent('email_verification', {
    category: 'user',
    label: success ? 'verified' : 'failed',
    user_type: userType,
    success: success,
    actionType: 'verification'
  });
}

// Track social login
function trackSocialLogin(provider, userType) {
  trackEvent('social_login', {
    category: 'user',
    label: provider,
    user_type: userType,
    provider: provider,
    actionType: 'social_login'
  });
}

// Track user dashboard interactions
function trackDashboardInteraction(action, userType) {
  trackEvent('dashboard_' + action, {
    category: 'dashboard',
    label: action,
    user_type: userType,
    actionType: action
  });
}

// Auto-track user authentication events
document.addEventListener('DOMContentLoaded', function() {
  // Track registration form submissions
  const registrationForms = document.querySelectorAll('form[data-track-type="registration"]');
  registrationForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const userType = this.dataset.userType || 'user';
      const registrationMethod = this.dataset.registrationMethod || 'form';
      trackUserRegistration(userType, registrationMethod);
    });
  });

  // Track login form submissions
  const loginForms = document.querySelectorAll('form[data-track-type="login"]');
  loginForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const userType = this.dataset.userType || 'user';
      const loginMethod = this.dataset.loginMethod || 'form';
      trackUserLogin(userType, loginMethod);
    });
  });

  // Track logout buttons
  const logoutButtons = document.querySelectorAll('[data-track-logout]');
  logoutButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      const userType = this.dataset.userType || 'user';
      trackUserLogout(userType);
    });
  });

  // Track profile update forms
  const profileForms = document.querySelectorAll('form[data-track-type="profile"]');
  profileForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const userType = this.dataset.userType || 'user';
      const updateType = this.dataset.updateType || 'general';
      trackProfileUpdate(userType, updateType);
    });
  });

  // Track password change forms
  const passwordForms = document.querySelectorAll('form[data-track-type="password"]');
  passwordForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const userType = this.dataset.userType || 'user';
      trackPasswordChange(userType);
    });
  });

  // Track social login buttons
  const socialButtons = document.querySelectorAll('[data-track-social-login]');
  socialButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      const provider = this.dataset.trackSocialLogin;
      const userType = this.dataset.userType || 'user';
      trackSocialLogin(provider, userType);
    });
  });

  // Track dashboard navigation
  const dashboardLinks = document.querySelectorAll('[data-track-dashboard-action]');
  dashboardLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      const action = this.dataset.trackDashboardAction;
      const userType = this.dataset.userType || 'user';
      trackDashboardInteraction(action, userType);
    });
  });

  // Track email verification
  const verificationLinks = document.querySelectorAll('[data-track-email-verification]');
  verificationLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      const userType = this.dataset.userType || 'user';
      const success = this.dataset.success === 'true';
      trackEmailVerification(userType, success);
    });
  });
});

  // Track user session events
  @if(auth()->check() && analytics('track_user_login'))
    // Track user login on page load
    trackUserLogin('{{ auth()->user()->user_type ?? "user" }}', 'session');
    
    // Track user logout on page unload
    window.addEventListener('beforeunload', function() {
      trackUserLogout('{{ auth()->user()->user_type ?? "user" }}');
    });
  @endif
</script> 