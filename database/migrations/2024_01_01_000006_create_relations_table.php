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
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_from_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('module_to_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('column_id')->constrained()->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->string('input_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};