<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSysConfigPrecheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'userPass',
            'value' => '1sdfr45g347dkf8gs0d',
            'description' => 'Password usuario conexion webservice SAFIT',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'userName',
            'value' => '000004',
            'description' => 'Usuario conexion webservice SAFIT',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'userHash',
            'value' => 'e10adc3949ba59abbe56e057f20f883e',
            'description' => 'Usuario Hash conexion webservice SAFIT',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'ws_url',
            'value' => 'https://testing.safit.com.ar/service/s_001.php?wsdl',
            'description' => 'Usuario Hash conexion webservice SAFIT',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'munID',
            'value' => '1',
            'description' => 'Municipio ID',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'ingID',
            'value' => '0',
            'description' => 'Ingreso ID',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'enabled',
            'value' => 'true',
            'description' => 'Habilitar Deshabitar WS',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_config')->where('name', 'SafitWS')->delete();
    }
}
