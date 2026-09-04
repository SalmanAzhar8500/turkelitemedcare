<?php

use App\Models\SitePage;
use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $page = SitePage::query()->where('slug', 'guides.index')->first();
        if ($page) {
            $translations = is_array($page->translations) ? $page->translations : [];
            $existingGerman = is_array($translations['de'] ?? null) ? $translations['de'] : [];
            $existingContent = is_array($existingGerman['content'] ?? null) ? $existingGerman['content'] : [];
            $germanContent = [
                'hero' => [
                    'kicker' => 'Wissenszentrum',
                    'headline' => 'Ratgeber',
                    'lead' => 'Patientenfreundliche Informationen für sicherere und besser informierte Entscheidungen bei medizinischen Reisen.',
                    'card_label' => 'Behandlungspfad',
                    'card_heading' => 'Planen -> Koordinieren -> Unterstützt zurückkehren',
                    'card_text' => 'Ein Team koordiniert den Ablauf Ihrer Behandlung.',
                ],
                'listing' => [
                    'label' => 'Ratgeber',
                    'read_label' => 'Ratgeber lesen',
                    'empty_label' => 'Ratgeberbibliothek',
                    'empty_heading' => 'Ratgeber werden vorbereitet',
                    'empty_text' => 'Veröffentlichte Ratgeber werden hier angezeigt, sobald sie im Admin-Bereich hinzugefügt wurden.',
                ],
                'contact_routes' => [
                    ['title' => 'Nachricht senden', 'text' => 'Stellen Sie eine Frage. Ohne Verpflichtung.', 'href' => '/contact#message'],
                    ['title' => 'Rückruf anfordern', 'text' => 'Auf Deutsch oder Englisch, zu einer passenden Zeit.', 'href' => '/contact#callback'],
                    ['title' => 'Behandlungsanfrage starten', 'text' => 'Wenn eine Klinik Ihren Fall prüfen soll.', 'href' => '/treatment-plan'],
                ],
            ];
            $translations['de'] = array_replace_recursive($existingGerman, [
                'title' => $existingGerman['title'] ?? 'Ratgeber | Turkelitemedcare',
                'description' => $existingGerman['description'] ?? 'Patientenfreundliche Informationen für medizinische Reiseentscheidungen.',
                'content' => array_replace_recursive($germanContent, $existingContent),
            ]);
            $page->forceFill(['translations' => $translations])->save();
        }

        SiteSetting::putMany([
            'footer_description_de' => 'Ein Koordinationsteam begleitet den praktischen Ablauf Ihrer Behandlung: von der Klinikkommunikation und Terminplanung bis zu Reise, Ankunft und Nachsorge. Die medizinische Versorgung erfolgt durch unabhängige Gesundheitsdienstleister.',
            'footer_trust_note_de' => 'Internationale Patientenkoordination - Deutschland & Türkei',
            'footer_treatments_heading_de' => 'Behandlungen',
            'footer_support_heading_de' => 'Patientenbetreuung',
            'footer_company_heading_de' => 'Unternehmen',
            'footer_about_de' => 'Über uns',
            'footer_guides_de' => 'Ratgeber',
            'footer_contact_de' => 'Kontakt',
            'footer_about_us_de' => 'Über uns',
            'footer_for_clinics_de' => 'Für Kliniken',
            'footer_legal_privacy_de' => 'Rechtliches & Datenschutz',
            'footer_copyright_de' => '© 2026 :legal_name.',
            'footer_medical_notice_de' => 'Kein medizinischer Dienstleister - Kein Notfallversorger',
            'footer_disclosure_de' => 'Unabhängige Kliniken erbringen die medizinische Versorgung. Anbieterinformationen werden vor der Veröffentlichung geprüft.',
        ]);
    }
};