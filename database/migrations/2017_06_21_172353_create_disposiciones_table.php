<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisposicionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposiciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('descripcion', 500);
            $table->string('estado', 10)->default('pendiente');
            $table->integer('tramite_id')->unsigned();
            $table->foreign('tramite_id')
                  ->references('tramite_id')->on('tramites');
            $table->integer('sys_user_id_otorgante')->nullable()->unsigned();
            $table->foreign('sys_user_id_otorgante')
                  ->references('id')->on('sys_users');
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
        Schema::dropIfExists('disposiciones');
    }
}
