<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\AnsvPaises;
use App\SysMultivalue;
use App\SigeciPaises;
use App\TramitesAIniciarErrores;
use App\TramitesAIniciarCheckprecheck;
use App\Http\Controllers\TramitesController;

class PreCheckController extends Controller
{
  public function checkPreCheck(){
    $paises = SysMultivalue::where('type','PAIS')->orderBy('description', 'asc')->pluck('description', 'id');
    $tdoc = SysMultivalue::where('type','TDOC')->orderBy('id', 'asc')->pluck('description', 'id');
    $sexo = SysMultivalue::where('type','SEXO')->where('id','<>',0)->orderBy('id', 'asc')->pluck('description', 'description');
    //dd($paises);
    return View('safit.checkModoAutonomo')->with('paises', $paises)
                                          ->with('tdoc', $tdoc)
                                          ->with('sexo', $sexo);
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
      $tramites[] = TramitesAIniciar::select('tramites_a_iniciar.*','sigeci.fecha','sigeci.hora')
                                  ->where([
                                    'nro_doc' => $persona->nro_doc,
                                    'tipo_doc'=> $persona->tipo_doc,
                                    'nacionalidad'=> $persona->nacionalidad
                                    ])
                                  ->leftjoin('validaciones_precheck','validaciones_precheck.tramite_a_iniciar_id','=','tramites_a_iniciar.id')
                                  ->leftjoin('sigeci','sigeci.idcita','=','tramites_a_iniciar.sigeci_idcita')
                                  ->orderBy('validaciones_precheck.validado', 'desc')
                                  ->first();
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
    $tramiteController = new TramitesController();
    return $tramiteController->consultarTramitesPrecheck($request->fecha, $request->estado);

  }

  //function get para API listar los errores generados en el precheck
  public function get_errores_precheck(Request $request){
    $fecha = ($request->fecha=='')?date("Y-m-d"):$request->fecha;
    
    $errores = $this->consultar_errores_precheck($fecha);

    $datos = [];
    
    if(count($errores)){
      foreach ($errores as $key => $persona){
        
        $datos[$persona->idcita] = ['nro_doc' => $persona->nro_doc, 'nombre' => $persona->nombre, 'apellido' => $persona->apellido, 'idcita' => $persona->idcita];

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
                  MAX(tramites_a_iniciar.nombre) as nombre,
                  MAX(tramites_a_iniciar.apellido) AS apellido,
                  tramites_a_iniciar_errores.tramites_a_iniciar_id,
                  validaciones_precheck.validation_id,
                  MAX(sys_multivalue.description) as validation_nombre,
                  MAX(tramites_a_iniciar_errores.description) as description, 
                  MAX(tramites_a_iniciar_errores.created_at) as created_at ";
    
      //Consulta para errores de Safit con estado_errores (2,3) y validaciones precheck (3)(false)
      $errores_safit = \DB::select($campos."
                      FROM tramites_a_iniciar_errores
                        INNER JOIN tramites_a_iniciar ON tramites_a_iniciar.id = tramites_a_iniciar_errores.tramites_a_iniciar_id
                        INNER JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id and 
                          validaciones_precheck.validation_id = 3 and validaciones_precheck.validado = false
                        LEFT JOIN sys_multivalue ON sys_multivalue.id = validaciones_precheck.validation_id AND type = 'VALP'
                        INNER JOIN sigeci ON sigeci.idcita = tramites_a_iniciar.sigeci_idcita
                      WHERE sigeci.fecha = '".$fecha."' and tramites_a_iniciar_errores.estado_error IN('2','3') and tramites_a_iniciar_errores.response_ws <> '\"\"'
                      GROUP BY tramites_a_iniciar.nro_doc, sigeci_idcita,  tramites_a_iniciar_errores.tramites_a_iniciar_id, validaciones_precheck.validation_id
                      ORDER BY sigeci_idcita ASC ");

      //Consulta para errores de Libre Deuda y BUI con estado_errores (4,5) y validaciones precheck (4,5)(false)
      $errores_ldbui = \DB::select($campos."                      
                      FROM tramites_a_iniciar_errores
                        INNER JOIN tramites_a_iniciar ON tramites_a_iniciar.id = tramites_a_iniciar_errores.tramites_a_iniciar_id
                        INNER JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id and 
                          validaciones_precheck.validation_id = tramites_a_iniciar_errores.estado_error and validaciones_precheck.validado = false
                        LEFT JOIN sys_multivalue ON sys_multivalue.id = validaciones_precheck.validation_id AND type = 'VALP'
                        INNER JOIN sigeci ON sigeci.idcita = tramites_a_iniciar.sigeci_idcita
                      WHERE sigeci.fecha = '".$fecha."' and tramites_a_iniciar_errores.estado_error IN('4','5') and tramites_a_iniciar_errores.response_ws <> '\"\"'
                      GROUP BY tramites_a_iniciar.nro_doc, sigeci_idcita, tramites_a_iniciar_errores.tramites_a_iniciar_id, validaciones_precheck.validation_id, tramites_a_iniciar_errores.estado_error
                      ORDER BY sigeci_idcita ASC, tramites_a_iniciar_errores.estado_error ASC ");

      $consulta = array_merge($errores_safit,$errores_ldbui);

    return $consulta;
  }


  public function actualizarPaseATurno(Request $request) {
    $fecha = ($request->fecha=='')?date("Y-m-d H:i:s"):$request->fecha;
    return TramitesAIniciar::where('id',$request->id)->update(['fecha_paseturno' => $fecha]);
  }

}
