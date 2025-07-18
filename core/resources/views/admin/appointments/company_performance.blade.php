@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Contractor Performance')</h5>
                <small class="text-muted">Detailed performance metrics for contractors based on appointment data</small>
            </div>
            <div class="card-body">
                
                <!-- Performance Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Contractor</th>
                                <th>Total Appointments</th>
                                <th>Confirmed</th>
                                <th>Completed</th>
                                <th>Cancelled</th>
                                <th>Confirmation Rate</th>
                                <th>Completion Rate</th>
                                <th>Earnings (50k each)</th>
                                <th>Wallet Balance</th>
                                <th>Performance Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $company->name }}</strong><br>
                                        <small class="text-muted">{{ $company->user->fullname ?? 'Unknown Owner' }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $company->total_appointments }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--success">{{ $company->confirmed_appointments }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--info">{{ $company->completed_appointments }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--danger">{{ $company->cancelled_appointments }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $company->confirmation_rate >= 80 ? 'badge--success' : ($company->confirmation_rate >= 50 ? 'badge--warning' : 'badge--danger') }}">
                                        {{ $company->confirmation_rate }}%
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $company->completion_rate >= 90 ? 'badge--success' : ($company->completion_rate >= 70 ? 'badge--warning' : 'badge--danger') }}">
                                        {{ $company->completion_rate }}%
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ number_format($company->earnings) }} VND</strong>
                                </td>
                                <td>
                                    @if($company->wallet)
                                        <span class="text-success">{{ number_format($company->wallet_balance) }} VND</span>
                                    @else
                                        <span class="text-danger">No wallet</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $scoreClass = $company->performance_score >= 80 ? 'badge--success' : 
                                                     ($company->performance_score >= 60 ? 'badge--warning' : 'badge--danger');
                                    @endphp
                                    <span class="badge {{ $scoreClass }}">
                                        {{ $company->performance_score }}/100
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($companies->hasPages())
                <div class="mt-3">
                    {{ paginateLinks($companies) }}
                </div>
                @endif

                <!-- Performance Insights -->
                <div class="row mt-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Performance Insights</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $totalEarnings = $companies->sum('earnings');
                                    $avgConfirmationRate = $companies->avg('confirmation_rate');
                                    $avgCompletionRate = $companies->avg('completion_rate');
                                    $topPerformer = $companies->sortByDesc('performance_score')->first();
                                @endphp
                                <div class="mb-2">
                                    <strong>Total Revenue Generated:</strong> {{ number_format($totalEarnings) }} VND
                                </div>
                                <div class="mb-2">
                                    <strong>Average Confirmation Rate:</strong> {{ number_format($avgConfirmationRate, 1) }}%
                                </div>
                                <div class="mb-2">
                                    <strong>Average Completion Rate:</strong> {{ number_format($avgCompletionRate, 1) }}%
                                </div>
                                @if($topPerformer)
                                <div class="mb-2">
                                    <strong>Top Performer:</strong> {{ $topPerformer->name }} ({{ $topPerformer->performance_score }}/100)
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Performance Categories</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $excellent = $companies->where('performance_score', '>=', 80)->count();
                                    $good = $companies->whereBetween('performance_score', [60, 79])->count();
                                    $needsImprovement = $companies->where('performance_score', '<', 60)->count();
                                @endphp
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Excellent (80-100):</span>
                                    <span class="badge badge--success">{{ $excellent }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Good (60-79):</span>
                                    <span class="badge badge--warning">{{ $good }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Needs Improvement (&lt;60):</span>
                                    <span class="badge badge--danger">{{ $needsImprovement }}</span>
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
                    <a href="{{ route('admin.appointments.analytics') }}" class="btn btn--primary">
                        <i class="las la-chart-line"></i> View Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 