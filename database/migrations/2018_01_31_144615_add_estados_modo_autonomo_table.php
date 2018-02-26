<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadosModoAutonomoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'se trae de sigeci y se inserta en tramites_a_iniciar',
          'text_id' => 'INICIO',
          'id' => '1',
          'rowid' => '3919'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Completar datos de tramite, desde Boleta Safit',
          'text_id' => 'SAFIT',
          'id' => '2',
          'rowid' => '3920'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Se emitio la boleta de safit',
          'text_id' => 'EMISION_BOLETA_SAFIT',
          'id' => '3',
          'rowid' => '3921'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Se verifico el Libre Deuda',
          'text_id' => 'LIBRE_DEUDA',
          'id' => '4',
          'rowid' => '3922'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Se verifico el Boleta BUI',
          'text_id' => 'BUI',
          'id' => '5',
          'rowid' => '3923'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Se inicio el tramite en Sinalic',
          'text_id' => 'INICIO_EN_SINALIC',
          'id' => '6',
          'rowid' => '3924'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_multivalue', function (Blueprint $table) {
            //
        });
    }
}
