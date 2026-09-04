<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = SitePage::query()->where('slug', 'legal')->first();
        if (! $page) {
            return;
        }

        $translations = is_array($page->translations) ? $page->translations : [];
        $german = is_array($translations['de'] ?? null) ? $translations['de'] : [];
        $content = is_array($german['content'] ?? null) ? $german['content'] : [];
        $content['ui'] = array_replace([
            'important_notice_label' => 'Wichtiger Hinweis',
            'draft_label' => 'Entwurf - rechtliche Prüfung ausstehend',
            'legal_flag' => 'Das Impressum ist für Veröffentlichungen in Deutschland gesetzlich vorgeschrieben. Die Cookie-Richtlinie setzt außerdem ein funktionierendes Einwilligungsbanner voraus. Kein Dokument auf dieser Seite ersetzt eine professionelle Rechtsberatung.',
            'urgent_notice' => 'Bei dringenden medizinischen Beschwerden wenden Sie sich bitte an Ihren behandelnden Anbieter oder den örtlichen Notdienst.',
        ], is_array($content['ui'] ?? null) ? $content['ui'] : []);
        $german['content'] = $content;
        $translations['de'] = $german;
        $page->forceFill(['translations' => $translations])->save();
    }
};