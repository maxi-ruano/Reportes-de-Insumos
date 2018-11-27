<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSinalicWSIdSedeToSysConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_config')->insert(array(
            'name' => 'SinalicWS',
            'param' => 'id_sede',
            'value' => '1',
            'description' => 'ID Sede para el inicio en Sinalic desde el Precheck'
        ));
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_config')->where('name', 'SinalicWS')->where('param','id_sede')->delete();
    }
}
