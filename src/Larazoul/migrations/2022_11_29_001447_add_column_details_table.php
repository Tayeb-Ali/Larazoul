<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('column_details', function (Blueprint $table) {
            $table->id();
            $table->text('validation')->nullable();
            $table->text('custom_validation')->nullable();
            $table->char('transformer')->nullable();
            $table->char('admin_crud')->nullable();
            $table->char('site_crud')->nullable();
            $table->char('admin_filter')->nullable();
            $table->char('website_filter')->nullable();
            $table->char('html_type', 50);
            $table->foreignId('module_id')->unsigned();
            $table->foreignId('column_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('column_details');
    }
};
