<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = SitePage::query()->where('slug', 'stories.index')->first();

        if (! $page) {
            return;
        }

        $translations = is_array($page->translations) ? $page->translations : [];
        $existingGerman = is_array($translations['de'] ?? null) ? $translations['de'] : [];
        $existingContent = is_array($existingGerman['content'] ?? null) ? $existingGerman['content'] : [];
        $germanContent = [
            'hero' => [
                'kicker' => 'Patientenreisen',
                'headline' => 'Patientengeschichten',
                'lead' => 'Erfahren Sie, wie Recherche, klinische Prüfung, Reiseplanung und Nachsorge eine Behandlungreise miteinander verbinden können.',
                'card_label' => 'Patientenpfad',
                'card_heading' => 'Anliegen -> Optionen -> Anbieter -> Reise',
                'card_text' => 'Für eine transparente Planung medizinischer Reisen entwickelt',
            ],
            'intro' => [
                'eyebrow' => 'Beispiele für Patientenreisen',
                'heading' => 'Von der Frage zum koordinierten Plan',
                'lead' => 'Jede veröffentlichte Geschichte beschreibt den Weg von der ersten Recherche über die Anbieterprüfung und Reisevorbereitung bis zur Nachsorgeplanung.',
                'card_meta' => 'Patientenreise',
                'published_label' => 'Veröffentlichte Geschichte',
                'read_label' => 'Geschichte lesen',
                'date_label' => 'Geschichte',
            ],
            'empty' => [
                'heading' => 'Noch keine Patientengeschichten veröffentlicht',
                'text' => 'Veröffentlichte Patientenreisen werden hier bald angezeigt.',
            ],
        ];

        $translations['de'] = array_replace_recursive($existingGerman, [
            'title' => $existingGerman['title'] ?? 'Patientengeschichten | Turkelitemedcare',
            'description' => $existingGerman['description'] ?? 'Veröffentlichte Geschichten über Patientenreisen.',
            'content' => array_replace_recursive($germanContent, $existingContent),
        ]);

        $page->forceFill(['translations' => $translations])->save();
    }
};