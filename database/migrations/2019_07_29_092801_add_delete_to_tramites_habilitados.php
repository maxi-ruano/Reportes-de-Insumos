<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeleteToTramitesHabilitados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tramites_habilitados', function (Blueprint $table) {
            $table->boolean('deleted')->default(0);
            $table->integer('deleted_by')->nullable()->unsigned();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tramites_habilitados', function (Blueprint $table) {
            $table->dropColumn('deleted');
            $table->dropColumn('deleted_by');
        });
    }
}
