@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.email_flow.top_bar')
@endpush

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--md table-responsive">
                    <table class="table--light style--two table">
                        <thead>
                            <tr>
                                <th>@lang('Campaign Name')</th>
                                <th>@lang('Template Act')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Scheduled')</th>
                                <th>@lang('Recipients')</th>
                                <th>@lang('Sent Count')</th>
                                <th>@lang('Last Sent')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ __($template->name) }}</span>
                                        @if($template->flow_description)
                                            <br><small class="text-muted">{{ __($template->flow_description) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge--dark">{{ __($template->act) }}</span>
                                    </td>
                                    <td>
                                        @if($template->priority == 'high')
                                            <span class="badge badge--danger">@lang('High')</span>
                                        @elseif($template->priority == 'normal')
                                            <span class="badge badge--info">@lang('Normal')</span>
                                        @else
                                            <span class="badge badge--secondary">@lang('Low')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->email_status)
                                            <span class="badge badge--success">@lang('Active')</span>
                                        @else
                                            <span class="badge badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($template->is_scheduled)
                                            <span class="badge badge--primary">@lang('Scheduled')</span>
                                            @if($template->scheduled_at)
                                                <br><small>{{ showDateTime($template->scheduled_at, 'd M Y h:i A') }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge--secondary">@lang('Manual')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $criteria = $template->recipient_criteria ?? [];
                                            $recipientCount = 0;
                                            if (isset($criteria['user_type'])) {
                                                if ($criteria['user_type'] === 'customers') {
                                                    $recipientCount = \App\Models\User::whereDoesntHave('company')->count();
                                                } elseif ($criteria['user_type'] === 'companies') {
                                                    $recipientCount = \App\Models\User::whereHas('company')->count();
                                                } else {
                                                    $recipientCount = \App\Models\User::count();
                                                }
                                            } else {
                                                $recipientCount = \App\Models\User::count();
                                            }
                                        @endphp
                                        <span class="fw-bold">{{ $recipientCount }}</span>
                                        @if(!empty($criteria))
                                            <br><small class="text-muted">
                                                @if(isset($criteria['user_type']))
                                                    {{ ucfirst($criteria['user_type']) }}
                                                @endif
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $template->sent_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if($template->last_sent_at)
                                            {{ showDateTime($template->last_sent_at, 'd M Y h:i A') }}
                                        @else
                                            <span class="text-muted">@lang('Never')</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="button-group">
                                            @if($template->email_status)
                                                <button type="button" class="btn btn-sm btn--success sendCampaignBtn" 
                                                        data-id="{{ $template->id }}"
                                                        data-name="{{ $template->name }}"
                                                        data-recipients="{{ $recipientCount }}">
                                                    <i class="la la-paper-plane"></i> @lang('Send')
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.email.flow.edit', $template->id) }}" 
                                               class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                            <a href="{{ route('admin.setting.notification.template.edit', ['email', $template->id]) }}" 
                                               class="btn btn-sm btn-outline--info">
                                                <i class="la la-eye"></i> @lang('Template')
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-muted text-center">{{ __($emptyMessage ?? 'No marketing campaigns found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card border--warning">
            <div class="card-header bg--warning">
                <h5 class="card-title text-white mb-0">
                    <i class="las la-bullhorn"></i> @lang('Marketing Flow Information')
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Total Campaigns')</span>
                        <span class="badge badge--primary">{{ $templates->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Active Campaigns')</span>
                        <span class="badge badge--success">{{ $templates->where('email_status', 1)->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Scheduled Campaigns')</span>
                        <span class="badge badge--info">{{ $templates->where('is_scheduled', 1)->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Total Emails Sent')</span>
                        <span class="badge badge--dark">{{ $templates->sum('sent_count') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border--info">
            <div class="card-header bg--info">
                <h5 class="card-title text-white mb-0">
                    <i class="las la-users"></i> @lang('Target Audience')
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Total Users')</span>
                        <span class="badge badge--primary">{{ \App\Models\User::count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Customers')</span>
                        <span class="badge badge--success">{{ \App\Models\User::whereDoesntHave('company')->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Companies')</span>
                        <span class="badge badge--info">{{ \App\Models\User::whereHas('company')->count() }}</span>
                    </li>
                </ul>
                <div class="mt-3">
                    <a href="{{ route('admin.email.flow.marketing.create') }}" class="btn btn--primary btn-sm w-100">
                        <i class="las la-plus"></i> @lang('Create New Campaign')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Send Campaign Modal --}}
<div class="modal fade" id="sendCampaignModal" tabindex="-1" role="dialog" aria-labelledby="sendCampaignModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCampaignModalLabel">@lang('Send Marketing Campaign')</h5>
                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </span>
            </div>
            <form action="" method="POST" id="sendCampaignForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert--warning">
                        <p><strong>@lang('Campaign Name'):</strong> <span id="campaignName"></span></p>
                        <p><strong>@lang('Target Recipients'):</strong> <span id="recipientCount"></span> @lang('users')</p>
                        <p class="mb-0"><strong>@lang('Note'):</strong> @lang('This action will send emails to all targeted recipients immediately.')</p>
                    </div>
                    <div class="form-group">
                        <label>@lang('Confirm by typing "SEND" below')</label>
                        <input type="text" class="form-control" id="confirmSend" placeholder="@lang('Type SEND to confirm')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--success" id="confirmSendBtn" disabled>@lang('Send Campaign')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.email.flow.marketing.create') }}" class="btn btn-sm btn--primary">
        <i class="la la-plus"></i>@lang('Create Marketing Campaign')
    </a>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.sendCampaignBtn').on('click', function () {
            var modal = $('#sendCampaignModal');
            var campaignId = $(this).data('id');
            var campaignName = $(this).data('name');
            var recipients = $(this).data('recipients');
            
            modal.find('#campaignName').text(campaignName);
            modal.find('#recipientCount').text(recipients);
            modal.find('#sendCampaignForm').attr('action', '{{ route("admin.email.flow.marketing.send", ":id") }}'.replace(':id', campaignId));
            modal.find('#confirmSend').val('');
            modal.find('#confirmSendBtn').prop('disabled', true);
            
            modal.modal('show');
        });
        
        $('#confirmSend').on('input', function () {
            if ($(this).val().toUpperCase() === 'SEND') {
                $('#confirmSendBtn').prop('disabled', false);
            } else {
                $('#confirmSendBtn').prop('disabled', true);
            }
        });
        
    })(jQuery);
</script>
@endpush 