<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['specialties', 'conditions', 'procedures', 'clinics', 'patient_services', 'patient_stories', 'site_pages'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->json('translations')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['specialties', 'conditions', 'procedures', 'clinics', 'patient_services', 'patient_stories', 'site_pages'] as $table) {
            Schema::table($table, function (Blueprint $table): void {
                $table->dropColumn('translations');
            });
        }
    }
};
