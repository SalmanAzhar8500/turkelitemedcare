<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['site_pages', 'specialties', 'procedures', 'clinics', 'patient_services', 'patient_stories'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->string('seo_title', 60)->nullable();
                $table->string('seo_description', 160)->nullable();
                $table->string('seo_keywords', 500)->nullable();
                $table->string('seo_robots', 100)->default('index,follow');
            });
        }
    }

    public function down(): void
    {
        foreach (['site_pages', 'specialties', 'procedures', 'clinics', 'patient_services', 'patient_stories'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'seo_robots']);
            });
        }
    }
};
