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
            $table->string('bopID');
            $table->string('bopCodigo');
            $table->string('nroDoc');
            $table->string('tdcID');
            $table->string('sexo');
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
