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
        $isForCompanyOwner = $notifiable->id !== $this->appointment->user_id;
        
        if ($isForCompanyOwner) {
            // Email cho thợ
            return (new MailMessage)
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('🔔 Bạn có lịch hẹn mới!')
                ->greeting('Xin chào ' . $notifiable->name . '!')
                ->line('Bạn vừa nhận được một lịch hẹn mới từ khách hàng.')
                ->line('**Thông tin khách hàng:**')
                ->line('👤 Tên: ' . $this->appointment->recipient_name)
                ->line('📞 Số điện thoại: ' . $this->appointment->recipient_phone)
                ->line('📍 Địa chỉ: ' . $this->appointment->recipient_address)
                ->line('📅 Ngày hẹn: ' . \Carbon\Carbon::parse($this->appointment->appointment_date)->format('d/m/Y'))
                ->line('⏰ Giờ hẹn: ' . $this->appointment->appointment_time)
                ->line('📝 Ghi chú: ' . ($this->appointment->notes ?: 'Không có'))
                ->line('**⚠️ Lưu ý quan trọng:**')
                ->line('• Bạn cần xác nhận lịch hẹn trong vòng 2-4 giờ')
                ->line('• Phí truy cập thông tin khách hàng: 50,000 VNĐ')
                ->line('• Sau khi xác nhận, bạn có thể liên hệ trực tiếp với khách hàng')
                ->action('Xác nhận lịch hẹn ngay', url('/user/company/appointments'))
                ->line('Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!')
                ->salutation('Trân trọng,<br>' . config('app.name'));
        } else {
            // Email cho khách hàng
            return (new MailMessage)
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('✅ Đặt lịch thành công!')
                ->greeting('Xin chào ' . $notifiable->name . '!')
                ->line('Cảm ơn bạn đã đặt lịch hẹn. Chúng tôi đã ghi nhận yêu cầu của bạn.')
                ->line('**Thông tin lịch hẹn:**')
                ->line('🏢 Công ty: ' . $this->appointment->company->name)
                ->line('📅 Ngày hẹn: ' . \Carbon\Carbon::parse($this->appointment->appointment_date)->format('d/m/Y'))
                ->line('⏰ Giờ hẹn: ' . $this->appointment->appointment_time)
                ->line('📍 Địa chỉ: ' . $this->appointment->recipient_address)
                ->line('**Bước tiếp theo:**')
                ->line('• Thợ sẽ xem xét và xác nhận lịch hẹn trong vòng 2-4 giờ')
                ->line('• Bạn sẽ nhận được thông báo khi lịch hẹn được xác nhận')
                ->line('• Thợ sẽ liên hệ trực tiếp với bạn để thống nhất chi tiết')
                ->action('Xem lịch hẹn của tôi', url('/appointments'))
                ->line('Cảm ơn bạn đã tin tưởng sử dụng dịch vụ!')
                ->salutation('Trân trọng,<br>' . config('app.name'));
        }
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