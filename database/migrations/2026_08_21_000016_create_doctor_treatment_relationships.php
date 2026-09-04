<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_specialty', function (Blueprint $table): void {
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('specialty_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['doctor_id', 'specialty_id']);
        });

        Schema::create('doctor_procedure', function (Blueprint $table): void {
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedure_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['doctor_id', 'procedure_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_procedure');
        Schema::dropIfExists('doctor_specialty');
    }
};
