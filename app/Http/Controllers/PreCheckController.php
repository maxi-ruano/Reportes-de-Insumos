<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\AnsvPaises;
use App\SysMultivalue;
use App\SigeciPaises;
use App\TramitesAIniciarErrores;
use App\TramitesAIniciarCheckprecheck;
use App\AnsvCelExpedidor;
use App\Http\Controllers\TramitesController;
use App\Sigeci;

class PreCheckController extends Controller
{
  
  public $centrosEmisores = null;

  public function __construct(){
    $this->centrosEmisores = new AnsvCelExpedidor();
    $this->crearConstantes(); //Esta funcion deberia ejecutarse desde Controllers pero se vuelve a ajecutar porq no reconocia las constants
  }

  public function checkPreCheck(){
    $paises = SysMultivalue::where('type','PAIS')->orderBy('description', 'asc')->pluck('description', 'id');
    $tdoc = SysMultivalue::where('type','TDOC')->orderBy('id', 'asc')->pluck('description', 'id');
    $sexo = SysMultivalue::where('type','SEXO')->where('id','<>',0)->orderBy('id', 'asc')->pluck('description', 'description');
    

    return View('safit.checkModoAutonomo')->with('paises', $paises)
                                          ->with('tdoc', $tdoc)
                                          ->with('sexo', $sexo)
                                          ->with('centrosEmisores', $this->centrosEmisores->getCentrosEmisores());
  }

  public function consultarPreCheck(Request $request){
    $tramiteAIniciar = TramitesAIniciar::find($request->id);
                           
    if($tramiteAIniciar){
      $tramiteAIniciar->nacionalidad = $tramiteAIniciar->nacionalidadTexto();

      $precheck =  \DB::table('validaciones_precheck as v')
                      ->select('v.tramite_a_iniciar_id', 'v.validado', 's.description', 'v.validation_id','v.comprobante')
                      ->join('sys_multivalue as s', 's.id', '=', 'v.validation_id')
                      ->where('s.type', 'VALP')
                      ->where('v.tramite_a_iniciar_id', $tramiteAIniciar->id)
                      ->get();
                      
      if(count($precheck)){
        $precheck = $this->getErroresTramite($precheck);
        return response()->json(['datosPersona' => $tramiteAIniciar, 'precheck' => $precheck]);
      }else{
        //Tramites antiguos que no tenian registros en validaciones_precheck
        return response()->json(['error'=> "El tramite no cuenta con validaciones en Pre-checkeo"]);
      } 

    }else{
      return response()->json(['error' => "El examen no se ha sido iniciado en el Pre-checkeo"]);
    }
  }

  public function  getErroresTramite($precheck){
    foreach ($precheck as $key => $value) {
      $estado = ($value->validation_id == 3)?[2,3]:[$value->validation_id];
      $value->error = TramitesAIniciarErrores::whereIn('estado_error', $estado)
                             ->where('tramites_a_iniciar_id', $value->tramite_a_iniciar_id)
                             ->where('response_ws', '!=','""')
                             ->select('description', 'id', 'created_at','response_ws')
                             ->orderBy('id', 'desc')
                             ->first();
    }
    return $precheck;
  }

  public function buscarTramitesPrecheck(Request $request){
    
    //Buscar las personas asociadas a un mismo nro de documento con diferente tipo documento y/o nacionalidad
    $personas = TramitesAIniciar::where('nro_doc', $request->nro_doc)
                  ->select('nro_doc','tipo_doc','nacionalidad')
                  ->groupBy('nro_doc','tipo_doc','nacionalidad')
                  ->orderBy('tipo_doc', 'asc')
                  ->get();
    
    $tramites = [];
    //Seleccionar solo un tramite por cada persona tomando en cuenta que exista en validaciones_precheck validado
    foreach ($personas as $key => $persona){
      $busqueda = TramitesAIniciar::select('tramites_a_iniciar.*','sigeci.fecha','sigeci.hora','sigeci.descsede as sede')
                                  ->where([
                                    'nro_doc' => $persona->nro_doc,
                                    'tipo_doc'=> $persona->tipo_doc,
                                    'nacionalidad'=> $persona->nacionalidad
                                    ])
                                  ->join('sigeci','sigeci.idcita','=','tramites_a_iniciar.sigeci_idcita')
                                  ->where('tramites_a_iniciar.estado','!=', TURNO_VENCIDO)
                                  ->orderBy('sigeci.idcita', 'desc')
                                  ->first();
      if($busqueda)
        $tramites [] = $busqueda;
      
    } 
   
   if(count($tramites)){
      foreach ($tramites as $key => $value) {
        $buscar = TramitesAIniciar::find($value->id);
        $value->nacionalidad = $buscar->nacionalidadTexto();
        $value->tipo_doc = $buscar->tipoDocText();
      }
      return response()->json(['res' => $tramites]);
    }else{
      return response()->json(['error' => "El examen no se ha sido iniciado en el Pre-checkeo"]);
    }
  }

  //function get para API listar los tramites iniciados con estado on ó off 
  public function get_tramites_precheck(Request $request){
    
    $fecha = ($request->fecha=='')?date("Y-m-d"):$request->fecha;

    $tramiteController = new TramitesController();
    $consulta = $tramiteController->consultarTramitesPrecheck($fecha, $request->estado);

    if($request->export) 
      $this->exportFile($consulta, $request->export, 'tramitesPrecheck'.$fecha);

    return $consulta;
  }

  //function get para API listar los errores generados en el precheck
  public function get_errores_precheck(Request $request){

    $fecha = ($request->fecha=='')?date("Y-m-d"):$request->fecha;
    
    //No limitar el limit Memory de PHP
    ini_set('memory_limit', '-1');

    $errores = $this->consultar_errores_precheck($fecha);

    //Solo si existe el parametro para export en: xls, xlsx, txt, csv, entre otros.
    $consulta = json_decode(json_encode($errores), true);
    if($request->export) 
      $this->exportFile($consulta, $request->export, 'erroresPrecheck'.$fecha);
    
    //Preparar array de array - agrupando por idcita con sus errores
    $datos = [];
    if(count($errores)){
      foreach ($errores as $key => $persona){
        $datos[$persona->idcita] = ['idcita' => $persona->idcita, 'tipo_doc' => $persona->tipo_doc, 'nro_doc' => $persona->nro_doc, 'nombre' => $persona->nombre, 'apellido' => $persona->apellido, 'sexo' => $persona->sexo];

        foreach ($errores as $key => $error){
          if($persona->idcita == $error->idcita){
            $datos[$persona->idcita]['error'][] = array(
                          'validation_id' => $error->validation_id, 
                          'validation_nombre' => $error->validation_nombre, 
                          'description' => $error->description, 
                          'created_at' => $error->created_at);
          }
        }
      }
    }else{
      $datos['mensaje']= "No existen registros de errores con fecha ".$fecha; 
    }
    
    return response()->json($datos);
  }

  public function consultar_errores_precheck($fecha){

      $campos = "SELECT 
                  sigeci_idcita as idcita, 
                  tramites_a_iniciar.nro_doc,
                  MAX(sys_multivalue_tdoc.description) as tipo_doc,
                  MAX(tramites_a_iniciar.nombre) as nombre,
                  MAX(tramites_a_iniciar.apellido) AS apellido,
                  MAX(tramites_a_iniciar.sexo) AS sexo,
                  tramites_a_iniciar_errores.tramites_a_iniciar_id,
                  validaciones_precheck.validation_id,
                  MAX(sys_multivalue.description) as validation_nombre,
                  MAX(tramites_a_iniciar_errores.description) as description, 
                  MAX(tramites_a_iniciar_errores.created_at) as created_at ";

      $table = "FROM tramites_a_iniciar_errores 
                INNER JOIN tramites_a_iniciar ON tramites_a_iniciar.id = tramites_a_iniciar_errores.tramites_a_iniciar_id
                INNER JOIN sigeci ON sigeci.idcita = tramites_a_iniciar.sigeci_idcita
                LEFT JOIN sys_multivalue sys_multivalue_tdoc ON sys_multivalue_tdoc.id = tramites_a_iniciar.tipo_doc AND sys_multivalue_tdoc.type = 'TDOC'
                 ";
     
      //Consulta para errores de Safit con estado_errores (2,3) y validaciones precheck (3)(false)
      $errores_safit = \DB::select($campos.$table."
                        INNER JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id and 
                          validaciones_precheck.validation_id = 3 and validaciones_precheck.validado = false
                        LEFT JOIN sys_multivalue ON sys_multivalue.id = validaciones_precheck.validation_id AND sys_multivalue.type = 'VALP'
                      WHERE sigeci.fecha = '".$fecha."' and tramites_a_iniciar_errores.estado_error IN('2','3') and tramites_a_iniciar_errores.response_ws <> '\"\"'
                      GROUP BY tramites_a_iniciar.tipo_doc, tramites_a_iniciar.nro_doc, sigeci_idcita,  tramites_a_iniciar_errores.tramites_a_iniciar_id, validaciones_precheck.validation_id
                      ORDER BY sigeci_idcita ASC ");

      //Consulta para errores de Libre Deuda y BUI con estado_errores (4,5) y validaciones precheck (4,5)(false)
      $errores_ldbui = \DB::select($campos.$table."                      
                        INNER JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id and 
                          validaciones_precheck.validation_id = tramites_a_iniciar_errores.estado_error and validaciones_precheck.validado = false
                        LEFT JOIN sys_multivalue ON sys_multivalue.id = validaciones_precheck.validation_id AND sys_multivalue.type = 'VALP'
                      WHERE sigeci.fecha = '".$fecha."' and tramites_a_iniciar_errores.estado_error IN('4','5') and tramites_a_iniciar_errores.response_ws <> '\"\"'
                      GROUP BY tramites_a_iniciar.tipo_doc, tramites_a_iniciar.nro_doc, sigeci_idcita, tramites_a_iniciar_errores.tramites_a_iniciar_id, validaciones_precheck.validation_id, tramites_a_iniciar_errores.estado_error
                      ORDER BY sigeci_idcita ASC, tramites_a_iniciar_errores.estado_error ASC ");

      $consulta = array_merge($errores_safit,$errores_ldbui);

    return $consulta;
  }


  public function actualizarPaseATurno(Request $request) {
    $fecha = ($request->fecha=='')?date("Y-m-d H:i:s"):$request->fecha;
    return TramitesAIniciar::where('id',$request->id)->update(['fecha_paseturno' => $fecha]);
  }

  public function get_precheck_comprobantes(Request $request) {
    
    $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');

    $sql  = "SELECT
              tramites_a_iniciar.id, 
              sigeci.tipodoc, 
              tramites_a_iniciar.nro_doc, 
              tramites_a_iniciar.nombre, 
              tramites_a_iniciar.apellido, 
              tramites_a_iniciar.sexo, 
              (CASE WHEN validaciones_precheck.validation_id = 3 THEN validaciones_precheck.comprobante ELSE null END) as SAFIT, 
              (CASE WHEN validaciones_precheck.validation_id = 4 THEN validaciones_precheck.comprobante ELSE null END) as LIBRE_DEUDA, 
              (CASE WHEN validaciones_precheck.validation_id = 5 THEN validaciones_precheck.comprobante ELSE null END) as BUI
            FROM tramites_a_iniciar
              INNER JOIN sigeci ON  sigeci.idcita =  tramites_a_iniciar.sigeci_idcita
              INNER JOIN validaciones_precheck ON  validaciones_precheck.tramite_a_iniciar_id =  tramites_a_iniciar.id
            WHERE sigeci.fecha = '".$fecha."' ";
    

    $consulta = \DB::table(\DB::raw('('.$sql.')  as precheck'))
                    ->selectRaw("id, tipodoc, nro_doc, MAX(nombre) as nombre, MAX(apellido) as apellido, MAX(sexo) as sexo, MAX(SAFIT) as safit, MAX(LIBRE_DEUDA) as libredeuda, MAX(BUI) as bui")
                    ->groupBy("id","tipodoc", "nro_doc")
                    ->orderBy('nro_doc','ASC')          
                    ->get(); 

    $data= json_decode( json_encode($consulta), true);
    $this->exportFile($data, 'xls', 'consultaPrecheck');

    return $data;
  }
}