<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmisoresSafitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ansv_cel_expedidor', function (Blueprint $table) {
          if (!Schema::hasColumn('ansv_cel_expedidor', 'safit_cem_id')) {
            $table->string('safit_cem_id', 3)->nullable();
          }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ansv_cel_expedidor', function (Blueprint $table) {
            //
        });
    }
}
