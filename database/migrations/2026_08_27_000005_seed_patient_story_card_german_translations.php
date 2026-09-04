<?php

use App\Models\PatientStory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $translations = [
            'martin-dental-implants-istanbul' => [
                'name' => 'Zahnimplantate in Istanbul',
                'summary' => 'Eine koordinierte Patientenreise für Zahnimplantate in Istanbul.',
                'bullet_points' => ['Veröffentlichte Patientenreise', 'Beispiel für Recherche und Planung', 'Unabhängige klinische Prüfung erforderlich'],
            ],
            'sophie-hair-restoration-istanbul' => [
                'name' => 'Haarwiederherstellung in Istanbul',
                'summary' => 'Eine strukturierte Reise zur Haarwiederherstellung mit Planung und Nachsorge.',
                'bullet_points' => ['Veröffentlichte Patientenreise', 'Beispiel für Recherche und Planung', 'Unabhängige klinische Prüfung erforderlich'],
            ],
            'james-knee-replacement-antalya' => [
                'name' => 'Kniegelenkersatz in Antalya',
                'summary' => 'Eine koordinierte Reise für die klinische Prüfung, Behandlung und Nachsorge.',
                'bullet_points' => ['Veröffentlichte Patientenreise', 'Beispiel für Recherche und Planung', 'Unabhängige klinische Prüfung erforderlich'],
            ],
            'anna-lasik-izmir' => [
                'name' => 'LASIK in Izmir',
                'summary' => 'Eine Patientenreise zur Sehkorrektur mit klarer Vorbereitung und Nachsorgeplanung.',
                'bullet_points' => ['Veröffentlichte Patientenreise', 'Beispiel für Recherche und Planung', 'Unabhängige klinische Prüfung erforderlich'],
            ],
            'luca-rhinoplasty-istanbul' => [
                'name' => 'Nasenkorrektur in Istanbul',
                'summary' => 'Eine koordinierte Reise für Beratung, Behandlung und Nachsorge in Istanbul.',
                'bullet_points' => ['Veröffentlichte Patientenreise', 'Beispiel für Recherche und Planung', 'Unabhängige klinische Prüfung erforderlich'],
            ],
            'nadia-gastric-sleeve-antalya' => [
                'name' => 'Magenverkleinerung in Antalya',
                'summary' => 'Eine strukturierte Reise von der ersten Gewichtsabklärung bis zur Nachsorge in Antalya.',
                'bullet_points' => ['Klinische Prüfung und bestätigter Behandlungsplan', 'Koordination von Terminen, Unterkunft und Transfers', 'Klare Entlassungs- und Nachsorgeinformationen vor der Rückreise'],
            ],
        ];

        foreach ($translations as $slug => $german) {
            $story = PatientStory::query()->where('slug', $slug)->first();
            if (! $story) {
                continue;
            }

            $allTranslations = is_array($story->translations) ? $story->translations : [];
            $existingGerman = is_array($allTranslations['de'] ?? null) ? $allTranslations['de'] : [];
            $allTranslations['de'] = array_replace_recursive($german, $existingGerman);
            $story->forceFill(['translations' => $allTranslations])->save();
        }
    }
};