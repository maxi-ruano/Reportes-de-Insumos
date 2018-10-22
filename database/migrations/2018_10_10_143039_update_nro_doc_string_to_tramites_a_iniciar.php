<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNroDocStringToTramitesAIniciar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_a_iniciar', function (Blueprint $table) {
            $table->string('nro_doc',10)->change();
        });
        
        Schema::table('tramites_a_iniciar_log', function (Blueprint $table) {
            $table->string('nro_doc',10)->change();
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
