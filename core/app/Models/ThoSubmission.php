<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Hồ sơ thợ do CTV nhập (Sale onboarding). Vòng đời: pending → approved | rejected.
 * Ref: DUC-SUBMISSION-CREATE.
 */
class ThoSubmission extends Model
{
    protected $fillable = [
        'ctv_id',
        'ten_tho',
        'nghe',
        'khu_vuc',
        'sdt_tho',
        'sdt_normalized',
        'nam_kn',
        'bang_gia',
        'status',
        'ly_do_tu_choi',
        'company_id',
    ];

    protected $casts = [
        'bang_gia' => 'array',
        'nam_kn' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(SubmissionImage::class, 'submission_id');
    }

    public function ctv(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ctv_id');
    }
}
