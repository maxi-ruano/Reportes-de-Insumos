<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsMotivos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::table('permissions')->insert([
            ['id' => '18', 'name' => 'view_tramites_habilitados_motivos', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '19', 'name' => 'add_tramites_habilitados_motivos', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '20', 'name' => 'edit_tramites_habilitados_motivos', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '21', 'name' => 'delete_tramites_habilitados_motivos', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);

        //Establecer todos los permisos al rol: Admin
        DB::table('role_has_permissions')->insert([
            ['role_id' => '1', 'permission_id' => '18'],
            ['role_id' => '1', 'permission_id' => '19'],
            ['role_id' => '1', 'permission_id' => '20'],
            ['role_id' => '1', 'permission_id' => '21']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::table('permissions')->delete();
    }
}
