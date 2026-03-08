<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->string('hero_video')->nullable()->after('hero_image');
            $table->json('about_data')->nullable()->after('hero_items');
            $table->json('contact_data')->nullable()->after('about_data');
            $table->json('services_data')->nullable()->after('contact_data');
            $table->json('whatwedo_data')->nullable()->after('services_data');
            $table->json('causes_data')->nullable()->after('whatwedo_data');
        });
    }

    public function down(): void
    {
        Schema::table('home_settings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_video',
                'about_data',
                'contact_data',
                'services_data',
                'whatwedo_data',
                'causes_data',
            ]);
        });
    }
};
