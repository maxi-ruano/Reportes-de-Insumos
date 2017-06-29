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
}
