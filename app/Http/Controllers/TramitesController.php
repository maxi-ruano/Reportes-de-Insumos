<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tramites;
use App\Http\Controllers\SigeciController;

class TramitesController extends Controller
{
    //Ignore los Estado Borrado o Cancelado
    private $estadosIgnore = ['93','94']; 
    
    public function __construct() {
      $this->Sigeci = new SigeciController();
    }

    public function buscarTramite(Request $request){
      $tramite = Tramites::where('nro_doc',$request->nro_doc)
                          ->where('tipo_doc',$request->tipo_doc)
                          ->where('sexo',$request->sexo)
                          ->where('pais',$request->pais);
      return $tramite;
    }

    //Consulta general de los tramites iniciados con parametros en fecha o/y estado (on/off)
    public function consultarTramitesPrecheck($fecha = '', $estado = ''){
      
      $fecha = ($fecha=='')?date("Y-m-d"):$fecha;

      $tramites =  Tramites::selectRaw('tramites.nro_doc,tramites_a_iniciar.nombre,tramites_a_iniciar.apellido,tramites.tramite_id')
                          ->join('tramites_a_iniciar','tramites_a_iniciar.tramite_dgevyl_id','tramites.tramite_id')
                          ->whereIn('tramites_a_iniciar.sigeci_idcita',$this->Sigeci->getTurnos($fecha)->pluck('idcita')->toArray())
                          ->orderby('tramites.nro_doc');

      if($estado == 'on')
        $tramites->whereIn('tramites_a_iniciar.id',$this->TramitesAIniciarCompletados($fecha)->pluck('id')->toArray());
      
      if($estado == 'off') 
        $tramites->whereNotIn('tramites_a_iniciar.id',$this->TramitesAIniciarCompletados($fecha)->pluck('id')->toArray());

      $consulta = $tramites->get();

      return $consulta;
    }

  //function get para API listar los tramites con licencias emitidas
  public function get_licencias_emitidas(Request $request){
    $ip = $request->ip();
    //IP permitidas para realizar la consulta: Daniela / Juan Ojeda / Pierre 
    $autorizadas = array('192.168.76.136','192.168.76.215','192.168.76.230');
    ///Secretaria de Atencion Ciudadana autorizados:
    array_push($autorizadas, '10.67.51.55','10.67.51.58','10.67.51.59','10.67.51.60');
    array_push($autorizadas, '10.10.14.37', '10.10.5.95');

    if(in_array($ip, $autorizadas)){ 

      $estado_finalizado = '95'; 
      $estado_completado = '14';

      //No limitar el limit Memory de PHP
      ini_set('memory_limit', '-1');

      $tramites =  Tramites::selectRaw('
                        tramites.nro_doc,
                        datos_personales.apellido,
                        datos_personales.nombre,
                        datos_personales.sexo,
                        licencias_otorgadas.nacionalidad,
                        datos_personales.fec_nacimiento,
                        datos_personales.correo,
                        datos_personales.calle as calle,
                        datos_personales.numero as altura,
                        tramites.sucursal,
                        tipo_tramites.descripcion AS tipo_tramite,
                        tramites.estado,
                        CAST(tramites.fec_inicio AS DATE),
                        CAST(tramites.fec_inicio AS TIME(0)) AS hora_inicio,
                        CAST(tramites_log.modification_date AS DATE) as fec_finalizacion,
                        CAST(tramites_log.modification_date AS TIME(0)) as hora_finalizacion,
                        CAST(tramites.fec_emision AS DATE),
                        CAST(tramites.fec_vencimiento AS DATE),
                        licencias_otorgadas.clase AS categoria')
                      ->join('licencias_otorgadas','licencias_otorgadas.tramite_id','tramites.tramite_id')
                      ->join('tipo_tramites','tipo_tramites.tipo_tramite_id','tramites.tipo_tramite_id')
                      ->join('datos_personales',function($join) {
                          $join->on('datos_personales.nro_doc', '=', 'tramites.nro_doc');
                          $join->on('datos_personales.tipo_doc', '=', 'tramites.tipo_doc');
                          $join->on('datos_personales.sexo', '=', 'tramites.sexo');
                      })
                      ->join('tramites_log',function($join) use($estado_finalizado) {
                        $join->on('tramites_log.tramite_id', 'tramites.tramite_id');
                        $join->where('tramites_log.estado', $estado_finalizado);
                      })
                      ->where('tramites.estado',$estado_completado)
                      ->orderby('tramites.fec_inicio');
      
      //validar si existen los parametros de busqueda por fecha (desde, hasta)
      if(!isset($request->desde) || !isset($request->hasta))
        return '<h2>Estimado usuario:</h2> Para realizar la consulta debe ingresar los parametros de búsqueda por fecha (desde,hasta). <br> Ejemplo: <h5>...api/reportes/get_licencias_emitidas?desde=2018-07-01&hasta=2018-07-31</h5>  <p>Puedes incluir tambien el parámetro de vencida para listar solo las licencias vencidas en ese rango de fechas.  <br> Ejemplo: <h5> ...api/reportes/get_licencias_emitidas?desde=2018-07-01&hasta=2018-07-31&vencida=true </h5> </p>';

      //Comprobar si este el parametro de vencida para poder hacer el filtro
      if($request->vencida) 
        $tramites->whereBetween('tramites.fec_vencimiento',[$request->desde,$request->hasta]);
      else
        $tramites->whereBetween('tramites.fec_emision',[$request->desde,$request->hasta]);

      //Se ejecuta la consulta final obtenida
      $consulta = $tramites->get();
      
      //Solo si existe el parametro para export en: xls, xlsx, txt, csv, entre otros.
      if($request->export) 
        $this->exportFile($consulta, $request->export, 'licenciasEmitidas');
    
    }else{
      $consulta = "Acceso denegado: IP no permitida!..";
    }
    return $consulta;
  }

    public function TramitesAIniciarCompletados($fecha) {
      $consulta = \DB::table('tramites_a_iniciar')
                        ->join('sigeci','sigeci.idcita','tramites_a_iniciar.sigeci_idcita')
                        ->where('sigeci.fecha',$fecha)
                        ->whereNotIn('tramites_a_iniciar.id', function($query) use($fecha) {
                          $query->select('validaciones_precheck.tramite_a_iniciar_id')
                                ->from("validaciones_precheck")
                                ->join('tramites_a_iniciar','tramites_a_iniciar.id','validaciones_precheck.tramite_a_iniciar_id')
                                ->join('sigeci','sigeci.idcita','tramites_a_iniciar.sigeci_idcita')
                                ->where('sigeci.fecha',$fecha)
                                ->where('validaciones_precheck.validado','false')
                                ->groupBy('validaciones_precheck.tramite_a_iniciar_id');  
                        })->get();
      return $consulta;
      
    }

  /*
  public function getValidacionesPrecheck($fecha, $validado='', $estado='') {

      $consulta = \DB::table("validaciones_precheck")
                      ->join('tramites_a_iniciar','tramites_a_iniciar.id','validaciones_precheck.tramite_a_iniciar_id')
                      ->whereIn('tramites_a_iniciar.sigeci_idcita',$this->Sigeci->getTurnos($fecha)->pluck('idcita')->toArray());
      if($estado)
        $consulta->where('validaciones_precheck.validation_id',$estado);
      
      if($validado)
        $consulta->where('validaciones_precheck.validado',$validado);

    return $consulta->get();
    
  }*/

}
