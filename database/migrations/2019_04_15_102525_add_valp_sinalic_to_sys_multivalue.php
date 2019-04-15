<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValpSinalicToSysMultivalue extends Migration
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
            'description' => 'SINALIC',
            'text_id' => 'SINALIC',
            'id' => '7',
            'rowid' => '3952'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('sys_multivalue')->whereIn('rowid', '3952')->delete();
    }
}
