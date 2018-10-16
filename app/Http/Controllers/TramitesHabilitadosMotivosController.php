<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;
use App\TramitesHabilitadosMotivos;

class TramitesHabilitadosMotivosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = TramitesHabilitadosMotivos::orderBy('id','asc')
                    ->where('description', 'LIKE', '%'. strtoupper($request->search) .'%')
                    ->paginate(10);

        return view('motivos.index')->with('data', $data);                                         ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('motivos.form');
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
        return view('motivos.form')->with('edit', $edit);
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
            $motivos->delete();
           
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