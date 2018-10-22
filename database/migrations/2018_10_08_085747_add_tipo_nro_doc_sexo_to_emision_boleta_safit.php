<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoNroDocSexoToEmisionBoletaSafit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emision_boleta_safit', function (Blueprint $table) {
            $table->integer('tipo_doc')->nullable();
            $table->integer('nro_doc')->nullable();            
            $table->character('sexo',1)>nullable();
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
