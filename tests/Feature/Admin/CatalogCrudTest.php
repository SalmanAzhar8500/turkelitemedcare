<?php

namespace Tests\Feature\Admin;

use App\Models\Specialty;
use App\Models\Procedure;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CatalogCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_manage_treatments_and_procedures(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'treatments'), [
                'name' => 'Dental Care',
                'slug' => 'dental-care',
                'summary' => 'A test treatment',
                'description' => 'Public treatment content.',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect();

        $specialty = Specialty::query()->where('slug', 'dental-care')->firstOrFail();

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'procedures'), [
                'name' => 'Dental Implant',
                'slug' => 'dental-implant',
                'summary' => 'A test procedure',
                'content' => 'Procedure content.',
                'specialty_id' => $specialty->id,
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('procedures', ['slug' => 'dental-implant', 'specialty_id' => $specialty->id]);
        $this->actingAs($user)->get(route('admin.catalog.index', 'procedures'))->assertOk();
        $this->assertSame($specialty->id, Procedure::query()->where('slug', 'dental-implant')->firstOrFail()->specialty_id);
    }

    public function test_an_authenticated_user_can_edit_a_treatment_using_its_slug_url(): void
    {
        $user = User::factory()->create();
        $treatment = Specialty::query()->create([
            'name' => 'Dental',
            'slug' => 'dental',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.catalog.edit', ['treatments', $treatment]))
            ->assertOk()
            ->assertSee('Edit Treatment');
    }

    public function test_an_authenticated_user_can_save_german_treatment_content(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'treatments'), [
                'name' => 'Dental', 'slug' => 'dental', 'summary' => 'Dental care',
                'description' => 'English description.', 'name_de' => 'Zahnmedizin',
                'summary_de' => 'Zahnmedizinische Versorgung',
                'description_de' => 'Deutsche Beschreibung.', 'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('specialties', ['slug' => 'dental']);
        $this->assertSame('Zahnmedizin', Specialty::query()->where('slug', 'dental')->firstOrFail()->translation('de')['name']);
    }

    public function test_an_authenticated_user_can_upload_a_catalog_cover_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'treatments'), [
                'name' => 'Image Treatment',
                'slug' => 'image-treatment',
                'image' => UploadedFile::fake()->image('treatment-cover.png', 1200, 800),
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $treatment = Specialty::query()->where('slug', 'image-treatment')->firstOrFail();

        $this->assertNotNull($treatment->image_path);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $treatment->image_path));
    }

    public function test_an_authenticated_user_can_manage_doctors_in_their_own_catalog_section(): void
    {
        $user = User::factory()->create();
        $clinic = Clinic::query()->create(['name' => 'Example Clinic', 'slug' => 'example-clinic', 'is_active' => true]);
        $treatment = Specialty::query()->create(['name' => 'General Surgery', 'slug' => 'general-surgery', 'is_active' => true]);
        $procedure = Procedure::query()->create(['name' => 'Hernia Repair', 'slug' => 'hernia-repair', 'specialty_id' => $treatment->id, 'is_active' => true]);

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'doctors'), [
                'name' => 'Dr Jane Doe',
                'slug' => 'dr-jane-doe',
                'clinic_id' => $clinic->id,
                'designation' => 'Consultant Surgeon',
                'treatment_ids' => [$treatment->id],
                'procedure_ids' => [$procedure->id],
                'summary' => 'Experienced consultant surgeon.',
                'content' => 'Professional biography.',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('doctors', ['slug' => 'dr-jane-doe', 'clinic_id' => $clinic->id]);
        $doctor = Doctor::query()->where('slug', 'dr-jane-doe')->firstOrFail();
        $this->assertTrue($doctor->treatments()->whereKey($treatment)->exists());
        $this->assertTrue($doctor->procedures()->whereKey($procedure)->exists());
        $this->actingAs($user)->get(route('admin.catalog.index', 'doctors'))->assertOk()->assertSee('Doctors');
    }

    public function test_a_doctor_requires_at_least_one_treatment(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->from(route('admin.catalog.create', 'doctors'))
            ->post(route('admin.catalog.store', 'doctors'), [
                'name' => 'Dr Missing Treatment',
                'slug' => 'dr-missing-treatment',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.catalog.create', 'doctors'))
            ->assertSessionHasErrors('treatment_ids');
    }

    public function test_an_authenticated_user_can_manage_guides_and_generate_a_public_detail_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('admin.catalog.store', 'guides'), [
                'name' => 'Preparing for treatment travel',
                'slug' => 'preparing-for-treatment-travel',
                'summary' => 'A practical guide for patients planning travel.',
                'content' => 'Guide content for a patient journey.',
                'is_active' => '1',
            ])
            ->assertSessionHasNoErrors();

        $guide = Guide::query()->where('slug', 'preparing-for-treatment-travel')->firstOrFail();

        $this->actingAs($user)->get(route('admin.catalog.index', 'guides'))->assertOk()->assertSee('Guides');
        $this->assertStringEndsWith('/guides/preparing-for-treatment-travel', route('guides.show', $guide));
    }
}
