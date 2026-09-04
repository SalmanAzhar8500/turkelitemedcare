<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('procedures')->update(['condition_id' => null]);
        DB::table('conditions')->delete();
    }

    public function down(): void
    {
        // Deleted condition content cannot be reconstructed automatically.
    }
};
