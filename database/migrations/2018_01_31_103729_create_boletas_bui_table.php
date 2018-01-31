<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoletasBuiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletas_bui', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_boleta');
            $table->string('nro_boleta');
            $table->string('cod_barras');
            $table->string('importe_total');
            $table->dateTime('fecha_pago');
            $table->string('lugar_pago');
            $table->string('medio_pago');
            $table->integer('tramite_a_iniciar_id');
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
        Schema::dropIfExists('boletas_bui');
    }
}
