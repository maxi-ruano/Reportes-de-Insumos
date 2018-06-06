<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tramites;

class DashboardController extends Controller
{
    public function consultaDashboard(Request $request){
        
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        
        //1)TOTAL TURNOS ASIGNADOS POR SIGECI / PRECHECK / SAFIT / LIBRE DEUDA / BUI
        $precheck = \DB::select("SELECT
                                count(DISTINCT sigeci.numdoc) as turnos,
                                count (DISTINCT tramites_a_iniciar.sigeci_idcita) as tramitesainiciar,
                                count (DISTINCT (case when tramites_a_iniciar.estado = 6 then sigeci.numdoc else null end) ) as tramitesainiciar_ok,
                                count (case when validaciones_precheck.validation_id = 3 and validaciones_precheck.validado=true then 1 else null end) as safit,
                                count (case when validaciones_precheck.validation_id = 4 and validaciones_precheck.validado=true then 1 else null end) as libredeuda,
                                count (case when validaciones_precheck.validation_id = 5 and validaciones_precheck.validado=true then 1 else null end) as bui
                            FROM sigeci
                            LEFT JOIN tramites_a_iniciar ON tramites_a_iniciar.sigeci_idcita = sigeci.idcita
                            LEFT JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id
                            WHERE sigeci.fecha = '".$fecha."'");
        
        $turnos                 = $precheck[0]->turnos;
        $tramitesainiciar       = $precheck[0]->tramitesainiciar;
        $tramitesainiciar_ok    = $precheck[0]->tramitesainiciar_ok;
        $safit                  = $precheck[0]->safit;
        $libredeuda             = $precheck[0]->libredeuda;
        $bui                    = $precheck[0]->bui;

        //2)TOTAL TRAMITES INICIADOS CON PRECHECK OK - OFF
        $tramites =  \DB::select("SELECT 
                                    count(DISTINCT tramites.nro_doc) as total_tramites,
                                    count(DISTINCT (case when tramites_a_iniciar.estado = 6 then tramites.nro_doc else null end)) as tramitesprecheck_on,
                                    count(DISTINCT (case when tramites_a_iniciar.estado <> 6 then tramites.nro_doc else null end)) as tramitesprecheck_off
                                FROM tramites 
                                INNER JOIN ansv_paises ON ansv_paises.id_dgevyl = tramites.pais
                                INNER JOIN tramites_a_iniciar ON tramites.nro_doc = CAST(tramites_a_iniciar.nro_doc AS varchar(10))
                                    AND tramites_a_iniciar.nacionalidad = ansv_paises.id_ansv
                                WHERE tramites.estado NOT IN('93','94') 
                                    AND tramites_a_iniciar.sigeci_idcita IN(SELECT idcita FROM sigeci WHERE fecha = '".$fecha."')
                                    AND CAST(tramites.fec_inicio as date) >= '".$fecha."'");

        $total_tramites         = $tramites[0]->total_tramites;        
        $tramitesprecheck_on    = $tramites[0]->tramitesprecheck_on;
        $tramitesprecheck_off   = $tramites[0]->tramitesprecheck_off;

        //Preparar un array para los datos a mostrar
        $datos_precheck[0] = ['titulo' => 'TURNOS', 'subtitulo' => 'en tramites a iniciar', 'total' => $turnos, 'porc' => '100', 'ico' => 'fa fa-user'];
        $datos_precheck[1] = ['titulo' => 'PRECHECK', 'subtitulo' => 'de los turnos', 'total' => $tramitesainiciar, 'porc' => $this->porcentaje($tramitesainiciar,$turnos), 'ico' => 'fa fa-check'];
        $datos_precheck[2] = ['titulo' => 'Precheck ON', 'subtitulo' => 'con PreCheck OK!', 'total' => $tramitesainiciar_ok, 'porc' => $this->porcentaje($tramitesainiciar_ok,$turnos), 'ico' => 'fa fa-clock-o'];
        $datos_precheck[3] = ['titulo' => 'SAFIT', 'subtitulo' => 'validados', 'total' => $safit, 'porc' => $this->porcentaje($safit,$turnos), 'ico' => 'fa fa-cloud-upload'];
        $datos_precheck[4] = ['titulo' => 'LIBRE DEUDA', 'subtitulo' => 'validados', 'total' => $libredeuda, 'porc' => $this->porcentaje($libredeuda,$turnos), 'ico' => 'fa fa-cloud-download'];
        $datos_precheck[5] = ['titulo' => 'BUI', 'subtitulo' => 'validados', 'total' => $bui, 'porc' => $this->porcentaje($bui,$turnos), 'ico' => 'fa fa-cloud-download'];

        $datos_tramites[0] = ['titulo' => 'TRAMITES', 'subtitulo' => 'de los turnos', 'total' => $total_tramites, 'porc' => $this->porcentaje($total_tramites,$turnos), 'ico' => 'fa fa-user'];
        $datos_tramites[1] = ['titulo' => 'con Precheck ON', 'subtitulo' => 'de los tramites', 'total' => $tramitesprecheck_on, 'porc' => $this->porcentaje($tramitesprecheck_on,$total_tramites), 'ico' => 'fa fa-check'];
        $datos_tramites[2] = ['titulo' => 'con Precheck OFF', 'subtitulo' => 'de los tramites', 'total' => $tramitesprecheck_off, 'porc' => $this->porcentaje($tramitesprecheck_off,$total_tramites), 'ico' => 'fa fa-clock-o'];

        //***************************************************************************************
        $fecha = date('d-m-Y', strtotime($fecha));

        return View('safit.consultaDashboard')->with('datos_precheck',$datos_precheck)
                                              ->with('datos_tramites',$datos_tramites)
                                              ->with('fecha',$fecha);

    }

  /*  public function consultaTotales(Request $request){

        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        
        //1)TOTAL TURNOS ASIGNADOS POR SIGECI / PRECHECK / SAFIT / LIBRE DEUDA / BUI
        $precheck = \DB::select("SELECT
                                count(DISTINCT sigeci.numdoc) as turnos,
                                count (DISTINCT tramites_a_iniciar.id) as tramitesainiciar,
                                count (DISTINCT (case when tramites_a_iniciar.estado = 6 then sigeci.numdoc else null end) ) as tramitesainiciar_ok,
                                count (case when validaciones_precheck.validation_id = 3 and validaciones_precheck.validado=true then 1 else null end) as safit,
                                count (case when validaciones_precheck.validation_id = 4 and validaciones_precheck.validado=true then 1 else null end) as libredeuda,
                                count (case when validaciones_precheck.validation_id = 5 and validaciones_precheck.validado=true then 1 else null end) as bui
                            FROM sigeci
                            LEFT JOIN tramites_a_iniciar ON tramites_a_iniciar.sigeci_idcita = sigeci.idcita
                            LEFT JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id
                            WHERE sigeci.fecha = '".$fecha."'");

        //2)TOTAL TRAMITES INICIADOS CON PRECHECK OK - OFF
        $tramites =  \DB::select("SELECT 
                                    count(DISTINCT tramites.nro_doc) as total_tramites,
                                    count(DISTINCT (case when tramites_a_iniciar.estado = 6 then tramites.nro_doc else null end)) as tramitesprecheck_on,
                                    count(DISTINCT (case when tramites_a_iniciar.estado <> 6 then tramites.nro_doc else null end)) as tramitesprecheck_off
                                FROM tramites 
                                INNER JOIN ansv_paises ON ansv_paises.id_dgevyl = tramites.pais
                                INNER JOIN tramites_a_iniciar ON tramites.nro_doc = CAST(tramites_a_iniciar.nro_doc AS varchar(10))
                                    AND tramites_a_iniciar.nacionalidad = ansv_paises.id_ansv
                                WHERE tramites.estado NOT IN('93','94') 
                                    AND tramites_a_iniciar.sigeci_idcita IN(SELECT idcita FROM sigeci WHERE fecha = '".$fecha."')
                                    AND CAST(tramites.fec_inicio as date) >= '".$fecha."'");

        $sql = array_merge($precheck,$tramites);

        $consulta = json_encode($sql);

        //dd('entro a consultaTotales');

        return response()->json($consulta);
    } */

    public function obtenerSucursales(){
        $sucursales =  \DB::table('sys_multivalue')
                        ->select('id','description')                        
                        ->where('type', 'SUCU')
                        ->whereNotIn('id', ['2','3','20','80','90','101','102','104','121','150'])
                        ->orderBy('description')
                        ->get();
        return $sucursales;
    }

    public function consultaTurnosPorEstacion(Request $request){
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        
        $sql = Tramites::selectRaw("tramites.sucursal, tramites.estado, (case when tramites.estado = 1 then 'Fotografia' else sys_multivalue.description end) as name, count(tramites.tramite_id) as cant")
                    ->join('sys_multivalue',function($join) {
                        $join->on('sys_multivalue.id', '=', 'tramites.estado')
                             ->whereRaw("sys_multivalue.type = 'STAT' ");
                    })
                    ->whereRaw("CAST(tramites.fec_inicio as date) = '".$fecha."'")
                    ->groupBy('tramites.sucursal','tramites.estado','sys_multivalue.description')
                    ->orderBy('tramites.sucursal','tramites.estado')
                    ->toSql();
        $consulta =  \DB::table(\DB::raw('('.$sql.') as consulta'))
                        ->selectRaw('sucursal, name, SUM(cant) as value')
                        ->whereIn('estado', ['1','2','3','4','5','12','13'])
                        ->where('sucursal','=',$request->sucursal)
                        ->groupBy('sucursal','name')
                        ->orderBy('sucursal',\DB::raw('MAX(estado)'))
                        ->get();
        return $consulta;
    }

    public function porcentaje($valor,$base){
        $porc = ( $valor > 0 )?round($valor*100/$base):0;
        return $porc;
    }
}