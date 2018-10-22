<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTramitesHabilitadosObservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Crear tabla 
        Schema::create('tramites_habilitados_observaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tramite_habilitado_id');
            $table->string('observacion');
            $table->timestamps();
        });

        //Migracion de los datos
        DB::insert("INSERT INTO tramites_habilitados_observaciones (tramite_habilitado_id, observacion) 
                    (SELECT id as tramite_habilitado_id, nro_expediente as observacion 
                        FROM tramites_habilitados WHERE nro_expediente != '')");

        //Borrar columnas
        Schema::table('tramites_habilitados', function (Blueprint $table) {
            $table->dropColumn('nro_expediente');
            $table->dropColumn('sigeci_idcita');
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
            $table->string('nro_expediente')->nullable();
            $table->integer('sigeci_idcita')->nullable();
        });

        DB::update("UPDATE tramites_habilitados as th
                    SET nro_expediente = tho.observacion
                    FROM tramites_habilitados_observaciones as tho 
                    WHERE th.id = tho.tramite_habilitado_id
                   ");
    
        Schema::dropIfExists('tramites_habilitados_observaciones');
    }
}