<?php

namespace Tests\Feature\Api\V1\Public;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for /api/v1/public/companies.
 *
 * Covers DUC-COMPANY-LIST-PUBLIC and DUC-COMPANY-SHOW-PUBLIC.
 *
 * Note: live `companies` table has no `slug` column. Resolution is by id;
 * the API exposes a derived `vanity_slug` so the frontend can build SEO URLs.
 *
 * @group api-v1
 * @group public
 * @group company
 */
class CompanyTest extends TestCase
{
    use RefreshDatabase;

    private function makeCompany(array $attrs = []): Company
    {
        $owner = User::factory()->create(['status' => 1]);
        $category = Category::factory()->create();

        return Company::factory()->create(array_merge([
            'user_id' => $owner->id,
            'category_id' => $category->id,
            'status' => 1, // approved
            'name' => 'ABC Company',
        ], $attrs));
    }

    // ───────────────────────────── LIST (DUC-COMPANY-LIST-PUBLIC) ─────────────────────────────

    /** @test AC1: index returns paginated list of approved companies */
    public function index_returns_only_approved_companies(): void
    {
        $approved = $this->makeCompany(['name' => 'Approved Co', 'status' => 1]);
        $pending  = $this->makeCompany(['name' => 'Pending Co',  'status' => 0]);

        $response = $this->getJson('/api/v1/public/companies');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'vanity_slug', 'name', 'category', 'rating_avg']],
                'meta' => ['current_page', 'per_page', 'total'],
            ])
            ->assertJsonPath('data.0.id', $approved->id)
            ->assertJsonMissing(['id' => $pending->id]);
    }

    /** @test AC5: per_page > 50 → 422 */
    public function index_rejects_per_page_over_50(): void
    {
        $this->getJson('/api/v1/public/companies?per_page=51')
            ->assertStatus(422);
    }

    /** @test AC2: filter by category */
    public function index_filters_by_category(): void
    {
        $owner = User::factory()->create(['status' => 1]);
        $cat1 = Category::factory()->create();
        $cat2 = Category::factory()->create();

        $inCat1 = Company::factory()->create([
            'user_id' => $owner->id, 'category_id' => $cat1->id,
            'status' => 1, 'name' => 'In Cat 1',
        ]);
        $inCat2 = Company::factory()->create([
            'user_id' => $owner->id, 'category_id' => $cat2->id,
            'status' => 1, 'name' => 'In Cat 2',
        ]);

        $response = $this->getJson("/api/v1/public/companies?category={$cat1->id}");

        $response->assertOk()
            ->assertJsonPath('data.0.id', $inCat1->id)
            ->assertJsonMissing(['id' => $inCat2->id]);
    }

    /** @test AC4: search keyword */
    public function index_search_matches_name(): void
    {
        $foo = $this->makeCompany(['name' => 'Foo Construction']);
        $bar = $this->makeCompany(['name' => 'Bar Painting']);

        $response = $this->getJson('/api/v1/public/companies?q=foo');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $foo->id)
            ->assertJsonMissing(['id' => $bar->id]);
    }

    /** @test AC6: never expose internal fields */
    public function index_response_never_exposes_admin_feedback_or_owner_email(): void
    {
        $this->makeCompany([
            'admin_feedback' => 'INTERNAL_NOTE_DO_NOT_LEAK',
        ]);

        $response = $this->getJson('/api/v1/public/companies');
        $body = $response->getContent();

        $this->assertStringNotContainsString('INTERNAL_NOTE_DO_NOT_LEAK', $body);
        $this->assertStringNotContainsString('admin_feedback', $body);
    }

    // ───────────────────────────── SHOW (DUC-COMPANY-SHOW-PUBLIC) ─────────────────────────────

    /** @test AC1: show by id */
    public function show_returns_company_detail_by_id(): void
    {
        $company = $this->makeCompany(['name' => 'Detail Co', 'status' => 1]);

        $response = $this->getJson("/api/v1/public/companies/{$company->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $company->id)
            ->assertJsonStructure([
                'data' => ['id', 'vanity_slug', 'name', 'description', 'category', 'rating_avg'],
            ]);
    }

    /** @test AC2: 404 on unknown id */
    public function show_returns_404_for_unknown_id(): void
    {
        $this->getJson('/api/v1/public/companies/9999999')
            ->assertStatus(404);
    }

    /** @test AC3: pending company hidden as 404 */
    public function show_returns_404_for_non_approved_company(): void
    {
        $pending = $this->makeCompany(['status' => 0]);

        $this->getJson("/api/v1/public/companies/{$pending->id}")
            ->assertStatus(404);
    }

    /** @test AC6: detail response never exposes admin_feedback */
    public function show_never_exposes_admin_feedback(): void
    {
        $company = $this->makeCompany([
            'admin_feedback' => 'SECRET_INTERNAL',
        ]);

        $body = $this->getJson("/api/v1/public/companies/{$company->id}")->getContent();
        $this->assertStringNotContainsString('SECRET_INTERNAL', $body);
        $this->assertStringNotContainsString('admin_feedback', $body);
    }
}
