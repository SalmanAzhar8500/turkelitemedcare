<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->json('testimonials_data')->nullable()->after('howitwork_data');
            $table->json('gallery_data')->nullable()->after('testimonials_data');
            $table->json('lasthope_data')->nullable()->after('gallery_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn(['testimonials_data', 'gallery_data', 'lasthope_data']);
        });
    }
};
