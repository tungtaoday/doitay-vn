@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Wallet Analytics')</h5>
                <small class="text-muted">Comprehensive analysis of contractor wallets and 50k fee transactions</small>
            </div>
            <div class="card-body">
                
                <!-- Wallet Overview Stats -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($walletStats['total_wallets']) }}</h4>
                                <p class="mb-0">Total Wallets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($walletStats['active_wallets']) }}</h4>
                                <p class="mb-0">Active Wallets</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($walletStats['total_balance']) }} VND</h4>
                                <p class="mb-0">Total Balance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ number_format($walletStats['avg_balance']) }} VND</h4>
                                <p class="mb-0">Average Balance</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wallet Health Indicators -->
                <div class="row mb-4">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Wallet Health</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Low Balance Wallets:</span>
                                    <span class="badge badge--warning">{{ $walletStats['low_balance_wallets'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Empty Wallets:</span>
                                    <span class="badge badge--danger">{{ $walletStats['empty_wallets'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Companies without Wallet:</span>
                                    <span class="badge badge--secondary">{{ $companiesWithoutWallet }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Top Wallets by Balance</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Company</th>
                                                <th>Balance</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topWallets as $wallet)
                                            <tr>
                                                <td>
                                                    <strong>{{ $wallet->company->name ?? 'Unknown' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="text-success">{{ number_format($wallet->balance) }} VND</span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $wallet->is_active ? 'badge--success' : 'badge--danger' }}">
                                                        {{ $wallet->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spending Analysis -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">50k Fee Spending Patterns</h6>
                                <small class="text-muted">Top contractors by appointment confirmations (spending 50k each)</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Contractor</th>
                                                <th>Confirmed Appointments</th>
                                                <th>Total Spent (50k each)</th>
                                                <th>Current Balance</th>
                                                <th>Balance vs Spending</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($spendingStats as $stat)
                                            <tr>
                                                <td>
                                                    <strong>{{ $stat->company->name ?? 'Unknown Company' }}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge--primary">{{ $stat->confirmed_count }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-danger">{{ number_format($stat->total_spent) }} VND</span>
                                                </td>
                                                <td>
                                                    <span class="text-success">{{ number_format($stat->current_balance) }} VND</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $ratio = $stat->current_balance > 0 ? ($stat->total_spent / $stat->current_balance) : 'N/A';
                                                    @endphp
                                                    @if($ratio === 'N/A')
                                                        <span class="text-muted">N/A</span>
                                                    @elseif($ratio > 2)
                                                        <span class="badge badge--danger">High Spending</span>
                                                    @elseif($ratio > 1)
                                                        <span class="badge badge--warning">Moderate</span>
                                                    @else
                                                        <span class="badge badge--success">Conservative</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Insights -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Insights & Recommendations</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Wallet Health Summary:</h6>
                                        <ul class="list-unstyled">
                                            <li><i class="las la-check text-success"></i> {{ $walletStats['active_wallets'] }} active wallets</li>
                                            <li><i class="las la-exclamation-triangle text-warning"></i> {{ $walletStats['low_balance_wallets'] }} wallets with low balance (&lt;100k)</li>
                                            <li><i class="las la-times text-danger"></i> {{ $walletStats['empty_wallets'] }} empty wallets</li>
                                            <li><i class="las la-minus text-secondary"></i> {{ $companiesWithoutWallet }} companies without wallets</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Revenue Impact:</h6>
                                        <ul class="list-unstyled">
                                            <li><strong>Total Revenue from 50k fees:</strong> {{ number_format($spendingStats->sum('total_spent')) }} VND</li>
                                            <li><strong>Average spend per active contractor:</strong> {{ number_format($spendingStats->avg('total_spent')) }} VND</li>
                                            <li><strong>Top spender:</strong> {{ $spendingStats->first()->company->name ?? 'N/A' }} ({{ number_format($spendingStats->first()->total_spent ?? 0) }} VND)</li>
                                        </ul>
                                    </div>
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