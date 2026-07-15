<?php

namespace Tests\Feature\Api\V1\Sale;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Feature tests — Sale CTV Onboarding Phase 1.
 * Ref: DUC-SUBMISSION-CREATE (AC1..AC5), DUC-SUBMISSION-LIST (AC1..AC4).
 */
class SubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $override = []): array
    {
        return array_merge([
            'ten_tho' => 'Vũ Hùng',
            'nghe'    => 'Thợ điện',
            'khu_vuc' => 'Cầu Giấy, Hà Nội',
            'sdt_tho' => '0972585990',
            'images'  => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.jpg'),
                UploadedFile::fake()->image('c.jpg'),
            ],
        ], $override);
    }

    /** AC1: payload hợp lệ + 3 ảnh → 201, status=pending */
    public function test_ctv_can_create_submission(): void
    {
        Storage::fake('public');
        $ctv = User::factory()->create();
        Sanctum::actingAs($ctv);

        $this->postJson('/api/v1/sale/submissions', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.status', 'pending')
            ->assertJsonPath('data.so_anh', 3);

        $this->assertDatabaseHas('tho_submissions', [
            'ten_tho' => 'Vũ Hùng',
            'ctv_id'  => $ctv->id,
            'status'  => 'pending',
        ]);
    }

    /** AC4: SĐT trùng kể cả khác định dạng → 422 */
    public function test_duplicate_phone_is_rejected_across_formats(): void
    {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/sale/submissions', $this->payload())->assertCreated();

        $this->postJson('/api/v1/sale/submissions', $this->payload([
            'sdt_tho' => '+84 972 585 990',
        ]))->assertStatus(422)->assertJsonValidationErrors('sdt_tho');
    }

    /** AC3: <3 ảnh → 422 */
    public function test_requires_min_three_images(): void
    {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/sale/submissions', $this->payload([
            'images' => [UploadedFile::fake()->image('a.jpg')],
        ]))->assertStatus(422)->assertJsonValidationErrors('images');
    }

    /** LIST AC1/AC2: CTV chỉ thấy hồ sơ của mình */
    public function test_ctv_only_sees_own_submissions(): void
    {
        Storage::fake('public');
        $a = User::factory()->create();
        $b = User::factory()->create();

        Sanctum::actingAs($a);
        $this->postJson('/api/v1/sale/submissions', $this->payload())->assertCreated();

        Sanctum::actingAs($b);
        $this->postJson('/api/v1/sale/submissions', $this->payload([
            'sdt_tho' => '0988111222',
        ]))->assertCreated();

        $this->getJson('/api/v1/sale/submissions')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.total', 1);
    }

    /** AC5 (security): chưa đăng nhập → 401 */
    public function test_requires_authentication(): void
    {
        $this->postJson('/api/v1/sale/submissions', [])->assertUnauthorized();
    }
}
