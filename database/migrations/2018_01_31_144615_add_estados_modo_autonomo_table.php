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
          'type' => 'VALP',
          'description' => 'EMISION BOLETA SAFIT',
          'text_id' => 'EMISION_BOLETA_SAFIT',
          'id' => '1',
          'rowid' => '3921'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'VALP',
          'description' => 'LIBRE DEUDA',
          'text_id' => 'LIBRE_DEUDA',
          'id' => '2',
          'rowid' => '3922'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'VALP',
          'description' => 'BUI',
          'text_id' => 'BUI',
          'id' => '3',
          'rowid' => '3923'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Validaciones Precheck',
          'text_id' => 'VALIDACIONES',
          'id' => '3',
          'rowid' => '3925'
        ));

        DB::table('sys_multivalue')->insert(array(
          'type' => 'AUTO',
          'description' => 'Se inicio el tramite en Sinalic',
          'text_id' => 'INICIO_EN_SINALIC',
          'id' => '4',
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
