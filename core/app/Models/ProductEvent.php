<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Event log sản phẩm (append-only) — đo phễu Bắc Đẩu.
 * Ghi qua ProductEvent::log([...]) — LUÔN nuốt lỗi để analytics không bao giờ
 * làm hỏng luồng chính (vd chưa chạy migration trên server).
 */
class ProductEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event', 'surface', 'channel', 'company_id', 'actor_key', 'meta', 'created_at',
    ];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
        'company_id' => 'integer',
    ];

    /** Danh sách event hợp lệ (khớp bộ metric). */
    public const ALLOWED = [
        'app_open',
        'profile_created',
        'profile_published',
        'profile_shared',
        'profile_viewed',
        'contact_clicked',
        'booking_started',
        'booking_confirmed',
    ];

    /** Ghi 1 event. Bỏ qua nếu event không hợp lệ; nuốt mọi exception. */
    public static function log(array $a): void
    {
        try {
            $event = (string) ($a['event'] ?? '');
            if (! in_array($event, self::ALLOWED, true)) {
                return;
            }

            static::create([
                'event'      => $event,
                'surface'    => isset($a['surface']) ? substr((string) $a['surface'], 0, 16) : null,
                'channel'    => isset($a['channel']) ? substr((string) $a['channel'], 0, 16) : null,
                'company_id' => isset($a['company_id']) && $a['company_id'] ? (int) $a['company_id'] : null,
                'actor_key'  => isset($a['actor_key']) ? substr((string) $a['actor_key'], 0, 191) : null,
                'meta'       => $a['meta'] ?? null,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Analytics không được làm hỏng request chính.
            Log::warning('ProductEvent.log failed: ' . $e->getMessage());
        }
    }
}
