<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharlaVirtualTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charla_virtual', function (Blueprint $table) {
	    $table->increments('id');
	    $table->string('codigo')->unique();
	    $table->string('nro_doc',10);
	    $table->string('apellido');
	    $table->string('nombre');
	    $table->string('sexo');
	    $table->string('email');
	    $table->boolean('aprobado');
	    $table->date('fecha_nacimiento')->nullable();
	    $table->date('fecha_charla')->required();
	    $table->date('fecha_aprobado')->nullable();
	    $table->date('fecha_vencimiento')->nullable();
	    $table->string('categoria');
            $table->text('response_ws');
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
        Schema::dropIfExists('charla_virtual');
    }
}
