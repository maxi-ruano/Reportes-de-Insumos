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

      $tramites =  Tramites::selectRaw('tramites.nro_doc,MAX(tramites_a_iniciar.nombre) as nombre,MAX(tramites_a_iniciar.apellido) as apellido,tramites.tramite_id')
                          ->join('ansv_paises','ansv_paises.id_dgevyl','tramites.pais')
                          ->join('tramites_a_iniciar',function($join) {
                              $join->on('tramites_a_iniciar.nacionalidad', '=', 'ansv_paises.id_ansv');
                              $join->on('tramites.nro_doc', '=', \DB::raw('CAST(tramites_a_iniciar.nro_doc AS varchar(10))'));
                          })
                          ->whereIn('tramites_a_iniciar.sigeci_idcita',$this->Sigeci->getTurnos($fecha)->pluck('idcita')->toArray())
                          ->whereNotIn('tramites.estado',$this->estadosIgnore)
                          ->whereRaw("CAST(tramites.fec_inicio as date) >= '".$fecha."' ")
                          ->groupBy('tramites.tramite_id')
                          ->orderby('tramites.nro_doc');

      if($estado == 'on')
        $tramites->whereIn('tramites_a_iniciar.id',$this->TramitesAIniciarCompletados($fecha)->pluck('id')->toArray());
      
      if($estado == 'off') 
        $tramites->whereNotIn('tramites_a_iniciar.id',$this->TramitesAIniciarCompletados($fecha)->pluck('id')->toArray());

      $consulta = $tramites->get();

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
