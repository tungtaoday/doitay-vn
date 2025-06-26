<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use App\Models\ANotification;
use Illuminate\Support\Str;

class AppointmentConfirmedNotification extends Notification implements ShouldQueue
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
        $message = $notifiable->id === $this->appointment->user_id
            ? 'Lịch hẹn của bạn đã được xác nhận bởi công ty.'
            : 'Bạn đã xác nhận lịch hẹn cho khách hàng.';

        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Lịch hẹn đã được xác nhận')
            ->line($message)
            ->line('Ngày hẹn: ' . $this->appointment->appointment_date)
            ->line('Tên khách hàng: ' . $this->appointment->recipient_name)
            ->action(text: 'Xem lịch hẹn', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line('Cảm ơn bạn đã sử dụng ứng dụng của chúng tôi!');
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'title' => 'Lịch hẹn đã được xác nhận',
            'message' => $notifiable->id === $this->appointment->user_id
                ? 'Lịch hẹn của bạn đã được xác nhận bởi công ty.'
                : 'Bạn đã xác nhận lịch hẹn cho khách hàng.',
        ];

        $notificationId = Str::uuid()->toString();

        $notification = \Illuminate\Notifications\DatabaseNotification::create([
            'id' => $notificationId,
            'type' => get_class($this),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'data' => $data,
        ]);

        ANotification::create([
            'user_id' => $this->appointment->user_id,
            'company_id' => $this->appointment->company_id,
            'appointment_id' => $this->appointment->id,
            'title' => 'Lịch hẹn đã được xác nhận',
            'message' => $notifiable->id === $this->appointment->user_id
                ? 'Lịch hẹn của bạn đã được xác nhận bởi công ty.'
                : 'Bạn đã xác nhận lịch hẹn cho khách hàng.',
            'type_id' => 'appointment_confirmed',
            'is_read' => false,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => get_class($notifiable),
            'notification_id' => $notification->id,
        ]);

        return $data;
    }
}