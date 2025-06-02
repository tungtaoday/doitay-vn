<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use App\Models\ANotification;
use Illuminate\Support\Str;

class NewAppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Gửi qua email và lưu vào database
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('New Appointment Created')
            ->line('A new appointment has been created.')
            ->line('Appointment Date: ' . $this->appointment->appointment_date)
            ->line('Recipient Name: ' . $this->appointment->recipient_name)
            ->action(text: 'View Appointment', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        // Dữ liệu cơ bản cho bảng notifications
        $data = [
            'appointment_id' => $this->appointment->id,
            'title' => 'New Appointment Created',
            'message' => 'A new appointment has been created.',
        ];

        // Sinh UUID cho cột id trong notifications
        $notificationId = Str::uuid()->toString();

        // Lưu vào bảng notifications trực tiếp
        $notification = \Illuminate\Notifications\DatabaseNotification::create([
            'id' => $notificationId,
            'type' => get_class($this),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'data' => $data,
        ]);

        // Lưu chi tiết vào bảng a_notifications
        ANotification::create([
            'user_id' => $this->appointment->user_id,
            'company_id' => $this->appointment->company_id,
            'appointment_id' => $this->appointment->id,
            'title' => 'New Appointment Created',
            'message' => 'A new appointment has been created.',
            'type_id' => 'new_appointment',
            'is_read' => false,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => get_class($notifiable),
            'notification_id' => $notification->id,
        ]);

        return $data; // Trả về mảng để DatabaseChannel hoàn tất
    }
}