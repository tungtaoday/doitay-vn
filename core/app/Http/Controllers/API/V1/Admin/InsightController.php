<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — Ba mặt còn thiếu của trung tâm quản trị:
 *
 *  1. khachHang()  — phía CẦU. Trung tâm điều hành cũ chỉ đếm khách mới theo
 *     tháng; ở đây mỗi khách được chẩn đoán đang kẹt ở đâu trên chuỗi
 *     đăng ký → gửi yêu cầu → thành lịch → xong việc → quay lại.
 *  2. diemCham()   — mọi hành động đo được (product_events) bóc theo ngày,
 *     theo kênh nguồn và theo bề mặt, kèm danh sách điểm chạm CHƯA gắn đo để
 *     bảng số không nói dối về độ phủ.
 *  3. viecHomNay() — nghiệp vụ hàng ngày: đúng những việc quá hạn/đến hạn hôm
 *     nay, kèm tên và số điện thoại để gọi được ngay, không phải tra tiếp.
 *
 * Gate dùng chung config('sale.manager_user_ids') như AdminDashboardController.
 */
class InsightController extends Controller
{
    use \App\Support\LocDuLieuMoi;

    private function assertManager(): void
    {
        $ids = config('sale.manager_user_ids', []);
        if (empty($ids) || ! in_array((int) auth()->id(), $ids, true)) {
            abort(403, 'Bạn không có quyền xem dữ liệu quản trị.');
        }
    }

    /** Loại tài khoản thợ (sở hữu company) và tài khoản seed khỏi tập "khách". */
    private function khachQuery()
    {
        $q = DB::table('users as u')
            ->leftJoin('companies as c', 'c.user_id', '=', 'u.id')
            ->whereNull('c.id')
            ->where(fn ($w) => $w->where('u.is_seeded', 0)->orWhereNull('u.is_seeded'));

        // Cờ is_seeded trên users KHÔNG đáng tin (xem LocDuLieuMoi) — suy thêm
        // từ dữ liệu, nếu không bảng khách hàng toàn khách do bộ seed sinh ra.
        return $this->boUserMoi($q, 'u.id');
    }

    // ─────────────────────────────────────────────────────────────────────
    // 1. HIỆU SUẤT KHÁCH HÀNG
    // ─────────────────────────────────────────────────────────────────────

    public function khachHang(Request $request): JsonResponse
    {
        $this->assertManager();
        $days = max(7, min(365, (int) $request->integer('days', 90)));
        $since = now()->subDays($days);

        $khach = $this->khachQuery()
            ->select('u.id', 'u.name', 'u.mobile', 'u.city', 'u.created_at')
            ->orderByDesc('u.created_at')
            ->get()
            ->keyBy('id');

        $ids = $khach->keys()->all();
        if (empty($ids)) {
            return response()->json(['data' => ['tong_quan' => $this->tongQuanRong(), 'khach' => []]]);
        }

        $yeuCau = DB::table('service_requests')
            ->selectRaw(
                'user_id, count(*) as n, '
                . "sum(status = 'open') as dang_mo, "
                . "sum(status = 'open' and created_at < ?) as treo, "
                . 'max(created_at) as last_at',
                [now()->subDay()]
            )
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $lich = DB::table('appointments')
            ->selectRaw(
                'user_id, count(*) as n, '
                . "sum(status = 'completed') as xong, "
                . "sum(status = 'confirmed') as xac_nhan, "
                . "sum(status = 'confirmed' and appointment_date < CURDATE()) as qua_ngay, "
                . 'max(created_at) as last_at'
            )
            ->whereIn('user_id', $ids)
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $rows = [];
        foreach ($khach as $id => $u) {
            $yc = $yeuCau->get($id);
            $lh = $lich->get($id);

            $soYeuCau = (int) ($yc->n ?? 0);
            $soLich   = (int) ($lh->n ?? 0);
            $soXong   = (int) ($lh->xong ?? 0);

            $lanCuoi = collect([$u->created_at, $yc->last_at ?? null, $lh->last_at ?? null])
                ->filter()->max();

            $rows[] = [
                'id'          => (int) $id,
                'name'        => $u->name,
                'mobile'      => $u->mobile,
                'city'        => $u->city,
                'dang_ky'     => $u->created_at,
                'so_yeu_cau'  => $soYeuCau,
                'yeu_cau_treo' => (int) ($yc->treo ?? 0),
                'so_lich'     => $soLich,
                'so_xong'     => $soXong,
                'lich_qua_ngay' => (int) ($lh->qua_ngay ?? 0),
                'lan_cuoi'    => $lanCuoi,
                'tinh_trang'  => $this->chanDoanKhach($u, $yc, $lh),
            ];
        }

        // Sắp: việc gấp lên trước, rồi tới hoạt động gần nhất.
        $uuTien = array_flip(['yeu_cau_treo', 'cho_chot_ket_qua', 'khach_moi_chua_dat', 'dang_dung', 'khach_quen', 'da_nguoi']);
        usort($rows, function ($a, $b) use ($uuTien) {
            $d = ($uuTien[$a['tinh_trang']] ?? 9) <=> ($uuTien[$b['tinh_trang']] ?? 9);
            return $d !== 0 ? $d : strcmp((string) $b['lan_cuoi'], (string) $a['lan_cuoi']);
        });

        $moiTrongKy = collect($rows)->filter(fn ($r) => $r['dang_ky'] >= $since)->count();
        $coYeuCau   = collect($rows)->filter(fn ($r) => $r['so_yeu_cau'] > 0)->count();
        $coLich     = collect($rows)->filter(fn ($r) => $r['so_lich'] > 0)->count();
        $coXong     = collect($rows)->filter(fn ($r) => $r['so_xong'] > 0)->count();
        $quayLai    = collect($rows)->filter(fn ($r) => $r['so_xong'] >= 2)->count();

        // Yêu cầu → lịch là chuyện của BẢNG YÊU CẦU, không suy ra từ số khách:
        // khách đặt thẳng thợ cũng thành lịch mà không đi qua yêu cầu nào, chia
        // chéo hai tập sẽ ra tỉ lệ vô nghĩa (>100%).
        $ycKy = DB::table('service_requests')->where('created_at', '>=', $since);
        $ycTong = (int) (clone $ycKy)->count();
        $ycGhep = (int) (clone $ycKy)->where('status', 'matched')->count();

        return response()->json(['data' => [
            'tong_quan' => [
                'tong_khach'      => count($rows),
                'khach_moi'       => $moiTrongKy,
                'co_yeu_cau'      => $coYeuCau,
                'co_lich'         => $coLich,
                'co_xong'         => $coXong,
                'quay_lai'        => $quayLai,
                // Mẫu số là TỔNG KHÁCH cho hai bậc đầu (đăng ký rồi có làm gì không),
                // rồi mới thu hẹp dần: có lịch → xong việc → quay lại.
                'ty_le_gui_yeu_cau' => count($rows) > 0 ? round($coYeuCau * 100 / count($rows), 1) : 0,
                'ty_le_co_lich'     => count($rows) > 0 ? round($coLich * 100 / count($rows), 1) : 0,
                'ty_le_xong_viec'   => $coLich > 0 ? round($coXong * 100 / $coLich, 1) : 0,
                'ty_le_quay_lai'    => $coXong > 0 ? round($quayLai * 100 / $coXong, 1) : 0,
                // Đo riêng trên bảng yêu cầu trong kỳ.
                'yeu_cau_trong_ky'  => $ycTong,
                'yeu_cau_ghep_duoc' => $ycGhep,
                'ty_le_ghep'        => $ycTong > 0 ? round($ycGhep * 100 / $ycTong, 1) : 0,
            ],
            'khach' => $rows,
        ]]);
    }

    private function tongQuanRong(): array
    {
        return [
            'tong_khach' => 0, 'khach_moi' => 0, 'co_yeu_cau' => 0, 'co_lich' => 0,
            'co_xong' => 0, 'quay_lai' => 0, 'ty_le_gui_yeu_cau' => 0,
            'ty_le_co_lich' => 0, 'ty_le_xong_viec' => 0, 'ty_le_quay_lai' => 0,
            'yeu_cau_trong_ky' => 0, 'yeu_cau_ghep_duoc' => 0, 'ty_le_ghep' => 0,
        ];
    }

    /**
     * Một khách chỉ mang MỘT nhãn — nhãn của việc phải làm với họ trước tiên.
     * Thứ tự kiểm tra chính là thứ tự ưu tiên xử lý.
     */
    private function chanDoanKhach(object $u, ?object $yc, ?object $lh): string
    {
        if ((int) ($lh->qua_ngay ?? 0) > 0)  return 'cho_chot_ket_qua';   // đã hẹn, qua ngày, chưa biết xong chưa
        if ((int) ($yc->treo ?? 0) > 0)      return 'yeu_cau_treo';       // gửi yêu cầu >24h chưa ai nhận
        if ((int) ($lh->xong ?? 0) >= 2)     return 'khach_quen';         // đã dùng ≥2 lần
        if ((int) ($yc->n ?? 0) === 0) {
            return $u->created_at >= now()->subDays(7) ? 'khach_moi_chua_dat' : 'da_nguoi';
        }

        $lanCuoi = collect([$u->created_at, $yc->last_at ?? null, $lh->last_at ?? null])->filter()->max();
        if ($lanCuoi && $lanCuoi < now()->subDays(60)) return 'da_nguoi';

        return 'dang_dung';
    }

    // ─────────────────────────────────────────────────────────────────────
    // 2. ĐIỂM CHẠM & HÀNH ĐỘNG
    // ─────────────────────────────────────────────────────────────────────

    /** Nhãn nguồn thống nhất Q12 — miniapp luôn tính là thẻ ThợTốt. */
    private const SRC_SQL = "COALESCE(CASE WHEN channel = 'miniapp' THEN 'thotot_app' "
        . "ELSE JSON_UNQUOTE(JSON_EXTRACT(meta, '$.src')) END, 'khac')";

    /** Cùng biểu thức nhưng có tiền tố bảng, dùng khi câu lệnh có join. */
    private const SRC_SQL_E = "COALESCE(CASE WHEN e.channel = 'miniapp' THEN 'thotot_app' "
        . "ELSE JSON_UNQUOTE(JSON_EXTRACT(e.meta, '$.src')) END, 'khac')";

    public function diemCham(Request $request): JsonResponse
    {
        $this->assertManager();
        $days = max(1, min(180, (int) $request->integer('days', 30)));
        $since = now()->subDays($days);

        // Từng hành động: bao nhiêu lượt, bao nhiêu người, lần cuối khi nào.
        $hanhDong = DB::table('product_events')
            ->selectRaw('event, surface, channel, count(*) as luot, count(distinct actor_key) as nguoi, max(created_at) as last_at')
            ->where('created_at', '>=', $since)
            ->groupBy('event', 'surface', 'channel')
            ->orderByDesc('luot')
            ->get();

        // Đường cong theo ngày để thấy xu hướng, không chỉ tổng.
        $theoNgayRaw = DB::table('product_events')
            ->selectRaw('DATE(created_at) as ngay, event, count(*) as n')
            ->where('created_at', '>=', $since)
            ->groupBy('ngay', 'event')
            ->get();
        $theoNgay = [];
        foreach ($theoNgayRaw as $r) {
            $theoNgay[$r->ngay] ??= ['ngay' => $r->ngay];
            $theoNgay[$r->ngay][$r->event] = (int) $r->n;
        }
        krsort($theoNgay);

        // Kênh nào mang khách tới và khách kênh đó có bấm gọi không.
        $kenhRaw = DB::table('product_events')
            ->selectRaw(self::SRC_SQL . ' as src, event, count(*) as n')
            ->where('created_at', '>=', $since)
            ->groupBy('src', 'event')
            ->get();
        $kenh = [];
        foreach ($kenhRaw as $r) {
            $s = $r->src ?: 'khac';
            $kenh[$s] ??= ['src' => $s, 'viewed' => 0, 'contacted' => 0, 'shared' => 0, 'booked' => 0, 'tong' => 0];
            $kenh[$s]['tong'] += (int) $r->n;
            $map = [
                'profile_viewed' => 'viewed', 'contact_clicked' => 'contacted',
                'profile_shared' => 'shared', 'booking_confirmed' => 'booked',
            ];
            if (isset($map[$r->event])) $kenh[$s][$map[$r->event]] = (int) $r->n;
        }
        foreach ($kenh as &$k) {
            $k['ty_le_goi'] = $k['viewed'] > 0 ? round($k['contacted'] * 100 / $k['viewed'], 1) : null;
        }
        unset($k);
        usort($kenh, fn ($a, $b) => $b['tong'] <=> $a['tong']);

        $dem = fn (string $e) => (int) DB::table('product_events')
            ->where('event', $e)->where('created_at', '>=', $since)->count();

        return response()->json(['data' => [
            'days'       => $days,
            'hanh_dong'  => $hanhDong,
            'theo_ngay'  => array_values($theoNgay),
            'kenh'       => array_values($kenh),
            'pheu' => [
                'profile_shared'    => $dem('profile_shared'),
                'profile_viewed'    => $dem('profile_viewed'),
                'contact_clicked'   => $dem('contact_clicked'),
                'booking_confirmed' => $dem('booking_confirmed'),
                'request_submitted' => $dem('request_submitted'),
            ],
            'gan_day' => DB::table('product_events as e')
                ->leftJoin('companies as c', 'c.id', '=', 'e.company_id')
                ->selectRaw('e.event, e.surface, e.channel, e.created_at, c.name as tho, ' . self::SRC_SQL_E . ' as src')
                ->where('e.created_at', '>=', $since)
                ->orderByDesc('e.id')
                ->limit(60)
                ->get(),
            // Trung thực về độ phủ: đây là những chỗ khách đi qua mà HIỆN CHƯA đo.
            'chua_do' => $this->diemChuaDo(),
        ]]);
    }

    /**
     * Danh sách cứng, cập nhật tay khi gắn thêm đo. Có nó thì người đọc bảng
     * biết con số 0 nghĩa là "chưa đo" hay "thật sự không ai làm".
     */
    private function diemChuaDo(): array
    {
        $daDo = DB::table('product_events')->distinct()->pluck('event')->all();
        $tatCa = [
            'home_viewed'       => 'Vào trang chủ doitay.vn',
            'search_performed'  => 'Tìm/lọc thợ ở trang danh sách',
            'request_started'   => 'Mở form gửi yêu cầu',
            'request_submitted' => 'Gửi yêu cầu thành công',
            'signup_completed'  => 'Đăng ký tài khoản xong',
            'booking_started'   => 'Bắt đầu đặt lịch',
            'booking_confirmed' => 'Lịch được xác nhận',
            'profile_viewed'    => 'Khách xem hồ sơ thợ',
            'contact_clicked'   => 'Khách bấm gọi/Zalo thợ',
            'profile_shared'    => 'Thợ gửi thẻ hồ sơ đi',
            'profile_published' => 'Thợ tạo xong hồ sơ',
        ];
        $out = [];
        foreach ($tatCa as $ev => $mo_ta) {
            if (! in_array($ev, $daDo, true)) $out[] = ['event' => $ev, 'mo_ta' => $mo_ta];
        }
        return $out;
    }

    // ─────────────────────────────────────────────────────────────────────
    // 3. NGHIỆP VỤ HÀNG NGÀY
    // ─────────────────────────────────────────────────────────────────────

    public function viecHomNay(): JsonResponse
    {
        $this->assertManager();

        // Yêu cầu khách gửi lên mà quá 24h chưa ghép được thợ — mất khách ở đây.
        // KHÔNG lọc suy đoán ở hàng đợi này: yêu cầu là do người thật tự gõ,
        // giấu nhầm một cái là mất một khách. Đo thực tế cũng cho thấy không có
        // yêu cầu nào do bộ seed sinh ra.
        $yeuCauTreo = DB::table('service_requests as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->select('r.id', 'r.title', 'r.city', 'r.district', 'r.contact_name', 'r.contact_phone', 'r.created_at')
            ->where('r.status', 'open')
            ->where('r.created_at', '<', now()->subDay())
            ->orderBy('r.created_at')
            ->limit(30)
            ->get();

        // Lịch hẹn diễn ra hôm nay — gọi nhắc cả hai đầu từ sáng.
        $lichHomNay = $this->lichKem()
            ->whereDate('a.appointment_date', now()->toDateString())
            ->whereIn('a.status', ['pending', 'confirmed'])
            ->orderBy('a.appointment_time')
            ->limit(30)->get();

        // Thợ chưa xác nhận sau 4h — quá lâu thì khách bỏ đi.
        $choTho = $this->lichKem()
            ->where('a.status', 'pending')
            ->where('a.created_at', '<', now()->subHours(4))
            ->orderBy('a.created_at')
            ->limit(30)->get();

        // Đã qua ngày hẹn mà chưa ai chốt xong hay không — số liệu treo từ đây.
        $choChot = $this->lichKem()
            ->where('a.status', 'confirmed')
            ->whereDate('a.appointment_date', '<', now()->toDateString())
            ->orderBy('a.appointment_date')
            ->limit(30)->get();

        // Khách đăng ký ≤3 ngày mà chưa gửi yêu cầu nào — gọi chào còn kịp.
        $khachMoi = $this->khachQuery()
            ->leftJoin('service_requests as r', 'r.user_id', '=', 'u.id')
            ->select('u.id', 'u.name', 'u.mobile', 'u.city', 'u.created_at')
            ->where('u.created_at', '>=', now()->subDays(3))
            ->whereNull('r.id')
            ->orderByDesc('u.created_at')
            ->limit(30)->get();

        // Nhat lenh cua bot blueprint - viec "phai lam theo ke hoach" dat canh
        // viec "dang ket theo du lieu", de mo mot trang la thay du ca hai loai.
        $nhatLenh = DB::table('nhat_lenh')->orderByDesc('ngay')->first();
        if ($nhatLenh) {
            $nhatLenh->noi_dung = json_decode((string) $nhatLenh->noi_dung, true) ?: [];
        }

        // Việc đã báo cáo hôm nay — đặt cạnh nhật lệnh để nhìn phát biết còn
        // đọng bao nhiêu, khỏi phải nhớ đã nhắn gì cho bot.
        $daLam = DB::table('bao_cao_viec')
            ->whereDate('ngay', now()->toDateString())
            ->orderBy('id')
            ->get(['so_viec', 'noi_dung', 'created_at']);

        return response()->json(['data' => [
            'nhat_lenh'     => $nhatLenh,
            'da_lam'        => $daLam,
            'yeu_cau_treo'  => $yeuCauTreo,
            'lich_hom_nay'  => $lichHomNay,
            'cho_tho_nhan'  => $choTho,
            'cho_chot'      => $choChot,
            'khach_moi'     => $khachMoi,
            'dem' => [
                'ho_so_cho_duyet'  => (int) DB::table('tho_submissions')->where('status', 'pending')->count(),
                'tho_cho_duyet'    => (int) DB::table('companies')->where('status', \App\Constants\Status::PENDING)->count(),
                'hoa_hong_chua_tra' => (float) DB::table('commissions')->whereNull('paid_at')->sum('so_tien'),
            ],
        ]]);
    }

    /** Lịch hẹn kèm tên/SĐT hai đầu để gọi được ngay từ bảng. */
    private function lichKem()
    {
        $q = DB::table('appointments as a')
            ->leftJoin('companies as c', 'c.id', '=', 'a.company_id');

        return $this->boThoMoi($q, 'c')
            ->select(
                'a.id', 'a.status', 'a.appointment_date', 'a.appointment_time', 'a.created_at',
                'a.recipient_name', 'a.recipient_phone', 'a.recipient_address',
                'c.name as tho', 'c.phone as tho_phone'
            );
    }
}
