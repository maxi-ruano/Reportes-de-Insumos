<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysMultivalue;
use App\SysUsers;
use App\TramitesHabilitados;

class TramitesHabilitadosController extends Controller
{
    private $path = 'tramiteshabilitados';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sql = TramitesHabilitados::orderBy('tramites_habilitados.fecha','desc')
                        ->orderBy('tramites_habilitados.id','desc')
                        ->where(function($query) use ($request) {
                            $query->where('nombre', 'LIKE', '%'. strtoupper($request->search) .'%')
                                ->orWhere('apellido', 'LIKE', '%'. strtoupper($request->search) .'%');
                            });
        
        //Mostrar solo los registros del usuario logeado si no es administrador
        /*if( session('usuario_rol_id') != 9 )
            $sql = $sql->where('tramites_habilitados.user_id',session('usuario_id'));
        */
        $data = $sql->paginate(6);

        if(count($data)){
            foreach ($data as $key => $value) {
                $buscar = TramitesHabilitados::find($value->id);
                $value->tipo_doc = $buscar->tipoDocText();
                $value->pais = $buscar->paisTexto();
                $value->user_id = $buscar->userTexto();
            }
        }
        return view($this->path.'.index', compact('data')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $paises = SysMultivalue::select('id','description')->where('type','PAIS')->orderBy('description', 'asc')->pluck('description','id');
        $tdocs = SysMultivalue::select('id','description')->where('type','TDOC')->orderBy('id', 'asc')->pluck('description','id');
        
        return view($this->path.'.form')->with('paises',$paises)
                                        ->with('tdocs',$tdocs);
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
            $tramiteshabilitados = new TramitesHabilitados();

            $tramiteshabilitados->fecha         = $request->fecha;
            $tramiteshabilitados->apellido      = strtoupper($request->apellido);
            $tramiteshabilitados->nombre        = strtoupper($request->nombre);
            $tramiteshabilitados->tipo_doc      = $request->tipo_doc;
            $tramiteshabilitados->nro_doc       = $request->nro_doc;
            $tramiteshabilitados->pais          = $request->pais;
            $tramiteshabilitados->user_id       = session('usuario_id');

            $tramiteshabilitados->save();
            //Flash::info('El Tramite se ha creado correctamente');
            return redirect()->route('tramitesHabilitados.index');
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
        $edit = TramitesHabilitados::find($id);
        $paises = SysMultivalue::select('id','description')->where('type','PAIS')->orderBy('description', 'asc')->pluck('description','id');
        $tdocs = SysMultivalue::select('id','description')->where('type','TDOC')->orderBy('id', 'asc')->pluck('description','id');
        
        return view($this->path.'.form')->with('edit', $edit)
                                        ->with('paises',$paises)
                                        ->with('tdocs',$tdocs);
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
        $tramitesHabilitados = TramitesHabilitados::find($id);
        $tramitesHabilitados->fill($request->all());
        $tramitesHabilitados->save();
        //Flash::info('El Tramite se ha editado correctamente');
        return redirect()->route('tramitesHabilitados.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo "entro a destroy ".$id;
        try{
            $tramiteshabilitados = TramitesHabilitados::find($id);
            $tramiteshabilitados->delete();
            //mensaje = 'El Tramite se ha eliminado correctamente';
            return redirect()->route('tramitesHabilitados.index');
        }
        catch(Exception $e){   
            return "Fatal error - ".$e->getMessage();
        }
    }
}
