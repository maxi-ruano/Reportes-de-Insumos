<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SysMultivalue;

use App\Tramites;

use App\TramitesFull;

use App\EtlExamen;

class BedelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $paises = SysMultivalue::where('type','PAIS')->orderBy('description', 'asc')->get();
      $tdoc = SysMultivalue::where('type','TDOC')->orderBy('id', 'asc')->get();
      $sexo = SysMultivalue::where('type','SEXO')->orderBy('id', 'asc')->get();
    // /dd($request->doc.' '. $request->sexo.' '. $request->pais);
      $row = array();
      if (isset($request->doc) && $request->doc != '' && isset($request->sexo) && $request->sexo != '' && isset($request->pais) && $request->pais != '' && isset($request->tipo_doc) && $request->tipo_doc != '') {
        //dd($request->doc.' '. $request->sexo.' '. $request->pais);
        $get_posibles = $this->getTramiteExactly($request->doc, $request->tipo_doc,$request->sexo, $request->pais);

        $lista = array();

        foreach($get_posibles[1] as $id => $peticion)
        {
          $bool = $this->existe_valor($row, $peticion);
          if (!$bool) {
            if ($peticion->detenido == 0) {
              $peticion->motivo_detencion_value = 'NO';
            }
            array_push($row, $peticion);
          }

          /*
          if (!in_array($peticion->tramite_id,$lista)) {
            $row['tramite_id'] = $peticion->tramite_id;
            if($peticion->detenido == 0)
                    $row['motivo_detencion_value'] = 'NO';
            $rows[$row['tramite_id']] = $peticion;
            array_push($lista, $peticion->tramite_id);
          }*/
        }

      }
      $peticion = $peticion ?? array(false);
      return view('bedel.asignacion')->with('paises',$paises)->with('tipo_doc',$tdoc)->with('sexo',$sexo)->with('peticion',$row);
    }
    public function existe_valor($array, $objeto)
    {
      foreach ($array as $key => $value)
        if($value->tramite_id == $objeto->tramite_id)
          return true;
      return false;
    }

    public function getTramiteExactly($nro_doc, $tipo_doc, $sexo, $pais)
    {
      $response_array = array();
      $posibles = TramitesFull::distinct()
      ->where('nro_doc', $nro_doc)
      ->where('tipo_doc', $tipo_doc)
      ->where('sexo', $sexo)
      ->where('pais', $pais)
//      ->where('estado', 8)
      ->orderBy('tramite_id', 'asc')
      ->get();

      if (count($posibles) > 0) {
        array_push($response_array,true);
        array_push($response_array,$posibles);
      }
      else {
        array_push($response_array,false);
      }
      return $response_array;
    }

    public function getExamenByTramite($tramite_id)
    {
      $response_array = array();
      //$posibles = EtlExamen::where('tramite_id',$tramite_id)->where()
    }

}
