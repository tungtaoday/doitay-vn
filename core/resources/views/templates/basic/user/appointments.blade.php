@extends($activeTemplate . 'layouts.auth')
@section('content')
<div class="container mt-4">
    <div class="mb-4">
        <h1 class="h3 text--base">{{ $pageTitle }}</h1>
    </div>

    @if ($appointments->isEmpty())
        <div class="card custom--card">
            <div class="card-body text-center py-5">
                <i class="las la-calendar-times text--base" style="font-size: 48px;"></i>
                <h4 class="mt-3">@lang('Chưa có lịch hẹn nào')</h4>
                <p class="text-muted">@lang('Bạn chưa tạo bất kỳ lịch hẹn nào.')</p>
            </div>
        </div>
    @else
        <div class="card custom--card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('Công ty')</th>
                                <th>@lang('Ngày hẹn')</th>
                                <th>@lang('Tên người nhận')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Thao tác')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr>
                                    <td data-label="@lang('Công ty')">
                                        <div class="d-flex align-items-center">
                                            <div class="company-thumb me-2">
                                                <img src="{{ getImage(getFilePath('company') . '/' . ($appointment->company->image ?? 'default.png'), getFileSize('company')) }}" alt="@lang('Company')">
                                            </div>
                                            <div class="company-info">
                                                <h6 class="mb-0">{{ $appointment->company ? $appointment->company->name : 'N/A' }}</h6>
                                                <small class="text-muted">{{ $appointment->company ? $appointment->company->address : '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Ngày hẹn')">
                                        <div class="d-flex flex-column">
                                            <span>{{ $appointment->appointment_date }}</span>
                                            <small class="text-muted">{{ $appointment->appointment_time }}</small>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Tên người nhận')">
                                        <div class="d-flex flex-column">
                                            <span>{{ $appointment->recipient_name }}</span>
                                            <small class="text-muted">{{ $appointment->recipient_phone }}</small>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Trạng thái')">
                                        <span class="badge {{ $appointment->status === 'pending' ? 'bg-warning' : ($appointment->status === 'confirmed' ? 'bg-success' : ($appointment->status === 'completed' ? 'bg-primary' : 'bg-danger')) }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td data-label="@lang('Thao tác')">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('appointments.show', $appointment->id) }}" class="btn btn--base btn-sm">
                                                <i class="las la-eye"></i>
                                            </a>
                                            @if ($appointment->status === 'pending')
                                                <form action="{{ route('appointments.cancel', $appointment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn--danger btn-sm" onclick="return confirm('@lang('Bạn có chắc muốn hủy lịch hẹn này?')')">
                                                        <i class="las la-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .custom--card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        border-radius: 10px;
    }
    .company-thumb {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
    }
    .company-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .company-info {
        flex: 1;
    }
    .table--responsive--lg {
        width: 100%;
        margin-bottom: 0;
    }
    .table--responsive--lg thead th {
        background: #f8f9fa;
        padding: 15px;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #dee2e6;
    }
    .table--responsive--lg tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #dee2e6;
    }
    .table--responsive--lg tbody tr:last-child td {
        border-bottom: none;
    }
    .badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: 500;
    }
    .btn--base {
        background: var(--base);
        color: white;
    }
    .btn--danger {
        background: #dc3545;
        color: white;
    }
    @media (max-width: 991px) {
        .table--responsive--lg {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
        .table--responsive--lg thead {
            display: none;
        }
        .table--responsive--lg tbody tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }
        .table--responsive--lg tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        .table--responsive--lg tbody td:last-child {
            border-bottom: none;
        }
        .table--responsive--lg tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 10px;
        }
    }
</style>
@endsection