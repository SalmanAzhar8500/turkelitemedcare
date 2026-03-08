<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_button_text_secondary')->nullable();
            $table->string('hero_button_link_secondary')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_video_url')->nullable();
            $table->json('hero_items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_settings');
    }
};
