<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_guide_id')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->enum('type', ['analysis', 'booking']);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('appointment_date')->nullable();
            $table->text('message')->nullable();
            $table->string('head_image')->nullable();
            $table->timestamps();

            $table->foreign('patient_guide_id')->references('id')->on('patient_guides')->nullOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
