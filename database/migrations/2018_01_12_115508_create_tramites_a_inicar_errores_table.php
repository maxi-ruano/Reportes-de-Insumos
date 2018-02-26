<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesAInicarErroresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites_a_iniciar_errores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->integer('estado_error');
            $table->text('request_ws')->nullable();
            $table->text('response_ws')->nullable();
            $table->integer('tramites_a_iniciar_id')->unsigned();
            $table->foreign('tramites_a_iniciar_id')
                  ->references('id')->on('tramites_a_iniciar');
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
        Schema::dropIfExists('tramites_a_iniciar_errores');
    }
}
