<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSigeciPrestacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigeci_prestaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prestacion_id');
            $table->integer('tipo_tramite_ansv_id');
            $table->string('descripcion')->nullable();
            $table->string('ws');
            $table->timestamps();
        });

        DB::table('sigeci_prestaciones')->insert(array(
          'prestacion_id' => '1356',
          'tipo_tramite_ansv_id' => '2',
          'descripcion' => 'RENOVACION - Renovación de Licencia',
          'ws' => 'IniciarTramiteRenovarLicencia'
        ));

        DB::table('sigeci_prestaciones')->insert(array(
          'prestacion_id' => '1341',
          'tipo_tramite_ansv_id' => '2',
          'descripcion' => 'RENOVACION - Renovación de Licencia',
          'ws' => 'IniciarTramiteRenovarLicencia'
        ));

        DB::table('sigeci_prestaciones')->insert(array(
          'prestacion_id' => '1342',
          'tipo_tramite_ansv_id' => '1',
          'descripcion' => 'OTORGAMIENTO - Otorgamiento de Licencia de Conducir',
          'ws' => 'IniciarTramiteNuevaLicencia'
        ));

        DB::table('sigeci_prestaciones')->insert(array(
          'prestacion_id' => '1344',
          'tipo_tramite_ansv_id' => '6',
          'descripcion' => 'RENOVACION_AMPLIACION - Renovación de Licencia con ampliación',
          'ws' => 'IniciarTramiteRenovacionConAmpliacion'
        ));

        DB::table('sigeci_prestaciones')->insert(array(
          'prestacion_id' => '1343',
          'tipo_tramite_ansv_id' => '2',
          'descripcion' => 'RENOVACION - Renovación de Licencia',
          'ws' => 'IniciarTramiteRenovarLicencia'
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sigeci_prestaciones');
    }
}
