<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysMultivalue;
use App\User;
use App\TramitesHabilitados;
use App\Http\Controllers\TramitesAInicarController;
use App\DatosPersonales;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;
use App\TramitesAIniciar;
use App\ValidacionesPrecheck;
use App\Jobs\ProcessPrecheck;

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
        
        $fecha = isset($_GET['fecha'])?$_GET['fecha']:date('Y-m-d');

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
                            });
            if($fecha)
                $data = $data->where('fecha',$fecha);

            $data = $data->paginate(10);

            if(count($data)){
                foreach ($data as $key => $value) {
                    $buscar = TramitesHabilitados::find($value->id);
                    $value->tipo_doc = $buscar->tipoDocText();
                    $value->pais = $buscar->paisTexto();
                    $value->user_id = $buscar->userTexto($value->user_id);
                    $value->habilitado_user_id = $buscar->userTexto($value->habilitado_user_id);
                    $value->motivo_id = $buscar->motivoTexto();
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
        $motivos = \DB::table('tramites_habilitados_motivos')->select('id','description')->where('activo','true')->orderBy('description', 'asc')->pluck('description','id');        

        $SysMultivalue = new SysMultivalue();
        $sucursales = $SysMultivalue->sucursales();
        $tdocs = $SysMultivalue->tipodocs(); 
        $paises = $SysMultivalue->paises();
        
        return view($this->path.'.form')->with('fecha',$fecha)
                                        ->with('sucursales',$sucursales)
                                        ->with('tdocs',$tdocs)
                                        ->with('paises',$paises)
                                        ->with('motivos',$motivos);
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
            //validar nro_doc solo si es pasaporte acepte letras y numeros de lo contrario solo numeros
            if($request->tipo_doc== '4')
                $this->validate($request, ['nro_doc' => 'required|min:0|max:10|regex:/^[0-9a-zA-Z]+$/']);
            else
                $this->validate($request, ['nro_doc' => 'required|min:0|max:10|regex:/(^(\d+)?$)/u']);

            //Validar que no exista el mismo registro
            $existe = TramitesHabilitados::where('tipo_doc',$request->tipo_doc)
                        ->where('nro_doc',$request->nro_doc)
                        ->where('pais',$request->pais)
                        ->where('fecha',$request->fecha)
                        ->count();
            if($existe){
                Flash::error('El Documento Nro. '.$request->nro_doc.' Ya tiene un turno asignado para el día '.$request->fecha);
                return back();   
            }

            //Si no existe entonces crear el registro
            $tramiteshabilitados = new TramitesHabilitados();

            $tramiteshabilitados->fecha         = $request->fecha;
            $tramiteshabilitados->apellido      = strtoupper($request->apellido);
            $tramiteshabilitados->nombre        = strtoupper($request->nombre);
            $tramiteshabilitados->tipo_doc      = $request->tipo_doc;
            $tramiteshabilitados->nro_doc       = strtoupper($request->nro_doc);
            $tramiteshabilitados->sexo          = $request->sexo;
            $tramiteshabilitados->fecha_nacimiento     = $request->fecha_nacimiento;
            $tramiteshabilitados->pais          = $request->pais;
            $tramiteshabilitados->user_id       = $request->user_id;
            $tramiteshabilitados->sucursal      = $request->sucursal;
            $tramiteshabilitados->motivo_id     = $request->motivo_id;

            if(Auth::user()->sucursal == '1') //Solo para la Sede Roca
                $tramiteshabilitados->habilitado = false;

            $tramiteshabilitados->save();

            //Crear registro en tramitesAIniciar y procesar el Precheck
            ProcessPrecheck::dispatch($tramiteshabilitados);
            /*$TramitesAIniciar = new TramitesAInicarController();
            $TramitesAIniciar->iniciarTramiteEnPrecheck($tramiteshabilitados);*/
            
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
        $motivos = \DB::table('tramites_habilitados_motivos')->select('id','description')->where('activo','true')->orderBy('description', 'asc')->pluck('description','id');        
        
        $SysMultivalue = new SysMultivalue();
        $sucursales = $SysMultivalue->sucursales();
        $tdocs = $SysMultivalue->tipodocs();    
        $paises = $SysMultivalue->paises();
        
        return view($this->path.'.form')->with('edit', $edit)
                                        ->with('sucursales',$sucursales)
                                        ->with('tdocs',$tdocs)
                                        ->with('paises',$paises)
                                        ->with('motivos',$motivos);
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
        //validar nro_doc solo si es pasaporte acepte letras y numeros de lo contrario solo numeros
        if($request->tipo_doc== '4')
            $this->validate($request, ['nro_doc' => 'required|min:0|max:10|regex:/^[0-9a-zA-Z]+$/']);
        else
            $this->validate($request, ['nro_doc' => 'required|min:0|max:10|regex:/(^(\d+)?$)/u']);
        
        $tramitesHabilitados = TramitesHabilitados::find($id);
        $tramitesHabilitados->fill($request->except('user_id'));
        $tramitesHabilitados->nro_doc = strtoupper($request->nro_doc);
        $tramitesHabilitados->nombre = strtoupper($request->nombre);
        $tramitesHabilitados->apellido = strtoupper($request->apellido);

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
        //echo "entro a destroy ".$id;
        try{
            $tramiteshabilitados = TramitesHabilitados::find($id);
            $tramiteshabilitados->delete();

            //Borrar registros creados en tramites_a_iniciar y validaciones_precheck
            /*$validacionesPrecheck = ValidacionesPrecheck::where('tramite_a_iniciar_id', $tramiteshabilitados->tramites_a_iniciar_id)->delete();
            $tramitesAIniciar = TramitesAIniciar::where('id', $tramiteshabilitados->tramites_a_iniciar_id)->delete();
            */
           
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


    public function buscarDatosPersonales(Request $request)
    {
        $buscar = DatosPersonales::selectRaw('nombre, apellido, UPPER(sexo) as sexo, fec_nacimiento as fecha_nacimiento, pais')->where("tipo_doc",$request->tipo_doc)->where("nro_doc",$request->nro_doc)->orderBy('modification_date','DESC')->first();
        
        if(!$buscar)
           $buscar = TramitesHabilitados::where("tipo_doc",$request->tipo_doc)->where("nro_doc",$request->nro_doc)->orderBy('id','DESC')->first();

        if(!$buscar)
           $buscar = TramitesAIniciar::where("tipo_doc",$request->tipo_doc)->where("nro_doc",$request->nro_doc)->orderBy('id','DESC')->first();

        return $buscar;
    }
}
