<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sigeci;
use App\Tramites;
use App\TramitesAIniciar;
use App\SysMultivalue;
use App\Http\Controllers\SigeciController;
use App\Http\Controllers\TramitesController;
use App\Http\Controllers\TramitesAIniciarController;

class DashboardController extends Controller
{
    public function __construct() {
        $this->Sigeci = new SigeciController();
        $this->Tramites = new TramitesController();
    }


    public function consultaDashboard(Request $request){
        
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        
        //1)TOTAL TURNOS ASIGNADOS POR SIGECI / PRECHECK / SAFIT / LIBRE DEUDA / BUI
        $precheck = \DB::select("SELECT
                                count(DISTINCT sigeci.numdoc) as turnos,
                                count (DISTINCT case when tramites_a_iniciar.estado = 7 then tramites_a_iniciar.sigeci_idcita else null end) as sinalic,
                                count (case when validaciones_precheck.validation_id = 3 and validaciones_precheck.validado=true then 1 else null end) as safit,
                                count (case when validaciones_precheck.validation_id = 4 and validaciones_precheck.validado=true then 1 else null end) as libredeuda,
                                count (case when validaciones_precheck.validation_id = 5 and validaciones_precheck.validado=true then 1 else null end) as bui
                            FROM sigeci
                            LEFT JOIN tramites_a_iniciar ON tramites_a_iniciar.sigeci_idcita = sigeci.idcita
                            LEFT JOIN validaciones_precheck ON validaciones_precheck.tramite_a_iniciar_id = tramites_a_iniciar.id
                            WHERE sigeci.fecha = '".$fecha."'");
    
        $turnos         = $this->Sigeci->getTurnos($fecha)->count();
        $precheck_ok    = $this->Tramites->TramitesAIniciarCompletados($fecha)->count();
        $safit          = $precheck[0]->safit;
        $libredeuda     = $precheck[0]->libredeuda;
        $bui            = $precheck[0]->bui;
        $sinalic        = $precheck[0]->sinalic;

        //2)TOTAL TRAMITES INICIADOS CON PRECHECK ON - OFF
        $total_tramites         = $this->Tramites->consultarTramitesPrecheck($fecha)->count();
        $tramitesprecheck_on    = $this->Tramites->consultarTramitesPrecheck($fecha,'on')->count();
        $tramitesprecheck_off   = $this->Tramites->consultarTramitesPrecheck($fecha,'off')->count();

        //Preparar un array para los datos a mostrar
        $datos_precheck[0] = ['titulo' => 'PRECHECK OK', 'subtitulo' => 'con PreCheck OK!', 'total' => $precheck_ok, 'porc' => $this->porcentaje($precheck_ok,$turnos), 'ico' => 'fa fa-clock-o'];
        $datos_precheck[1] = ['titulo' => 'SAFIT', 'subtitulo' => 'validados', 'total' => $safit, 'porc' => $this->porcentaje($safit,$turnos), 'ico' => 'fa fa-cloud-upload'];
        $datos_precheck[2] = ['titulo' => 'LIBRE DEUDA', 'subtitulo' => 'validados', 'total' => $libredeuda, 'porc' => $this->porcentaje($libredeuda,$turnos), 'ico' => 'fa fa-cloud-download'];
        $datos_precheck[3] = ['titulo' => 'BUI', 'subtitulo' => 'validados', 'total' => $bui, 'porc' => $this->porcentaje($bui,$turnos), 'ico' => 'fa fa-cloud-download'];
       
        $datos_tramites[0] = ['titulo' => 'SINALIC', 'subtitulo' => 'Iniciados desde el Preckeck', 'total' => $sinalic, 'porc' => $this->porcentaje($sinalic,$turnos), 'ico' => 'fa fa-cloud-download'];
        $datos_tramites[1] = ['titulo' => 'TRAMITES', 'subtitulo' => 'se ha iniciado en LICTA', 'total' => $total_tramites, 'porc' => $this->porcentaje($total_tramites,$turnos), 'ico' => 'fa fa-user'];
        $datos_tramites[2] = ['titulo' => 'TRAMITES OK', 'subtitulo' => 'de tramites en LICTA', 'total' => $tramitesprecheck_on, 'porc' => $this->porcentaje($tramitesprecheck_on,$turnos), 'ico' => 'fa fa-check'];
        $datos_tramites[3] = ['titulo' => 'TRAMITES OFF', 'subtitulo' => 'de tramites en LICTA', 'total' => $tramitesprecheck_off, 'porc' => $this->porcentaje($tramitesprecheck_off,$turnos), 'ico' => 'fa fa-clock-o'];

        //***************************************************************************************
        $fecha = date('d-m-Y', strtotime($fecha));

        return View('safit.consultaDashboard')->with('turnos',$turnos)
                                              ->with('datos_precheck',$datos_precheck)
                                              ->with('datos_tramites',$datos_tramites)
                                              ->with('fecha',$fecha);
        
    }

    public function consultaDashboardGraf(Request $request){
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        return View('safit.consultaDashboardGraf')->with('fecha',$fecha);
    }

    /*Consulta general para obtener el listado de sucursales */
    public function obtenerSucursales(){
        $sucursales =  SysMultivalue::select('id','description',\DB::raw("(Case When split_part(description,' ',3) = '' then split_part(description,' ',1) else split_part(description,' ',3) end) as name"))
                        ->where('type', 'SUCU')
                        ->orderBy('id')
                        ->get();
        return $sucursales;
    }

    public function consultaTurnosEnEspera(Request $request){
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        $fechasiguiente = date("d-m-Y",strtotime($fecha."+ 1 day"));

        $tramitesDetenidos =  \DB::table('tramites_log')->select('tramite_id')->whereRaw("detenido = 'R' AND fec_inicio >= '".$fecha."' AND fec_inicio < '".$fechasiguiente."' ");
        
        $sql = SysMultivalue::selectRaw("sys_multivalue.id, MAX(case when sys_multivalue.id = 1 then 'Fotografia' else sys_multivalue.description end) as description, count(tramites.tramite_id) as cant")
                    ->leftjoin('tramites',function($join) use($fecha, $request) {
                        $join->on('tramites.estado', '=', 'sys_multivalue.id')
                        ->whereRaw("CAST(tramites.fec_inicio as date) = '".$fecha."'")
                        ->whereRaw("tramites.sucursal = '".$request->sucursal."'");
                    })
                    ->whereRaw("sys_multivalue.type = 'STAT' ")
                    ->whereRaw("sys_multivalue.id IN('1','2','3','4','5','6','12','13') ")
                    ->whereNotIn('tramites.tramite_id', $tramitesDetenidos)
                    ->groupBy('sys_multivalue.id')
                    ->orderBy('sys_multivalue.id')
                    ->toSql();

        $consulta =  \DB::table(\DB::raw('('.$sql.') as consulta'))
                        ->selectRaw('description as name, SUM(cant) as value')
                        ->groupBy('description')
                        ->orderBy(\DB::raw('MAX(id)'))
                        ->get();
        return $consulta;
    
    }

    public function consultaTurnosEnEsperaPorSucursal(Request $request){

        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        $fechasiguiente = date("d-m-Y",strtotime($fecha."+ 1 day"));

        $tramitesDetenidos =  \DB::table('tramites_log')->select('tramite_id')->whereRaw("detenido = 'R' AND fec_inicio >= '".$fecha."' AND fec_inicio < '".$fechasiguiente."' ");
        
        //Nuevo array para mostrar totales en cada sede por estacion
        $consulta = Tramites::selectRaw("tramites.sucursal as sucursal_id, (case when sys_multivalue.id = 1 then 2 else sys_multivalue.id end) as estacion_id, MAX(case when sys_multivalue.id = 1 then 'Fotografia' else sys_multivalue.description end) as estacion, count(tramites.tramite_id) as cant")
                        ->leftjoin('sys_multivalue',function($join)    {
                            $join->on('tramites.estado', '=', 'sys_multivalue.id')
                            ->whereRaw("sys_multivalue.type = 'STAT' ");
                        })
                        ->whereRaw("sys_multivalue.id IN('1','2','3','4','5','6','12','13') ")
                        ->whereRaw("tramites.sucursal NOT IN('2','3','20','80','90','101','102','104','121','150')")
                        ->whereRaw("CAST(tramites.fec_inicio as date) = '".$fecha."'")
                        ->groupBy('tramites.sucursal','estacion_id')
                        ->orderBy('estacion_id');
                        
        //Se filter solo los tramites que no esten detenidos
        $consulta = $consulta->whereNotIn('tramites.tramite_id', $tramitesDetenidos)->get();

        return $consulta;
    }

    public function porcentaje($valor,$base){
        $porc = ( $valor > 0 && $base >0 )?round($valor*100/$base):0;
        return $porc;
    }

    public function comparacionPrecheck(Request $request){
        $fecha = isset($request->fecha)?date('Y-m-d', strtotime($request->fecha)):date('Y-m-d');
        $tresdiasantes = date ( 'Y-m-j' , strtotime ( '-3 day' , strtotime ( $fecha ) ) );
        $undiaantes = date ( 'Y-m-j' , strtotime ( '-1 day' , strtotime ( $fecha ) ) );

        $res = $this->getCantidadErrores($fecha, $tresdiasantes);
        $resBui = $this->getCantidadErrores($fecha, $tresdiasantes, 5);
        $resInfracciones = $this->getCantidadErrores($fecha, $tresdiasantes, 4);
        $resSafit = $this->getCantidadErrores($fecha, $tresdiasantes, 3);
        $res1 = $this->getCantidadErrores($fecha, $undiaantes);
        $res1Bui = $this->getCantidadErrores($fecha, $undiaantes, 5);
        $res1Infracciones = $this->getCantidadErrores($fecha, $undiaantes, 4);
        $res1Safit = $this->getCantidadErrores($fecha, $undiaantes, 3);

        echo "Fecha Consulta $request->fecha <br>";
        echo "<br>";
        echo "Total errores 72 hrs antes ".$tresdiasantes .": ".$res." <br>";
        echo "Total errores 24 hrs antes ".$undiaantes .": ".$res1." <br>";
        echo "<br>";
        echo "Detalle errores 72 hrs antes $tresdiasantes <br>";
        echo "<br>";
        echo "BUI : ".$resBui." <br>";
        echo "INRFACCIONES : ".$resInfracciones." <br>";
        echo "SAFIT : ".$resSafit." <br>";
        echo "<br>";
        echo "Detalle errores 24 hrs antes $undiaantes <br>";
        echo "<br>";
        echo "BUI : ".$res1Bui." <br>";
        echo "INRFACCIONES : ".$res1Infracciones." <br>";
        echo "SAFIT : ".$res1Safit." <br>";
    }

    public function getCantidadErrores($fechaTurno, $fechaCheckeo, $estado = false){
        $sql = "SELECT e.tramites_a_iniciar_id
                FROM tramites_a_iniciar_errores e, sigeci s
                WHERE e.tramites_a_iniciar_id = s.tramite_a_iniciar_id
                AND s.fecha = '$fechaTurno'
                AND e.created_at::date = '$fechaCheckeo' ";
        if($estado) 
            if($estado == 3)
                $sql.="AND ( e.estado_error = $estado OR e.estado_error = 2 )"; 
            else    
                $sql.="AND e.estado_error = $estado"; 
        $sql.= " GROUP BY e.tramites_a_iniciar_id";                 
        $res = \DB::select($sql);
        return count($res);                            
    }
}