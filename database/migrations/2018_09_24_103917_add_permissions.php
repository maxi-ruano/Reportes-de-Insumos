<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        DB::table('permissions')->insert([
            ['id' => '1', 'name' => 'view_users', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '2', 'name' => 'add_users', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '3', 'name' => 'edit_users', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '4', 'name' => 'delete_users', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);

        DB::table('permissions')->insert([
            ['id' => '5', 'name' => 'view_roles', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '6', 'name' => 'add_roles', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '7', 'name' => 'edit_roles', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '8', 'name' => 'delete_roles', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);

        DB::table('permissions')->insert([
            ['id' => '9', 'name' => 'view_all_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '10', 'name' => 'add_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '11', 'name' => 'edit_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '12', 'name' => 'delete_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '13', 'name' => 'view_self_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '14', 'name' => 'habilita_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '15', 'name' => 'enable_fecha_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '16', 'name' => 'enable_sede_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '17', 'name' => 'view_sede_tramites_habilitados', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);


        //Establecer todos los permisos al rol: Admin
        DB::table('role_has_permissions')->insert([
            ['role_id' => '1', 'permission_id' => '1'],
            ['role_id' => '1', 'permission_id' => '2'],
            ['role_id' => '1', 'permission_id' => '3'],
            ['role_id' => '1', 'permission_id' => '4'],
            ['role_id' => '1', 'permission_id' => '5'],
            ['role_id' => '1', 'permission_id' => '6'],
            ['role_id' => '1', 'permission_id' => '7'],
            ['role_id' => '1', 'permission_id' => '8'],
            ['role_id' => '1', 'permission_id' => '9'],
            ['role_id' => '1', 'permission_id' => '10'],
            ['role_id' => '1', 'permission_id' => '11'],
            ['role_id' => '1', 'permission_id' => '12'],
            ['role_id' => '1', 'permission_id' => '14'],
            ['role_id' => '1', 'permission_id' => '15'],
            ['role_id' => '1', 'permission_id' => '16']
        ]);
        
        //Permisos a: Operador (Informes - solo add_tramites_habilitados)
        DB::table('role_has_permissions')->insert([
            ['role_id' => '2', 'permission_id' => '10']
        ]);

        //Permisos a: Soporte de Procesos (view_all_tramites_abilitados, habilita_tramites_abilitados, view_sede_tramites_habilitados)
        DB::table('role_has_permissions')->insert([
            ['role_id' => '3', 'permission_id' => '9'],
            ['role_id' => '3', 'permission_id' => '14'],
            ['role_id' => '3', 'permission_id' => '17']
        ]);

        //Permisos a: Administrador de Tramites Habilitados
        DB::table('role_has_permissions')->insert([
            ['role_id' => '4', 'permission_id' => '9'],
            ['role_id' => '4', 'permission_id' => '10'],
            ['role_id' => '4', 'permission_id' => '11'],
            ['role_id' => '4', 'permission_id' => '12'],
            ['role_id' => '4', 'permission_id' => '14'],
            ['role_id' => '4', 'permission_id' => '15'],
            ['role_id' => '4', 'permission_id' => '16']
        ]);
        
        //Permisos a: Auditoria (view_all_tramites_habilitados)
        DB::table('role_has_permissions')->insert([
            ['role_id' => '5', 'permission_id' => '9']
        ]);

        //Permisos a: Legales
        DB::table('role_has_permissions')->insert([
            ['role_id' => '6', 'permission_id' => '10'],
            ['role_id' => '6', 'permission_id' => '13'],
            ['role_id' => '6', 'permission_id' => '14']
        ]);

        //Permisos a: Direccion 
        DB::table('role_has_permissions')->insert([
            ['role_id' => '7', 'permission_id' => '10'],
            ['role_id' => '7', 'permission_id' => '13'],
            ['role_id' => '7', 'permission_id' => '15'],
            ['role_id' => '7', 'permission_id' => '16']
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->delete();
    }
}
