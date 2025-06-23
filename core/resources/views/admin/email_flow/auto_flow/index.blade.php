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
                                <th>@lang('Template Name')</th>
                                <th>@lang('Template Act')</th>
                                <th>@lang('Flow Type')</th>
                                <th>@lang('Priority')</th>
                                <th>@lang('Status')</th>
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
                                        <span class="badge badge--primary">{{ __($template->act) }}</span>
                                    </td>
                                    <td>
                                        @if($template->flow_type == 'auto')
                                            <span class="badge badge--success">@lang('Auto Flow')</span>
                                        @else
                                            <form action="{{ route('admin.email.flow.auto.integrate') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="badge badge--warning border-0" title="@lang('Click to integrate to Auto Flow')">
                                                    @lang('System')
                                                </button>
                                            </form>
                                        @endif
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
                                    <td colspan="8" class="text-muted text-center">{{ __($emptyMessage ?? 'No auto flow templates found') }}</td>
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
        <div class="card border--primary">
            <div class="card-header bg--primary">
                <h5 class="card-title text-white mb-0">
                    <i class="las la-info-circle"></i> @lang('Auto Flow Information')
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Appointment Templates')</span>
                        <span class="badge badge--primary">{{ $templates->whereIn('act', ['NEW_APPOINTMENT', 'APPOINTMENT_CONFIRMED', 'APPOINTMENT_COMPLETED', 'APPOINTMENT_CANCELED'])->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Active Templates')</span>
                        <span class="badge badge--success">{{ $templates->where('email_status', 1)->count() }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>@lang('Total Auto Templates')</span>
                        <span class="badge badge--info">{{ $templates->where('flow_type', 'auto')->count() }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border--success">
            <div class="card-header bg--success">
                <h5 class="card-title text-white mb-0">
                    <i class="las la-cogs"></i> @lang('Quick Actions')
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.email.flow.auto.create') }}" class="btn btn--primary">
                        <i class="las la-plus"></i> @lang('Create Auto Template')
                    </a>
                    <form action="{{ route('admin.email.flow.auto.integrate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn--success w-100">
                            <i class="las la-sync"></i> @lang('Integrate Appointment Templates')
                        </button>
                    </form>
                    <a href="{{ route('admin.email.flow.statistics') }}" class="btn btn--info">
                        <i class="las la-chart-bar"></i> @lang('View Statistics')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.email.flow.auto.create') }}" class="btn btn-sm btn--primary">
        <i class="la la-plus"></i>@lang('Add New Auto Template')
    </a>
@endpush 