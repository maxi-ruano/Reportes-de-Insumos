<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysMultivalue;
use App\User;
use App\TramitesHabilitados;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;

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
        //Cargar por defecto el formulario solo al Operador
        if(Auth::user()->hasRole('Operador')){
            return $this->create();
        }else{
            $data = TramitesHabilitados::orderBy('tramites_habilitados.fecha','desc')
                        ->orderBy('tramites_habilitados.id','desc')
                        ->where(function($query) use ($request) {
                            $query->where('nombre', 'LIKE', '%'. strtoupper($request->search) .'%')
                                ->orWhere('apellido', 'LIKE', '%'. strtoupper($request->search) .'%')
                                ->orWhereRaw("CAST(nro_doc AS text) LIKE '%$request->search%' ");
                            })
                        ->paginate(10);

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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha = date('Y-m-d');
        //$paises = SysMultivalue::select('id','description')->where('type','PAIS')->orderBy('description', 'asc')->pluck('description','id');
        //$tdocs = SysMultivalue::select('id','description')->where('type','TDOC')->orderBy('id', 'asc')->pluck('description','id');
        $SysMultivalue = new SysMultivalue();
        $sucursales = $SysMultivalue->sucursales();
        $tdocs = $SysMultivalue->tipodocs(); 
        $paises = $SysMultivalue->paises();
        
        return view($this->path.'.form')->with('fecha',$fecha)
                                        ->with('sucursales',$sucursales)
                                        ->with('tdocs',$tdocs)
                                        ->with('paises',$paises);
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
            $tramiteshabilitados->user_id       = $request->user_id;
            $tramiteshabilitados->sucursal      = $request->sucursal;

            if(Auth::user()->sucursal == '1') //Solo para la Sede Roca
                $tramiteshabilitados->habilitado = false;

            $tramiteshabilitados->save();
            Flash::success('El Tramite se ha creado correctamente');
            return redirect()->route('tramitesHabilitados.create');
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
        //$paises = SysMultivalue::select('id','description')->where('type','PAIS')->orderBy('description', 'asc')->pluck('description','id');
        //$tdocs = SysMultivalue::select('id','description')->where('type','TDOC')->orderBy('id', 'asc')->pluck('description','id');
        
        $SysMultivalue = new SysMultivalue();
        $sucursales = $SysMultivalue->sucursales();
        $tdocs = $SysMultivalue->tipodocs();    
        $paises = $SysMultivalue->paises();
        
        return view($this->path.'.form')->with('edit', $edit)
                                        ->with('sucursales',$sucursales)
                                        ->with('tdocs',$tdocs)
                                        ->with('paises',$paises);
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
        Flash::success('El Tramite se ha editado correctamente');
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
            Flash::success('El Tramite se ha eliminado correctamente');
            return redirect()->route('tramitesHabilitados.index');
        }
        catch(Exception $e){   
            return "Fatal error - ".$e->getMessage();
        }
    }

    public function habilitar(Request $request)
    {
        $sql = TramitesHabilitados::where("id",$request->id)
                                ->update(array('habilitado' => $request->valor, 'habilitado_user_id' => Auth::user()->id));
        return $sql;
    }
}
