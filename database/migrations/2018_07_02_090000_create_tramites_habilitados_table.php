<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesHabilitadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites_habilitados', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->string('apellido');
            $table->string('nombre');
            $table->integer('tipo_doc');
            $table->integer('nro_doc');
            $table->integer('pais');
            $table->integer('nro_doc');
            $table->integer('user_id')->nullable()->unsigned();
            $table->boolean('habilitado')->nullable()->default(true);
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
        Schema::dropIfExists('tramites_habilitados');
    }
}
