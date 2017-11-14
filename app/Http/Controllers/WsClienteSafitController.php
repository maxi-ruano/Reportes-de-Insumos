<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoapController;
use SoapClient;


class WsClienteSafitController extends Controller
{
  var $url = 'https://testing.safit.com.ar/service/s_001.php?wsdl';
  var $uswID = '000016';
  var $uswPassword = 'twe546av1e89as4';
  var $uswHash = 'e10adc3949ba59abbe56e057f20f883e';
  var $munID = '1';
  var $ingID = null;
  var $wsSafit = [];
  var $cliente = null;
  var $sesID = null;

  public function iniciarSesion(){
    $res = $this->cliente->abrir_sesion( $this->uswID,
                                         $this->uswPassword,
                                         $this->uswHash );
    $this->sisID = $res->sesID;
    $this->ingID = $res->ingID;
    //dd($this->cliente->__getFunctions());
  }

  public function cerrarSesion(){
    $parametros = array();
    $parametros['uswID'] = $uswID;
    $parametros['uswPassword'] = $uswPassword;
    $parametros['uswHash'] = $uswHash;

  }

  public function getBoletas($persona){
    //dd($this->cliente->__getFunctions());
    $res = $this->cliente->consultar_boleta_pago_persona($this->uswID,
                                                   $this->ingID,
                                                   $this->munID,
                                                   $persona->nro_doc,
                                                   $persona->tipo_doc);
    return $res;
    //$res = $cliente->consultar_boleta_pago_persona("000016","462718","1","36918762","1");
  }
  public function soapClienteDispoble(){

  }

  public function existeSession(){
    return $this->ingID != null;
  }

  public function existeClienteSoap(){
    return $this->cliente != null;
  }

  public function createClienteSoap(){
    try {
        $context = stream_context_create(array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        ));
        $soapClientOptions = array(
                'stream_context' => $context,
                'soap_version' => SOAP_1_1,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1
        );
        $this->cliente = new SoapClient($this->url, $soapClientOptions);
      }
      catch(Exception $e) {
          echo $e->getMessage();
      }
  }
}
