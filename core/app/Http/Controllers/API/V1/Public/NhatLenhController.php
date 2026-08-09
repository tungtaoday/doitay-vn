<?php

namespace App\Http\Controllers\API\V1\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — NHẬT LỆNH: việc trong ngày do bot blueprint đẩy lên.
 *
 * Chặn bằng METRICS_TOKEN chứ không phải phiên đăng nhập, vì bên ghi là cron
 * chạy `agent-system/quan-tri/telegram_push.py` trên máy chủ — không có session.
 * Cùng cơ chế với /public/metrics/*.
 *
 * Bên ĐỌC là trung tâm quản trị (đã có gate riêng), đọc qua
 * InsightController::viecHomNay nên không mở endpoint đọc công khai ở đây.
 */
class NhatLenhController extends Controller
{
    private function guard(Request $request): void
    {
        $configured = (string) config('metrics.token', '');
        $given = (string) ($request->query('token') ?? $request->bearerToken() ?? '');
        if ($configured === '' || ! hash_equals($configured, $given)) {
            abort(403, 'Metrics token không hợp lệ.');
        }
    }

    /** Ghi (hoặc ghi đè) nhật lệnh của một ngày. */
    public function store(Request $request): JsonResponse
    {
        $this->guard($request);

        $data = $request->validate([
            'ngay'     => 'nullable|date',
            'so_ngay'  => 'nullable|integer|min:0|max:999',
            'tieu_de'  => 'nullable|string|max:200',
            'noi_dung' => 'required|array',
        ]);

        $ngay = $data['ngay'] ?? now()->toDateString();

        $ban = [
            'so_ngay'    => $data['so_ngay'] ?? null,
            'tieu_de'    => $data['tieu_de'] ?? null,
            'noi_dung'   => json_encode($data['noi_dung'], JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ];

        // Chạy lại bot trong ngày thì ghi đè nội dung nhưng GIỮ created_at, để
        // còn biết bản đầu tiên được đẩy lúc mấy giờ.
        $daCo = DB::table('nhat_lenh')->where('ngay', $ngay)->exists();
        if ($daCo) {
            DB::table('nhat_lenh')->where('ngay', $ngay)->update($ban);
        } else {
            DB::table('nhat_lenh')->insert($ban + ['ngay' => $ngay, 'created_at' => now()]);
        }

        return response()->json(['data' => ['message' => 'Đã ghi nhật lệnh ngày ' . $ngay]]);
    }

    /**
     * Ghi một BÁO CÁO việc đã làm.
     *
     * Bên gọi là bot Telegram (cron/nền, không có phiên) nên vẫn chặn bằng
     * METRICS_TOKEN như các endpoint đo lường khác.
     */
    public function baoCao(Request $request): JsonResponse
    {
        $this->guard($request);

        $data = $request->validate([
            'ngay'      => 'nullable|date',
            'noi_dung'  => 'required|string|max:500',
            'so_viec'   => 'nullable|integer|min:1|max:99',
            'nguon'     => 'nullable|string|max:20',
        ]);

        $ngay = $data['ngay'] ?? now()->toDateString();

        // Báo cáo trùng (nhắn lại "xong 1" hai lần) thì bỏ qua, không nhân đôi.
        $trung = DB::table('bao_cao_viec')
            ->where('ngay', $ngay)
            ->where('noi_dung', $data['noi_dung'])
            ->exists();

        if (! $trung) {
            DB::table('bao_cao_viec')->insert([
                'ngay'       => $ngay,
                'noi_dung'   => $data['noi_dung'],
                'so_viec'    => $data['so_viec'] ?? null,
                'nguon'      => $data['nguon'] ?? 'telegram',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $tong = (int) DB::table('bao_cao_viec')->where('ngay', $ngay)->count();

        return response()->json(['data' => [
            'message' => $trung ? 'Việc này đã ghi rồi.' : 'Đã ghi.',
            'trong_ngay' => $tong,
        ]]);
    }
}
