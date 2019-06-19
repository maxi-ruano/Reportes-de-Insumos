<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDigitalizacionPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Se crea tabla digitalizacion para asociar los tramites que se procesaron
        //con el nuevo sistema - dghct.buenosaires.gob.ar - Datos Biometricos 
        if (!Schema::hasTable('digitalizacion')) {
            Schema::create('digitalizacion', function (Blueprint $table) {
                $table->integer('tramite_id')->unsigned()->nullable(false)->unique();
                $table->timestamp('creation_date')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('modification_date')->nullable();
                $table->foreign('tramite_id')->references('tramite_id')->on('tramites'); 
            });            
        }

        DB::table('permissions')->insert([
            ['id' => '46', 'name' => 'add_datos_biometricos', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->where('id', '46')->where('name','add_datos_biometricos')->delete();
        Schema::dropIfExists('digitalizacion');
    }
}
