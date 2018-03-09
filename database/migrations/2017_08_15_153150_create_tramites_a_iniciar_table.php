<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesAIniciarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites_a_iniciar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apellido');
            $table->string('nombre');
            $table->integer('tipo_doc');
            $table->integer('nro_doc');
            $table->integer('nacionalidad');
            $table->string('sexo')->nullable();
            $table->string('bop_cb')->nullable();
            $table->string('bop_monto')->nullable();
            $table->string('bop_fec_pag')->nullable();
            $table->string('bop_id')->nullable();
            $table->string('cem_id')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->integer('estado')->default(1); // 1 = pendiente - 2 = completado, listo para enviar - 3 enviado todo ok - 4 enviado errores
            $table->integer('sigeci_idcita');
            $table->integer('tramite_sinalic_id')->nullable();
            $table->integer('tipo_tramite')->nullable();
            $table->text('response_ws')->nullable();
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
        Schema::dropIfExists('tramites_a_iniciar');
    }
}
