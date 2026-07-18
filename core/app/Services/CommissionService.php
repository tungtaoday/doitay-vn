<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\ThoSubmission;

/**
 * Ghi hoa hồng cho CTV khi submission hợp lệ. Ref: DUC-COMMISSION-RECORD.
 */
class CommissionService
{
    /**
     * Ghi commission `base` cho submission (idempotent — BR-COMM-1).
     * Trả về Commission mới, hoặc null nếu đã tồn tại.
     */
    public function recordFor(ThoSubmission $submission): ?Commission
    {
        $exists = Commission::query()
            ->where('submission_id', $submission->id)
            ->where('loai', 'base')
            ->exists();

        if ($exists) {
            return null;
        }

        return Commission::create([
            'ctv_id'        => $submission->ctv_id,
            'submission_id' => $submission->id,
            'so_tien'       => (int) config('sale.commission_base', 30000),
            'loai'          => 'base',
            'tuan'          => now()->format('o-\WW'),
        ]);
    }

    /**
     * Thưởng KÍCH HOẠT: thợ do CTV tuyển vừa xác nhận lịch hẹn ĐẦU TIÊN.
     * - Idempotent (unique submission_id + loai).
     * - Mức từ config sale.commission_activation; 0 = TẮT (config theo giai đoạn).
     * - Chỉ áp dụng cho thợ có nguồn CTV (tho_submissions.company_id).
     */
    public function grantActivationBonus(int $companyId): ?Commission
    {
        $amount = (int) config('sale.commission_activation', 20000);
        if ($amount <= 0) {
            return null; // tắt theo giai đoạn
        }

        $submission = ThoSubmission::where('company_id', $companyId)
            ->where('status', 'approved')
            ->first();
        if (! $submission) {
            return null; // thợ tự đăng ký — không có CTV
        }

        $exists = Commission::query()
            ->where('submission_id', $submission->id)
            ->where('loai', 'activation')
            ->exists();
        if ($exists) {
            return null;
        }

        $commission = Commission::create([
            'ctv_id'        => $submission->ctv_id,
            'submission_id' => $submission->id,
            'so_tien'       => $amount,
            'loai'          => 'activation',
            'tuan'          => now()->format('o-\WW'),
        ]);

        try {
            $ctv = \App\Models\User::find($submission->ctv_id);
            if ($ctv) {
                NotificationService::sendSystemNotification(
                    $ctv,
                    'Thợ của bạn đã kích hoạt 🎉',
                    'Thợ "' . $submission->ten_tho . '" vừa nhận lịch hẹn đầu tiên. Bạn được cộng '
                        . number_format($amount, 0, ',', '.') . 'đ thưởng kích hoạt.',
                    'activation_bonus',
                    url('/sale'),
                );
            }
        } catch (\Throwable) {
            // notify lỗi không làm hỏng ghi hoa hồng
        }

        return $commission;
    }
}
