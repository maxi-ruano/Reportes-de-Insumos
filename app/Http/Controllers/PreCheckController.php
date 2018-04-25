<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\AnsvPaises;
use App\SysMultivalue;
use App\SigeciPaises;
use App\TramitesAIniciarErrores;

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
    $nacionalidad = AnsvPaises::where('id_dgevyl', $request->nacionalidad)->first();

    $tramiteAIniciar = TramitesAIniciar::where('nro_doc', $request->nro_doc)
                           ->where('nacionalidad', $nacionalidad->id_ansv)
                           ->where('tipo_doc', $request->tipo_doc)
                           ->where('sexo', $request->sexo)
                           ->first();
    if($tramiteAIniciar){
      $precheck =  \DB::table('validaciones_precheck as v')
                      ->select('v.tramite_a_iniciar_id', 'v.validado', 's.description', 'v.validation_id')
                      ->join('sys_multivalue as s', 's.id', '=', 'v.validation_id')
                      ->where('s.type', 'VALP')
                      ->where('v.tramite_a_iniciar_id', $tramiteAIniciar->id)
                      ->get();
      $id = SigeciPaises::where('id', $nacionalidad->id_sigeci_paises)->first();
      if($id)
        $tramiteAIniciar->nacionalidad = $id->pais;
      else
        $tramiteAIniciar->nacionalidad = "";
      $precheck = $this->getErroresTramite($precheck);
      return response()->json(['datosPersona' => $tramiteAIniciar, 'precheck' => $precheck]);
    }
  }

  public function  getErroresTramite($precheck){
    foreach ($precheck as $key => $value) {
      $value->error = TramitesAIniciarErrores::where('estado_error', $value->validation_id)
                             ->where('tramites_a_iniciar_id', $value->tramite_a_iniciar_id)
                             ->select('description', 'id', 'created_at')
                             ->orderBy('id', 'desc')
                             ->first();
    }
    return $precheck;
  }
}
