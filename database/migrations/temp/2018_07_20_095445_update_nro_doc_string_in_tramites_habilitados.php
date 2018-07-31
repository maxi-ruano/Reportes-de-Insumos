<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateNroDocStringInTramitesHabilitados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_habilitados', function (Blueprint $table) {
            $table->string('nro_doc',10)->change();
        });

        Schema::table('tramites_habilitados_log', function (Blueprint $table) {
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
