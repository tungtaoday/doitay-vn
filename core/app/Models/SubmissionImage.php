<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Ảnh công việc gắn với một ThoSubmission. Cờ approved do Quản lý duyệt (Phase 2).
 */
class SubmissionImage extends Model
{
    protected $fillable = [
        'submission_id',
        'url',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(ThoSubmission::class, 'submission_id');
    }
}
