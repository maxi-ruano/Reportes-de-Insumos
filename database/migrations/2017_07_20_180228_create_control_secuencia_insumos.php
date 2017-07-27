<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlSecuenciaInsumos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_secuencia_insumos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('insumo_ultimo');
            $table->integer('insumo_intento_insercion');
            $table->integer('sucursal');
            $table->integer('user_justificacion')->nullable();
            $table->boolean('justificado')->default(false);
            $table->text('justificacion')->nullable();
            $table->dateTime('fecha_justificacion')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_secuencia_insumos');
    }
}
