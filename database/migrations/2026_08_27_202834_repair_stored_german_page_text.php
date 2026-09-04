<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        $replace = function (mixed $value) use (&$replace): mixed {
            if (is_array($value)) return array_map($replace, $value);
            if (!is_string($value)) return $value;
            return str_replace(["\u{00C3}\u{201E}", "\u{00C3}\u{00BC}", "\u{00C3}\u{00A4}", "\u{00C3}\u{00B6}"], ["\u{00C4}", "\u{00DC}", "\u{00E4}", "\u{00F6}"], $value);
        };
        foreach (SitePage::query()->whereIn("slug", ["doctors.index", "treatment-plan", "clinics.index"])->get() as $page) {
            $translations = $page->getRawOriginal("translations");
            $translations = is_string($translations) ? json_decode($translations, true) : ($translations ?: []);
            if (!is_array($translations)) continue;
            $clean = $replace($translations);
            if (is_array($clean)) $page->forceFill(["translations" => $clean])->save();
        }
    }
    public function down(): void {}
};
