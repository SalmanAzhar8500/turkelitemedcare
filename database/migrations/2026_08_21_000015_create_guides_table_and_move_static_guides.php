<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guides', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('summary', 500)->nullable();
            $table->longText('content')->nullable();
            $table->string('image_path')->nullable();
            $table->json('translations')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('seo_title', 60)->nullable();
            $table->string('seo_description', 160)->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->string('seo_robots', 100)->default('index,follow');
            $table->timestamps();
        });

        $pages = DB::table('site_pages')->where('slug', 'like', 'static/guides/%')->get();

        foreach ($pages as $order => $page) {
            $content = is_string($page->content) ? json_decode($page->content, true) : $page->content;
            $content = is_array($content) ? $content : [];
            $slug = Str::afterLast($page->slug, '/');

            DB::table('guides')->updateOrInsert(
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
                    'sort_order' => $order,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        DB::table('site_pages')->where('slug', 'like', 'static/guides/%')->delete();
    }

    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
