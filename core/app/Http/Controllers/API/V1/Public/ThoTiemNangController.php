<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — THỢ TIỀM NĂNG từ seeding (xem migration create_tho_tiem_nang).
 *
 * Bên ghi là bot seeding trên máy người vận hành (không có phiên đăng nhập)
 * nên chặn bằng METRICS_TOKEN, cùng cơ chế với /public/nhat-lenh.
 * Bên đọc là trung tâm quản trị (InsightController::viecHomNay).
 */
class ThoTiemNangController extends Controller
{
    private function guard(Request $request): void
    {
        $configured = (string) config('metrics.token', '');
        $given = (string) ($request->query('token') ?? $request->bearerToken() ?? '');
        if ($configured === '' || ! hash_equals($configured, $given)) {
            abort(403, 'Metrics token không hợp lệ.');
        }
    }

    /** SĐT về dạng 0xxxxxxxxx để so trùng — "+84 912.345.678" = "0912345678". */
    private function chuanSdt(string $sdt): string
    {
        $so = preg_replace('/\D+/', '', $sdt);
        if (str_starts_with($so, '84') && strlen($so) === 11) {
            $so = '0' . substr($so, 2);
        }
        return $so;
    }

    public function store(Request $request): JsonResponse
    {
        $this->guard($request);

        $data = $request->validate([
            'ten'      => 'nullable|string|max:120',
            'sdt'      => 'required|string|max:30',
            'link_bai' => 'nullable|string|max:500',
            'trich'    => 'nullable|string|max:500',
            'loai'     => 'nullable|string|max:30',
        ]);

        $sdt = $this->chuanSdt($data['sdt']);
        if (strlen($sdt) < 9 || strlen($sdt) > 11) {
            abort(422, 'SĐT không hợp lệ sau chuẩn hoá: ' . $sdt);
        }

        // Trùng lead cũ → trả về bản cũ, không nhân đôi (dán lại link là chuyện thường).
        $cu = DB::table('tho_tiem_nang')->where('sdt', $sdt)->first();
        if ($cu) {
            return response()->json(['data' => [
                'message'    => 'Số này đã có trong danh sách từ ' . substr((string) $cu->created_at, 0, 10) . '.',
                'trang_thai' => $cu->trang_thai,
                'da_co'      => true,
            ]]);
        }

        // Số đã có hồ sơ trên chợ? So 9 số cuối để khớp mọi cách lưu (0/84/chấm cách).
        $duoi = substr($sdt, -9);
        $daCoHoSo = DB::table('companies')
            ->whereRaw('RIGHT(REGEXP_REPLACE(COALESCE(phone, ""), "[^0-9]", ""), 9) = ?', [$duoi])
            ->exists();

        DB::table('tho_tiem_nang')->insert([
            'ten'        => $data['ten'] ?? null,
            'sdt'        => $sdt,
            'link_bai'   => $data['link_bai'] ?? null,
            'trich'      => $data['trich'] ?? null,
            'loai'       => $data['loai'] ?? null,
            'trang_thai' => $daCoHoSo ? 'da_co_ho_so' : 'moi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['data' => [
            'message'    => $daCoHoSo
                ? 'Số này ĐÃ CÓ HỒ SƠ trên chợ — đừng chào lại.'
                : 'Đã lưu vào danh sách thợ tiềm năng.',
            'trang_thai' => $daCoHoSo ? 'da_co_ho_so' : 'moi',
            'da_co'      => false,
        ]]);
    }
}
