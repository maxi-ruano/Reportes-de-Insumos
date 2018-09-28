<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstantesLimiteTramitesHabilitados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_multivalue')->insert([
            
            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP2',
            'text_id' => 'LIMITE_TH_SUCU_40',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP4',
            'text_id' => 'LIMITE_TH_SUCU_120',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP5',
            'text_id' => 'LIMITE_TH_SUCU_110',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP12',
            'text_id' => 'LIMITE_TH_SUCU_140',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP13',
            'text_id' => 'LIMITE_TH_SUCU_60',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP14',
            'text_id' => 'LIMITE_TH_SUCU_50',
            'id' => '5'],

            ['type' => 'CONS',
            'description' => 'Limite Tramites Habilitados de CGP15',
            'text_id' => 'LIMITE_TH_SUCU_70',
            'id' => '5']
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
