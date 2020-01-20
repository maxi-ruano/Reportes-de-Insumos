<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToTramitesHabilitadosMotivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_habilitados_motivos', function (Blueprint $table) {
            $table->datetime('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable()->unsigned()->default(null);
            $table->integer('limite')->nullable()->default(null);
            $table->integer('sucursal_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramites_habilitados_motivos', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('deleted_by');
            $table->dropColumn('limite');
            $table->dropColumn('sucursal_id');
        });
    }
}