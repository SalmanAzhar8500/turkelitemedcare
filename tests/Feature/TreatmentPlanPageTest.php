<?php

namespace Tests\Feature;

use App\Models\SiteInquiry;
use App\Models\Specialty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentPlanPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_treatment_plan_page_displays_the_selected_specialty(): void
    {
        Specialty::query()->create([
            'name' => 'Dental',
            'slug' => 'dental',
            'summary' => 'Dental care',
            'description' => 'Dental treatment coordination.',
            'is_active' => true,
        ]);

        $this->get('/treatment-plan?specialty=dental')
            ->assertOk()
            ->assertSee('Get Your Treatment Plan')
            ->assertSee('Dental');
    }

    public function test_treatment_plan_submission_is_saved_in_the_admin_inbox(): void
    {
        $this->post('/treatment-plan', [
            'type' => 'treatment-plan',
            'page_slug' => 'treatment-plan',
            'subject' => 'Treatment plan request',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
            'phone' => '+49 123 456',
            'country' => 'Germany',
            'specialty' => 'Dental',
            'procedure' => 'Dental implants',
            'preferred_language' => 'German',
            'preferred_time' => 'Morning (09:00-12:00 CET)',
            'message' => 'I would like to understand practical next steps.',
            'consent_processing' => '1',
        ])->assertRedirect('/treatment-plan');

        $this->assertDatabaseHas('site_inquiries', [
            'type' => 'treatment-plan',
            'page_slug' => 'treatment-plan',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
        ]);

        $inquiry = SiteInquiry::query()->firstOrFail();

        $this->assertSame('Dental', $inquiry->metadata['specialty']);
        $this->assertSame('Germany', $inquiry->metadata['country']);
    }

    public function test_treatment_plan_requires_the_patient_details_and_consent(): void
    {
        $this->from('/treatment-plan')->post('/treatment-plan', [
            'type' => 'treatment-plan',
            'page_slug' => 'treatment-plan',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
            'message' => 'I would like to understand the available next steps.',
        ])->assertRedirect('/treatment-plan')
            ->assertSessionHasErrors(['specialty', 'country', 'consent_processing']);

        $this->assertDatabaseCount('site_inquiries', 0);
    }
}
