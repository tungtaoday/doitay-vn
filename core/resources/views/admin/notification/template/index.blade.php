@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.notification.top_bar')
@endpush

<!-- Flow Type Filter Tabs -->
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="flowTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                            @lang('All Templates') ({{ $templates->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="auto-tab" data-bs-toggle="tab" data-bs-target="#auto" type="button" role="tab">
                            @lang('Auto Flow') ({{ $templates->where('flow_type', 'auto')->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="marketing-tab" data-bs-toggle="tab" data-bs-target="#marketing" type="button" role="tab">
                            @lang('Marketing Flow') ({{ $templates->where('flow_type', 'marketing')->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                            @lang('System Flow') ({{ $templates->where('flow_type', 'system')->count() }})
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-lg-12">
        <div class="card">
            <div class="card-body px-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two custom-data-table">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Subject')</th>
                                <th>@lang('Flow Type')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Sent Count')</th>
                                <th>@lang('Edit Template')</th>
                                <th>@lang('Actions')</th>
                            </tr>
                        </thead>
                        <tbody id="templates-tbody">
                        @forelse($templates as $template)
                            <tr data-flow-type="{{ $template->flow_type ?? 'system' }}">
                                <td>{{ __($template->name) }}</td>
                                <td>{{ __($template->subject) }}</td>
                                <td>
                                    @php
                                        $flowType = $template->flow_type ?? 'system';
                                        $badgeClass = match($flowType) {
                                            'auto' => 'badge--success',
                                            'marketing' => 'badge--info',
                                            'system' => 'badge--primary',
                                            default => 'badge--secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($flowType) }}</span>
                                </td>
                                <td>
                                    @if($template->priority)
                                        @php
                                            $priorityBadge = match($template->priority) {
                                                'high' => 'badge--danger',
                                                'normal' => 'badge--warning',
                                                'low' => 'badge--secondary',
                                                default => 'badge--primary'
                                            };
                                        @endphp
                                        <span class="badge {{ $priorityBadge }}">{{ ucfirst($template->priority) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($template->email_status == Status::ENABLE)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $template->sent_count ?? 0 }}</span>
                                    @if($template->last_sent_at)
                                        <small class="text-muted d-block">{{ $template->last_sent_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.setting.notification.template.edit', ['email',$template->id]) }}" class="btn btn-outline--primary">@lang('Email')</a>
                                            <span class="btn btn--primary">@if($template->email_status != Status::ENABLE)<i class="las la-times"></i> @else <i class="las la-check"></i> @endif</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.setting.notification.template.edit', ['sms',$template->id]) }}"  class="btn btn-outline--info">@lang('SMS')</a>
                                            <span class="btn btn--info">@if($template->sms_status != Status::ENABLE)<i class="las la-times"></i> @else <i class="las la-check"></i> @endif</span>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.setting.notification.template.edit', ['push',$template->id]) }}" class="btn btn-outline--success">@lang('Push')</a>
                                            <span class="btn btn--success">@if($template->push_status != Status::ENABLE)<i class="las la-times"></i> @else <i class="las la-check"></i> @endif</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn--primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            @lang('Actions')
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.setting.notification.template.flow.edit', $template->id) }}">
                                                <i class="las la-edit"></i> @lang('Flow Settings')
                                            </a></li>
                                            @if($template->flow_type == 'marketing')
                                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="sendCampaign({{ $template->id }})">
                                                    <i class="las la-paper-plane"></i> @lang('Send Campaign')
                                                </a></li>
                                                @if($template->is_scheduled)
                                                    <li><a class="dropdown-item text-info" href="javascript:void(0)">
                                                        <i class="las la-clock"></i> @lang('Scheduled') - {{ $template->scheduled_at?->format('M d, Y H:i') }}
                                                    </a></li>
                                                @endif
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="changeFlowType({{ $template->id }}, '{{ $template->flow_type }}')">
                                                <i class="las la-exchange-alt"></i> @lang('Change Flow Type')
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse

                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
        </div><!-- card end -->
    </div>
</div>

<!-- Quick Create Flow Templates -->
<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">@lang('Quick Actions')</h5>
            </div>
            <div class="card-body">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn--success" onclick="createTemplate('auto')">
                        <i class="las la-plus"></i> @lang('Create Auto Flow')
                    </button>
                    <button type="button" class="btn btn--info" onclick="createTemplate('marketing')">
                        <i class="las la-plus"></i> @lang('Create Marketing Campaign')
                    </button>
                    <button type="button" class="btn btn--primary" onclick="initializeAppointmentFlow()">
                        <i class="las la-sync"></i> @lang('Initialize Appointment Auto Flow')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Flow Type Modal -->
<div class="modal fade" id="changeFlowTypeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Change Flow Type')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.setting.notification.template.flow.change') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="template_id" id="changeFlowTemplateId">
                    <div class="form-group">
                        <label class="form-label">@lang('Current Flow Type')</label>
                        <input type="text" class="form-control" id="currentFlowType" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">@lang('New Flow Type') <span class="text--danger">*</span></label>
                        <select name="flow_type" class="form-control" required>
                            <option value="">@lang('Select Flow Type')</option>
                            <option value="auto">@lang('Auto Flow') - @lang('Automatic appointment notifications')</option>
                            <option value="marketing">@lang('Marketing Flow') - @lang('Manual campaigns')</option>
                            <option value="system">@lang('System Flow') - @lang('System notifications')</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">@lang('Priority')</label>
                        <select name="priority" class="form-control">
                            <option value="normal">@lang('Normal')</option>
                            <option value="high">@lang('High')</option>
                            <option value="low">@lang('Low')</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">@lang('Description')</label>
                        <textarea name="flow_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Update Flow Type')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Campaign Modal -->
<div class="modal fade" id="sendCampaignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Send Marketing Campaign')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.setting.notification.template.campaign.send') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="template_id" id="campaignTemplateId">
                    <div class="alert alert--warning">
                        <i class="las la-exclamation-triangle"></i>
                        @lang('This will send the campaign to all matching recipients immediately.')
                    </div>
                    <div class="form-group">
                        <label class="form-label">@lang('Confirm Campaign Name')</label>
                        <input type="text" class="form-control" id="campaignName" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">@lang('Estimated Recipients')</label>
                        <div id="estimatedRecipients" class="text--info">@lang('Calculating...')</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary">@lang('Send Campaign')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('style')
    <style>
        i.fas.fa-circle {
            font-size: 12px;
        }
        .btn-group button{
            padding: 0px 15px;
        }
        .btn-group span{
            width: 34px;
            font-size: 10px;
            line-height: 24px;
        }
        .table td{
            white-space: unset;
        }

        .action-btns{
            display: flex;
            justify-content: flex-end;
            gap: 4px;
            row-gap: 5px;
            flex-wrap: wrap;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: 1px solid transparent;
            margin-right: 5px;
        }
        
        .nav-tabs .nav-link.active {
            color: #007bff;
            border-color: #007bff #007bff #fff;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .dropdown-menu {
            min-width: 200px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 13px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .flow-hidden {
            display: none !important;
        }
    </style>
@endpush

@push('script')
<script>
    'use strict';
    
    // Flow type filtering
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#flowTabs button[data-bs-toggle="tab"]');
        const rows = document.querySelectorAll('#templates-tbody tr[data-flow-type]');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                filterByFlowType(target);
            });
        });
        
        function filterByFlowType(target) {
            rows.forEach(row => {
                const flowType = row.getAttribute('data-flow-type');
                
                if (target === '#all') {
                    row.classList.remove('flow-hidden');
                } else if (target === '#auto' && flowType === 'auto') {
                    row.classList.remove('flow-hidden');
                } else if (target === '#marketing' && flowType === 'marketing') {
                    row.classList.remove('flow-hidden');
                } else if (target === '#system' && (flowType === 'system' || flowType === '' || flowType === null)) {
                    row.classList.remove('flow-hidden');
                } else {
                    row.classList.add('flow-hidden');
                }
            });
        }
    });

    // Change flow type modal
    function changeFlowType(templateId, currentType) {
        document.getElementById('changeFlowTemplateId').value = templateId;
        document.getElementById('currentFlowType').value = currentType || 'system';
        
        const modal = new bootstrap.Modal(document.getElementById('changeFlowTypeModal'));
        modal.show();
    }

    // Send campaign modal
    function sendCampaign(templateId) {
        document.getElementById('campaignTemplateId').value = templateId;
        
        // Get template name from the row
        const row = document.querySelector(`tr[data-flow-type] td:first-child`);
        const templateName = row ? row.textContent.trim() : 'Campaign';
        document.getElementById('campaignName').value = templateName;
        
        // Calculate estimated recipients (you can implement AJAX call here)
        document.getElementById('estimatedRecipients').innerHTML = '<i class="las la-spinner la-spin"></i> Calculating...';
        
        // Simulate API call to get recipient count
        setTimeout(() => {
            document.getElementById('estimatedRecipients').innerHTML = '<strong class="text--success">~50 recipients</strong> (estimated)';
        }, 1000);
        
        const modal = new bootstrap.Modal(document.getElementById('sendCampaignModal'));
        modal.show();
    }

    // Create template
    function createTemplate(type) {
        if (type === 'auto') {
            window.location.href = '{{ route("admin.setting.notification.template.flow.create") }}?type=auto';
        } else if (type === 'marketing') {
            window.location.href = '{{ route("admin.setting.notification.template.flow.create") }}?type=marketing';
        }
    }

    // Initialize appointment flow
    function initializeAppointmentFlow() {
        if (confirm('This will convert all appointment-related templates to auto flow. Continue?')) {
            window.location.href = '{{ route("admin.setting.notification.template.flow.initialize") }}';
        }
    }
</script>
@endpush
