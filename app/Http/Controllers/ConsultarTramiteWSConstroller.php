<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoapController;

class ConsultarTramiteWSController extends Controller
{
  var $client = null;

  public function __construct()
  {
    $this->client = new SoapController();
  }

  public function nuevo(){
    $this->ConsultarTramites('35124321', 1, 'm');
  }

  public function ConsultarTramites($nroDocumento, $tipoDocumento, $sexo){
    $parametros = $this->ConsultarTramiteParametros($nroDocumento, $tipoDocumento, $sexo);
    $ws = $this->client->getClienteSoap();
    $response = $ws->ConsultarTramites($parametros);
    return $response;
  }

  public function ConsultarTramiteParametros($nroDocumento, $tipoDocumento, $sexo){
    $parametros = array();
    $parametros['nroDocumento'] = $nroDocumento;
    $parametros['tipoDocumento'] = $tipoDocumento;
    $parametros['Sexo'] = $sexo;
    return $parametros;
  }

}
