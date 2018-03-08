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
        DB::table('ansv_cel_expedidor')->where('sucursal_id','1')->update(['safit_cem_id' => '1']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','2')->update(['safit_cem_id' => '']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','3')->update(['safit_cem_id' => '']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','10')->update(['safit_cem_id' => '2']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','20')->update(['safit_cem_id' => '15']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','30')->update(['safit_cem_id' => '6']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','40')->update(['safit_cem_id' => '3']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','50')->update(['safit_cem_id' => '4']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','60')->update(['safit_cem_id' => '7']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','70')->update(['safit_cem_id' => '5']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','80')->update(['safit_cem_id' => '8']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','90')->update(['safit_cem_id' => '9']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','100')->update(['safit_cem_id' => '10']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','101')->update(['safit_cem_id' => '']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','103')->update(['safit_cem_id' => '11']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','110')->update(['safit_cem_id' => '12']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','120')->update(['safit_cem_id' => '13']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','130')->update(['safit_cem_id' => '14']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','140')->update(['safit_cem_id' => '16']);
        DB::table('ansv_cel_expedidor')->where('sucursal_id','150')->update(['safit_cem_id' => '']);
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
