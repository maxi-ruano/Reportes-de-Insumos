<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdDgevylTramitesAIniciar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_a_iniciar', function (Blueprint $table) {
            $table->integer('tramite_dgevyl_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramites_a_iniciar', function (Blueprint $table) {
            $table->dropColumn('tramite_dgevyl_id');
        });
    }
}
