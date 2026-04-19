<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Verify a Google ID token issued to the frontend via @react-oauth/google.
 *
 * We hit Google's tokeninfo endpoint instead of pulling google-api-client
 * because that's a lighter dependency and the trust boundary is the same:
 * Google signs, we verify audience + expiry.
 *
 * Config keys: services.google.client_id
 */
class GoogleOAuthVerifier
{
    /**
     * @return array{sub:string,email?:string,name?:string,picture?:string,aud:string,exp:int}
     *
     * @throws \RuntimeException khi token không hợp lệ / hết hạn / sai audience.
     */
    public function verify(string $idToken): array
    {
        $clientId = config('services.google.client_id');
        if (! $clientId) {
            throw new \RuntimeException('Google client_id chưa cấu hình');
        }

        $res = Http::timeout(5)->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $idToken,
        ]);

        if (! $res->successful()) {
            throw new \RuntimeException('tokeninfo request failed');
        }

        $payload = $res->json();
        if (! is_array($payload) || empty($payload['sub'])) {
            throw new \RuntimeException('invalid payload');
        }

        $aud = (string) ($payload['aud'] ?? '');
        $allowed = is_array($clientId) ? $clientId : array_map('trim', explode(',', (string) $clientId));
        if (! in_array($aud, $allowed, true)) {
            throw new \RuntimeException('audience mismatch');
        }

        if ((int) ($payload['exp'] ?? 0) < time()) {
            throw new \RuntimeException('token expired');
        }

        return $payload;
    }
}
