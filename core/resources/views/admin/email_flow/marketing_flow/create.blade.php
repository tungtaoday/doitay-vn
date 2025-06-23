@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.email_flow.top_bar')
@endpush

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-bullhorn"></i> @lang('Create Marketing Campaign')
                </h5>
            </div>
            <form action="{{ route('admin.email.flow.marketing.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Campaign Name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Template Act (Unique ID)') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="act" value="{{ old('act') }}" required 
                                       placeholder="e.g., MARKETING_CAMPAIGN_001">
                                <small class="text-muted">@lang('Unique identifier for this campaign')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('Email Subject') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Priority') <span class="text-danger">*</span></label>
                                <select class="form-control" name="priority" required>
                                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>@lang('Normal')</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>@lang('High')</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>@lang('Low')</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('Campaign Description')</label>
                        <textarea class="form-control" name="flow_description" rows="3">{{ old('flow_description') }}</textarea>
                        <small class="text-muted">@lang('Brief description of this marketing campaign')</small>
                    </div>

                    <div class="form-group">
                        <label>@lang('Email Content') <span class="text-danger">*</span></label>
                        <textarea class="form-control nicEdit" name="email_body" rows="10" required>{{ old('email_body') }}</textarea>
                        <small class="text-muted">@lang('You can use shortcodes like {{user_name}}, {{user_email}}, {{site_name}}, etc.')</small>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">@lang('Target Audience')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Target User Type')</label>
                                <select class="form-control" name="recipient_criteria[user_type]">
                                    <option value="">@lang('All Users')</option>
                                    <option value="customers" {{ old('recipient_criteria.user_type') == 'customers' ? 'selected' : '' }}>@lang('Customers Only')</option>
                                    <option value="companies" {{ old('recipient_criteria.user_type') == 'companies' ? 'selected' : '' }}>@lang('Companies Only')</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Registered After')</label>
                                <input type="date" class="form-control" name="recipient_criteria[registered_after]" 
                                       value="{{ old('recipient_criteria.registered_after') }}">
                                <small class="text-muted">@lang('Only users registered after this date')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="recipient_criteria[has_appointments]" value="1" 
                                           {{ old('recipient_criteria.has_appointments') ? 'checked' : '' }}>
                                    <label class="form-check-label">
                                        @lang('Only users with appointments')
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3">@lang('Scheduling Options')</h6>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="is_scheduled" value="1" 
                                           {{ old('is_scheduled') ? 'checked' : '' }} id="isScheduled">
                                    <label class="form-check-label" for="isScheduled">
                                        @lang('Schedule this campaign')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="scheduledAtGroup" style="display: none;">
                                <label>@lang('Schedule Date & Time')</label>
                                <input type="datetime-local" class="form-control" name="scheduled_at" 
                                       value="{{ old('scheduled_at') }}">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert--info">
                        <h6 class="alert-heading">@lang('Available Shortcodes:')</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><code>{{user_name}}</code> - @lang('User Name')</li>
                                    <li><code>{{user_email}}</code> - @lang('User Email')</li>
                                    <li><code>{{site_name}}</code> - @lang('Site Name')</li>
                                    <li><code>{{site_currency}}</code> - @lang('Site Currency')</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><code>{{currency_symbol}}</code> - @lang('Currency Symbol')</li>
                                    <li><code>{{fullname}}</code> - @lang('User Full Name')</li>
                                    <li><code>{{username}}</code> - @lang('Username')</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn--primary">
                        <i class="las la-save"></i> @lang('Create Campaign')
                    </button>
                    <a href="{{ route('admin.email.flow.marketing') }}" class="btn btn--dark">
                        <i class="las la-times"></i> @lang('Cancel')
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border--info">
            <div class="card-header bg--info">
                <h6 class="card-title text-white mb-0">@lang('Target Audience Preview')</h6>
            </div>
            <div class="card-body">
                <div id="audiencePreview">
                    <p class="mb-2"><strong>@lang('Total Users'):</strong> {{ \App\Models\User::count() }}</p>
                    <p class="mb-2"><strong>@lang('Customers'):</strong> {{ \App\Models\User::whereDoesntHave('company')->count() }}</p>
                    <p class="mb-0"><strong>@lang('Companies'):</strong> {{ \App\Models\User::whereHas('company')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border--success">
            <div class="card-header bg--success">
                <h6 class="card-title text-white mb-0">@lang('Campaign Tips')</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="las la-check text-success"></i> @lang('Use engaging subject lines')</li>
                    <li><i class="las la-check text-success"></i> @lang('Test with small groups first')</li>
                    <li><i class="las la-check text-success"></i> @lang('Include clear call-to-action')</li>
                    <li><i class="las la-check text-success"></i> @lang('Monitor open and click rates')</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        // Toggle scheduled datetime field
        $('#isScheduled').on('change', function() {
            if ($(this).is(':checked')) {
                $('#scheduledAtGroup').show();
            } else {
                $('#scheduledAtGroup').hide();
            }
        });
        
        // Show/hide based on initial state
        if ($('#isScheduled').is(':checked')) {
            $('#scheduledAtGroup').show();
        }
        
    })(jQuery);
</script>
@endpush 