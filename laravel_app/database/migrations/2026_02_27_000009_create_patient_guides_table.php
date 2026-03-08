<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_guides', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parentid')->nullable();
            $table->enum('type', ['main', 'child', 'prechild'])->default('main');
            $table->timestamps();

            $table->foreign('parentid')->references('id')->on('patient_guides')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_guides');
    }
};
