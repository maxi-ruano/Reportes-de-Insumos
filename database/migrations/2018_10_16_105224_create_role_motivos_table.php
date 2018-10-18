<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleMotivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_motivos_sel', function (Blueprint $table) {
            $table->integer('role_id');
            $table->foreign('role_id')
                  ->references('id')->on('roles');       
            $table->integer('motivo_id');
            $table->foreign('motivo_id')
                ->references('id')->on('tramites_habilitados_motivos');      
        });

        Schema::create('role_motivos_lis', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('motivo_id');
            $table->foreign('role_id')
                  ->references('id')->on('roles');      
            $table->foreign('motivo_id')
                ->references('id')->on('tramites_habilitados_motivos');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_motivos_sel');
        Schema::dropIfExists('role_motivos_lis');
    }
}
