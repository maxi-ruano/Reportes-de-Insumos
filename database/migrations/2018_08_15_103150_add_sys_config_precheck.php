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
            'value' => 'weporjgsdf41654',
            'description' => 'Password usuario conexion webservice SAFIT',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'SafitWS',
            'param' => 'userName',
            'value' => '000016',
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
            'value' => 'https://www.safit.com.ar/service/s_001.php?wsdl',
            'description' => 'URL conexion webservice SAFIT',
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


        //WS BUI 
        DB::table('sys_config')->insert(array(
            'name' => 'BuiWS',
            'param' => 'userPass',
            'value' => 'lic189',
            'description' => 'Password usuario conexion webservice BUI',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'BuiWS',
            'param' => 'userName',
            'value' => 'licenciasws',
            'description' => 'Usuario conexion webservice BUI',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'BuiWS',
            'param' => 'ws_url',
            'value' => 'http://10.73.100.42:6748/service/api/BUI/GetResumenBoletasPagas',
            'description' => 'URL conexion webservice BUI',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'BuiWS',
            'param' => 'enabled',
            'value' => 'true',
            'description' => 'Habilitar Deshabitar WS',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));
        

        /*//WS LibreDeuda - Ya se encuenta en BD
        DB::table('sys_config')->insert(array(
            'name' => 'LibreDeudaWS',
            'param' => 'userPass',
            'value' => 'LICWEB',
            'description' => 'Password usuario conexion webservice LIBRE DEUDA',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'LibreDeudaWS',
            'param' => 'userName',
            'value' => 'LICENCIAS01',
            'description' => 'Usuario conexion webservice LIBRE DEUDA',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'LibreDeudaWS',
            'param' => 'wsdl_url',
            'value' => 'https://tcaba2.dgai.com.ar/LicenciaWS/LicenciaWS?',
            'description' => 'URL conexion webservice LIBRE DEUDA',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));

        DB::table('sys_config')->insert(array(
            'name' => 'LibreDeudaWS',
            'param' => 'enabled',
            'value' => 'true',
            'description' => 'Habilitar Deshabitar WS',
            'created_by' => '2828',
            'modified_by' => '2829',
            'creation_date' => null,
            'modification_date' => date("Y-m-d H:i:s")
        ));
        */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_config')->where('name', 'SafitWS')->delete();
        DB::table('sys_config')->where('name', 'BuiWS')->delete();
        //DB::table('sys_config')->where('name', 'LibreDeudaWS')->delete();
    }
}
