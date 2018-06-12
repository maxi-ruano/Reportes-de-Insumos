<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidacionesPrecheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validaciones_precheck', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('validation_id');
            $table->integer('tramites_a_iniciar_id')->unsigned();
            $table->foreign('tramites_a_iniciar_id')
                  ->references('id')->on('tramites_a_iniciar');
            $table->boolean('validado')->dafault(false);
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
        Schema::dropIfExists('validaciones_precheck');
    }
}
