<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->id();

            /*
             * the primary relation module
             */
            $table->unsignedBigInteger('module_from_id')->unsigned();

            $table->foreign('module_from_id')->references('id')->on('modules')->onDelete('cascade')->obUpdate('cascade');

            /*
             * the foreign relation module
             */

            $table->unsignedBigInteger('module_to_id')->unsigned();

            /*
             * id of column that will show in select menu
             * or checkbox
             */
            $table->foreignId('column_id')->unsigned();
            $table->string('type')->nullable();
            $table->string('input_type')->nullable();
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
        Schema::drop('relations');
    }
};
