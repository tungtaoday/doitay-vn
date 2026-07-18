<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — Trung tâm điều hành (/quan-tri): phễu vận hành + CHI PHÍ + hiệu suất CTV.
 *
 * Chi phí khớp FM model (docs/business/fm-model-doitay.xlsx):
 *  - TIỀN MẶT: hoa hồng CTV (bảng commissions, paid_at đánh dấu đã trả).
 *  - TIỀN ẢO:  welcome_bonus đã tặng + customer_info_access (phí lead trừ credit)
 *    — theo dõi để biết mức "trợ giá", không phải chi tiền mặt.
 */
class AdminDashboardController extends Controller
{
    /** Cùng gate với duyệt hồ sơ: user_id thuộc config sale.manager_user_ids. */
    private function assertManager(): void
    {
        $ids = config('sale.manager_user_ids', []);
        if (empty($ids) || ! in_array((int) auth()->id(), $ids, true)) {
            abort(403, 'Bạn không có quyền xem trung tâm điều hành.');
        }
    }

    /** Tổng quan theo tháng (mặc định 6 tháng gần nhất, gồm tháng hiện tại). */
    public function overview(Request $request): JsonResponse
    {
        $this->assertManager();
        $months = max(1, min(12, (int) $request->integer('months', 6)));
        $from = now()->startOfMonth()->subMonths($months - 1);

        $byMonth = fn ($rows) => collect($rows)->keyBy('thang');

        // Phễu CTV
        $nhap = $byMonth(DB::table('tho_submissions')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang, COUNT(*) as n")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());
        $duyet = $byMonth(DB::table('tho_submissions')
            ->selectRaw("DATE_FORMAT(updated_at,'%Y-%m') as thang, COUNT(*) as n")
            ->where('status', 'approved')
            ->where('updated_at', '>=', $from)->groupBy('thang')->get());

        // Hoa hồng (TIỀN MẶT)
        $hh = $byMonth(DB::table('commissions')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang, SUM(so_tien) as tong,
                         SUM(CASE WHEN paid_at IS NULL THEN so_tien ELSE 0 END) as chua_tra")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());

        // Tiền ảo: tặng ví + phí lead đã tiêu
        $ao = $byMonth(DB::table('wallet_transactions')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang,
                         SUM(CASE WHEN transaction_type='welcome_bonus' THEN amount ELSE 0 END) as tang_vi,
                         SUM(CASE WHEN transaction_type='customer_info_access' THEN amount ELSE 0 END) as phi_lead")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());

        // Lịch hẹn (nhịp giao dịch)
        $lich = $byMonth(DB::table('appointments')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang, COUNT(*) as tao,
                         SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) as confirmed,
                         SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());

        // ── PHỄU CẦU (khách) ──
        $khach = $byMonth(DB::table('users')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang, COUNT(*) as n")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());

        $yeuCau = $byMonth(DB::table('service_requests')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as thang, COUNT(*) as tao,
                         SUM(CASE WHEN selected_appointment_id IS NOT NULL THEN 1 ELSE 0 END) as thanh_lich")
            ->where('created_at', '>=', $from)->groupBy('thang')->get());

        // ── KÍCH HOẠT: công ty có lịch confirmed ĐẦU TIÊN trong tháng ──
        $kichHoat = $byMonth(DB::table('appointments')
            ->whereNotNull('confirmed_at')
            ->selectRaw("company_id, MIN(confirmed_at) as first_at")
            ->groupBy('company_id')
            ->havingRaw('MIN(confirmed_at) >= ?', [$from])
            ->get()
            ->groupBy(fn ($r) => substr((string) $r->first_at, 0, 7))
            ->map(fn ($g, $k) => (object) ['thang' => $k, 'n' => $g->count()])
            ->values());

        // Thợ CÓ VIỆC trong tháng (proxy "thợ sống")
        $coViec = $byMonth(DB::table('appointments')
            ->whereNotNull('confirmed_at')
            ->selectRaw("DATE_FORMAT(confirmed_at,'%Y-%m') as thang, COUNT(DISTINCT company_id) as n")
            ->where('confirmed_at', '>=', $from)->groupBy('thang')->get());

        $out = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $t = now()->startOfMonth()->subMonths($i)->format('Y-m');
            $out[] = [
                'thang'          => $t,
                'ho_so_nhap'     => (int) ($nhap[$t]->n ?? 0),
                'ho_so_duyet'    => (int) ($duyet[$t]->n ?? 0),
                'hoa_hong'       => (float) ($hh[$t]->tong ?? 0),
                'hoa_hong_chua_tra' => (float) ($hh[$t]->chua_tra ?? 0),
                'tang_vi_ao'     => (float) ($ao[$t]->tang_vi ?? 0),
                'phi_lead_ao'    => (float) ($ao[$t]->phi_lead ?? 0),
                'lich_tao'       => (int) ($lich[$t]->tao ?? 0),
                'lich_confirmed' => (int) ($lich[$t]->confirmed ?? 0),
                'lich_completed' => (int) ($lich[$t]->completed ?? 0),
                // Phễu cầu + kích hoạt
                'khach_moi'        => (int) ($khach[$t]->n ?? 0),
                'yeu_cau_tao'      => (int) ($yeuCau[$t]->tao ?? 0),
                'yeu_cau_thanh_lich' => (int) ($yeuCau[$t]->thanh_lich ?? 0),
                'tho_kich_hoat'    => (int) ($kichHoat[$t]->n ?? 0),
                'tho_co_viec'      => (int) ($coViec[$t]->n ?? 0),
            ];
        }

        return response()->json([
            'months' => $out,
            'tong' => [
                'hoa_hong_chua_tra_toan_bo' => (float) DB::table('commissions')->whereNull('paid_at')->sum('so_tien'),
                'tho_dang_hoat_dong' => (int) DB::table('companies')->where('status', \App\Constants\Status::APPROVED)->count(),
                'tong_vi_ao_dang_no' => (float) DB::table('company_wallets')->sum('balance'),
                'tho_kich_hoat_luy_ke' => (int) DB::table('appointments')->whereNotNull('confirmed_at')->distinct()->count('company_id'),
            ],
            // Cấu hình GIAI ĐOẠN hiện tại (đọc từ .env qua config) — hiển thị để chủ luôn
            // biết mình đang chạy tham số nào; đổi = sửa .env + config:cache.
            'cau_hinh' => [
                'lead_fee'             => (int) config('marketplace.lead_fee', 10000),
                'welcome_credit'       => (int) config('marketplace.welcome_credit', 200000),
                'show_contact_public'  => (bool) config('marketplace.show_contact', false),
                'commission_base'      => (int) config('sale.commission_base', 30000),
                'commission_share'     => (int) config('sale.commission_share_bonus', 10000),
                'commission_activation' => (int) config('sale.commission_activation', 20000),
            ],
        ]);
    }

    /** Hiệu suất từng CTV (phục vụ KPI + đối soát). */
    public function ctvPerformance(): JsonResponse
    {
        $this->assertManager();

        $rows = DB::table('tho_submissions as s')
            ->join('users as u', 'u.id', '=', 's.ctv_id')
            ->selectRaw("s.ctv_id, u.name,
                COUNT(*) as nhap,
                SUM(CASE WHEN s.status='approved' THEN 1 ELSE 0 END) as duyet,
                SUM(CASE WHEN s.status='rejected' THEN 1 ELSE 0 END) as tu_choi")
            ->groupBy('s.ctv_id', 'u.name')
            ->get();

        // Hoa hồng tách query riêng (tránh nhân bản do join)
        $hh = DB::table('commissions')
            ->selectRaw('ctv_id, SUM(so_tien) as tong, SUM(CASE WHEN paid_at IS NULL THEN so_tien ELSE 0 END) as chua_tra')
            ->groupBy('ctv_id')->get()->keyBy('ctv_id');

        $data = $rows->map(function ($r) use ($hh) {
            $h = $hh[$r->ctv_id] ?? null;
            return [
                'ctv_id'   => (int) $r->ctv_id,
                'name'     => $r->name,
                'nhap'     => (int) $r->nhap,
                'duyet'    => (int) $r->duyet,
                'tu_choi'  => (int) $r->tu_choi,
                'ti_le_duyet' => $r->nhap > 0 ? round($r->duyet / $r->nhap, 2) : 0,
                'hoa_hong_tong'     => (float) ($h->tong ?? 0),
                'hoa_hong_chua_tra' => (float) ($h->chua_tra ?? 0),
            ];
        })->sortByDesc('duyet')->values();

        return response()->json(['data' => $data]);
    }

    /** Đối soát: đánh dấu ĐÃ TRẢ toàn bộ hoa hồng đang nợ của 1 CTV. */
    public function markPaid(Request $request): JsonResponse
    {
        $this->assertManager();
        $ctvId = (int) $request->integer('ctv_id');
        abort_if($ctvId <= 0, 422, 'Thiếu ctv_id.');

        $due = DB::table('commissions')->where('ctv_id', $ctvId)->whereNull('paid_at');
        $total = (float) (clone $due)->sum('so_tien');
        $count = (clone $due)->count();
        $due->update(['paid_at' => now(), 'updated_at' => now()]);

        return response()->json(['data' => ['ctv_id' => $ctvId, 'so_khoan' => $count, 'tong_tien' => $total]]);
    }
}
