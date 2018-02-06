<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesAInicarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites_a_inicar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apellido');
            $table->string('nombre');
            $table->string('tipo_doc');
            $table->string('nro_doc');
            $table->integer('tipo_tramite_sigeci');
            $table->string('nacionalidad');
            $table->string('sexo')->nullable();
            $table->string('bop_cb')->nullable();
            $table->string('bop_monto')->nullable();
            $table->string('bop_fec_pag')->nullable();
            $table->string('bop_id')->nullable();
            $table->string('cem_id')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('estado')->default(1); // 1 = pendiente - 2 = completado, listo para enviar - 3 enviado todo ok - 4 enviado errores
            $table->integer('sigeci_idcita');
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
        Schema::dropIfExists('tramites_a_inicar');
    }
}
