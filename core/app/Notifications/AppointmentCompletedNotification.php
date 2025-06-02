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
            ->subject('Appointment Completed')
            ->line('Your appointment has been completed.')
            ->line('Appointment Date: ' . $this->appointment->appointment_date)
            ->line('Recipient Name: ' . $this->appointment->recipient_name)
            ->action(text: 'View Appointment', url: url(path: '/user/appointments/' . $this->appointment->id))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        ANotification::create([
            'user_id' => $this->appointment->user_id,
            'company_id' => $this->appointment->company_id,
            'appointment_id' => $this->appointment->id,
            'title' => 'Appointment Completed',
            'message' => 'Your appointment has been completed.',
            'type_id' => 'appointment_completed',
            'is_read' => false,
        ]);

        return [
            'appointment_id' => $this->appointment->id,
            'message' => 'Your appointment has been completed.',
        ];
    }
}