<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEstadoTramitesAIniciar extends Migration
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
            'description' => 'Se vencio el turno',
            'text_id' => 'TURNO_VENCIDO',
            'id' => '8',
            'rowid' => '3937'
        ));

        DB::table('sys_multivalue')->insert(array(
            'type' => 'CONS',
            'description' => 'Dias de valiedez del turno',
            'text_id' => 'DIAS_VALIDEZ_TURNO',
            'id' => '16',
            'rowid' => '3938'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_multivalue')->whereIn('rowid', '3937')->delete();
        DB::table('sys_multivalue')->whereIn('rowid', '3938')->delete();
    }
}
