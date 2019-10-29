<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoletasSafitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boletas_safit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bop_id')->nullable(false);
            $table->string('bop_cb')->nullable();
            $table->float('bop_monto');
            $table->datetime('bop_fec_reg');
            $table->date('bop_fec_ven');
            $table->date('bop_fec_pag');
            $table->string('cem_id',3);
            $table->char('bop_estado',1);
            $table->char('est_id',1);
            $table->string('est_descrip',15);
            $table->integer('tipo_doc');
            $table->string('nro_doc',10);            
            $table->char('sexo',1);
            $table->boolean('certificado_virtual')->default(0);
            $table->boolean('infracciones')->default(0);
            $table->boolean('inhabilitaciones')->default(0);
            $table->boolean('reincidencias')->default(0);
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
        Schema::dropIfExists('boletas_safit');
    }
}
