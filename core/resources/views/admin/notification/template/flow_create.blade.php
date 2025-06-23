@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.notification.top_bar')
@endpush

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="las la-plus"></i> @lang('Create Flow Template')
                </h5>
                <div class="card-header-actions">
                    <a href="{{ route('admin.setting.notification.templates') }}" class="btn btn--dark btn-sm">
                        <i class="las la-arrow-left"></i> @lang('Back to Templates')
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.notification.template.flow.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Template Code') <span class="text--danger">*</span></label>
                                <input type="text" name="act" class="form-control" required placeholder="e.g., WELCOME_EMAIL, PROMOTION_CAMPAIGN">
                                <small class="text-muted">@lang('Unique identifier (uppercase, underscore separated)')</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Template Name') <span class="text--danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="@lang('Friendly display name')">
                                <small class="text-muted">@lang('Human-readable name for the template')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Flow Type') <span class="text--danger">*</span></label>
                                <select name="flow_type" class="form-control" required>
                                    <option value="">@lang('Select Flow Type')</option>
                                    <option value="auto" {{ (request('type') == 'auto') ? 'selected' : '' }}>
                                        @lang('Auto Flow') - @lang('Automatic triggers')
                                    </option>
                                    <option value="marketing" {{ (request('type') == 'marketing') ? 'selected' : '' }}>
                                        @lang('Marketing Flow') - @lang('Manual campaigns')
                                    </option>
                                    <option value="system" {{ (request('type') == 'system') ? 'selected' : '' }}>
                                        @lang('System Flow') - @lang('System notifications')
                                    </option>
                                </select>
                                <small class="text-muted">
                                    <strong>Auto:</strong> @lang('Triggered automatically by events') <br>
                                    <strong>Marketing:</strong> @lang('Manual campaign sending') <br>
                                    <strong>System:</strong> @lang('Default system notifications')
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Priority') <span class="text--danger">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="normal">@lang('Normal Priority')</option>
                                    <option value="high">@lang('High Priority')</option>
                                    <option value="low">@lang('Low Priority')</option>
                                </select>
                                <small class="text-muted">@lang('Higher priority emails are processed first')</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('Email Subject') <span class="text--danger">*</span></label>
                        <input type="text" name="subject" class="form-control" required placeholder="@lang('Email subject line')">
                        <small class="text-muted">@lang('You can use shortcodes like {{site_name}}, {{user_name}}')</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('Email Body') <span class="text--danger">*</span></label>
                        <textarea name="email_body" class="form-control nicEdit" rows="8" required>@lang('Enter your email content here...')</textarea>
                        <small class="text-muted">@lang('HTML is supported. Use shortcodes for dynamic content.')</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('Flow Description')</label>
                        <textarea name="flow_description" class="form-control" rows="3" placeholder="@lang('Describe the purpose and usage of this email flow')"></textarea>
                        <small class="text-muted">@lang('Optional description to help understand this flow\'s purpose')</small>
                    </div>

                    <!-- Marketing Settings -->
                    <div id="marketing-settings" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">@lang('Marketing Campaign Settings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_scheduled" class="form-check-input" id="is_scheduled">
                                                <label class="form-check-label" for="is_scheduled">
                                                    @lang('Schedule Campaign')
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Scheduled Date & Time')</label>
                                            <input type="datetime-local" name="scheduled_at" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">@lang('Recipient Criteria')</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <select name="recipient_criteria[user_type]" class="form-control">
                                                <option value="">@lang('All Users')</option>
                                                <option value="customers">@lang('Customers Only')</option>
                                                <option value="companies">@lang('Companies Only')</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <input type="date" name="recipient_criteria[registered_after]" class="form-control" placeholder="@lang('Registered after')">
                                        </div>
                                    </div>
                                    
                                    <div class="form-check mt-2">
                                        <input type="checkbox" name="recipient_criteria[has_appointments]" class="form-check-input" id="has_appointments">
                                        <label class="form-check-label" for="has_appointments">
                                            @lang('Only users with appointments')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auto Flow Helper -->
                    <div id="auto-settings" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">@lang('Auto Flow Settings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert--info">
                                    <i class="las la-info-circle"></i>
                                    <strong>@lang('Auto Flow Templates'):</strong><br>
                                    @lang('These templates are triggered automatically by system events.')
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">@lang('Common Template Codes')</label>
                                    <div class="text-muted">
                                        <small>
                                            <strong>NEW_APPOINTMENT</strong> - @lang('New appointment created')<br>
                                            <strong>APPOINTMENT_CONFIRMED</strong> - @lang('Appointment confirmed')<br>
                                            <strong>APPOINTMENT_COMPLETED</strong> - @lang('Appointment completed')<br>
                                            <strong>APPOINTMENT_CANCELED</strong> - @lang('Appointment canceled')
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block">
                            <i class="las la-save"></i> @lang('Create Flow Template')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    'use strict';
    
    // Show/hide flow-specific settings
    document.querySelector('select[name="flow_type"]').addEventListener('change', function() {
        const marketingSettings = document.getElementById('marketing-settings');
        const autoSettings = document.getElementById('auto-settings');
        
        marketingSettings.style.display = 'none';
        autoSettings.style.display = 'none';
        
        if (this.value === 'marketing') {
            marketingSettings.style.display = 'block';
        } else if (this.value === 'auto') {
            autoSettings.style.display = 'block';
        }
    });

    // Auto-generate template code from name
    document.querySelector('input[name="name"]').addEventListener('input', function() {
        const codeInput = document.querySelector('input[name="act"]');
        if (!codeInput.value) {
            codeInput.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '_').replace(/_+/g, '_');
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const flowType = document.querySelector('select[name="flow_type"]').value;
        if (flowType === 'marketing') {
            document.getElementById('marketing-settings').style.display = 'block';
        } else if (flowType === 'auto') {
            document.getElementById('auto-settings').style.display = 'block';
        }
    });
</script>
@endpush 