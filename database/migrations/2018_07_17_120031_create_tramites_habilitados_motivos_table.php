<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesHabilitadosMotivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tramites_habilitados_motivos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '1',
            'description' => 'RETOMA TURNOS',
            'created_at' => date(),
            'updated_at' => date()
        ));
        
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '2',
            'description' => 'RETOMA POR ESTUDIOS',
            'created_at' => date(),
            'updated_at' => date()
        ));
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '3',
            'description' => 'MAYOR DE 65',
            'created_at' => date(),
            'updated_at' => date()
        ));
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '4',
            'description' => 'DISCAPACITADOS',
            'created_at' => date(),
            'updated_at' => date()
        ));
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '5',
            'description' => 'EMBARAZADAS',
            'created_at' => date(),
            'updated_at' => date()
        ));
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '6',
            'description' => 'TAXIS',
            'created_at' => date(),
            'updated_at' => date()
        ));
        DB::table('tramites_habilitados_motivos')->insert(array(
            'id' => '7',
            'description' => 'OTRO',
            'created_at' => date(),
            'updated_at' => date()
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tramites_habilitados_motivos');
    }
}
