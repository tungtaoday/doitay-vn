@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="las la-user-shield"></i>
                    Authentication Management Dashboard
                </h5>
            </div>
            <div class="card-body">
                <!-- Stats Overview -->
                <div class="row gy-4 mb-4">
                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                            <div class="widget-two__icon b-radius--5 bg--primary">
                                <i class="las la-users"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ number_format($stats['total_users']) }}</h3>
                                <p class="text-white">Total Users</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two style--two box--shadow2 b-radius--5 bg--success">
                            <div class="widget-two__icon b-radius--5 bg--success">
                                <i class="las la-user-plus"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ number_format($stats['users_today']) }}</h3>
                                <p class="text-white">New Users Today</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two style--two box--shadow2 b-radius--5 bg--info">
                            <div class="widget-two__icon b-radius--5 bg--info">
                                <i class="las la-sign-in-alt"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ number_format($stats['total_logins_today']) }}</h3>
                                <p class="text-white">Logins Today</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-sm-6">
                        <div class="widget-two style--two box--shadow2 b-radius--5 bg--warning">
                            <div class="widget-two__icon b-radius--5 bg--warning">
                                <i class="las la-check-circle"></i>
                            </div>
                            <div class="widget-two__content">
                                <h3 class="text-white">{{ number_format($stats['verified_users']) }}</h3>
                                <p class="text-white">Verified Users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.auth.analytics') }}" class="btn btn--primary">
                                        <i class="las la-chart-line"></i> View Analytics
                                    </a>
                                    <a href="{{ route('admin.auth.verification') }}" class="btn btn--warning">
                                        <i class="las la-user-check"></i> User Verification
                                    </a>
                                    <a href="{{ route('admin.auth.security') }}" class="btn btn--danger">
                                        <i class="las la-shield-alt"></i> Security Settings
                                    </a>
                                    <a href="{{ route('admin.auth.social') }}" class="btn btn--info">
                                        <i class="lab la-google"></i> Social Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Authentication Methods Breakdown -->
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Authentication Methods</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="authMethodsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Registration Conversion Funnel</h6>
                            </div>
                            <div class="card-body">
                                <div class="funnel-step">
                                    <div class="funnel-label">Registration Started</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: 100%">
                                            {{ number_format($funnelData['registration_started']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="funnel-step">
                                    <div class="funnel-label">Step 1 (Email) Completed</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: {{ ($funnelData['step_1_completed'] / $funnelData['registration_started']) * 100 }}%">
                                            {{ number_format($funnelData['step_1_completed']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="funnel-step">
                                    <div class="funnel-label">Step 2 (Password) Completed</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: {{ ($funnelData['step_2_completed'] / $funnelData['registration_started']) * 100 }}%">
                                            {{ number_format($funnelData['step_2_completed']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="funnel-step">
                                    <div class="funnel-label">Registration Completed</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: {{ ($funnelData['registration_completed'] / $funnelData['registration_started']) * 100 }}%">
                                            {{ number_format($funnelData['registration_completed']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="funnel-step">
                                    <div class="funnel-label">Fully Active Users</div>
                                    <div class="progress">
                                        <div class="progress-bar bg-dark" style="width: {{ ($funnelData['fully_active'] / $funnelData['registration_started']) * 100 }}%">
                                            {{ number_format($funnelData['fully_active']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Login Activity -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Recent Login Activity</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive--md">
                                    <table class="table table--light style--two">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>IP Address</th>
                                                <th>Location</th>
                                                <th>Browser</th>
                                                <th>Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($recentLogins as $login)
                                            <tr>
                                                <td>
                                                    <div class="user-info">
                                                        <div class="user-name">
                                                            {{ $login->user ? $login->user->fullname : 'Unknown' }}
                                                        </div>
                                                        <div class="user-email text-muted">
                                                            {{ $login->user ? $login->user->email : 'N/A' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <code>{{ $login->user_ip }}</code>
                                                </td>
                                                <td>
                                                    <i class="las la-map-marker"></i>
                                                    {{ $login->city ?: 'Unknown' }}, {{ $login->country ?: 'Unknown' }}
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($login->browser, 30) }}</small>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $login->created_at->diffForHumans() }}</span>
                                                </td>
                                                <td>
                                                    @if($login->user)
                                                    <a href="{{ route('admin.users.detail', $login->user->id) }}" class="btn btn--primary btn--sm">
                                                        <i class="las la-eye"></i>
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No recent login activity</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.funnel-step {
    margin-bottom: 1rem;
}

.funnel-label {
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.progress {
    height: 1.5rem;
    background-color: #e9ecef;
    border-radius: 0.375rem;
}

.progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 500;
    font-size: 0.875rem;
}

.user-info .user-name {
    font-weight: 500;
    color: #495057;
}

.user-info .user-email {
    font-size: 0.875rem;
}

.widget-two {
    position: relative;
    overflow: hidden;
}

.widget-two__icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.3;
    font-size: 3rem;
}

.widget-two__content {
    position: relative;
    z-index: 1;
}

.widget-two__content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.widget-two__content p {
    font-size: 0.875rem;
    margin-bottom: 0;
    opacity: 0.9;
}
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Authentication Methods Chart
    const authMethodsCtx = document.getElementById('authMethodsChart').getContext('2d');
    
    new Chart(authMethodsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Email/Password', 'Google', 'Facebook', 'Mobile Only'],
            datasets: [{
                data: [
                    {{ $authMethods['email_password'] }},
                    {{ $authMethods['google'] }},
                    {{ $authMethods['facebook'] }},
                    {{ $authMethods['mobile_only'] }}
                ],
                backgroundColor: [
                    '#36a2eb',
                    '#ff6384',
                    '#4bc0c0',
                    '#ff9f40'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed * 100) / total).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush 