<?php

use App\Models\LegalDocument;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $translations = [
            'impressum' => ['title' => 'Impressum / Rechtlicher Hinweis', 'summary' => 'Unternehmensangaben und gesetzliche Hinweise.'],
            'privacy-policy' => ['title' => 'Datenschutzhinweis', 'summary' => 'Wie personenbezogene Daten erhoben und verarbeitet werden.'],
            'cookie-policy' => ['title' => 'Cookie-Richtlinie', 'summary' => 'Informationen zu Cookies und Einwilligungseinstellungen.'],
            'terms' => ['title' => 'Plattformbedingungen', 'summary' => 'Bedingungen für die Nutzung der Koordinationsplattform.'],
            'medical-disclaimer' => ['title' => 'Medizinischer Haftungsausschluss', 'summary' => 'Der Unterschied zwischen Koordination und medizinischer Versorgung.'],
            'complaints' => ['title' => 'Beschwerdeverfahren', 'summary' => 'Wie Sie ein Anliegen vorbringen und was danach geschieht.'],
            'clinic-selection-standards' => ['title' => 'Standards für die Klinikauswahl', 'summary' => 'Wie Partneranbieter vor der Veröffentlichung geprüft werden.'],
            'patient-rights' => ['title' => 'Patientenrechte und Verantwortlichkeiten der Anbieter', 'summary' => 'Ein klarer Überblick über die Verantwortung von Patienten und Anbietern.'],
        ];
        foreach ($translations as $slug => $german) {
            $document = LegalDocument::query()->where('slug', $slug)->first();
            if (! $document) continue;
            $allTranslations = is_array($document->translations) ? $document->translations : [];
            $existingGerman = is_array($allTranslations['de'] ?? null) ? $allTranslations['de'] : [];
            $allTranslations['de'] = array_replace_recursive($german, $existingGerman);
            $document->forceFill(['translations' => $allTranslations])->save();
        }
    }
};