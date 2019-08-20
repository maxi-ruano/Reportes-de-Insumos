<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsModificacionesDelSistema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['id' => '47', 'name' => 'anular_comprobantes_precheck', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '48', 'name' => 'anular_examen_teorico', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '49', 'name' => 'cambiar_pcs_examen_teorico', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
        ]);

        //Establecer todos los permisos al rol: Admin
        DB::table('role_has_permissions')->insert([
            ['role_id' => '1', 'permission_id' => '47'],
            ['role_id' => '1', 'permission_id' => '48'],
            ['role_id' => '1', 'permission_id' => '49']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->where('id', '47')->delete();
        DB::table('permissions')->where('id', '48')->delete();
        DB::table('permissions')->where('id', '49')->delete();
    }
}
