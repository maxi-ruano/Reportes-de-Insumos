<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysMultivalue;
use App\User;
use App\TramitesHabilitados;
use App\AnsvPaises;
use App\AnsvCelExpedidor;
use App\DatosPersonales;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;
use App\TramitesAIniciar;
use App\ValidacionesPrecheck;
use App\Jobs\ProcessPrecheck;
use App\Sigeci;
use App\TramitesHabilitadosMotivos;

class TramitesHabilitadosController extends Controller
{
    private $path = 'tramiteshabilitados';
    public $centrosEmisores = null;

    public function __construct(){
        $this->centrosEmisores = new AnsvCelExpedidor();
        //Iniciar precheck de los tramites que no iniciaron el dia de hoy --PAUSADO Colapsa el demonio Queque
        //$this->verificarPrecheckHabilitados();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fecha = isset($_GET['fecha'])?$_GET['fecha']:'';

        $data = TramitesHabilitados::selectRaw('tramites_habilitados.*, tramites_habilitados_observaciones.observacion')
                    ->leftjoin('tramites_habilitados_observaciones','tramites_habilitados_observaciones.tramite_habilitado_id','tramites_habilitados.id')
                    ->whereIn('tramites_habilitados.motivo_id', $this->getRoleMotivos('role_motivos_lis'))
                    ->where(function($query) use ($request) {
                        $query->where('nombre', 'iLIKE', '%'. $request->search .'%')
                            ->orWhere('apellido', 'iLIKE', '%'. $request->search .'%')
                            ->orWhereRaw("CAST(nro_doc AS text) iLIKE '%$request->search%' ");
                    })
                    ->orderBy('tramites_habilitados.updated_at','desc');
        if($fecha)
            $data = $data->where('fecha',$fecha);
        
        if(isset($request->sucursal))
            $data = $data->where('sucursal',$request->sucursal);
                    
        //Verificar si tiene permisos para filtrar solo los que registro
        $user = Auth::user();
        if($user->hasPermissionTo('view_self_tramites_habilitados'))
            $data = $data->where('user_id',$user->id);
        
        if($user->hasPermissionTo('view_sede_tramites_habilitados'))
            $data = $data->where('sucursal',$user->sucursal);
        
        //Finalizar Query con el Paginador
        $data = $data->paginate(10);
        
        //Se reemplaza id por texto de cada tabla relacionada
        if(count($data)){
            foreach ($data as $key => $value) {
                $buscar = TramitesHabilitados::find($value->id);
                $value->tipo_doc = $buscar->tipoDocText();
		        $value->pais = $buscar->paisTexto();
		        $value->rol = $buscar->rolTexto();
                $value->user_id = $buscar->userTexto($value->user_id);
                $value->habilitado_user_id = $buscar->userTexto($value->habilitado_user_id);
                $value->motivo_id = $buscar->motivoTexto();
                $value->sucursal = $buscar->sucursalTexto();
                $value->fecha = date('d-m-Y', strtotime($value->fecha));
            }
        }

        //Se envia listado d elas Sucursales para el select del buscar
        $SysMultivalue = new SysMultivalue();        
        $sucursales = $SysMultivalue->sucursales();

        return view($this->path.'.index')->with('data', $data)
                                         ->with('centrosEmisores', $this->centrosEmisores->getCentrosEmisores())
                                         ->with('sucursales', $sucursales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $fecha = $this->calcularFecha();

        //Se cargan motivos segun el permiso asignado en roles_motivos_sel
        $motivos = \DB::table('tramites_habilitados_motivos')->whereIn('id',$this->getRoleMotivos('role_motivos_sel'))->orderBy('description', 'asc')->pluck('description','id');
        
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
            if($this->verificarLimite($request->sucursal, $request->motivo_id, $request->fecha)){
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
                $tramiteshabilitados->habilitado = false;               
                $tramiteshabilitados->save();

                if(isset($request->observacion))
                    $this->guardarObservacion($tramiteshabilitados->id, $request->observacion);

                //Vincular con el precheck generado solo si coinciden los datos 
                if($request->precheck_id){
                    $precheck = TramitesAIniciar::find($request->precheck_id);
                    if($precheck->nro_doc == $tramiteshabilitados->nro_doc && $precheck->tipo_doc == $tramiteshabilitados->tipo_doc){
                        $tramiteshabilitados->tramites_a_iniciar_id = $request->precheck_id;
                        $tramiteshabilitados->save();

                        //Corregimos la nacionalidad en el Precheck que asociamos
                        $nacionalidad = AnsvPaises::where('id_dgevyl', $tramiteshabilitados->pais)->first()->id_ansv;
                        if($precheck->nacionalidad != $nacionalidad){
                            $precheck->nacionalidad = $nacionalidad;
                            $precheck->save();
                        }
                    }else{
                        //Crear registro en tramitesAIniciar y procesar el Precheck
                        ProcessPrecheck::dispatch($tramiteshabilitados);
                    }
                }else{
                    //Crear registro en tramitesAIniciar y procesar el Precheck
                    ProcessPrecheck::dispatch($tramiteshabilitados);
                }

                Flash::success('El Tramite se ha creado correctamente');
                return redirect()->route('tramitesHabilitados.create');
            }else{
                Flash::error('LIMITE DIARIO PERMITIDO para la sucursal seleccionada.!!');
                return back();  
            }
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
        $fecha = $this->calcularFecha();

        $edit = TramitesHabilitados::where('tramites_habilitados.id',$id)
                    ->selectRaw('tramites_habilitados.*, tramites_habilitados_observaciones.observacion')
                    ->leftjoin('tramites_habilitados_observaciones','tramites_habilitados_observaciones.tramite_habilitado_id','tramites_habilitados.id')
                    ->first();

        $inicio_tramite = ($edit->tramites_a_iniciar_id)?TramitesAIniciar::find($edit->tramites_a_iniciar_id)->tramite_dgevyl_id:'';
        //No realizar ninguna modificacion si el tramiteAIniciar inicio en Fotografia
        if($inicio_tramite){
            Flash::error('El Tramite ya se inicio no se puede modificar!');
            return redirect()->route('tramitesHabilitados.index');
        }else{

            //Se cargan motivos segun el permiso asignado en roles_motivos_sel
            $motivos = \DB::table('tramites_habilitados_motivos')->whereIn('id',$this->getRoleMotivos('role_motivos_sel'))->orderBy('description', 'asc')->pluck('description','id');

            $SysMultivalue = new SysMultivalue();
            $sucursales = $SysMultivalue->sucursales();
            $tdocs = $SysMultivalue->tipodocs();
            $paises = $SysMultivalue->paises();

            return view($this->path.'.form')->with('edit', $edit)
                                            ->with('fecha',$fecha)
                                            ->with('sucursales',$sucursales)
                                            ->with('tdocs',$tdocs)
                                            ->with('paises',$paises)
                                            ->with('motivos',$motivos);
        }
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

        //Buscar tramites habilitado, guardarmos tipo y nro de documento actual para comparar luego si fueron modificaron
        $tramitesHabilitados = TramitesHabilitados::find($id);
        $tipodoc = $tramitesHabilitados->tipo_doc;
        $nrodoc = $tramitesHabilitados->nro_doc;
        $tramitesAIniciar_id = $tramitesHabilitados->tramites_a_iniciar_id;

        //Actualizar datos en TramitesHabilitados
        $tramitesHabilitados->fill($request->except('user_id'));
        $tramitesHabilitados->nro_doc = strtoupper($request->nro_doc);
        $tramitesHabilitados->nombre = strtoupper($request->nombre);
        $tramitesHabilitados->apellido = strtoupper($request->apellido);
        $tramitesHabilitados->save();

        if(isset($request->observacion))
            $this->guardarObservacion($id, $request->observacion);

        //Si existe un TramiteAIniciar asociado hacer lo siguiente
        if($tramitesAIniciar_id){
            //Si se modifico el Tipo o Nro de Documento se anula el tramiteAiniciar asociado y se crea uno nuevo
            if( ($tipodoc != $tramitesHabilitados->tipo_doc) || ($nrodoc != $tramitesHabilitados->nro_doc)){
                TramitesAIniciar::where('id',$tramitesAIniciar_id)
                    ->whereNull('tramite_dgevyl_id')
                    ->update(['estado'=> TURNO_VENCIDO]);
                //Crear un nuevo tramitesAIniciar y procesar el Precheck
                ProcessPrecheck::dispatch($tramitesHabilitados);
            }else{
                //De lo contrario se modifica en TramitesAIniciar los datos
                $nacionalidad = AnsvPaises::where('id_dgevyl', $request->pais)->first()->id_ansv;
                $tramiesAIniciar = TramitesAIniciar::find($tramitesAIniciar_id);
                $tramiesAIniciar->nombre = strtoupper($request->nombre);
                $tramiesAIniciar->apellido = strtoupper($request->apellido);
                $tramiesAIniciar->sexo = $request->sexo;
                $tramiesAIniciar->fecha_nacimiento = $request->fecha_nacimiento;
                $tramiesAIniciar->nacionalidad = $nacionalidad;
                $tramiesAIniciar->save();
            }
        }

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

    public function guardarObservacion($tramite_habilitado_id, $observacion)
    {
        \DB::table('tramites_habilitados_observaciones')->where('tramite_habilitado_id',$tramite_habilitado_id)->delete();
        $sql =\DB::insert("INSERT INTO tramites_habilitados_observaciones (tramite_habilitado_id, observacion) 
                   VALUES (".$tramite_habilitado_id.", '".$observacion."' )");
        return $sql;
    }


    public function buscarDatosPersonales(Request $request)
    {
        $buscar='';
        $sql = DatosPersonales::selectRaw('nombre, apellido, UPPER(sexo) as sexo, fec_nacimiento as fecha_nacimiento, pais')->where("tipo_doc",$request->tipo_doc)->where("nro_doc",$request->nro_doc)->orderBy('modification_date','DESC');
        $duplicado = $sql->count();
        //Verificar si existe una persona con el mismo numero de documento
        if(!($duplicado>2)){
            if($duplicado==1){
                $buscar = $sql->first();
            }else{
                $buscar = TramitesHabilitados::where("tipo_doc",$request->tipo_doc)->where("nro_doc",$request->nro_doc)->orderBy('id','DESC')->first();
                if(!$buscar){
                    $buscar = TramitesAIniciar::selectRaw("tramites_a_iniciar.*, ansv_paises.id_dgevyl as pais")
                                ->join('ansv_paises','ansv_paises.id_ansv','tramites_a_iniciar.nacionalidad')
                                ->where("tipo_doc",$request->tipo_doc)
                                ->where("nro_doc",$request->nro_doc)
                                ->orderBy('id','DESC')
                                ->first();      
                }
            }
        }
        return $buscar;
    }

    public function consultarTurnoSigeci(Request $request){
        $consulta = Sigeci::selectRaw("sigeci.*, tramites_a_iniciar.tramite_dgevyl_id")
                        ->leftjoin('tramites_a_iniciar','tramites_a_iniciar.sigeci_idcita','sigeci.idcita')
                        ->where("sigeci.idcita",$request->idcita)
                        ->whereNotIn('sigeci.idprestacion', $this->prestacionesCursos)
                        ->first();
        return $consulta;
    }

    public function consultarUltimoTurno(Request $request){
        $consulta = Sigeci::selectRaw("sigeci.*")
                        ->join("tipo_doc","tipo_doc.id_sigeci","sigeci.idtipodoc")
                        ->leftjoin('tramites_a_iniciar','tramites_a_iniciar.sigeci_idcita','sigeci.idcita')
                        ->where("tipo_doc.id_dgevyl",$request->tipo_doc)
                        ->where("sigeci.numdoc",$request->nro_doc)
                        ->whereNull('tramites_a_iniciar.tramite_dgevyl_id')
                        ->whereNotIn('sigeci.idprestacion', $this->prestacionesCursos)
                        ->orderBy('sigeci.idcita','DESC')
                        ->first();
        return $consulta;
    }

    public function calcularFecha(){
        $fecha = date('Y-m-d');
        $dia_semana = date('w');

        //Si es Jueves o viernes sumar 5, por incluir fin de semana, de lo contrario sumar 3
        $sumar_dias = ($dia_semana == 4 || $dia_semana == 5)?'5':'3'; 
        
        //Se valida que solo para el Rol Comuna permita ingresar 72 en adelante
        $user = Auth::user();
        if($user->hasRole('Comuna'))
            $fecha = date('Y-m-d', strtotime('+'.$sumar_dias.' days', strtotime(date('Y-m-d'))));
            
        return $fecha;
    }

    public function verificarLimite($sucursal, $motivo, $fecha){
        $acceso= false;
        $user = Auth::user();
        $role_id = $user->roles->pluck('id')->first();
        
        //Encontrar todas las posibilidades establecidas en roles_limites
        $roles_limites = \DB::table('roles_limites')->where('role_id',$role_id)->where('sucursal',$sucursal)->where('motivo_id',$motivo)->where('activo', true)->first();
        
        if($roles_limites == null)
            $roles_limites = \DB::table('roles_limites')->where('role_id',$role_id)->where('sucursal',$sucursal)->whereNull('motivo_id')->where('activo', true)->first();
        
        if($roles_limites == null)
            $roles_limites = \DB::table('roles_limites')->where('role_id',$role_id)->where('motivo_id',$motivo)->whereNull('sucursal')->where('activo', true)->first();
        
        if($roles_limites == null)
            $roles_limites = \DB::table('roles_limites')->where('role_id',$role_id)->whereNull('sucursal')->whereNull('motivo_id')->where('activo', true)->first();

        if($roles_limites){

            $consulta = TramitesHabilitados::where('fecha',$fecha)
                            ->whereIn('user_id',function($query) use($role_id){
                                $query->select('model_id')->from('model_has_roles')->where('role_id',$role_id);
                             });
            
            if($roles_limites->sucursal)
                $consulta = $consulta->where('sucursal',$roles_limites->sucursal);
            
            if($roles_limites->motivo_id)
                $consulta = $consulta->where('motivo_id',$roles_limites->motivo_id);
                        
            $consulta = $consulta->count();

            if($consulta >= $roles_limites->limite)
                $acceso = false;
            else
                $acceso = true;
        }else{
            $acceso = true;
        }

        return $acceso;  
    }

    public function getRoleMotivos($tabla){
        $user = Auth::user();
        $roles = $user->roles->pluck('id')->toArray();

        $motivos = \DB::table($tabla)->whereIn('role_id',$roles)->pluck('motivo_id')->toArray();
        return $motivos;
    }

    //Se envia masivamente los turnos que no ha inciado a procesar Precheck con el queque (Demonio)
    public function verificarPrecheckHabilitados(){
        $turnos =  TramitesHabilitados::whereNull('tramites_a_iniciar_id')->where('fecha',date('Y-m-d'))->get();
        foreach ($turnos as $key => $turno) {
            //Crear registro en tramitesAIniciar y procesar el Precheck
            ProcessPrecheck::dispatch($turno);
        }
    }
}