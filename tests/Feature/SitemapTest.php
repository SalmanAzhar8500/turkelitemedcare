<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_includes_published_content_and_excludes_drafts(): void
    {
        $specialty = Specialty::query()->create(['name' => 'Dental', 'slug' => 'dental', 'is_active' => true]);
        Procedure::query()->create(['specialty_id' => $specialty->id, 'name' => 'Dental implants', 'slug' => 'dental-implants', 'is_active' => true]);
        Clinic::query()->create(['name' => 'Example Clinic', 'slug' => 'example-clinic', 'is_active' => true]);
        PatientStory::query()->create(['name' => 'Example Story', 'slug' => 'example-story', 'is_active' => true]);
        Specialty::query()->create(['name' => 'Draft', 'slug' => 'draft-treatment', 'is_active' => false]);

        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee(route('treatments.show', $specialty), false);
        $response->assertSee(route('procedures.show', [$specialty, 'dental-implants']), false);
        $response->assertSee(route('clinics.show', 'example-clinic'), false);
        $response->assertSee(route('stories.show', 'example-story'), false);
        $response->assertDontSee('draft-treatment');
        $response->assertDontSee('.html', false);
    }

    public function test_robots_references_the_dynamic_sitemap(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee(url('/sitemap.xml'));
    }
}
