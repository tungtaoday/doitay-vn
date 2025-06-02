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
            ? 'Your appointment has been confirmed by the company.'
            : 'You have confirmed an appointment for a customer.';

        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Appointment Confirmed')
            ->line($message)
            ->line('Appointment Date: ' . $this->appointment->appointment_date)
            ->line('Recipient Name: ' . $this->appointment->recipient_name)
            ->action(text: 'View Appointment', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable)
    {
        $data = [
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Confirmed',
            'message' => $notifiable->id === $this->appointment->user_id
                ? 'Your appointment has been confirmed by the company.'
                : 'You have confirmed an appointment for a customer.',
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
            'title' => 'Appointment Confirmed',
            'message' => $notifiable->id === $this->appointment->user_id
                ? 'Your appointment has been confirmed by the company.'
                : 'You have confirmed an appointment for a customer.',
            'type_id' => 'appointment_confirmed',
            'is_read' => false,
            'notifiable_id' => $notifiable->getKey(),
            'notifiable_type' => get_class($notifiable),
            'notification_id' => $notification->id,
        ]);

        return $data;
    }
}