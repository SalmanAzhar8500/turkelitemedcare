<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $pages = DB::table('site_pages')->where('slug', 'like', 'static/doctors/%')->get();

        foreach ($pages as $page) {
            $content = is_string($page->content) ? json_decode($page->content, true) : $page->content;
            $content = is_array($content) ? $content : [];
            $slug = Str::afterLast($page->slug, '/');

            DB::table('doctors')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $page->title,
                    'summary' => Str::limit($page->description ?? '', 500, ''),
                    'content' => data_get($content, 'imported_static_content.text', ''),
                    'is_active' => (bool) $page->is_active,
                    'seo_title' => $page->seo_title,
                    'seo_description' => $page->seo_description,
                    'seo_keywords' => $page->seo_keywords,
                    'seo_robots' => $page->seo_robots ?: 'index,follow',
                    'sort_order' => 0,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        DB::table('site_pages')->where('slug', 'like', 'static/doctors/%')->delete();
    }

    public function down(): void
    {
        // Legacy page records are intentionally not recreated on rollback.
    }
};
