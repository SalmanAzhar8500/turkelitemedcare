<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_inquiries', function (Blueprint $table): void {
            $table->id();
            $table->string('type')->default('contact');
            $table->string('page_slug')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_language')->nullable();
            $table->string('preferred_time')->nullable();
            $table->string('status')->default('new');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_inquiries');
    }
};
