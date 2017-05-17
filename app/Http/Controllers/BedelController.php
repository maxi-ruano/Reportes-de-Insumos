<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SysMultivalue;

use App\Tramites;

use App\TramitesFull;

use App\EtlExamen;

use App\TeoricoPc;

use App\AnsvAmpliaciones;

class BedelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      //Datos por defecto
      $default = $this->defaultParams();
      //Busqueda de tramite
      if ($request->op == 'find') {
        $peticion = $this->findTramite($request->doc, (int)$request->tipo_doc, strtolower($request->sexo), $request->pais);
        if ($peticion):
          $peticion = $this->validarEncontrados($peticion);
        endif;
        //$test = $this->api_get('http://192.168.76.233/api_dc.php',array('function' => 'get','tipo_doc' => (int)$request->tipo_doc, 'nro_doc' => $request->doc, 'sexo' => strtolower($request->sexo), 'pais' => $request->pais));
        //dd($test);
      }
      // SI existe peticion fine, si no existe agregale false
      $peticion = $peticion ?? array(false);
      return view('bedel.asignacion')->with('default',$default)->with('peticion',$peticion);
    }
    /**
     *
     * Funcion findTramite - Para buscar los tramites disponibles para rendir
     */
    public function findTramite($nro_doc, $tipo_doc, $sexo, $pais)
    {
      $response_array = array();
      if($nro_doc AND $tipo_doc AND $sexo AND $pais):
        $posibles = TramitesFull::where('nro_doc', $nro_doc)
        ->where('tipo_doc', $tipo_doc)
        ->where('sexo', $sexo)
        ->where('pais', $pais)
        ->where('estado', 8)
        ->orderBy('tramite_id', 'asc')
        ->first();

        if (count($posibles) > 0) {
          array_push($response_array,true);
          array_push($response_array,$posibles);
        }
        else {
          array_push($response_array,false);
        }
      else:
        array_push($response_array,false);
      endif;
      return $response_array;
    }
    /**
     *
     * Funcion validarEncontrados - Valida clase_value, clase_otorgada y si esta detenido el tramite
     */
     public function validarEncontrados($peticion)
     {
       if ($peticion[0]):
         if ($peticion[1]->clase_value == 'NADA' OR $peticion[1]->clase_otorgada_value == 'NADA') {
           $get_class = AnsvAmpliaciones::where('tramite_id', $peticion[1]->tramite_id)->first();
           $peticion[1]->clase_value = $get_class->clases_dif;
           $peticion[1]->clase_otorgada_value = $get_class->clases_dif;
         }

         if ($peticion[1]->detenido == 0) {
           $peticion[1]->motivo_detencion_value = 'NO';
         }
         return $peticion;
       endif;
     }
     /**
      *
      * Funcion defaultParams - Trae los valores por defecto y los retorna en un array
      */
      public function defaultParams()
      {
        $default['paises'] = SysMultivalue::where('type','PAIS')->orderBy('description', 'asc')->get(['id','description']);
        $default['tdoc'] = SysMultivalue::where('type','TDOC')->orderBy('id', 'asc')->get(['id','description']);
        $default['sexo'] = SysMultivalue::where('type','SEXO')->orderBy('id', 'asc')->get(['id','description']);
        return $default;
      }
      /**
       * Funcion api_get - Hace una peticiones get, se le pasa la url y un array asociativo con los parametros
       * $test = $this->api_get('http://192.168.76.233/api_dc.php', array('doc' => $request->doc, 'tdoc' => (int)$request->tipo_doc));
       */
       function api_get($url, $params)
       {
          $url .= "?";
          foreach ($params as $key => $value)
          {
            $url .= $key . "=" . $value . "&";
          }
          $url = substr($url,0,-1);

          $ch = curl_init();

          curl_setopt($ch,CURLOPT_URL,$url);
          curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
          //  curl_setopt($ch,CURLOPT_HEADER, false);

          $output=curl_exec($ch);

          curl_close($ch);
          $res = json_decode($output, false);
          return $res;
       }
}
