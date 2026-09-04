<?php

namespace Tests\Feature\Admin;

use App\Models\SiteInquiry;
use App\Models\SitePage;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_load_yajra_catalog_pages_and_inquiry_data(): void
    {
        $user = User::factory()->create();
        Specialty::query()->create([
            'name' => 'Dental',
            'slug' => 'dental',
            'summary' => 'Oral health',
            'description' => 'Dental care.',
            'is_active' => true,
        ]);
        SitePage::query()->create([
            'slug' => 'about',
            'title' => 'About',
            'description' => 'About the team.',
            'template' => 'about',
            'content' => [],
            'is_active' => true,
        ]);
        SiteInquiry::query()->create([
            'type' => 'treatment-plan',
            'page_slug' => 'treatment-plan',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
            'message' => 'Please contact me.',
            'status' => 'new',
        ]);

        $this->actingAs($user)
            ->getJson('/admin/catalog/treatments/data?draw=1&start=0&length=10')
            ->assertOk()
            ->assertJsonPath('draw', 1)
            ->assertJsonFragment(['name' => '<strong>Dental</strong><small>Oral health</small>']);

        $this->actingAs($user)
            ->getJson('/admin/pages/data?draw=1&start=0&length=10')
            ->assertOk()
            ->assertJsonPath('draw', 1)
            ->assertJsonFragment(['slug' => 'about']);

        $this->actingAs($user)
            ->getJson('/admin/treatment-requests/data?draw=1&start=0&length=10')
            ->assertOk()
            ->assertJsonPath('draw', 1)
            ->assertJsonFragment(['type' => 'treatment-plan']);
    }

    public function test_authenticated_admin_can_delete_catalog_pages_and_inquiries(): void
    {
        $user = User::factory()->create();
        $specialty = Specialty::query()->create([
            'name' => 'Dental',
            'slug' => 'dental',
            'summary' => 'Oral health',
            'description' => 'Dental care.',
            'is_active' => true,
        ]);
        $page = SitePage::query()->create([
            'slug' => 'temporary-page',
            'title' => 'Temporary page',
            'template' => 'default',
            'content' => [],
            'is_active' => true,
        ]);
        $inquiry = SiteInquiry::query()->create([
            'type' => 'contact',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
            'message' => 'Please contact me.',
            'status' => 'new',
        ]);

        $this->actingAs($user)
            ->delete(route('admin.catalog.destroy', ['treatments', $specialty]))
            ->assertRedirect(route('admin.catalog.index', 'treatments'));
        $this->assertModelMissing($specialty);

        $this->actingAs($user)
            ->delete(route('admin.pages.destroy', $page))
            ->assertRedirect(route('admin.pages.index'));
        $this->assertModelMissing($page);

        $this->actingAs($user)
            ->delete(route('admin.inquiries.destroy', $inquiry))
            ->assertRedirect(route('admin.inquiries.index'));
        $this->assertModelMissing($inquiry);
    }
}
