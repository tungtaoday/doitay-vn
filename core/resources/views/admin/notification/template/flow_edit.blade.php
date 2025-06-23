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
                    <i class="las la-cogs"></i> @lang('Flow Settings') - {{ $template->name }}
                </h5>
                <div class="card-header-actions">
                    <a href="{{ route('admin.setting.notification.templates') }}" class="btn btn--dark btn-sm">
                        <i class="las la-arrow-left"></i> @lang('Back to Templates')
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.notification.template.flow.update', $template->id) }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Template Name') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $template->name }}" readonly>
                                <small class="text-muted">@lang('This is the system name for the template')</small>
                            </div>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Template Code') <span class="text--danger">*</span></label>
                                <input type="text" class="form-control" value="{{ $template->act }}" readonly>
                                <small class="text-muted">@lang('Unique identifier used in code')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Flow Type') <span class="text--danger">*</span></label>
                                <select name="flow_type" class="form-control" required>
                                    <option value="">@lang('Select Flow Type')</option>
                                    <option value="auto" {{ ($template->flow_type == 'auto') ? 'selected' : '' }}>
                                        @lang('Auto Flow') - @lang('Automatic triggers')
                                    </option>
                                    <option value="marketing" {{ ($template->flow_type == 'marketing') ? 'selected' : '' }}>
                                        @lang('Marketing Flow') - @lang('Manual campaigns')
                                    </option>
                                    <option value="system" {{ ($template->flow_type == 'system' || !$template->flow_type) ? 'selected' : '' }}>
                                        @lang('System Flow') - @lang('System notifications')
                                    </option>
                                </select>
                                <small class="text-muted">
                                    <strong>Auto:</strong> @lang('Triggered automatically by system events') <br>
                                    <strong>Marketing:</strong> @lang('Sent manually as campaigns') <br>
                                    <strong>System:</strong> @lang('Default system notifications')
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">@lang('Priority') <span class="text--danger">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="low" {{ ($template->priority == 'low') ? 'selected' : '' }}>
                                        @lang('Low Priority')
                                    </option>
                                    <option value="normal" {{ ($template->priority == 'normal' || !$template->priority) ? 'selected' : '' }}>
                                        @lang('Normal Priority')
                                    </option>
                                    <option value="high" {{ ($template->priority == 'high') ? 'selected' : '' }}>
                                        @lang('High Priority')
                                    </option>
                                </select>
                                <small class="text-muted">@lang('Higher priority emails are processed first')</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">@lang('Flow Description')</label>
                        <textarea name="flow_description" class="form-control" rows="3" placeholder="@lang('Describe the purpose and usage of this email flow')">{{ $template->flow_description }}</textarea>
                        <small class="text-muted">@lang('Optional description to help understand this flow\'s purpose')</small>
                    </div>

                    <!-- Marketing Flow Specific Settings -->
                    <div id="marketing-settings" style="display: {{ ($template->flow_type == 'marketing') ? 'block' : 'none' }};">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">@lang('Marketing Campaign Settings')</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" name="is_scheduled" class="form-check-input" id="is_scheduled" 
                                                       {{ $template->is_scheduled ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_scheduled">
                                                    @lang('Schedule Campaign')
                                                </label>
                                            </div>
                                            <small class="text-muted">@lang('Enable to schedule this campaign for later')</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label">@lang('Scheduled Date & Time')</label>
                                            <input type="datetime-local" name="scheduled_at" class="form-control" 
                                                   value="{{ $template->scheduled_at ? $template->scheduled_at->format('Y-m-d\TH:i') : '' }}">
                                            <small class="text-muted">@lang('When should this campaign be sent?')</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">@lang('Recipient Criteria')</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('User Type')</label>
                                                <select name="recipient_criteria[user_type]" class="form-control">
                                                    <option value="">@lang('All Users')</option>
                                                    <option value="customers" {{ (isset($template->recipient_criteria['user_type']) && $template->recipient_criteria['user_type'] == 'customers') ? 'selected' : '' }}>
                                                        @lang('Customers Only')
                                                    </option>
                                                    <option value="companies" {{ (isset($template->recipient_criteria['user_type']) && $template->recipient_criteria['user_type'] == 'companies') ? 'selected' : '' }}>
                                                        @lang('Companies Only')
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Registered After')</label>
                                                <input type="date" name="recipient_criteria[registered_after]" class="form-control" 
                                                       value="{{ $template->recipient_criteria['registered_after'] ?? '' }}">
                                                <small class="text-muted">@lang('Only users registered after this date')</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" name="recipient_criteria[has_appointments]" class="form-check-input" id="has_appointments"
                                               {{ (isset($template->recipient_criteria['has_appointments']) && $template->recipient_criteria['has_appointments']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_appointments">
                                            @lang('Only users with appointments')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">@lang('Template Statistics')</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-between">
                                        <span>@lang('Total Sent'):</span>
                                        <strong class="text--success">{{ $template->sent_count ?? 0 }}</strong>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-between">
                                        <span>@lang('Email Status'):</span>
                                        <strong class="{{ $template->email_status ? 'text--success' : 'text--danger' }}">
                                            {{ $template->email_status ? 'Active' : 'Inactive' }}
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-between">
                                        <span>@lang('Last Sent'):</span>
                                        <strong>{{ $template->last_sent_at ? $template->last_sent_at->format('M d, Y') : 'Never' }}</strong>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex justify-content-between">
                                        <span>@lang('Created'):</span>
                                        <strong>{{ $template->created_at->format('M d, Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block">
                            <i class="las la-save"></i> @lang('Update Flow Settings')
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
    
    // Show/hide marketing settings based on flow type
    document.querySelector('select[name="flow_type"]').addEventListener('change', function() {
        const marketingSettings = document.getElementById('marketing-settings');
        if (this.value === 'marketing') {
            marketingSettings.style.display = 'block';
        } else {
            marketingSettings.style.display = 'none';
        }
    });

    // Show/hide scheduled datetime based on checkbox
    document.getElementById('is_scheduled').addEventListener('change', function() {
        const scheduledInput = document.querySelector('input[name="scheduled_at"]');
        if (this.checked) {
            scheduledInput.style.display = 'block';
            scheduledInput.required = true;
        } else {
            scheduledInput.style.display = 'none';
            scheduledInput.required = false;
        }
    });
</script>
@endpush 