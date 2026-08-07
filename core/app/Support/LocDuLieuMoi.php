<?php

namespace App\Support;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * LỌC DỮ LIỆU MỒI khỏi các màn vận hành.
 *
 * Vì sao cần: trên production `companies.is_seeded` được đánh đúng (108/109 thợ
 * là mồi) nhưng `users.is_seeded` thì KHÔNG — cả 174 tài khoản đều đang là 0,
 * kể cả 108 chủ thợ mồi và 166 khách do bộ seed sinh ra. Hệ quả: màn "Việc hôm
 * nay" hiện 147 việc thì cả 147 là giả, và bảng khách hàng đọc ra tỉ lệ quay lại
 * đẹp một cách vô lý.
 *
 * Ở đây suy ra "mồi" từ dữ liệu thay vì tin vào cờ, để KHÔNG phải sửa dữ liệu
 * production:
 *   - thợ mồi  = companies.is_seeded = 1
 *   - user mồi = sở hữu một thợ mồi, HOẶC có lịch hẹn mà toàn bộ lịch đều với
 *                thợ mồi (khách thật đặt thợ thật sẽ không lọt vào)
 *
 * Nếu về sau xoá hẳn dữ liệu mồi hoặc đánh lại cờ cho đúng thì các bộ lọc này
 * vẫn chạy đúng, chỉ là không còn gì để loại.
 */
trait LocDuLieuMoi
{
    /** Loại thợ mồi. $bang là alias của bảng companies trong truy vấn. */
    protected function boThoMoi(Builder $q, string $bang = 'c'): Builder
    {
        return $q->where(function ($w) use ($bang) {
            $w->whereNull("{$bang}.is_seeded")->orWhere("{$bang}.is_seeded", 0);
        });
    }

    /**
     * Loại tài khoản mồi. $cot là cột chứa user_id trong truy vấn hiện tại.
     *
     * Giữ lại user khi: chưa từng đặt thợ mồi, HOẶC đã từng đặt ít nhất một thợ
     * thật. Chỉ rơi ra đúng nhóm "chỉ toàn đặt thợ mồi".
     */
    protected function boUserMoi(Builder $q, string $cot = 'users.id'): Builder
    {
        return $q
            ->whereNotExists(fn ($s) => $s->from('companies as ctho')
                ->whereColumn('ctho.user_id', $cot)
                ->where('ctho.is_seeded', 1))
            ->where(function ($w) use ($cot) {
                $w->whereNotExists(fn ($s) => $s->from('appointments as amoi')
                        ->join('companies as cmoi', 'cmoi.id', '=', 'amoi.company_id')
                        ->whereColumn('amoi.user_id', $cot)
                        ->where('cmoi.is_seeded', 1))
                    ->orWhereExists(fn ($s) => $s->from('appointments as athat')
                        ->join('companies as cthat', 'cthat.id', '=', 'athat.company_id')
                        ->whereColumn('athat.user_id', $cot)
                        ->where(fn ($x) => $x->whereNull('cthat.is_seeded')->orWhere('cthat.is_seeded', 0)))
                    ->orWhereExists(fn ($s) => $s->from('service_requests as ryc')
                        ->whereColumn('ryc.user_id', $cot));
            });
    }

    /**
     * ID của những tài khoản được coi là THẬT (không phải mồi).
     *
     * Dùng cho bộ lọc ở màn Người dùng. Danh sách nhỏ (vài trăm) nên trả mảng
     * là đủ; nếu về sau tài khoản lên hàng chục nghìn thì đổi sang subquery.
     */
    protected function idUserThat(): array
    {
        return $this->boUserMoi(DB::table('users'), 'users.id')->pluck('users.id')->all();
    }

    /** Đếm nhanh: hệ đang còn bao nhiêu dữ liệu mồi (để hiện cảnh báo). */
    protected function demDuLieuMoi(): array
    {
        return [
            'tho_moi'  => (int) DB::table('companies')->where('is_seeded', 1)->count(),
            'lich_moi' => (int) DB::table('appointments as a')
                ->join('companies as c', 'c.id', '=', 'a.company_id')
                ->where('c.is_seeded', 1)->count(),
        ];
    }
}
