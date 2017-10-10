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
            $table->string('nombre');
            $table->string('tipo_doc');
            $table->string('nacionalidad');
            $table->string('sexo');
            $table->string('estado');
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
