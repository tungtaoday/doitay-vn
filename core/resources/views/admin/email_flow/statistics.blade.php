@extends('admin.layouts.app')
@section('panel')
@push('topBar')
  @include('admin.email_flow.top_bar')
@endpush

<div class="row gy-4 mb-4">
    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--success">
            <div class="widget-two__icon b-radius--5 bg--success">
                <i class="las la-robot"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ $autoFlowStats['total_templates'] }}</h3>
                <p class="text-white">@lang('Auto Flow Templates')</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
            <div class="widget-two__icon b-radius--5 bg--primary">
                <i class="las la-bullhorn"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ $marketingFlowStats['total_campaigns'] }}</h3>
                <p class="text-white">@lang('Marketing Campaigns')</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--warning">
            <div class="widget-two__icon b-radius--5 bg--warning">
                <i class="las la-paper-plane"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ $autoFlowStats['total_sent'] + $marketingFlowStats['total_sent'] }}</h3>
                <p class="text-white">@lang('Total Emails Sent')</p>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6">
        <div class="widget-two style--two box--shadow2 b-radius--5 bg--info">
            <div class="widget-two__icon b-radius--5 bg--info">
                <i class="las la-clock"></i>
            </div>
            <div class="widget-two__content">
                <h3 class="text-white">{{ $marketingFlowStats['scheduled_campaigns'] }}</h3>
                <p class="text-white">@lang('Scheduled Campaigns')</p>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-robot text-success"></i> @lang('Auto Flow Statistics')
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Templates')
                        <span class="badge badge--primary">{{ $autoFlowStats['total_templates'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Active Templates')
                        <span class="badge badge--success">{{ $autoFlowStats['active_templates'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Inactive Templates')
                        <span class="badge badge--warning">{{ $autoFlowStats['total_templates'] - $autoFlowStats['active_templates'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Emails Sent')
                        <span class="badge badge--dark">{{ $autoFlowStats['total_sent'] }}</span>
                    </div>
                </div>
                <div class="mt-3 d-grid">
                    <a href="{{ route('admin.email.flow.auto') }}" class="btn btn--success btn-sm">
                        <i class="las la-eye"></i> @lang('View Auto Flow')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-bullhorn text-warning"></i> @lang('Marketing Flow Statistics')
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Campaigns')
                        <span class="badge badge--primary">{{ $marketingFlowStats['total_campaigns'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Active Campaigns')
                        <span class="badge badge--success">{{ $marketingFlowStats['active_campaigns'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Scheduled Campaigns')
                        <span class="badge badge--info">{{ $marketingFlowStats['scheduled_campaigns'] }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Emails Sent')
                        <span class="badge badge--dark">{{ $marketingFlowStats['total_sent'] }}</span>
                    </div>
                </div>
                <div class="mt-3 d-grid">
                    <a href="{{ route('admin.email.flow.marketing') }}" class="btn btn--warning btn-sm">
                        <i class="las la-eye"></i> @lang('View Marketing Flow')
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-calendar-check text-info"></i> @lang('Recent Appointments')
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Customer')</th>
                                <th>@lang('Company')</th>
                                <th>@lang('Date & Time')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Email Sent')</th>
                                <th>@lang('Created')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAppointments as $appointment)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-bold">{{ $appointment->recipient_name }}</span>
                                            <br><small class="text-muted">{{ $appointment->recipient_phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($appointment->company)
                                            <span class="fw-bold">{{ $appointment->company->name }}</span>
                                        @else
                                            <span class="text-muted">@lang('N/A')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->appointment_date && $appointment->appointment_time)
                                            {{ showDateTime($appointment->appointment_date . ' ' . $appointment->appointment_time, 'd M Y h:i A') }}
                                        @else
                                            <span class="text-muted">@lang('Not set')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($appointment->status == 'pending')
                                            <span class="badge badge--warning">@lang('Pending')</span>
                                        @elseif($appointment->status == 'confirmed')
                                            <span class="badge badge--success">@lang('Confirmed')</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="badge badge--primary">@lang('Completed')</span>
                                        @elseif($appointment->status == 'cancelled')
                                            <span class="badge badge--danger">@lang('Cancelled')</span>
                                        @else
                                            <span class="badge badge--secondary">{{ ucfirst($appointment->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $emailSent = \App\Models\NotificationLog::where('user_id', $appointment->user_id)
                                                        ->whereIn('template_name', ['NEW_APPOINTMENT', 'APPOINTMENT_CONFIRMED', 'APPOINTMENT_COMPLETED', 'APPOINTMENT_CANCELED'])
                                                        ->whereDate('created_at', $appointment->created_at->toDateString())
                                                        ->exists();
                                        @endphp
                                        @if($emailSent)
                                            <span class="badge badge--success">@lang('Yes')</span>
                                        @else
                                            <span class="badge badge--secondary">@lang('No')</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ showDateTime($appointment->created_at, 'd M Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted text-center">@lang('No recent appointments found')</td>
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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-envelope text-primary"></i> @lang('Email Flow Integration Status')
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $appointmentTemplates = \App\Models\NotificationTemplate::whereIn('act', ['NEW_APPOINTMENT', 'APPOINTMENT_CONFIRMED', 'APPOINTMENT_COMPLETED', 'APPOINTMENT_CANCELED'])->get();
                    @endphp
                    @foreach($appointmentTemplates as $template)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold">{{ $template->name }}</span>
                                <br><small class="text-muted">{{ $template->act }}</small>
                            </div>
                            @if($template->flow_type == 'auto')
                                <span class="badge badge--success">@lang('Integrated')</span>
                            @else
                                <span class="badge badge--warning">@lang('Not Integrated')</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($appointmentTemplates->where('flow_type', '!=', 'auto')->count() > 0)
                    <div class="mt-3">
                        <form action="{{ route('admin.email.flow.auto.integrate') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn--success btn-sm w-100">
                                <i class="las la-sync"></i> @lang('Integrate All Appointment Templates')
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-users text-info"></i> @lang('User Statistics')
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Users')
                        <span class="badge badge--primary">{{ \App\Models\User::count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Active Users')
                        <span class="badge badge--success">{{ \App\Models\User::where('status', 1)->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Customers')
                        <span class="badge badge--info">{{ \App\Models\User::whereDoesntHave('company')->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Companies')
                        <span class="badge badge--warning">{{ \App\Models\User::whereHas('company')->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        @lang('Total Appointments')
                        <span class="badge badge--dark">{{ \App\Models\Appointment::count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 