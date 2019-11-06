<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WsCharlaVirtualController extends Controller
{
    private $url;
    private $userName;
    private $userPassword;
    private $wsEnabled;
    
    public function __construct(){
      $this->crearConstantes();
      $this->url = CharlaVirtualWS_ws_url;
      $this->userName = CharlaVirtualWS_userName;
      $this->userPassword = CharlaVirtualWS_userPass;
      $this->wsEnabled = CharlaVirtualWS_enabled;
    }

    public function consultar($tramite)
    {
        $success = false;
        $message = '';
        $response = '';
        try {
            
            $request = $this->url."?dni=".$tramite->nro_doc."&sexo=".strtolower($tramite->sexo);
            $response = file_get_contents($request, false);

            $success = $response->encontrado;
            $message = isset($response->descripcion)?$response->descripcion:'';

        }catch(\Exception $e) {
            $message = $e->getMessage();
        }

        $salida = array(
            'success' => $success,
            'error' => $message,
            'request' => parse_url($request), 
            'response' => $response 
        );
        return (object) $salida;
    }
}
