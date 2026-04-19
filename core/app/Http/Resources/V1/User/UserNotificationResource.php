<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape for /api/v1/user/notifications — mirrors UserNotification model
 * but drops `user_id`, `user_type`, and unsafe internal fields.
 *
 * `action_url` is rewritten to a frontend-safe relative path where possible
 * (legacy Blade routes like /user/appointments/123 are mapped to
 * /vi/lich-hen/123 so the Next.js client can deep-link correctly).
 */
class UserNotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'type'         => $this->type,
            'title'        => $this->title,
            'message'      => $this->message,
            'icon'         => $this->icon,
            'color'        => $this->color,
            'action_url'   => $this->rewriteActionUrl($this->action_url),
            'data'         => $this->data,
            'is_read'      => (bool) $this->is_read,
            'is_important' => (bool) $this->is_important,
            'priority'     => $this->priority,
            'read_at'      => optional($this->read_at)->toIso8601String(),
            'created_at'   => optional($this->created_at)->toIso8601String(),
        ];
    }

    /**
     * Map legacy Blade URLs to Next.js routes so notification click-throughs
     * land on the correct frontend page. Falls back to null if the legacy
     * URL shape is unknown — the frontend then treats it as non-clickable.
     */
    private function rewriteActionUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: $url;

        // Customer-side appointment detail
        if (preg_match('#/user/appointments/(\d+)#', $path, $m)) {
            return '/vi/lich-hen/' . $m[1];
        }
        // Contractor-side appointment detail
        if (preg_match('#/company/appointments/(\d+)#', $path, $m)) {
            return '/vi/tho/lich-hen/' . $m[1];
        }
        // Deposits
        if (preg_match('#/user/deposits/(\d+)#', $path, $m)) {
            return '/vi/wallet/deposits/' . $m[1];
        }
        // Company profile
        if (preg_match('#/companies/(\d+)#', $path, $m)) {
            return '/cong-ty/' . $m[1];
        }

        return $path;
    }
}
