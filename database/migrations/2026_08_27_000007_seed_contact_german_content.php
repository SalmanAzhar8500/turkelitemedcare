<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = SitePage::query()->where('slug', 'contact')->first();
        if (! $page) {
            return;
        }

        $translations = is_array($page->translations) ? $page->translations : [];
        $existingGerman = is_array($translations['de'] ?? null) ? $translations['de'] : [];
        $existingContent = is_array($existingGerman['content'] ?? null) ? $existingGerman['content'] : [];
        $germanContent = [
            'hero' => [
                'kicker' => 'Sprechen Sie mit uns',
                'headline' => 'Kontakt',
                'lead' => 'Nutzen Sie diese Seite für allgemeine Fragen. Behandlungsbezogene Anliegen senden Sie bitte über die strukturierte Behandlungsanfrage.',
            ],
            'contact_info' => [
                ['label' => 'E-Mail', 'value' => '{{brand.email}}'],
                ['label' => 'Telefon', 'value' => '{{brand.phone}}'],
                ['label' => 'Sprachen', 'value' => 'Deutsch, Englisch und weitere Sprachen nach Vereinbarung'],
            ],
            'form' => [
                'heading' => 'Kontakt zum Koordinationsteam',
                'note' => 'Ihre Anfrage wird im Rahmen unseres Behandlungskoordinationsprozesses bearbeitet.',
            ],
            'callback' => [
                'heading' => 'Rückruf anfordern',
                'lead' => 'Teilen Sie uns mit, wann Sie Zeit haben und welche Sprache Sie bevorzugen. Wir rufen Sie an. Es besteht keine Verpflichtung und Sie müssen keine medizinischen Details nennen.',
                'note' => 'Rückrufe an Werktagen, normalerweise am selben oder nächsten Werktag.',
            ],
        ];
        $translations['de'] = array_replace_recursive($existingGerman, [
            'title' => $existingGerman['title'] ?? 'Kontakt | Turkelitemedcare',
            'description' => $existingGerman['description'] ?? 'Kontaktieren Sie das internationale Patiententeam von Turkelitemedcare.',
            'content' => array_replace_recursive($germanContent, $existingContent),
        ]);
        $page->forceFill(['translations' => $translations])->save();
    }
};