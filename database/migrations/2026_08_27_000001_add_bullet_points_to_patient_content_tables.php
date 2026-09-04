<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_services', function (Blueprint $table): void {
            $table->json('bullet_points')->nullable()->after('content');
        });

        Schema::table('patient_stories', function (Blueprint $table): void {
            $table->json('bullet_points')->nullable()->after('content');
        });

        DB::table('patient_services')->update([
            'bullet_points' => json_encode([
                'Planning around the confirmed treatment timetable',
                'One coordination contact from arrival to return home',
                'Support tailored to the patient journey',
            ]),
        ]);

        DB::table('patient_stories')->update([
            'bullet_points' => json_encode([
                'Published patient journey',
                'Research and planning example',
                'Independent provider assessment required',
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('patient_stories', function (Blueprint $table): void {
            $table->dropColumn('bullet_points');
        });

        Schema::table('patient_services', function (Blueprint $table): void {
            $table->dropColumn('bullet_points');
        });
    }
};
