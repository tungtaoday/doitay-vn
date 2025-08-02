<!-- Google Analytics Tracking Script -->
@if(analytics('google_analytics_id') && analytics('google_analytics_id') != 'GA-XXXXXXXXX' && analytics('analytics_enabled'))
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ analytics('google_analytics_id') }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{{ analytics('google_analytics_id') }}', {
    'custom_map': {
      'dimension1': 'user_type',
      'dimension2': 'page_category',
      'dimension3': 'action_type'
    }
  });

  // Enhanced Ecommerce Tracking
  gtag('config', '{{ analytics('google_analytics_id') }}', {
    'send_page_view': false
  });

  // Page View Tracking
  gtag('event', 'page_view', {
    'page_title': '{{ $pageTitle ?? "Doitay.vn" }}',
    'page_location': window.location.href,
    'user_type': '{{ auth()->check() ? (auth()->user()->user_type ?? "guest") : "guest" }}',
    'page_category': '{{ $pageCategory ?? "general" }}'
  });

  // Custom Event Tracking Function
  function trackEvent(eventName, parameters = {}) {
    gtag('event', eventName, {
      'event_category': parameters.category || 'engagement',
      'event_label': parameters.label || '',
      'value': parameters.value || 1,
      'user_type': '{{ auth()->check() ? (auth()->user()->user_type ?? "guest") : "guest" }}',
      'page_category': '{{ $pageCategory ?? "general" }}',
      'action_type': parameters.actionType || 'click',
      ...parameters
    });
  }

  // Form Tracking
  function trackFormSubmission(formId, formType) {
    trackEvent('form_submit', {
      category: 'form',
      label: formType,
      form_id: formId,
      actionType: 'submit'
    });
  }

  // Button Click Tracking
  function trackButtonClick(buttonId, buttonText, category = 'engagement') {
    trackEvent('button_click', {
      category: category,
      label: buttonText,
      button_id: buttonId,
      actionType: 'click'
    });
  }

  // Link Click Tracking
  function trackLinkClick(linkUrl, linkText, category = 'navigation') {
    trackEvent('link_click', {
      category: category,
      label: linkText,
      link_url: linkUrl,
      actionType: 'click'
    });
  }

  // Search Tracking
  function trackSearch(searchTerm, resultsCount = 0) {
    trackEvent('search', {
      category: 'search',
      label: searchTerm,
      search_term: searchTerm,
      results_count: resultsCount,
      actionType: 'search'
    });
  }

  // Appointment Tracking
  function trackAppointment(action, appointmentId, companyId = null) {
    trackEvent('appointment_' + action, {
      category: 'appointment',
      label: action,
      appointment_id: appointmentId,
      company_id: companyId,
      actionType: action
    });
  }

  // Lead Tracking
  function trackLead(action, leadId, category = 'lead') {
    trackEvent('lead_' + action, {
      category: category,
      label: action,
      lead_id: leadId,
      actionType: action
    });
  }

  // User Registration/Login Tracking
  function trackUserAction(action, userType) {
    trackEvent('user_' + action, {
      category: 'user',
      label: action,
      user_type: userType,
      actionType: action
    });
  }

  // Scroll Depth Tracking
  let maxScroll = 0;
  function trackScrollDepth() {
    const scrollPercent = Math.round((window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100);
    if (scrollPercent > maxScroll) {
      maxScroll = scrollPercent;
      if (maxScroll % 25 === 0) { // Track at 25%, 50%, 75%, 100%
        trackEvent('scroll_depth', {
          category: 'engagement',
          label: maxScroll + '%',
          scroll_depth: maxScroll,
          actionType: 'scroll'
        });
      }
    }
  }

  // Time on Page Tracking
  let startTime = Date.now();
  function trackTimeOnPage() {
    const timeSpent = Math.round((Date.now() - startTime) / 1000);
    if (timeSpent % 30 === 0 && timeSpent > 0) { // Track every 30 seconds
      trackEvent('time_on_page', {
        category: 'engagement',
        label: timeSpent + 's',
        time_spent: timeSpent,
        actionType: 'time'
      });
    }
  }

  // Auto-track common interactions
  document.addEventListener('DOMContentLoaded', function() {
    // Track form submissions
    @if(analytics('track_appointments'))
    document.addEventListener('submit', function(e) {
      const form = e.target;
      const formId = form.id || form.className || 'unknown_form';
      const formType = form.dataset.trackType || 'general';
      trackFormSubmission(formId, formType);
    });
    @endif

    // Track button clicks
    document.addEventListener('click', function(e) {
      if (e.target.tagName === 'BUTTON' || e.target.closest('button')) {
        const button = e.target.tagName === 'BUTTON' ? e.target : e.target.closest('button');
        const buttonId = button.id || button.className || 'unknown_button';
        const buttonText = button.textContent.trim() || 'Unknown Button';
        const category = button.dataset.trackCategory || 'engagement';
        trackButtonClick(buttonId, buttonText, category);
      }
    });

    // Track link clicks
    document.addEventListener('click', function(e) {
      if (e.target.tagName === 'A' || e.target.closest('a')) {
        const link = e.target.tagName === 'A' ? e.target : e.target.closest('a');
        const linkUrl = link.href || '';
        const linkText = link.textContent.trim() || 'Unknown Link';
        const category = link.dataset.trackCategory || 'navigation';
        trackLinkClick(linkUrl, linkText, category);
      }
    });

    // Track search functionality
    @if(analytics('track_search'))
    const searchForms = document.querySelectorAll('form[data-track-type="search"]');
    searchForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const searchInput = form.querySelector('input[name="search"], input[name="q"], input[type="search"]');
        if (searchInput) {
          trackSearch(searchInput.value);
        }
      });
    });
    @endif

    // Start scroll and time tracking
    @if(analytics('track_scroll_depth'))
    window.addEventListener('scroll', trackScrollDepth);
    @endif
    setInterval(trackTimeOnPage, 1000);
  });

  // Track specific business events
  @if(auth()->check())
    // Track user login
    trackUserAction('login', '{{ auth()->user()->user_type ?? "user" }}');
  @endif

  // Track page specific events
  @if(request()->routeIs('home'))
    trackEvent('page_view', {
      category: 'page',
      label: 'homepage',
      page_type: 'home'
    });
  @endif

  @if(request()->routeIs('company.*'))
    trackEvent('page_view', {
      category: 'page',
      label: 'company_pages',
      page_type: 'company'
    });
  @endif

  @if(request()->routeIs('appointments.*'))
    trackEvent('page_view', {
      category: 'page',
      label: 'appointment_pages',
      page_type: 'appointment'
    });
  @endif

</script>

<!-- Enhanced Ecommerce Tracking -->
<script>
  // Track appointment creation
  function trackAppointmentCreation(appointmentData) {
    gtag('event', 'begin_checkout', {
      'currency': 'VND',
      'value': appointmentData.value || 0,
      'items': [{
        'item_id': 'appointment_' + appointmentData.id,
        'item_name': 'Appointment with ' + appointmentData.company_name,
        'item_category': 'appointment',
        'price': appointmentData.value || 0,
        'quantity': 1
      }]
    });
  }

  // Track appointment confirmation
  function trackAppointmentConfirmation(appointmentData) {
    gtag('event', 'purchase', {
      'transaction_id': 'appointment_' + appointmentData.id,
      'value': appointmentData.value || 0,
      'currency': 'VND',
      'tax': 0,
      'shipping': 0,
      'items': [{
        'item_id': 'appointment_' + appointmentData.id,
        'item_name': 'Appointment with ' + appointmentData.company_name,
        'item_category': 'appointment',
        'price': appointmentData.value || 0,
        'quantity': 1
      }]
    });
  }

  // Track lead generation
  function trackLeadGeneration(leadData) {
    gtag('event', 'generate_lead', {
      'currency': 'VND',
      'value': leadData.value || 0,
      'lead_type': leadData.type || 'general'
    });
  }
</script>

@endif 
 