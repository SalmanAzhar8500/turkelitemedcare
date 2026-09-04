<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_update_website_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('admin.settings.update'), [
            'site_name' => 'Turkelite Care', 'legal_name' => 'Turkelite Care Ltd',
            'contact_email' => 'hello@example.test', 'contact_phone' => '+49 30 123',
            'support_hours' => 'Mon-Fri 09:00-17:00', 'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.example.test', 'mail_port' => 587,
            'mail_username' => 'mailer@example.test', 'mail_encryption' => 'tls',
            'mail_from_address' => 'hello@example.test', 'mail_from_name' => 'Turkelite Care',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Turkelite Care', SiteSetting::query()->where('key', 'site_name')->value('value'));
        $this->assertSame('smtp.example.test', SiteSetting::query()->where('key', 'mail_host')->value('value'));
    }

    public function test_an_authenticated_user_can_upload_a_public_website_logo(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('admin.settings.update'), [
            'site_name' => 'Turkelite Care', 'contact_email' => 'hello@example.test',
            'mail_mailer' => 'log', 'mail_from_address' => 'hello@example.test',
            'mail_from_name' => 'Turkelite Care',
            'logo' => UploadedFile::fake()->image('logo.png', 600, 240),
        ])->assertSessionHasNoErrors();

        $logoPath = SiteSetting::query()->where('key', 'logo_path')->value('value');
        $this->assertStringStartsWith('/storage/settings/', $logoPath);
        Storage::disk('public')->assertExists(ltrim(substr($logoPath, strlen('/storage/')), '/'));
    }

    public function test_an_authenticated_user_can_upload_a_public_favicon(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('admin.settings.update'), [
            'site_name' => 'Turkelite Care', 'contact_email' => 'hello@example.test',
            'mail_mailer' => 'log', 'mail_from_address' => 'hello@example.test',
            'mail_from_name' => 'Turkelite Care',
            'favicon' => UploadedFile::fake()->image('favicon.png', 32, 32),
        ])->assertSessionHasNoErrors();

        $faviconPath = SiteSetting::query()->where('key', 'favicon_path')->value('value');
        $this->assertStringStartsWith('/storage/settings/', $faviconPath);
        Storage::disk('public')->assertExists(ltrim(substr($faviconPath, strlen('/storage/')), '/'));
    }
}
