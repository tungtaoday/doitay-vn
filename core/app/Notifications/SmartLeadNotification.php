<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lead;

class SmartLeadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $lead;
    protected $priorityScore;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lead $lead, $priorityScore = 0)
    {
        $this->lead = $lead;
        $this->priorityScore = $priorityScore;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $priorityText = $this->getPriorityText();
        $urgencyEmoji = $this->getUrgencyEmoji();
        
        return (new MailMessage)
            ->subject("🎯 Lead {$priorityText} - Cơ hội độc quyền 24h!")
            ->greeting("Xin chào {$notifiable->firstname}!")
            ->line("🔥 **Bạn được chọn** trong top 3 thợ có rating cao nhất để nhận lead này!")
            ->line("📋 **Tiêu đề:** {$this->lead->title}")
            ->line("💰 **Ngân sách:** {$this->lead->getBudgetRange()}")
            ->line("📍 **Địa điểm:** {$this->lead->location}")
            ->line("{$urgencyEmoji} **Mức độ:** " . ucfirst($this->lead->urgency))
            ->line("⭐ **Điểm ưu tiên của bạn:** {$this->priorityScore}/5.0")
            ->line("⏰ **Thời gian độc quyền:** 24 giờ (chỉ 3 thợ được xem)")
            ->action('🚀 Xem Lead Ngay', route('user.leads.show', $this->lead->id))
            ->line("💡 **Lưu ý:** Sau 24h, lead sẽ mở rộng cho thêm thợ khác. Hãy nhanh tay!")
            ->line("Chúc bạn thành công! 🎉")
            ->salutation("Đội ngũ " . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'type' => 'smart_lead',
            'title' => '🎯 Lead ưu tiên: ' . $this->lead->title,
            'message' => "Bạn được chọn trong top 3 thợ để nhận lead tại {$this->lead->location}",
            'budget' => $this->lead->getBudgetRange(),
            'urgency' => $this->lead->urgency,
            'priority_score' => $this->priorityScore,
            'exclusive_until' => now()->addHours(24)->toISOString(),
            'action_url' => route('user.leads.show', $this->lead->id)
        ];
    }

    private function getPriorityText()
    {
        if ($this->priorityScore >= 4.5) {
            return 'Ưu Tiên Cao';
        } elseif ($this->priorityScore >= 4.0) {
            return 'Ưu Tiên';
        } else {
            return 'Phù Hợp';
        }
    }

    private function getUrgencyEmoji()
    {
        return match($this->lead->urgency) {
            'high' => '🚨',
            'medium' => '⚡',
            'low' => '📅',
            default => '📋'
        };
    }
}
