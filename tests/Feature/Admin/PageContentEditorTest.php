<?php

namespace Tests\Feature\Admin;

use App\Models\SitePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageContentEditorTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_edits_page_sections_without_json(): void
    {
        $user = User::factory()->create();
        $page = SitePage::query()->create([
            'slug' => 'example',
            'title' => 'Example page',
            'template' => 'example',
            'content' => ['hero' => ['headline' => 'Original heading', 'lead' => 'Original lead']],
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('Page content (English)')
            ->assertDontSee('Content JSON');

        $this->actingAs($user)
            ->put(route('admin.pages.update', $page), [
                'title' => 'Example page',
                'description' => 'Example description',
                'template' => 'example',
                'content_fields' => ['hero' => ['headline' => 'Updated heading', 'lead' => 'Updated lead']],
                'content_de_fields' => ['hero' => ['headline' => 'Aktualisierte Uberschrift', 'lead' => 'Aktualisierte Einleitung']],
                'seo_title' => 'Example SEO title',
                'seo_description' => 'A search-friendly description for the example page.',
                'seo_keywords' => 'example, treatment, support',
                'seo_robots' => 'noindex,nofollow',
            ])
            ->assertRedirect(route('admin.pages.edit', $page));

        $page->refresh();

        $this->assertSame('Updated heading', data_get($page->content, 'hero.headline'));
        $this->assertSame('Aktualisierte Uberschrift', data_get($page->translation('de'), 'content.hero.headline'));
        $this->assertSame('Example SEO title', $page->seo_title);
        $this->assertSame('noindex,nofollow', $page->seo_robots);
    }

    public function test_saved_page_seo_is_rendered_on_the_public_page(): void
    {
        SitePage::query()->create([
            'slug' => 'about',
            'title' => 'About',
            'template' => 'about',
            'content' => [],
            'seo_title' => 'About Turkelitemedcare',
            'seo_description' => 'Learn about the patient coordination team.',
            'seo_keywords' => 'about, patient coordination',
            'seo_robots' => 'index,follow',
            'is_active' => true,
        ]);

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('<title>About Turkelitemedcare</title>', false)
            ->assertSee('name="keywords" content="about, patient coordination"', false)
            ->assertSee('name="robots" content="index,follow"', false);

        $about = SitePage::query()->where('slug', 'about')->firstOrFail();
        $this->assertSame('Care pathway', data_get($about->content, 'hero_card.label'));
        $this->assertSame('Commercial transparency', data_get($about->content, 'transparency.kicker'));
    }

}
