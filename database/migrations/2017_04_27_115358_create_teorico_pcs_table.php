<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeoricoPcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teorico_pcs', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('ip')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->integer('estado')->unsigned();
            $table->boolean('activo')->default(true);
            $table->bigInteger('examen_id')->default(0);
            $table->string('name');
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
        Schema::dropIfExists('teorico_pcs');
    }
}
