<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AnsvControl;
use App\AnsvDescartes;
use App\AnsvLotes;
use App\SysMultivalue;
use App\SysUsers;
use App\ControlSecuenciaInsumos;
use App\Insumos2;
use App\Sucursal;

class ReportesController extends Controller
{
    function reporteControlInsumos(){
     
      // $excludedIds = [150,100];
      // $sucursalTipo = 'SUCU';

      $sucursales = SysMultivalue::where('type', 'SUCU')->get();
     

      $res = array();

      foreach ($sucursales as $key => $sucursal) {
        //  dd($sucursal->id);
        $lotes= AnsvLotes::where('sucursal_id', $sucursal->id)
                        //  ->whereBetween('creation_date')
                         ->get();
                        //  dd($lotes);
        foreach ($lotes as $key => $lote) {
          // dd($lote->id);
          //posiblemnte pordriamos agregar where fecha > a la fecha del lote
          $impresos = AnsvControl::select('nro_control')
                                 ->whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
                                 ->where('liberado', 'false')
                                 ->orderBy('nro_control', 'asc')->get();
  // dd($impresos);
          $asignados = AnsvControl::select('nro_control')
                                  ->whereBetween('nro_control', [$lote->control_desde, $lote->control_hasta])
                                  ->groupBy('nro_control')->get();
          $faltantesSecuencia = $this->getFaltantesSecuencia($impresos);
          $cantidadCodificados = count($impresos);
        
          $descartados = AnsvDescartes::whereBetween('control', [$lote->control_desde, $lote->control_hasta])->get();
    //  dd($descartados);
          $division = $this->dividirImpresosDescartados($impresos, $descartados);
// dd($division);
          $descartadosImpresos = $division[0];

          // dd($descartadosImpresos);
          $arrayDescartadosImpresos = json_decode(json_encode($descartadosImpresos), true);
          // dd($arrayDescartadosImpresos);
          $descartesi = count($arrayDescartadosImpresos);
          // dd($descar)
          $descartadosNoImpresos = $division[1];
          $arrayDescartadosNoImpresos = json_decode(json_encode($descartadosNoImpresos), true);
          $descartesn = count($arrayDescartadosNoImpresos);

           $cantidadDescartados = count($descartados);
          //  dd($cantidadDescartados);
          $cantidadLote = $lote->control_hasta - $lote->control_desde + 1;
          // dd($cantidadLote);
          $cantidadFaltantes = $lote->control_hasta - $impresos[count($impresos) - 1]['nro_control']; //$cantidadLote - ($cantidadImpresos + $cantidadDescartados);
          // dd($cantidadFaltantes);
          $subRes = array(
              "sucursal"    => $sucursal->description,
              "loteDesde"  => $lote->control_desde,
              "loteHasta"  => $lote->control_hasta,
              "cantidadLote" => $cantidadLote,
              "cantidadCodificados" => $cantidadCodificados,
           
              "cantidadDescartados" => $cantidadDescartados
            
          );
          array_push($res, (object)$subRes);
        } break;
      }

      return View('reportes.reporteControlInsumos')->with('lotes', (object)$res);
    }

    function getFaltantesSecuencia($impresos)
    {
      $anterior = null;
      $res = array();
      $cadena = '';
      foreach ($impresos as $value) {
        $actual = $value->nro_control;
        if(is_null($anterior)){
          $anterior = $actual;
        }

        else
          if($anterior == ($actual-1))
            $anterior = $actual;
          else{
            $res = array_merge($res,$this->generarNumerosEntre($anterior,$actual));
            $anterior = $actual;
          }
      }

      return count($res);
      //return $cadena;
    }

    function generarNumerosEntre($ini,$fin){
      $res = array();
      $ini+=1;
      for($i = $ini; $i < $fin; $i++){
        array_push($res, $i);
      }
      return $res;
    }

    function dividirImpresosDescartados($impresos, $des){
      $descartadosImpresos = array();
      $descartadosNoImpresos = array();
      $res = array();
      $descartados = $des;

      foreach($impresos as $impreso){
        foreach($descartados as $descartado){
          if($impreso->nro_control == $descartado->control)
            array_push($descartadosImpresos, $descartado->control);
          else
            array_push($descartadosNoImpresos, $descartado->control);
        }
      }
      $res [0] = (object)$descartadosImpresos ;
      $res [1] = (object)$descartadosNoImpresos ;
      return $res;
    }

    function reporteSecuenciaInsumos(){
      $controlSecuenciaInsumos = ControlSecuenciaInsumos::all();
      foreach ($controlSecuenciaInsumos as $key => $value) {
        $sucursal = SysMultivalue::where('type', 'SUCU')->where('id', $value->sucursal)->first();
        $datosPersonales = SysUsers::where('id',$value->user_id)->first();
        $value->sucursal = $sucursal->description;
        $value->userName = $datosPersonales->first_name.' '.$datosPersonales->last_name;

      }

      $noJustificados = ControlSecuenciaInsumos::where('justificado','false')->count();
      return view('reportes.reporteSecuenciaInsumos')->with('items', $controlSecuenciaInsumos)
                                                     ->with('noJustificados', $noJustificados);
    }

    function justificar(Request $request){
      $justificado = controlSecuenciaInsumos::find($request->id);;
      return view('justificaciones.new', compact('justificado'));
    }

    function justificacionStore(Request $request){
      if(!empty(session('usuario_id')) && !(empty($request->descripcion))){
        $crtl = controlSecuenciaInsumos::find($request->id);
        $crtl->justificacion = $request->descripcion;
        $crtl->justificado = true;
        $crtl->user_justificacion = session('usuario_id');
        $crtl->fecha_justificacion = date("Y-m-d H:i:s");
        $crtl->save();
      }

      return redirect()->route('reporteSecuenciaInsumos');
    }

    function mostrarJustificacion(Request $request){
        $crtl = controlSecuenciaInsumos::find($request->id);
        $this->getDatos($crtl);
        return view('justificaciones.show')->with('controlSecuenciaInsumos', $crtl);
    }

    function getDatos($controlSecuenciaInsumos){
        $sucursal = SysMultivalue::where('type', 'SUCU')->where('id', $controlSecuenciaInsumos->sucursal)->first();
        $controlSecuenciaInsumos->sucursal = $sucursal->description;
        $datosPersonales = SysUsers::where('id',$controlSecuenciaInsumos->user_id)->first();
        $controlSecuenciaInsumos->userName = $datosPersonales->first_name.' '.$datosPersonales->last_name;
        $datosPersonales = SysUsers::where('id',$controlSecuenciaInsumos->user_justificacion)->first();
        if(!empty($datosPersonales))
          $controlSecuenciaInsumos->user_justificacion = $datosPersonales->first_name.' '.$datosPersonales->last_name;
    }
}
