<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use App\TramitesHabilitadosMotivos;
use App\SysMultivalue;

class TramitesHabilitadosMotivosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TramitesHabilitadosMotivos::whereNull('deleted_at')
                    ->where('description', 'iLIKE', '%'. $request->search .'%')
                    ->orderBy('id','asc')
                    ->paginate(10);
        
        if(count($data)){
            foreach ($data as $key => $value) {
                $buscar = TramitesHabilitadosMotivos::find($value->id);
                $value->sucursal = $buscar->sucursalTexto();
            }
        }

        return view('motivos.index')->with('data', $data);                                         ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();

        return view('motivos.form')->with('sucursales', $sucursales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            $this->validate($request, [
                'description' => 'required|unique:tramites_habilitados_motivos'
            ]);
            
            $motivos = new TramitesHabilitadosMotivos();
            $motivos->description = $request->description;
            $motivos->activo = true;
            if($request->limite)
                $motivos->limite = $request->limite;
            if($request->sucursal_id)
                $motivos->sucursal_id = $request->sucursal_id;
            $motivos->save();

            Flash::success('El Motivo se ha creado correctamente');
            return redirect()->route('tramitesHabilitadosMotivos.create');
        }
        catch(Exception $e){   
            return "Fatal error - ".$e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = TramitesHabilitadosMotivos::find($id);
        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();

        return view('motivos.form')->with('edit', $edit)->with('sucursales', $sucursales);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $motivos = TramitesHabilitadosMotivos::find($id);
        $motivos->description = $request->description;
        $motivos->limite = ($request->limite)?$request->limite:null;
        $motivos->sucursal_id = ($request->sucursal_id)?$request->sucursal_id:0;
        $motivos->save();

        Flash::success('El Motivo se ha editado correctamente');
        return redirect()->route('tramitesHabilitadosMotivos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $motivos = TramitesHabilitadosMotivos::find($id);
            $motivos->activo = false;
            $motivos->deleted_at = date('Y-m-d H:i:s');
            $motivos->deleted_by = Auth::user()->id;
            $motivos->save();

            Flash::success('El Motivo se ha eliminado correctamente');
            return redirect()->route('tramitesHabilitadosMotivos.index');
        }
        catch(Exception $e){   
            return "Fatal error - ".$e->getMessage();
        }
    }

    public function habilitar(Request $request)
    {
        $sql = TramitesHabilitadosMotivos::where("id",$request->id)
                ->update(array('activo' => $request->activo));
        return $sql;
    }
}