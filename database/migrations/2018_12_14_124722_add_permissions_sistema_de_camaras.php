<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermissionsSistemaDeCamaras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert([
            [ 'id' => '20', 'guard_name'  =>  'web' , 'name'  =>  'Redactor de exámen', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            [ 'id' => '21', 'guard_name'  =>  'web' , 'name'  =>  'Inspector', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);
        
        DB::table('permissions')->insert([
            ['id' => '22', 'name' => 'view_camaras', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '23', 'name' => 'add_camaras', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '24', 'name' => 'edit_camaras', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '25', 'name' => 'delete_camaras', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '26', 'name' => 'view_camaras_sedes', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '27', 'name' => 'add_camaras_sedes', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '28', 'name' => 'edit_camaras_sedes', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '29', 'name' => 'delete_camaras_sedes', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '30', 'name' => 'view_configuracion_examen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '31', 'name' => 'add_configuracion_examen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '32', 'name' => 'edit_configuracion_exaLmen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '33', 'name' => 'delete_configuracion_examen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '34', 'name' => 'add_start_examen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ],
            ['id' => '35', 'name' => 'edit_start_examen', 'guard_name' => 'web', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s") ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permissions')->whereIn('id', ['22','23','24','25','26','27','28','29','30','31','32','33','34','35'])->delete();
        DB::table('roles')->whereIn('name', ['Redactor de exámen','Inspector'])->delete();
    }
}
