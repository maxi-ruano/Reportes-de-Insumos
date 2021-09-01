<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidacionCharlaVirtualToSysMultivalue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('sys_multivalue')->insert(array(
            'type' => 'VALP',
            'description' => 'CHARLA VIRTUAL',
            'text_id' => 'CHARLA_VIRTUAL',
            'id' => '6'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_multivalue')->where('text_id','CHARLA_VIRTUAL')->where('id','6')->delete();
    }
}
