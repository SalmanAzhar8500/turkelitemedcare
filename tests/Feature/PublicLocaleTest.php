<?php

namespace Tests\Feature;

use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_german_treatment_page_renders_saved_german_content(): void
    {
        Specialty::query()->create([
            'name' => 'Dental',
            'slug' => 'dental',
            'summary' => 'English summary',
            'description' => 'English description',
            'translations' => [
                'de' => [
                    'name' => 'Zahnmedizin',
                    'summary' => 'Deutsche Zusammenfassung',
                    'description' => 'Deutsche Beschreibung',
                ],
            ],
            'is_active' => true,
        ]);

        $this->get('/treatments/dental?lang=de')
            ->assertOk()
            ->assertSee('Zahnmedizin')
            ->assertSee('Startseite');
    }
}
