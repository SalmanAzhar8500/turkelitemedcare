<?php

namespace Tests\Feature;

use App\Models\Clinic;
use App\Models\PatientService;
use App\Models\PatientStory;
use App\Models\Procedure;
use App\Models\SitePage;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaticHtmlImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_static_html_content_is_imported_into_the_laravel_content_tables(): void
    {
        $this->artisan('site:import-static-content')
            ->assertSuccessful();

        $this->assertSame(10, Specialty::count());
        $this->assertSame(0, \App\Models\Condition::count());
        $this->assertSame(100, Procedure::count());
        $this->assertSame(6, Clinic::count());
        $this->assertSame(6, PatientService::count());
        $this->assertSame(6, PatientStory::count());
        $this->assertSame(38, SitePage::count());

        $procedure = Procedure::query()->where('slug', 'dental-implants')->firstOrFail();
        $this->assertStringContainsString('What are dental implants?', $procedure->content);

        $guide = SitePage::query()->where('slug', 'static/guides/aftercare')->firstOrFail();
        $this->assertSame('guides/aftercare.html', data_get($guide->content, 'imported_static_content.source_path'));
        $this->assertNotEmpty(data_get($guide->content, 'imported_static_content.text'));
    }
}
