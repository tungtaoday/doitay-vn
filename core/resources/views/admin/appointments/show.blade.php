@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">@lang('Appointment Details') #{{ $appointment->id }}</h5>
                <small class="text-muted">Detailed view of appointment and payment tracking</small>
            </div>
            <div class="card-body">
                
                <!-- Appointment Overview -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Appointment Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Customer Name:</strong> {{ $appointment->recipient_name }}</p>
                                        <p><strong>Phone:</strong> {{ $appointment->recipient_phone }}</p>
                                        <p><strong>Address:</strong> {{ $appointment->recipient_address }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Appointment Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</p>
                                        <p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>
                                        <p><strong>Status:</strong> 
                                            @php
                                                $statusClass = [
                                                    'pending' => 'badge--warning',
                                                    'confirmed' => 'badge--success', 
                                                    'completed' => 'badge--primary',
                                                    'cancelled' => 'badge--danger'
                                                ][$appointment->status] ?? 'badge--secondary';
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst($appointment->status) }}</span>
                                        </p>
                                    </div>
                                </div>
                                @if($appointment->notes)
                                <div class="mt-3">
                                    <strong>Notes:</strong>
                                    <p class="bg-light p-2 rounded">{{ $appointment->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Payment Status</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($appointment->status == 'confirmed' || $appointment->status == 'completed')
                                    <div class="text-success">
                                        <i class="las la-check-circle la-3x"></i>
                                        <h4 class="text-success">50,000 VND</h4>
                                        <p>Fee Paid Successfully</p>
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="las la-clock la-3x"></i>
                                        <h4 class="text-muted">0 VND</h4>
                                        <p>No fee charged yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contractor Information -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Contractor Information</h6>
                            </div>
                            <div class="card-body">
                                @if($appointment->company)
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong>Company Name:</strong> {{ $appointment->company->name }}</p>
                                        <p><strong>Owner:</strong> {{ $appointment->company->user->fullname ?? 'N/A' }}</p>
                                        <p><strong>Email:</strong> {{ $appointment->company->user->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Phone:</strong> {{ $appointment->company->mobile ?? 'N/A' }}</p>
                                        <p><strong>Address:</strong> {{ $appointment->company->address ?? 'N/A' }}</p>
                                        <p><strong>Rating:</strong> 
                                            @if($appointment->company->reviews_avg_rating)
                                                {{ number_format($appointment->company->reviews_avg_rating, 1) }}/5.0
                                            @else
                                                No reviews yet
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong>Wallet Balance:</strong> 
                                            @if($appointment->company->wallet)
                                                {{ number_format($appointment->company->wallet->balance) }} VND
                                            @else
                                                <span class="text-danger">No wallet</span>
                                            @endif
                                        </p>
                                        <p><strong>Company Status:</strong> 
                                            <span class="badge {{ $appointment->company->status == 1 ? 'badge--success' : 'badge--danger' }}">
                                                {{ $appointment->company->status == 1 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-warning">
                                    <i class="las la-exclamation-triangle"></i>
                                    No contractor assigned to this appointment
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Appointment Timeline</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @foreach($timeline as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-{{ $event['color'] }}">
                                            <i class="{{ $event['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">{{ $event['title'] }}</h6>
                                            <p class="timeline-description">{{ $event['description'] }}</p>
                                            <small class="text-muted">{{ $event['timestamp']->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn--secondary">
                        <i class="las la-arrow-left"></i> Back to List
                    </a>
                    @if($appointment->company)
                    <a href="{{ route('admin.company.details', $appointment->company->id) }}" class="btn btn--info">
                        <i class="las la-building"></i> View Company Details
                    </a>
                    @endif
                    @if($appointment->user)
                    <a href="{{ route('admin.users.detail', $appointment->user->id) }}" class="btn btn--primary">
                        <i class="las la-user"></i> View Customer Details
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    bottom: -30px;
    width: 2px;
    background: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 10px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-description {
    margin-bottom: 5px;
    color: #6c757d;
}
</style>
@endsection 