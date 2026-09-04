<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->string('profile_highlight')->nullable()->after('content');
            $table->unsignedTinyInteger('experience_years')->nullable()->after('profile_highlight');
            $table->string('languages')->nullable()->after('experience_years');
            $table->string('consultation_method')->nullable()->after('languages');
            $table->string('response_time')->nullable()->after('consultation_method');
            $table->text('verification_notes')->nullable()->after('response_time');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table): void {
            $table->dropColumn(['profile_highlight', 'experience_years', 'languages', 'consultation_method', 'response_time', 'verification_notes']);
        });
    }
};
