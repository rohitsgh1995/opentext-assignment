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
        Schema::create('dependency_files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('status')->default('pending')->comment('pending, processing, failed, analyzed');
            $table->string('vulnerabilities')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependency_files');
    }
};
