<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lead;

class NewLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lead;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Lead mới phù hợp với dịch vụ của bạn')
            ->line('Có một lead mới phù hợp với danh mục dịch vụ của công ty bạn.')
            ->line('Tiêu đề: ' . $this->lead->title)
            ->line('Ngân sách: ' . $this->lead->getBudgetRange())
            ->line('Địa điểm: ' . $this->lead->location)
            ->line('Mức độ khẩn cấp: ' . ucfirst($this->lead->urgency))
            ->action('Xem chi tiết Lead', route('user.leads.show', $this->lead->id))
            ->line('Hãy nhanh tay để không bỏ lỡ cơ hội!');
    }

    public function toArray($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'title' => 'Lead mới: ' . $this->lead->title,
            'message' => 'Có lead mới phù hợp với dịch vụ của bạn tại ' . $this->lead->location,
            'budget' => $this->lead->getBudgetRange(),
            'urgency' => $this->lead->urgency
        ];
    }
} 