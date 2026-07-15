<?php

namespace Tests\Feature\Api\V1\Sale;

use App\Models\ThoSubmission;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\SubmissionReviewService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * Phase 2 — review lifecycle + commission (service-level, không qua HTTP middleware).
 * Ref: DUC-SUBMISSION-REJECT, DUC-COMMISSION-RECORD.
 * (Test approve → tạo thợ cần bảng users legacy nên chạy ở môi trường có schema đầy đủ.)
 */
class SubmissionReviewTest extends TestCase
{
    use DatabaseTransactions;

    private function submission(array $override = []): ThoSubmission
    {
        $ctv = User::factory()->create();

        return ThoSubmission::create(array_merge([
            'ctv_id'         => $ctv->id,
            'ten_tho'        => 'Vũ Hùng',
            'nghe'           => 'Thợ điện',
            'khu_vuc'        => 'Cầu Giấy, Hà Nội',
            'sdt_tho'        => '0972585990',
            'sdt_normalized' => '0972585990',
            'status'         => 'pending',
        ], $override));
    }

    public function test_reject_sets_status_and_reason(): void
    {
        $s = $this->submission();

        app(SubmissionReviewService::class)->reject($s, 'Ảnh không thật');

        $this->assertDatabaseHas('tho_submissions', [
            'id'            => $s->id,
            'status'        => 'rejected',
            'ly_do_tu_choi' => 'Ảnh không thật',
        ]);
    }

    public function test_cannot_reject_non_pending(): void
    {
        $s = $this->submission(['status' => 'approved']);

        $this->expectException(ValidationException::class);
        app(SubmissionReviewService::class)->reject($s, 'lý do hợp lệ');
    }

    public function test_commission_is_idempotent(): void
    {
        $s = $this->submission();
        $svc = app(CommissionService::class);

        $first = $svc->recordFor($s);
        $second = $svc->recordFor($s);

        $this->assertNotNull($first);
        $this->assertNull($second);
        $this->assertDatabaseCount('commissions', 1);
        $this->assertDatabaseHas('commissions', [
            'submission_id' => $s->id,
            'ctv_id'        => $s->ctv_id,
            'loai'          => 'base',
        ]);
    }

    private function seedCategory(string $name = 'Thợ điện'): int
    {
        return DB::table('categories')->insertGetId([
            'name'        => $name,
            'icon'        => '',
            'description' => '',
            'image'       => '',
            'status'      => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /** Approve → tạo thợ (User+Company) + ghi hoa hồng + duyệt ảnh. Ref: DUC-SUBMISSION-APPROVE. */
    public function test_approve_creates_tho_and_records_commission(): void
    {
        $catId = $this->seedCategory('Thợ điện');
        $s = $this->submission();
        $s->images()->create(['url' => 'http://x/a.jpg', 'approved' => false]);

        $result = app(SubmissionReviewService::class)->approve($s);

        $this->assertSame('approved', $result->status);
        $this->assertNotNull($result->company_id);

        // thợ user tạo theo SĐT chuẩn hoá
        $this->assertDatabaseHas('users', ['mobile' => '0972585990']);
        // company tạo, gắn đúng category name-match, trạng thái PENDING (đi tiếp luồng duyệt company)
        $this->assertDatabaseHas('companies', [
            'id'          => $result->company_id,
            'name'        => 'Vũ Hùng',
            'category_id' => $catId,
        ]);
        // hoa hồng ghi cho CTV
        $this->assertDatabaseHas('commissions', [
            'submission_id' => $s->id,
            'ctv_id'        => $s->ctv_id,
            'loai'          => 'base',
        ]);
        // ảnh được đánh dấu đã duyệt
        $this->assertDatabaseHas('submission_images', [
            'submission_id' => $s->id,
            'approved'      => 1,
        ]);
    }

    /** Không approve được submission đã rejected. */
    public function test_cannot_approve_non_pending(): void
    {
        $this->seedCategory();
        $s = $this->submission(['status' => 'rejected']);

        $this->expectException(ValidationException::class);
        app(SubmissionReviewService::class)->approve($s);
    }
}
