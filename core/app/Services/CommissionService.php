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
}
