<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use App\Models\ANotification;
use Illuminate\Support\Str;

class AppointmentCanceledNotification extends Notification implements ShouldQueue
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
        // return ['mail'];

    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME')) // Lấy email từ .env
            ->subject('Appointment Canceled')
            ->line('Your appointment has been canceled.')
            ->line('Appointment Date: ' . $this->appointment->appointment_date)
            ->line('Recipient Name: ' . $this->appointment->recipient_name)
            ->action(text: 'View Appointment', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line(line: 'Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Canceled',
            'message' => 'Your appointment has been canceled.',
        ];

        // Để DatabaseChannel tự lưu vào notifications và lấy bản ghi vừa tạo
        $notification = \Illuminate\Notifications\DatabaseNotification::create([
            'id' => Str::uuid()->toString(),
            'type' => get_class($this),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'data' => $data,
        ]);

        // Lưu vào bảng a_notifications
        ANotification::create([
            'user_id' => $this->appointment->user_id,
            'company_id' => $this->appointment->company_id,
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Canceled',
            'message' => 'Your appointment has been canceled.',
            'type_id' => 'appointment_canceled',
            'is_read' => false,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => get_class($notifiable),
            'notification_id' => $notification->id,
        ]);

        return $data; // Trả về mảng để DatabaseChannel hoàn tất
    }

}