<?php

use App\Models\SitePage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        $fill = function (array $defaults, array $current) use (&$fill): array {
            foreach ($defaults as $key => $default) {
                $value = $current[$key] ?? null;
                if (is_array($default)) {
                    $current[$key] = $fill($default, is_array($value) ? $value : []);
                } elseif ($value === null || $value === '') {
                    $current[$key] = $default;
                }
            }
            return $current;
        };
        foreach (config('site-pages', []) as $slug => $defaults) {
            if (!is_array($defaults)) continue;
            $page = SitePage::query()->where('slug', $slug)->first();
            if (!$page) $page = SitePage::resolve($slug, $defaults);
            $content = $page->getRawOriginal('content');
            $content = is_string($content) ? json_decode($content, true) : ($content ?: []);
            $content = $fill($defaults['content'] ?? [], is_array($content) ? $content : []);
            $page->forceFill(['content' => $content])->save();
        }
    }
    public function down(): void {}
};
