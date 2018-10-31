<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesLimitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_limites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('sucursal')->nullable();
            $table->integer('motivo_id')->nullable();
            $table->integer('limite');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->foreign('role_id')
                ->references('id')->on('roles'); 
            $table->foreign('motivo_id')
                ->references('id')->on('tramites_habilitados_motivos');      
        });

        //Establecer limites para el Rol COMUNA por SUCURSAL (CGP2, CGP4, CGP5, CGP12, CGP13, CGP14, CGP15)
        DB::table('roles_limites')->insert([
            ['role_id' => '8', 'sucursal' => 40, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 50, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 60, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 70, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 110, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 120, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '8', 'sucursal' => 140, 'motivo_id' => null, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);

        //Obtener id del motivo CORTESIA y asignar al rol Jefe Sede
        $motivo_id = \DB::table('tramites_habilitados_motivos')->where('description','CORTESIA')->pluck('id')->first();
        if(!$motivo_id)
           $motivo_id = DB::table('tramites_habilitados_motivos')->insertGetId(['description' => 'CORTESIA', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]);
        
        //Establecer limites para el Rol JEFE SEDE por SUCURSAL (CGP2, CGP4, CGP5, CGP12, CGP13, CGP14, CGP15, La Nueva, CHACABUCO, )
        DB::table('roles_limites')->insert([
            ['role_id' => '9', 'sucursal' => 40, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 50, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 60, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 70, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 90, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 110, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 120, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 130, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 140, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['role_id' => '9', 'sucursal' => 160, 'motivo_id' => $motivo_id, 'limite' => 5, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);

        //se elimina de sys_multivalue las CONS que se usaban para los Limites
        DB::table('sys_multivalue')->where('type', 'CONS')->whereRaw("description like 'Limite%'")->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_limites');
    }
}
