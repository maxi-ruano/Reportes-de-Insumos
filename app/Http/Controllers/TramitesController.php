<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tramites;

class TramitesController extends Controller
{
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

      $tramites =  Tramites::selectRaw('tramites.nro_doc,MAX(tramites_a_iniciar.nombre) as nombre,MAX(tramites_a_iniciar.apellido) as apellido,tramites.tramite_id')
                          ->join('ansv_paises','ansv_paises.id_dgevyl','tramites.pais')
                          ->join('tramites_a_iniciar',function($join) {
                              $join->on('tramites_a_iniciar.nacionalidad', '=', 'ansv_paises.id_ansv');
                              $join->on('tramites.nro_doc', '=', \DB::raw('CAST(tramites_a_iniciar.nro_doc AS varchar(10))'));
                          })
                          ->whereIn('tramites_a_iniciar.sigeci_idcita', function($query) use ($fecha) {
                              $query->select('idcita')->from('sigeci')->where('fecha',$fecha);
                          })
                          ->whereNotIn('tramites.estado',['93','94'])
                          ->whereRaw("CAST(tramites.fec_inicio as date) >= '".$fecha."' ")
                          ->groupBy('tramites.tramite_id')
                          ->orderby('tramites.nro_doc');

      if($estado == 'on') 
        $tramites->where('tramites_a_iniciar.estado','6');
      
      if($estado == 'off') 
        $tramites->where('tramites_a_iniciar.estado','!=','6');

      $consulta = $tramites->get();

      return $consulta;
    }


  //function get para API listar los tramites con licencias emitidas
  public function get_licencias_emitidas(Request $request){
    $estado_finalizado = '95';
    $estado_completado = '14';

    $tramites =  Tramites::selectRaw('
                      tramites.tramite_id,
                      tramites.nro_doc,
                      datos_personales.apellido,
                      datos_personales.nombre,
                      datos_personales.sexo,
                      licencias_otorgadas.nacionalidad,
                      datos_personales.fec_nacimiento,
                      datos_personales.correo,
                      tramites.sucursal,
                      tipo_tramites.descripcion AS tipo_tramite,
                      tramites.estado,
                      CAST(tramites.fec_inicio AS DATE),
                      CAST(tramites.fec_inicio AS TIME(0)) AS hora_inicio,
                      CAST(tramites_log.modification_date AS DATE) as fec_finalizacion,
                      CAST(tramites_log.modification_date AS TIME(0)) as hora_finalizacion,
                      CAST(tramites.fec_emision AS DATE),
                      CAST(tramites.fec_vencimiento AS DATE),
                      ansv_control.nro_control AS nro_insumo,
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
                    ->join('ansv_control',function($join) {
                      $join->on('ansv_control.tramite_id', '=', 'tramites.tramite_id');
                      $join->where('ansv_control.liberado', 'false');
                    })
                    ->where('tramites.estado',$estado_completado)
                    ->orderby('tramites_log.modification_date');
    
    if($request->vencida) 
      $tramites->whereBetween('tramites.fec_vencimiento',[$request->desde,$request->hasta]);
    else
      $tramites->whereBetween('tramites.fec_emision',[$request->desde,$request->hasta]);

    $consulta = $tramites->get();

    return $consulta;

  }

}
