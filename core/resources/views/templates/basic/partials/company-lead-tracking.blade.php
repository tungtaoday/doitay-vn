<!-- Company & Lead Tracking Events -->
<script>
// Track company interactions
function trackCompanyInteraction(action, companyId, companyName, category = 'company') {
  trackEvent('company_' + action, {
    category: category,
    label: action,
    company_id: companyId,
    company_name: companyName,
    actionType: action
  });
}

// Track lead interactions
function trackLeadInteraction(action, leadId, leadType = 'general', category = 'lead') {
  trackEvent('lead_' + action, {
    category: category,
    label: action,
    lead_id: leadId,
    lead_type: leadType,
    actionType: action
  });
}

// Track search and filter interactions
function trackSearchFilter(action, searchTerm, filters = {}, resultsCount = 0) {
  trackEvent('search_filter_' + action, {
    category: 'search',
    label: action,
    search_term: searchTerm,
    filters: JSON.stringify(filters),
    results_count: resultsCount,
    actionType: action
  });
}

// Track company profile views
function trackCompanyProfileView(companyId, companyName, source = 'search') {
  trackEvent('company_profile_view', {
    category: 'company',
    label: 'profile_view',
    company_id: companyId,
    company_name: companyName,
    source: source,
    actionType: 'view'
  });
}

// Track company contact actions
function trackCompanyContact(companyId, companyName, contactMethod) {
  trackEvent('company_contact', {
    category: 'company',
    label: contactMethod,
    company_id: companyId,
    company_name: companyName,
    contact_method: contactMethod,
    actionType: 'contact'
  });
}

// Track lead generation
function trackLeadGeneration(leadData) {
  trackEvent('lead_generation', {
    category: 'lead',
    label: 'generated',
    lead_id: leadData.id,
    lead_type: leadData.type,
    company_id: leadData.company_id,
    lead_value: leadData.value || 0,
    actionType: 'generation'
  });
}

// Track lead status changes
function trackLeadStatusChange(leadId, oldStatus, newStatus) {
  trackEvent('lead_status_change', {
    category: 'lead',
    label: oldStatus + '_to_' + newStatus,
    lead_id: leadId,
    old_status: oldStatus,
    new_status: newStatus,
    actionType: 'status_change'
  });
}

// Auto-track company interactions
document.addEventListener('DOMContentLoaded', function() {
  // Track company card clicks
  const companyCards = document.querySelectorAll('[data-track-company-card]');
  companyCards.forEach(card => {
    card.addEventListener('click', function(e) {
      const companyId = this.dataset.companyId;
      const companyName = this.dataset.companyName;
      trackCompanyInteraction('card_click', companyId, companyName);
    });
  });

  // Track company contact buttons
  const contactButtons = document.querySelectorAll('[data-track-contact-method]');
  contactButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      const contactMethod = this.dataset.trackContactMethod;
      const companyId = this.dataset.companyId;
      const companyName = this.dataset.companyName;
      trackCompanyContact(companyId, companyName, contactMethod);
    });
  });

  // Track search functionality
  const searchForms = document.querySelectorAll('form[data-track-type="search"]');
  searchForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const searchInput = form.querySelector('input[name="search"], input[name="q"], input[type="search"]');
      if (searchInput) {
        trackSearchFilter('submitted', searchInput.value);
      }
    });
  });

  // Track filter interactions
  const filterElements = document.querySelectorAll('[data-track-filter]');
  filterElements.forEach(element => {
    element.addEventListener('change', function(e) {
      const filterType = this.dataset.trackFilter;
      const filterValue = this.value;
      trackSearchFilter('changed', '', { [filterType]: filterValue });
    });
  });

  // Track lead form submissions
  const leadForms = document.querySelectorAll('form[data-track-type="lead"]');
  leadForms.forEach(form => {
    form.addEventListener('submit', function(e) {
      const formData = new FormData(form);
      const leadData = {
        type: formData.get('lead_type') || 'general',
        company_id: formData.get('company_id'),
        name: formData.get('name'),
        phone: formData.get('phone'),
        email: formData.get('email')
      };
      
      trackLeadGeneration(leadData);
    });
  });

  // Track lead status changes
  const leadStatusButtons = document.querySelectorAll('[data-track-lead-status]');
  leadStatusButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      const newStatus = this.dataset.trackLeadStatus;
      const leadId = this.dataset.leadId;
      const oldStatus = this.dataset.oldStatus;
      
      trackLeadStatusChange(leadId, oldStatus, newStatus);
    });
  });

  // Track company rating/review interactions
  const ratingElements = document.querySelectorAll('[data-track-rating]');
  ratingElements.forEach(element => {
    element.addEventListener('click', function(e) {
      const rating = this.dataset.trackRating;
      const companyId = this.dataset.companyId;
      const companyName = this.dataset.companyName;
      
      trackEvent('company_rating', {
        category: 'company',
        label: 'rating_' + rating,
        company_id: companyId,
        company_name: companyName,
        rating: rating,
        actionType: 'rating'
      });
    });
  });

  // Track company service category interactions
  const serviceCategories = document.querySelectorAll('[data-track-service-category]');
  serviceCategories.forEach(element => {
    element.addEventListener('click', function(e) {
      const category = this.dataset.trackServiceCategory;
      const companyId = this.dataset.companyId;
      
      trackEvent('service_category_click', {
        category: 'service',
        label: category,
        company_id: companyId,
        service_category: category,
        actionType: 'category_click'
      });
    });
  });
});
</script> 