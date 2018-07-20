<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoDocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_doc', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_dgevyl');
            $table->integer('id_sigeci');
            $table->timestamps();
        });
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '1',
            'id_sigeci' => '1',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '1',
            'id_sigeci' => '2',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '1',
            'id_sigeci' => '3',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '1',
            'id_sigeci' => '4',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '1',
            'id_sigeci' => '5',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
        DB::table('tipo_doc')->insert(array(
            'id_dgevyl' => '4',
            'id_sigeci' => '6',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_doc');
    }
}
