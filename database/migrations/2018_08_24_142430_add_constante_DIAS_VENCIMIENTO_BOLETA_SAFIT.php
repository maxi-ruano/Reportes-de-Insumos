<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstanteDIASVENCIMIENTOBOLETASAFIT extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_multivalue')->insert(array(
            'type' => 'CONS',
            'description' => 'Cantidad Dias Vencimiento Boleta Safit',
            'text_id' => 'DIAS_VENCIMIENTO_BOLETA_SAFIT',
            'id' => '90',
            'rowid' => '3940'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_multivalue')->whereIn('rowid', '3940')->delete();
    }
}
