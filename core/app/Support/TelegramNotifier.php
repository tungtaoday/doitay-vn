<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Gửi thông báo tức thời vào Telegram của người vận hành.
 *
 * Vì sao cần: đăng ký mới đã bắn email + chuông admin Blade, nhưng người vận
 * hành xem TELEGRAM chứ không mở hai chỗ đó. Đây là kênh báo real-time đúng nơi
 * họ nhìn — khác bot 7h sáng (gộp lô, chỉ thợ).
 *
 * Cấu hình (config/services.php → telegram, đọc từ .env, cần config:cache):
 *   TELEGRAM_BOT_TOKEN, TELEGRAM_ADMIN_CHAT
 *
 * Nguyên tắc: KHÔNG bao giờ ném lỗi ra ngoài — báo hỏng thì thôi, tuyệt đối
 * không được làm gãy luồng gọi (đăng ký, duyệt…).
 */
class TelegramNotifier
{
    public static function send(string $text): void
    {
        try {
            $token = (string) config('services.telegram.token', '');
            $chat = (string) config('services.telegram.admin_chat', '');
            if ($token === '' || $chat === '') {
                return;
            }

            Http::timeout(4)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'                  => $chat,
                'text'                     => mb_substr($text, 0, 4000),
                'parse_mode'               => 'HTML',
                'disable_web_page_preview' => true,
            ]);
        } catch (\Throwable $e) {
            Log::warning('TelegramNotifier lỗi: ' . $e->getMessage());
        }
    }
}
