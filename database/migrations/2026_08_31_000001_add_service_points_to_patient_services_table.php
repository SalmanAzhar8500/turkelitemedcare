<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('patient_services', 'service_points')) {
            Schema::table('patient_services', function (Blueprint $table): void {
                $table->json('service_points')->nullable()->after('bullet_points');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('patient_services', 'service_points')) {
            Schema::table('patient_services', function (Blueprint $table): void {
                $table->dropColumn('service_points');
            });
        }
    }
};
