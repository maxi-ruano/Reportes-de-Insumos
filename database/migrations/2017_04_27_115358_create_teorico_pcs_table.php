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
            $table->integer('ip')->unsigned();
            $table->integer('sucursal_id')->unsigned();
            $table->foreign('sucursal_id')
                ->references('id')->on('sys_multivalue');
            $table->integer('estado')->unsigned();
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
