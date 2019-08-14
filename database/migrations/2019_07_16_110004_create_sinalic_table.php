<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSinalicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sinalic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero_tramite_ansv');
            $table->integer('sucursal_id');
            $table->text('request_ws')->nullable();
            $table->text('response_ws')->nullable();
            $table->integer('tramites_a_iniciar_id');
            $table ->integer('tipo_tramite');
            $table->boolean('anulado');
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
        Schema::dropIfExists('sinalic');
    }
}
