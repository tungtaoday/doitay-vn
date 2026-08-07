<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyWallet;
use App\Models\DepositRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * API V1 — Các mảng kéo từ admin Blade về trung tâm quản trị mới.
 *
 * Chỉ kéo những thứ THẬT SỰ được dùng (đọc log truy cập 15 ngày: lệnh nạp tiền,
 * ví thợ, người dùng). Phần còn lại của admin cũ là hàng thừa của script mua
 * sẵn — không port, xem ghi chú ở docs/architecture/quan-tri-gom-mot-cho.md.
 *
 * TIỀN: tuyệt đối không viết lại logic cộng ví. Duyệt/từ chối gọi thẳng
 * DepositRequest::approve()/reject() — nơi đã có transaction, ghi sổ
 * wallet_transactions và bắn thông báo cho thợ.
 */
class AdminOpsController extends Controller
{
    private function assertManager(): void
    {
        $ids = config('sale.manager_user_ids', []);
        if (empty($ids) || ! in_array((int) auth()->id(), $ids, true)) {
            abort(403, 'Bạn không có quyền thao tác quản trị.');
        }
    }

    // ── Lệnh nạp tiền ────────────────────────────────────────────────────

    public function deposits(Request $request): JsonResponse
    {
        $this->assertManager();
        $status = (string) $request->query('status', '');

        $q = DepositRequest::with(['user:id,name,mobile', 'wallet.company:id,name,phone'])
            ->latest();
        if ($status !== '') {
            $q->where('status', $status);
        }

        $rows = $q->limit(100)->get()->map(fn (DepositRequest $d) => [
            'id'            => $d->id,
            'deposit_code'  => $d->deposit_code,
            'amount'        => (float) $d->amount,
            'status'        => $d->status,
            'payment_method' => $d->payment_method,
            'bank_name'     => $d->bank_name,
            'bank_account_name' => $d->bank_account_name,
            'transaction_reference' => $d->transaction_reference,
            'payment_date'  => $d->payment_date,
            'payment_proof' => $d->payment_proof,
            'user_notes'    => $d->user_notes,
            'admin_notes'   => $d->admin_notes,
            'rejection_reason' => $d->rejection_reason,
            'created_at'    => $d->created_at,
            'processed_at'  => $d->processed_at,
            'tho'           => $d->wallet?->company?->name,
            'tho_phone'     => $d->wallet?->company?->phone,
            'nguoi_gui'     => $d->user?->name,
            'nguoi_gui_sdt' => $d->user?->mobile,
        ]);

        return response()->json(['data' => [
            'items' => $rows,
            'dem' => [
                'pending'    => (int) DepositRequest::where('status', 'pending')->count(),
                'processing' => (int) DepositRequest::where('status', 'processing')->count(),
                'completed'  => (int) DepositRequest::where('status', 'completed')->count(),
                'rejected'   => (int) DepositRequest::whereIn('status', ['rejected', 'cancelled'])->count(),
                'cho_duyet_tien' => (float) DepositRequest::where('status', 'pending')->sum('amount'),
            ],
        ]]);
    }

    public function processDeposit(Request $request, int $id): JsonResponse
    {
        $this->assertManager();
        $data = $request->validate([
            'action'           => 'required|in:approve,reject,processing',
            'admin_notes'      => 'nullable|string|max:1000',
            'rejection_reason' => 'required_if:action,reject|nullable|string|max:500',
        ]);

        $d = DepositRequest::findOrFail($id);

        // Chặn duyệt lại lệnh đã xử lý — cộng ví hai lần là mất tiền thật.
        if (! in_array($d->status, ['pending', 'processing'], true)) {
            abort(422, 'Lệnh này đã ở trạng thái "' . $d->status . '", không xử lý lại được.');
        }

        // processed_by là khoá ngoại sang bảng `admins`; tài khoản đang đăng nhập
        // ở đây là user thường nên để null và ghi người thao tác vào ghi chú.
        $ai = auth()->user()?->name ?? ('user#' . auth()->id());
        $ghiChu = trim(($data['admin_notes'] ?? '') . ' [' . $ai . ' xử lý qua trung tâm quản trị]');

        if ($data['action'] === 'processing') {
            $d->markAsProcessing(null);
            $message = 'Đã chuyển sang đang xử lý.';
        } elseif ($data['action'] === 'approve') {
            $d->approve(null, $ghiChu);
            $message = 'Đã duyệt và cộng tiền vào ví thợ.';
        } else {
            $d->reject($data['rejection_reason'], null, $ghiChu);
            $message = 'Đã từ chối lệnh nạp.';
        }

        return response()->json(['data' => ['message' => $message, 'status' => $d->fresh()?->status]]);
    }

    // ── Ví thợ ───────────────────────────────────────────────────────────

    public function wallets(): JsonResponse
    {
        $this->assertManager();

        $rows = CompanyWallet::with('company:id,name,phone')
            ->orderByDesc('balance')
            ->limit(200)
            ->get()
            ->map(fn (CompanyWallet $w) => [
                'id'       => $w->id,
                'tho'      => $w->company?->name,
                'tho_phone' => $w->company?->phone,
                'balance'  => (float) $w->balance,
                'is_active' => (bool) $w->is_active,
            ]);

        $giaoDich = DB::table('wallet_transactions as t')
            ->leftJoin('company_wallets as w', 'w.id', '=', 't.company_wallet_id')
            ->leftJoin('companies as c', 'c.id', '=', 'w.company_id')
            ->selectRaw('t.id, t.type, t.amount, t.transaction_type, t.description, t.created_at, c.name as tho')
            ->orderByDesc('t.id')
            ->limit(60)
            ->get();

        return response()->json(['data' => [
            'vi' => $rows,
            'tong_du' => (float) CompanyWallet::sum('balance'),
            'giao_dich' => $giaoDich,
        ]]);
    }

    // ── Người dùng ───────────────────────────────────────────────────────

    public function users(Request $request): JsonResponse
    {
        $this->assertManager();
        $q = trim((string) $request->query('q', ''));
        $loc = (string) $request->query('loc', '');

        // Dùng subquery thay vì join: một người có thể sở hữu nhiều công ty,
        // join sẽ nhân dòng lên và số đếm lệch với thực tế.
        $query = User::query()
            ->select(
                'users.id', 'users.name', 'users.username', 'users.email', 'users.mobile',
                'users.city', 'users.status', 'users.ev', 'users.sv', 'users.created_at',
                'users.is_seeded'
            )
            ->selectSub(
                DB::table('companies')->whereColumn('companies.user_id', 'users.id')
                    ->select('id')->orderBy('id')->limit(1),
                'company_id'
            )
            ->selectSub(
                DB::table('companies')->whereColumn('companies.user_id', 'users.id')
                    ->select('name')->orderBy('id')->limit(1),
                'company_name'
            );

        $laTho = fn ($w) => $w->from('companies')->whereColumn('companies.user_id', 'users.id');

        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.mobile', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%")
                    ->orWhere('users.username', 'like', "%{$q}%");
            });
        }
        if ($loc === 'tho')    $query->whereExists($laTho);
        if ($loc === 'khach')  $query->whereNotExists($laTho);
        if ($loc === 'khoa')   $query->where('users.status', 0);
        if ($loc === 'seed')   $query->where('users.is_seeded', 1);

        $rows = $query->orderByDesc('users.id')->limit(200)->get();

        return response()->json(['data' => [
            'items' => $rows,
            'dem' => [
                'tong'  => (int) DB::table('users')->count(),
                'tho'   => (int) DB::table('users')->join('companies', 'companies.user_id', '=', 'users.id')->distinct()->count('users.id'),
                'khoa'  => (int) DB::table('users')->where('status', 0)->count(),
                'seed'  => (int) DB::table('users')->where('is_seeded', 1)->count(),
            ],
        ]]);
    }

    public function setUserStatus(Request $request, int $id): JsonResponse
    {
        $this->assertManager();
        $data = $request->validate([
            'status'     => 'required|in:0,1',
            'ban_reason' => 'nullable|string|max:255',
        ]);

        $u = User::findOrFail($id);
        // Không cho tự khoá chính mình — khoá xong là mất luôn đường vào.
        if ((int) $u->id === (int) auth()->id()) {
            abort(422, 'Không thể khoá chính tài khoản đang dùng.');
        }

        $u->status = (int) $data['status'];
        if ((int) $data['status'] === 0) {
            $u->ban_reason = $data['ban_reason'] ?: 'Khoá bởi quản trị';
        } else {
            $u->ban_reason = null;
        }
        $u->save();

        return response()->json(['data' => [
            'message' => (int) $data['status'] === 1 ? 'Đã mở khoá tài khoản.' : 'Đã khoá tài khoản.',
        ]]);
    }

    // ── Lịch hẹn ─────────────────────────────────────────────────────────

    public function appointments(Request $request): JsonResponse
    {
        $this->assertManager();
        $status = (string) $request->query('status', '');
        $q = trim((string) $request->query('q', ''));

        $query = DB::table('appointments as a')
            ->leftJoin('companies as c', 'c.id', '=', 'a.company_id')
            ->select(
                // KHÔNG select confirmed_at: cột này chỉ có trên DB production,
                // DB dev cũ chưa có — mà bảng cũng không cần hiển thị nó.
                'a.id', 'a.status', 'a.appointment_date', 'a.appointment_time', 'a.created_at',
                'a.recipient_name', 'a.recipient_phone', 'a.recipient_address',
                'a.notes', 'a.company_id', 'c.name as tho', 'c.phone as tho_phone'
            );

        if ($status !== '') $query->where('a.status', $status);
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('a.recipient_name', 'like', "%{$q}%")
                    ->orWhere('a.recipient_phone', 'like', "%{$q}%")
                    ->orWhere('c.name', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderByDesc('a.id')->limit(200)->get();

        $dem = DB::table('appointments')
            ->selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        return response()->json(['data' => [
            'items' => $rows,
            'dem' => [
                'pending'   => (int) ($dem['pending'] ?? 0),
                'confirmed' => (int) ($dem['confirmed'] ?? 0),
                'completed' => (int) ($dem['completed'] ?? 0),
                'canceled'  => (int) ($dem['canceled'] ?? 0),
            ],
        ]]);
    }

    /**
     * Admin chốt hoặc huỷ một lịch hẹn.
     *
     * Cố ý KHÔNG cho admin "xác nhận" hộ thợ: xác nhận sẽ trừ phí lead trong ví
     * thợ và có thể kích thưởng CTV (AppointmentService::confirmByCompany) — đó
     * phải là hành động của chính thợ. Ở đây chỉ gọi lại đúng service với tài
     * khoản chủ sở hữu để mọi thông báo và ràng buộc trạng thái giữ nguyên.
     */
    public function appointmentAction(Request $request, int $id): JsonResponse
    {
        $this->assertManager();
        $data = $request->validate(['action' => 'required|in:hoan_thanh,huy']);

        $a = \App\Models\Appointment::findOrFail($id);
        $chuTho = DB::table('companies')->where('id', $a->company_id)->value('user_id');
        if (! $chuTho) {
            abort(422, 'Lịch này không gắn với thợ nào, không xử lý tự động được.');
        }
        $owner = User::findOrFail($chuTho);
        $svc = app(\App\Services\AppointmentService::class);

        try {
            if ($data['action'] === 'hoan_thanh') {
                $svc->completeByCompany($owner, $id);
                $msg = 'Đã chốt hoàn thành.';
            } else {
                $svc->cancelByCompany($owner, $id);
                $msg = 'Đã huỷ lịch.';
            }
        } catch (\DomainException $e) {
            abort(422, $e->getMessage());
        }

        return response()->json(['data' => ['message' => $msg]]);
    }

    // ── Đánh giá ─────────────────────────────────────────────────────────

    public function reviews(Request $request): JsonResponse
    {
        $this->assertManager();
        $max = $request->query('max');
        $q = trim((string) $request->query('q', ''));

        $query = DB::table('reviews as r')
            ->leftJoin('users as u', 'u.id', '=', 'r.user_id')
            ->leftJoin('companies as c', 'c.id', '=', 'r.company_id')
            ->select('r.id', 'r.rating', 'r.review', 'r.created_at', 'r.company_id',
                'u.name as khach', 'c.name as tho');

        if ($max !== null && $max !== '') $query->where('r.rating', '<=', (int) $max);
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('r.review', 'like', "%{$q}%")->orWhere('c.name', 'like', "%{$q}%");
            });
        }

        return response()->json(['data' => [
            'items' => $query->orderByDesc('r.id')->limit(200)->get(),
            'dem' => [
                'tong'  => (int) DB::table('reviews')->count(),
                'thap'  => (int) DB::table('reviews')->where('rating', '<=', 2)->count(),
                'trung_binh' => round((float) DB::table('reviews')->avg('rating'), 2),
            ],
        ]]);
    }

    public function deleteReview(int $id): JsonResponse
    {
        $this->assertManager();
        $r = DB::table('reviews')->where('id', $id)->first();
        if (! $r) abort(404, 'Không tìm thấy đánh giá.');
        DB::table('reviews')->where('id', $id)->delete();

        return response()->json(['data' => ['message' => 'Đã xoá đánh giá.']]);
    }

    // ── Danh mục nghề ────────────────────────────────────────────────────

    public function categories(): JsonResponse
    {
        $this->assertManager();

        // Kèm số thợ mỗi nghề: danh mục không có thợ nào thì bật lên chỉ làm
        // khách bấm vào rồi thấy trang rỗng.
        $rows = DB::table('categories as k')
            ->selectRaw('k.id, k.name, k.description, k.status, k.icon, '
                . '(select count(*) from companies c where c.category_id = k.id) as so_tho')
            ->orderBy('k.name')
            ->get();

        return response()->json(['data' => ['items' => $rows]]);
    }

    public function saveCategory(Request $request): JsonResponse
    {
        $this->assertManager();
        $data = $request->validate([
            'id'          => 'nullable|integer|exists:categories,id',
            'name'        => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:0,1',
        ]);

        $payload = [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'status'      => (int) $data['status'],
            'updated_at'  => now(),
        ];

        if (! empty($data['id'])) {
            DB::table('categories')->where('id', $data['id'])->update($payload);
            $msg = 'Đã cập nhật nghề.';
        } else {
            $payload['created_at'] = now();
            DB::table('categories')->insert($payload);
            $msg = 'Đã thêm nghề mới.';
        }

        return response()->json(['data' => ['message' => $msg]]);
    }

    // ── Cài đặt chung ────────────────────────────────────────────────────

    /**
     * CHỈ mở đúng những trường an toàn. general_settings còn chứa mail_config,
     * sms_config, socialite_credentials, system_info — bí mật hệ thống, không
     * bao giờ trả ra API này.
     */
    private const CAI_DAT_CHO_PHEP = [
        'site_name', 'cur_text', 'cur_sym', 'email_from',
        'zalo_phone', 'zalo_name', 'zalo_message', 'zalo_position', 'zalo_online',
        'registration', 'maintenance_mode',
    ];

    public function settings(): JsonResponse
    {
        $this->assertManager();
        $s = \App\Models\GeneralSetting::first();
        if (! $s) abort(404, 'Chưa có bản ghi cài đặt.');

        $out = [];
        foreach (self::CAI_DAT_CHO_PHEP as $k) {
            $out[$k] = $s->{$k} ?? null;
        }

        return response()->json(['data' => ['cai_dat' => $out]]);
    }

    public function saveSettings(Request $request): JsonResponse
    {
        $this->assertManager();
        $data = $request->validate([
            'site_name'        => 'nullable|string|max:120',
            'cur_text'         => 'nullable|string|max:10',
            'cur_sym'          => 'nullable|string|max:10',
            'email_from'       => 'nullable|email|max:120',
            'zalo_phone'       => 'nullable|string|max:20',
            'zalo_name'        => 'nullable|string|max:120',
            'zalo_message'     => 'nullable|string|max:300',
            'zalo_position'    => 'nullable|string|max:20',
            'zalo_online'      => 'nullable|in:0,1',
            'registration'     => 'nullable|in:0,1',
            'maintenance_mode' => 'nullable|in:0,1',
        ]);

        $s = \App\Models\GeneralSetting::first();
        if (! $s) abort(404, 'Chưa có bản ghi cài đặt.');

        foreach ($data as $k => $v) {
            if (in_array($k, self::CAI_DAT_CHO_PHEP, true)) {
                $s->{$k} = $v;
            }
        }
        $s->save();

        return response()->json(['data' => ['message' => 'Đã lưu cài đặt.']]);
    }
}
