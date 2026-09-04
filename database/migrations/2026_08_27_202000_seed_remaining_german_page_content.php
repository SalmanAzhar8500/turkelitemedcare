<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        $translations = [
            'doctors.index' => ['hero' => ['kicker' => 'Medizinische Teams', 'headline' => 'Ärzte', 'lead' => 'Entdecken Sie Facharztprofile nach Schwerpunkt, Klinik, Sprachen und Behandlungspfaden.', 'card_label' => 'Transparenz der Anbieter', 'card_heading' => 'Arzt -> Klinik -> Behandlung', 'card_text' => 'Qualifikationen müssen vor der Veröffentlichung geprüft werden.'], 'listing' => ['eyebrow' => 'Fachärztenetzwerk', 'heading' => 'Spezialisten für unsere Behandlungspfade', 'lead' => 'Die Profile enthalten die Informationen, die internationale Patienten vor einer klinischen Prüfung benötigen.', 'card_label' => 'Facharztprofil']],
            'treatment-plan' => ['hero' => ['kicker' => 'Hier beginnen', 'headline' => 'Ihren Behandlungsplan erhalten', 'lead' => 'Sie müssen den genauen Eingriff noch nicht kennen. Beschreiben Sie Ihr Anliegen, damit unser Koordinationsteam die nächsten Schritte vorbereiten kann.', 'card_label' => 'Behandlungspfad', 'card_heading' => 'Planen, koordinieren, unterstützt zurückkehren', 'card_text' => 'Ein Team koordiniert den praktischen Ablauf Ihrer Behandlung.'], 'form' => ['success_heading' => 'Anfrage erhalten', 'error' => 'Bitte prüfen Sie die markierten Felder und versuchen Sie es erneut.', 'reply_heading' => 'Ein Koordinator antwortet innerhalb eines Werktages.', 'reply_text' => 'Montag bis Freitag auf Deutsch oder Englisch.', 'step1' => 'Wobei können wir helfen?', 'step2' => 'Über Sie', 'step3' => 'Weitere wichtige Informationen', 'next' => 'Weiter', 'back' => 'Zurück', 'submit' => 'Behandlungsanfrage senden'], 'aside' => ['heading' => 'Wie geht es weiter?', 'privacy_heading' => 'Ihre Daten sind wichtig']],
            'clinics.index' => ['hero' => ['kicker' => 'Partnernetzwerk', 'headline' => 'Ausgewählte Kliniken in der Türkei', 'lead' => 'Entdecken Sie veröffentlichte Partnerkliniken nach Stadt, Profil und Unterstützung für internationale Patienten.', 'card_label' => 'Netzwerkübersicht']],
        ];
        foreach ($translations as $slug => $content) {
            $page = SitePage::query()->where('slug', $slug)->first();
            if (!$page) continue;
            $all = $page->getRawOriginal('translations');
            $all = is_string($all) ? json_decode($all, true) : ($all ?: []);
            $all['de']['content'] = array_replace_recursive($all['de']['content'] ?? [], $content);
            $page->forceFill(['translations' => $all])->save();
        }
    }
    public function down(): void {}
};
