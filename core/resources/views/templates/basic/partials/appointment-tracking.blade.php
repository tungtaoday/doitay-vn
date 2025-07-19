<!-- Appointment Tracking Events -->
<script>
// Track appointment booking process
function trackAppointmentBooking(step, appointmentData = {}) {
  const trackingData = {
    category: 'appointment_booking',
    label: step,
    appointment_id: appointmentData.id || null,
    company_id: appointmentData.company_id || null,
    company_name: appointmentData.company_name || null,
    appointment_date: appointmentData.appointment_date || null,
    appointment_time: appointmentData.appointment_time || null,
    user_type: '{{ auth()->check() ? (auth()->user()->user_type ?? "guest") : "guest" }}',
    actionType: 'booking_step'
  };

  trackEvent('appointment_booking_' + step, trackingData);
}

// Track appointment status changes
function trackAppointmentStatusChange(oldStatus, newStatus, appointmentId, companyId = null) {
  trackEvent('appointment_status_change', {
    category: 'appointment',
    label: oldStatus + '_to_' + newStatus,
    appointment_id: appointmentId,
    company_id: companyId,
    old_status: oldStatus,
    new_status: newStatus,
    actionType: 'status_change'
  });
}

// Track appointment cancellation
function trackAppointmentCancellation(appointmentId, companyId, reason = 'user_request') {
  trackEvent('appointment_cancellation', {
    category: 'appointment',
    label: 'cancelled',
    appointment_id: appointmentId,
    company_id: companyId,
    cancellation_reason: reason,
    actionType: 'cancellation'
  });
}

// Track appointment confirmation
function trackAppointmentConfirmation(appointmentId, companyId) {
  trackEvent('appointment_confirmation', {
    category: 'appointment',
    label: 'confirmed',
    appointment_id: appointmentId,
    company_id: companyId,
    actionType: 'confirmation'
  });
}

// Track appointment completion
function trackAppointmentCompletion(appointmentId, companyId) {
  trackEvent('appointment_completion', {
    category: 'appointment',
    label: 'completed',
    appointment_id: appointmentId,
    company_id: companyId,
    actionType: 'completion'
  });
}

// Track appointment rescheduling
function trackAppointmentReschedule(appointmentId, companyId, oldDate, newDate) {
  trackEvent('appointment_reschedule', {
    category: 'appointment',
    label: 'rescheduled',
    appointment_id: appointmentId,
    company_id: companyId,
    old_date: oldDate,
    new_date: newDate,
    actionType: 'reschedule'
  });
}

// Track appointment form interactions
function trackAppointmentFormInteraction(fieldName, action) {
  trackEvent('appointment_form_interaction', {
    category: 'form',
    label: fieldName + '_' + action,
    form_type: 'appointment',
    field_name: fieldName,
    action: action,
    actionType: 'form_interaction'
  });
}

// Auto-track appointment form fields
document.addEventListener('DOMContentLoaded', function() {
  const appointmentForms = document.querySelectorAll('form[data-track-type="appointment"]');
  
  appointmentForms.forEach(form => {
    // Track form field focus
    form.addEventListener('focusin', function(e) {
      if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
        trackAppointmentFormInteraction(e.target.name || e.target.id, 'focus');
      }
    });

    // Track form field blur
    form.addEventListener('focusout', function(e) {
      if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
        trackAppointmentFormInteraction(e.target.name || e.target.id, 'blur');
      }
    });

    // Track form submission
    form.addEventListener('submit', function(e) {
      const formData = new FormData(form);
      const appointmentData = {
        company_id: formData.get('company_id'),
        appointment_date: formData.get('appointment_date'),
        appointment_time: formData.get('appointment_time'),
        recipient_name: formData.get('recipient_name'),
        recipient_phone: formData.get('recipient_phone')
      };
      
      trackAppointmentBooking('submitted', appointmentData);
    });
  });

  // Track appointment action buttons
  const appointmentButtons = document.querySelectorAll('[data-track-appointment-action]');
  appointmentButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      const action = this.dataset.trackAppointmentAction;
      const appointmentId = this.dataset.appointmentId;
      const companyId = this.dataset.companyId;
      
      switch(action) {
        case 'cancel':
          trackAppointmentCancellation(appointmentId, companyId);
          break;
        case 'confirm':
          trackAppointmentConfirmation(appointmentId, companyId);
          break;
        case 'complete':
          trackAppointmentCompletion(appointmentId, companyId);
          break;
        case 'reschedule':
          trackAppointmentReschedule(appointmentId, companyId);
          break;
      }
    });
  });
});
</script> 