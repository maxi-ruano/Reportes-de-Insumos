<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoletaSafitTable extends Migration
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
            $table->string('bop_id');
            $table->string('bop_codigo');
            $table->string('nro_doc');
            $table->string('tdc_id');
            $table->char('sexo', 1);
            $table->string('nombre');
            $table->string('apellido');
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
