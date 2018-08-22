<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTramitesAIniciarIdToTramitesHabilitados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_habilitados', function (Blueprint $table) {
            $table->dropColumn('tramite_id');
            $table->integer('tramites_a_iniciar_id')->nullable();
        });

        Schema::table('tramites_habilitados_log', function (Blueprint $table) {
            $table->dropColumn('tramite_id');
            $table->integer('tramites_a_iniciar_id')->nullable();
        });
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
