<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use App\Models\ANotification;

class AppointmentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lịch hẹn đã hoàn thành')
            ->line('Lịch hẹn của bạn đã được hoàn thành.')
            ->line('Ngày hẹn: ' . $this->appointment->appointment_date)
            ->line('Tên khách hàng: ' . $this->appointment->recipient_name)
            ->action(text: 'Xem lịch hẹn', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã sử dụng ứng dụng của chúng tôi!');
    }

    public function toArray($notifiable)
    {
        ANotification::create([
            'user_id' => $this->appointment->user_id,
            'company_id' => $this->appointment->company_id,
            'appointment_id' => $this->appointment->id,
            'title' => 'Lịch hẹn đã hoàn thành',
            'message' => 'Lịch hẹn của bạn đã được hoàn thành.',
            'type_id' => 'appointment_completed',
            'is_read' => false,
        ]);

        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Lịch hẹn của bạn đã được hoàn thành.',
        ];
    }
}