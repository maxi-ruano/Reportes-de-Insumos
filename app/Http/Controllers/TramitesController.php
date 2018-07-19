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

}
