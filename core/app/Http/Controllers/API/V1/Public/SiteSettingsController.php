<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Public site-settings endpoint — gates EVERY frontend customization
 * available to admin (brand, colors, copy, contact, banner, ...) so the
 * Next.js client never hardcodes them.
 *
 * Cached for 5 minutes; admin can clear cache if needed via /admin tools.
 */
class SiteSettingsController extends Controller
{
    public function show(): JsonResponse
    {
        $payload = Cache::remember('api.v1.public.site-settings', 300, function () {
            $gs = GeneralSetting::first();
            $shortcodes = $this->toArray($gs?->global_shortcodes);

            $banner = Frontend::where('data_keys', 'banner.content')->first();
            $bannerData = $this->toArray($banner?->data_values);

            $socialiteCreds = $this->toArray($gs?->socialite_credentials);

            return [
                'data' => [
                    'site_name'         => $gs?->site_name ?? 'doitay.vn',
                    'site_logo'         => siteLogo(),
                    'site_logo_dark'    => siteLogo('dark'),
                    'site_favicon'      => siteFavicon(),
                    'colors' => [
                        'primary'   => $this->normalizeColor($gs?->base_color),
                        'secondary' => $this->normalizeColor($gs?->secondary_color),
                    ],
                    'currency' => [
                        'text'   => $gs?->cur_text ?? 'VND',
                        'symbol' => $gs?->cur_sym  ?? '₫',
                    ],
                    'features' => [
                        'registration'      => (bool) ($gs?->registration ?? true),
                        'maintenance_mode'  => (bool) ($gs?->maintenance_mode ?? false),
                        'email_verify'      => (bool) ($gs?->ev ?? false),
                        'sms_verify'        => (bool) ($gs?->sv ?? false),
                        'kyc'               => (bool) ($gs?->kv ?? false),
                        'multi_language'    => (bool) ($gs?->multi_language ?? false),
                        'force_ssl'         => (bool) ($gs?->force_ssl ?? false),
                    ],
                    'contact' => [
                        'support_email' => $shortcodes['support_email'] ?? null,
                        'phone'         => $gs?->zalo_phone ?? null,
                    ],
                    // Ưu đãi giai đoạn (config .env) — FE hiển thị, không hardcode số.
                    'incentives' => [
                        'welcome_credit' => (int) config('marketplace.welcome_credit', 200000),
                    ],
                    'social' => [
                        'google_enabled'   => $this->socialEnabled($socialiteCreds, 'google'),
                        'facebook_enabled' => $this->socialEnabled($socialiteCreds, 'facebook'),
                    ],
                    'zalo' => [
                        'enabled'      => (bool) ($gs?->zalo_online ?? false),
                        'phone'        => $gs?->zalo_phone ?? null,
                        'name'         => $gs?->zalo_name ?? null,
                        'avatar'       => $gs?->zalo_avatar ?? null,
                        'message'      => $gs?->zalo_message ?? null,
                        'position'     => $gs?->zalo_position ?? 'bottom-right',
                        'button_size'  => $gs?->zalo_button_size ?? 'medium',
                        'show_mobile'  => (bool) ($gs?->zalo_show_mobile ?? true),
                    ],
                    'banner' => [
                        'heading'    => $bannerData['heading']    ?? null,
                        'subheading' => $bannerData['subheading'] ?? null,
                        'image'      => ! empty($bannerData['image'])
                            ? frontendImage('banner', $bannerData['image'], '1920x840')
                            : null,
                    ],
                ],
            ];
        });

        return response()->json($payload);
    }

    private function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_object($value)) {
            return json_decode(json_encode($value), true) ?: [];
        }
        if (is_string($value) && $value !== '') {
            return (array) (json_decode($value, true) ?? []);
        }
        return [];
    }

    private function normalizeColor(?string $hex): ?string
    {
        if (! $hex) {
            return null;
        }
        return str_starts_with($hex, '#') ? $hex : '#' . ltrim($hex, '#');
    }

    private function socialEnabled(array $creds, string $provider): bool
    {
        $entry = $creds[$provider] ?? null;
        if (! is_array($entry)) {
            return false;
        }
        return (bool) ($entry['status'] ?? $entry['enabled'] ?? false);
    }
}
