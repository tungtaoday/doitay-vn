<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Company;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Services\WalletService;
use App\Services\CompanyStatisticsService;
use App\Services\NotificationService;
use App\Services\ServiceRequestService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AppointmentService
{
    public function __construct(
        protected WalletService $walletService,
        protected CompanyStatisticsService $statisticsService,
        protected ServiceRequestService $serviceRequestService,
    ) {}

    public function createForUser(User $user, array $data): Appointment
    {
        $existing = Appointment::where('user_id', $user->id)
            ->where('company_id', $data['company_id'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existing) {
            throw new \DomainException('Bạn đã có lịch hẹn với thợ này rồi!');
        }

        // Validate service request ownership BEFORE creating appointment —
        // avoid orphan appointment if someone passes another user's request.
        $serviceRequest = null;
        if (! empty($data['service_request_id'])) {
            $serviceRequest = ServiceRequest::where('user_id', $user->id)
                ->where('id', $data['service_request_id'])
                ->first();
            if (! $serviceRequest) {
                throw new \DomainException('Yêu cầu dịch vụ không tồn tại hoặc không thuộc về bạn.');
            }
        }

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'company_id' => $data['company_id'],
            'service_request_id' => $serviceRequest?->id,
            'recipient_name' => $data['recipient_name'],
            'recipient_phone' => $data['recipient_phone'],
            'recipient_address' => $data['recipient_address'],
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);

        $this->notifyBothParties($user, $appointment, 'NEW_APPOINTMENT', 'appointment_created');

        if ($serviceRequest) {
            $this->serviceRequestService->linkAppointment(
                $serviceRequest,
                $appointment->id,
                $appointment->company_id,
            );
        }

        return $appointment->load('company');
    }

    public function listForUser(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return Appointment::where('user_id', $user->id)
            ->with('company:id,name,image')
            ->latest()
            ->paginate($perPage);
    }

    public function listForCompany(User $user, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $companyIds = $user->companies()->pluck('id');

        $query = Appointment::whereIn('company_id', $companyIds)
            ->with('company:id,name');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function companyStats(User $user): array
    {
        $companyIds = $user->companies()->pluck('id');
        $appointments = Appointment::whereIn('company_id', $companyIds)->get();

        $wallets = $user->companies()->with('wallet')->get();
        $totalBalance = $wallets->sum(fn ($c) => $c->wallet?->balance ?? 0);
        $pendingCount = $appointments->where('status', 'pending')->count();

        return [
            'total_balance' => (float) $totalBalance,
            'pending_count' => $pendingCount,
            'pending_cost' => $pendingCount * 50000,
            'can_afford_all' => $totalBalance >= ($pendingCount * 50000),
            'confirmed_this_month' => $appointments
                ->where('status', 'confirmed')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
            'completed_this_month' => $appointments
                ->where('status', 'completed')
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];
    }

    public function findForUser(User $user, int $id): Appointment
    {
        return Appointment::where('user_id', $user->id)
            ->with(['company:id,name,image,phone', 'user:id,name'])
            ->findOrFail($id);
    }

    public function findForCompany(User $user, int $id): Appointment
    {
        $companyIds = $user->companies()->pluck('id');

        return Appointment::whereIn('company_id', $companyIds)
            ->with(['company:id,name', 'user:id,name,email,mobile'])
            ->findOrFail($id);
    }

    public function cancelByUser(User $user, int $id): Appointment
    {
        $appointment = Appointment::where('user_id', $user->id)->findOrFail($id);

        if ($appointment->status !== 'pending') {
            throw new \DomainException('Chỉ có thể huỷ lịch hẹn đang chờ xác nhận.');
        }

        $appointment->status = 'canceled';
        $appointment->save();

        $this->notifyBothParties($user, $appointment, 'APPOINTMENT_CANCELED', 'appointment_cancelled');

        return $appointment;
    }

    public function confirmByCompany(User $user, int $id): Appointment
    {
        $appointment = $this->findForCompany($user, $id);

        if ($appointment->status !== 'pending') {
            throw new \DomainException('Chỉ có thể xác nhận lịch hẹn đang chờ.');
        }

        $company = Company::findOrFail($appointment->company_id);
        $wallet = $company->wallet;

        if (!$wallet) {
            throw new \DomainException('Vui lòng tạo ví cho công ty trước khi xác nhận lịch hẹn.');
        }

        $leadFee = 10000;

        $this->walletService->debitWithLock(
            $wallet->id,
            $leadFee,
            'customer_info_access',
            'Phí truy cập thông tin khách hàng - ' . $appointment->recipient_name,
            ['appointment_id' => $appointment->id],
        );

        $appointment->status = 'confirmed';
        $appointment->customer_info_unlocked = true;
        $appointment->save();

        $this->statisticsService->incrementHires($company);

        $customer = User::find($appointment->user_id);
        if ($customer) {
            $this->notifyBothParties($customer, $appointment, 'APPOINTMENT_CONFIRMED', 'appointment_confirmed');
        }

        return $appointment->load('company');
    }

    public function completeByCompany(User $user, int $id): Appointment
    {
        $appointment = $this->findForCompany($user, $id);

        if ($appointment->status !== 'confirmed') {
            throw new \DomainException('Chỉ có thể hoàn thành lịch hẹn đã xác nhận.');
        }

        $appointment->status = 'completed';
        $appointment->save();

        $customer = User::find($appointment->user_id);
        if ($customer) {
            $this->notifyBothParties($customer, $appointment, 'APPOINTMENT_COMPLETED', 'appointment_completed');
        }

        return $appointment;
    }

    public function cancelByCompany(User $user, int $id): Appointment
    {
        $appointment = $this->findForCompany($user, $id);

        if ($appointment->status !== 'pending') {
            throw new \DomainException('Chỉ có thể huỷ lịch hẹn đang chờ xác nhận.');
        }

        $appointment->status = 'canceled';
        $appointment->save();

        $customer = User::find($appointment->user_id);
        if ($customer) {
            $this->notifyBothParties($customer, $appointment, 'APPOINTMENT_CANCELED', 'appointment_cancelled');
        }

        return $appointment;
    }

    protected function notifyBothParties(User $customer, Appointment $appointment, string $template, string $inAppType): void
    {
        $appointment->loadMissing('company');

        $shortcodes = [
            'user_name' => $customer->fullname ?: $customer->name,
            'user_email' => $customer->email,
            'appointment_id' => $appointment->id,
            'appointment_date' => date('d/m/Y', strtotime($appointment->appointment_date)),
            'appointment_time' => $appointment->appointment_time,
            'company_name' => $appointment->company->name ?? 'Service Provider',
            'company_phone' => $appointment->company->mobile ?? $appointment->company->phone ?? '',
            'appointment_address' => $appointment->recipient_address,
            'site_url' => url('/'),
            'current_year' => date('Y'),
            'notes' => $appointment->notes ?? '',
        ];

        notify($customer, $template, $shortcodes);
        NotificationService::sendAppointmentNotification($customer, $appointment, $inAppType);

        $companyOwner = $appointment->company->user ?? null;
        if ($companyOwner) {
            $shortcodes['user_name'] = $companyOwner->fullname ?: $companyOwner->name;
            $shortcodes['user_email'] = $companyOwner->email;
            notify($companyOwner, $template, $shortcodes);
            NotificationService::sendAppointmentNotification($companyOwner, $appointment, $inAppType);
        }
    }
}
