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
        Schema::create('column_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id')->constrained()->cascadeOnDelete();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('html_type')->nullable();
            $table->text('validation')->nullable();
            $table->text('custom_validation')->nullable();
            $table->text('transformer')->nullable();
            $table->boolean('admin_crud')->default(true);
            $table->boolean('site_crud')->default(true);
            $table->boolean('admin_filter')->default(false);
            $table->boolean('website_filter')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('column_details');
    }
};