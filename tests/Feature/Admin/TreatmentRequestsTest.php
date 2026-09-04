<?php

namespace Tests\Feature\Admin;

use App\Models\SiteInquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TreatmentRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function test_treatment_requests_have_a_separate_admin_table_and_detail_page(): void
    {
        $user = User::factory()->create();
        $request = SiteInquiry::query()->create([
            'type' => 'treatment-plan',
            'page_slug' => 'treatment-plan',
            'name' => 'Jane Patient',
            'email' => 'jane@example.test',
            'message' => 'I need support planning a dental treatment journey.',
            'status' => 'new',
            'metadata' => ['specialty' => 'Dental', 'procedure' => 'Dental implants', 'country' => 'Germany'],
        ]);
        SiteInquiry::query()->create(['type' => 'contact', 'name' => 'General Contact', 'message' => 'A general question.']);

        $this->actingAs($user)
            ->get(route('admin.treatment-requests.index'))
            ->assertOk()
            ->assertSee('Treatment requests');

        $this->actingAs($user)
            ->get(route('admin.treatment-requests.data'))
            ->assertOk()
            ->assertJsonFragment(['name' => 'Jane Patient'])
            ->assertJsonMissing(['name' => 'General Contact']);

        $this->actingAs($user)
            ->get(route('admin.treatment-requests.show', $request))
            ->assertOk()
            ->assertSee('Dental implants')
            ->assertSee('Germany');

        $this->actingAs($user)
            ->patch(route('admin.treatment-requests.update', $request), ['status' => 'reviewing'])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('site_inquiries', ['id' => $request->id, 'status' => 'reviewing']);
    }
}
