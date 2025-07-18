@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Appointment Analytics')</h5>
                <small class="text-muted">Detailed insights into contractor appointment performance and revenue</small>
            </div>
            <div class="card-body">
                
                <!-- Revenue Stats -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($revenueStats['total_revenue']) }} VND</h4>
                                <p class="mb-0">Total Revenue</p>
                                <small>(50k × {{ number_format($revenueStats['total_revenue'] / 50000) }} confirmations)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($revenueStats['revenue_this_month']) }} VND</h4>
                                <p class="mb-0">This Month Revenue</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($revenueStats['avg_revenue_per_company']) }} VND</h4>
                                <p class="mb-0">Avg Revenue/Contractor</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($revenueStats['total_wallet_balance']) }} VND</h4>
                                <p class="mb-0">Total Wallet Balance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Time Analytics -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Time-based Analytics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <strong>Avg Confirmation Time:</strong><br>
                                        <span class="text-primary">{{ round($timeStats['avg_confirmation_time']) }} minutes</span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Avg Completion Time:</strong><br>
                                        <span class="text-success">{{ round($timeStats['avg_completion_time']) }} hours</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Peak Hours</h6>
                            </div>
                            <div class="card-body">
                                @forelse($timeStats['peak_hours'] as $hour)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $hour->hour }}:00</span>
                                    <strong>{{ $hour->count }} appointments</strong>
                                </div>
                                @empty
                                <p class="text-muted">No appointment data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Status Distribution</h6>
                            </div>
                            <div class="card-body">
                                @forelse($statusDistribution as $status)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-capitalize">{{ $status->status }}:</span>
                                    <strong>{{ number_format($status->count) }}</strong>
                                </div>
                                @empty
                                <p class="text-muted">No status data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Peak Days</h6>
                            </div>
                            <div class="card-body">
                                @forelse($timeStats['peak_days'] as $day)
                                <div class="d-flex justify-content-between">
                                    <span>{{ $day->day }}</span>
                                    <strong>{{ $day->count }} appointments</strong>
                                </div>
                                @empty
                                <p class="text-muted">No appointment data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Companies -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Performing Contractors (by Appointments)</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Contractor</th>
                                                <th>Total</th>
                                                <th>Confirmed</th>
                                                <th>Completed</th>
                                                <th>Confirmation Rate</th>
                                                <th>Earnings</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($companyStats as $company)
                                            <tr>
                                                <td>
                                                    <strong>{{ $company->name }}</strong><br>
                                                    <small class="text-muted">
                                                        Wallet: {{ $company->wallet ? number_format($company->wallet->balance) . ' VND' : 'No wallet' }}
                                                    </small>
                                                </td>
                                                <td>{{ $company->total_appointments }}</td>
                                                <td>{{ $company->confirmed_appointments }}</td>
                                                <td>{{ $company->completed_appointments }}</td>
                                                <td>
                                                    <span class="badge {{ $company->confirmation_rate >= 80 ? 'badge--success' : ($company->confirmation_rate >= 50 ? 'badge--warning' : 'badge--danger') }}">
                                                        {{ $company->confirmation_rate }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>{{ number_format($company->earnings) }} VND</strong>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    No contractor data available
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-4">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn--secondary">
                        <i class="las la-arrow-left"></i> Back to Appointments
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 