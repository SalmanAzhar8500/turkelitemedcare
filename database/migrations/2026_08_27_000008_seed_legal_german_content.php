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
        $existingGerman = is_array($translations['de'] ?? null) ? $translations['de'] : [];
        $existingContent = is_array($existingGerman['content'] ?? null) ? $existingGerman['content'] : [];
        $germanContent = [
            'hero' => [
                'kicker' => 'Vertrauen und Datenschutz',
                'headline' => 'Rechtliches & Datenschutz',
                'lead' => 'Klare Informationen darüber, wie Turkelitemedcare Behandlungen koordiniert, Informationen schützt und mit unabhängigen Gesundheitsdienstleistern arbeitet.',
            ],
            'hero_card' => [
                'label' => 'Klare Rollen',
                'heading' => 'Mit Vertrauen koordinieren',
                'text' => 'Medizinische Entscheidungen bleiben bei unabhängigen Partnern.',
            ],
            'documents' => [
                'heading' => 'Rechtliche und Datenschutz-Dokumente',
                'intro' => 'Lesen Sie die folgenden Dokumente, um die Plattform, Ihre Rechte und die Verantwortlichkeiten von Turkelitemedcare und seinen unabhängigen Partnern zu verstehen.',
            ],
            'notice' => 'Diese Dokumente sind strukturelle Entwürfe und müssen vor der Veröffentlichung von qualifizierten Rechtsberatern in den zuständigen Rechtsordnungen geprüft werden.',
            'role_clarity' => [
                'heading' => 'Klare Rollenverteilung',
                'paragraphs' => [
                    'Turkelitemedcare ist eine Koordinationsplattform. Wir unterstützen die Kommunikation, Terminplanung und praktische Reisebegleitung rund um Behandlungen durch unabhängige Gesundheitsdienstleister.',
                    'Partnerkliniken und Ärzte bleiben verantwortlich für klinische Beurteilung, Behandlungsempfehlungen, Einwilligung, medizinische Versorgung, Abrechnung medizinischer Leistungen und klinische Nachsorge.',
                ],
            ],
            'sidebar' => [
                'kicker' => 'Brauchen Sie Hilfe?',
                'heading' => 'Fragen zu Ihren Informationen?',
                'text' => 'Kontaktieren Sie das Koordinationsteam, wenn Sie Hilfe zu einer Anfrage benötigen oder Bedenken zur Verarbeitung Ihrer Informationen haben.',
                'button' => 'Team kontaktieren',
            ],
        ];
        $translations['de'] = array_replace_recursive($existingGerman, [
            'title' => $existingGerman['title'] ?? 'Rechtliches & Datenschutz | Turkelitemedcare',
            'description' => $existingGerman['description'] ?? 'Rechtliche, Datenschutz- und Compliance-Informationen für Turkelitemedcare.',
            'content' => array_replace_recursive($germanContent, $existingContent),
        ]);
        $page->forceFill(['translations' => $translations])->save();
    }
};