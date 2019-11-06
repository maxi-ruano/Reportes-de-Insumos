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
            $table->integer('tramites_a_iniciar_id');
            $table->string('nro_doc',10);
            $table->date('fecha_charla');
            $table->date('fecha_vencimiento');
            $table->boolean('aprobado')->nullable();
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
        Schema::dropIfExists('charla_virtual');
    }
}
