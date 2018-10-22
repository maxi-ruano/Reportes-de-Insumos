<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use App\Role;
use App\TramitesHabilitadosMotivos;

class RoleMotivosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->show($request);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $role_id = $request->role_id;

            //Borrar e insertar en role_motivos_sel (select)
            $role_motivos_sel = \DB::table('role_motivos_sel')->where('role_id',$role_id);
            $role_motivos_sel->delete();

            if(isset($request->motivos_select)){
                $data = [];
                foreach ($request->motivos_select as $motivo) {
                    $data[] = [
                        'role_id'  => $role_id,
                        'motivo_id' => $motivo
                    ];
                }

                \DB::table('role_motivos_sel')->insert($data);
            }

            //Borrar e insertar en role_motivos_lis (listado Tramite Habilitados)
            $role_motivos_lis = \DB::table('role_motivos_lis')->where('role_id',$request->role_id);
            $role_motivos_lis->delete();

            if(isset($request->motivos_list)){
                $data = [];
                foreach ($request->motivos_list as $motivo) {
                    $data[] = [
                        'role_id'  => $role_id,
                        'motivo_id' => $motivo
                    ];
                }

                \DB::table('role_motivos_lis')->insert($data);
            }


            Flash::success('Se ha actualizado correctamente');
            return $this->show($request);
        }
        catch(Exception $e){   
            return "Fatal error - ".$e->getMessage();
        }  
    }

    public function show($request){
       
        $roles = Role::orderBy('name','ASC')->select('id','name')->pluck('name','id');
        $motivos = TramitesHabilitadosMotivos::orderBy('description','ASC')->select('id','description')->pluck('description','id');
        $motivos_select = '';
        $motivos_list = '';

        
        if(isset($request->role_id)){
            $motivos_select = \DB::table('role_motivos_sel')->where('role_id',$request->role_id);
            $motivos_list = \DB::table('role_motivos_lis')->where('role_id',$request->role_id);
        }
        
        return view('rolemotivos.form')->with('roles', $roles)
                                            ->with('motivos', $motivos)
                                            ->with('motivos_select', $motivos_select)
                                            ->with('motivos_list',$motivos_list);
    }
}