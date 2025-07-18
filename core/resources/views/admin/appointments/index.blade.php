@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Total Appointments</h6>
                        <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="las la-calendar-alt la-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Pending</h6>
                        <h3 class="mb-0">{{ number_format($stats['pending']) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="las la-clock la-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Confirmed</h6>
                        <h3 class="mb-0">{{ number_format($stats['confirmed']) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="las la-check-circle la-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-white-50">Revenue (50k/confirmation)</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_revenue']) }} VND</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="las la-money-bill la-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <!-- Quick Action Buttons -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">@lang('Appointment Management')</h5>
                        <small class="text-muted">Track contractor appointments and 50k fee revenue</small>
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('admin.appointments.analytics') }}" class="btn btn--success">
                            <i class="las la-chart-line"></i> Analytics
                        </a>
                        <a href="{{ route('admin.appointments.company.performance') }}" class="btn btn--info">
                            <i class="las la-users"></i> Contractor Performance
                        </a>
                        <a href="{{ route('admin.appointments.wallet.analytics') }}" class="btn btn--warning">
                            <i class="las la-wallet"></i> Wallet Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.appointments.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div class="form-control">
                                <button type="submit" class="btn btn--primary">Filter</button>
                                <a href="{{ route('admin.appointments.index') }}" class="btn btn--secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Appointments Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Contractor</th>
                                <th>Appointment Date</th>
                                <th>Status</th>
                                <th>Fee Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td>
                                    <span class="font-weight-bold">#{{ $appointment->id }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $appointment->recipient_name }}</strong><br>
                                        <small class="text-muted">{{ $appointment->recipient_phone }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $appointment->company->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">
                                            Wallet: {{ $appointment->company && $appointment->company->wallet ? number_format($appointment->company->wallet->balance) . ' VND' : 'No wallet' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</strong><br>
                                        <small>{{ $appointment->appointment_time }}</small>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'pending' => 'badge--warning',
                                            'confirmed' => 'badge--success', 
                                            'completed' => 'badge--primary',
                                            'cancelled' => 'badge--danger'
                                        ][$appointment->status] ?? 'badge--secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                                </td>
                                <td>
                                    @if($appointment->status == 'confirmed' || $appointment->status == 'completed')
                                        <span class="badge badge--success">50k Paid</span>
                                    @else
                                        <span class="badge badge--secondary">No Fee</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        {{ $appointment->created_at->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $appointment->created_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}" 
                                       class="btn btn--primary btn--sm">
                                        <i class="las la-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="las la-calendar-times la-3x text-muted"></i>
                                        <p class="text-muted mt-2">No appointments found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($appointments->hasPages())
        <div class="card-footer">
            {{ paginateLinks($appointments) }}
        </div>
        @endif
    </div>
</div>

<!-- Summary Stats -->
<div class="row mt-4">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Today's Stats</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>Appointments Today:</span>
                    <strong>{{ $stats['today'] }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">This Week</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>Appointments This Week:</span>
                    <strong>{{ $stats['this_week'] }}</strong>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Avg Confirmation Time</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span>Minutes:</span>
                    <strong>{{ round($stats['avg_confirmation_time'] ?? 0) }} min</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 