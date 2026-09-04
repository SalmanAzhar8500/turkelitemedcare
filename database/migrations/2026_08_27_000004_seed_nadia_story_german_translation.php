<?php

use App\Models\PatientStory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $story = PatientStory::query()->where('slug', 'nadia-gastric-sleeve-antalya')->first();

        if (! $story) {
            return;
        }

        $translations = is_array($story->translations) ? $story->translations : [];
        $existingGerman = is_array($translations['de'] ?? null) ? $translations['de'] : [];
        $german = [
            'name' => 'Nadias Magenverkleinerung in Antalya',
            'summary' => 'Eine strukturierte Reise von der ersten Gewichtsabklärung bis zur Nachsorge in Antalya.',
            'content' => 'Nadia wollte ihre Möglichkeiten für eine nachhaltige Gewichtsreduktion verstehen und die praktischen Schritte einer Behandlung im Ausland sorgfältig planen. Die koordinierte Reise verband die klinische Prüfung, Terminplanung, Unterkunft und Transfers rund um den bestätigten Behandlungsplan.\n\nVor der Rückreise wurden die Entlassungsunterlagen, die Hinweise zur Erholung und der Kontakt für die Nachsorge gemeinsam abgestimmt. Die endgültige Eignung, Behandlung und medizinische Nachsorge lagen beim unabhängigen behandelnden Klinikteam.',
            'bullet_points' => [
                'Klinische Prüfung und bestätigter Behandlungsplan',
                'Koordination von Terminen, Unterkunft und Transfers',
                'Klare Entlassungs- und Nachsorgeinformationen vor der Rückreise',
            ],
            'seo_title' => 'Nadias Magenverkleinerung in Antalya | Patientengeschichte',
            'seo_description' => 'Nadias koordinierte Reise zur Magenverkleinerung in Antalya mit klinischer Prüfung, Reiseplanung und Nachsorge.',
        ];

        $translations['de'] = array_replace_recursive($german, $existingGerman);
        $story->forceFill(['translations' => $translations])->save();
    }
};