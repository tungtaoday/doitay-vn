<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Hoa hồng cho CTV, gắn 1-1 với một ThoSubmission hợp lệ. Ref: DUC-COMMISSION-RECORD.
 */
class Commission extends Model
{
    protected $fillable = [
        'ctv_id',
        'submission_id',
        'so_tien',
        'loai',
        'tuan',
    ];

    protected $casts = [
        'so_tien' => 'integer',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThoSubmission::class, 'submission_id');
    }

    public function ctv(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ctv_id');
    }
}
