<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoapController;
use SoapClient;
use App\ModoAutonomoLog;

class WsClienteSafitController extends Controller
{
	// var $url = 'https://testing.safit.com.ar/service/s_001.php?wsdl';
  var $url = 'https://www.safit.com.ar/service/s_001.php?wsdl';        
  var $uswID = '000016';
  var $uswPassword = 'weporjgsdf41654';
  var $uswHash = 'e10adc3949ba59abbe56e057f20f883e';
  var $munID = '1';
  var $ingID = null;
  var $wsSafit = [];
  var $cliente = null;
  var $sesID = null;

  public function __construct(){
      $this->createClienteSoap();
      $this->iniciarSesion();
  }

  public function iniciarSesion(){
    $res = null;
    try {
      $res = $this->cliente->abrir_sesion( $this->uswID,
                                           $this->uswPassword,
                                           $this->uswHash );
      $this->sisID = $res->sesID;
      $this->ingID = $res->ingID;
    }catch(\Exception $e) {
      ModoAutonomoLog::create(array('ws' => 'safit-abrir_sesion', 'description' => $e->getMessage()));
    }
    return $res;
  }

  public function cerrarSesion(){
    $parametros = array();
    $parametros['uswID'] = $uswID;
    $parametros['uswPassword'] = $uswPassword;
    $parametros['uswHash'] = $uswHash;
  }

  public function getBoletas($persona){
    $res = null;
    try {
      $res = $this->cliente->consultar_boleta_pago_persona($this->uswID,
                                                   $this->ingID,
                                                   $this->munID,
                                                   $persona->nro_doc,
                                                   $persona->tipoDocSafit());
      /*
      echo "REQUEST:\n" . htmlentities($this->cliente->__getLastRequest()) . "\n";

      echo "REQUEST:\n" . htmlentities($this->cliente->__getLastResponse()) . "\n";
      dd("final");
       */
    }

    catch(\Exception $e) {
      ModoAutonomoLog::create(array('ws' => 'safit-consultar_boleta_pago_persona', 'description' => $e->getMessage()));
    }
    return $res;
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
                'trace' => 1,
                'exceptions' => true
        );
        $this->cliente = new SoapClient($this->url, $soapClientOptions);
      }catch(\Exception $e) {
          ModoAutonomoLog::create(array('ws' => 'safit-SoapClient', 'description' => $e->getMessage()));
      }
  }

  public function emitirBoletaVirtualPago($tramiteAIniciar){
    $datosComprobante = array("nroDoc" => $tramiteAIniciar->nro_doc,
                              "tdcID" => $tramiteAIniciar->tipo_doc,
                              "sexo" => $tramiteAIniciar->sexo,
                              "nombre" => $tramiteAIniciar->nombre,
                              "apellido" => $tramiteAIniciar->apellido,
                              "fechaNac" => $tramiteAIniciar->fecha_nacimiento,
                              "nacionalidad" => $tramiteAIniciar->nacionalidad,// revisar se le esta mandando texto y deberia se
                              "nombre_materno" => "",
                              "apellido_materno" => "",
                              "nombre_paterno" => "",
                              "apellido_paterno" => "");

      $datosPago = array("codigo" => $tramiteAIniciar->bop_cb,
                         "importe" => $tramiteAIniciar->bop_monto,
                         "fechaPago" => $tramiteAIniciar->bop_fec_pag,
                         "codigoComprobante" => $tramiteAIniciar->bop_id);
    $res = null;
    try {
      $res = $this->cliente->obtener_certificado_virtual_pago($this->uswID,
                                                              $this->ingID,
                                                              $this->munID,
                                                              $tramiteAIniciar->cem_id,
                                                              $datosComprobante,
                                                              $datosPago);
    }catch(\Exception $e) {
        ModoAutonomoLog::create(array('ws' => 'safit-obtener_certificado_virtual_pago', 'description' => $e->getMessage()));
        $res = $e->getMessage();
    }
    return $res;
  }

  public function consultarBoletaPago($bopCB, $cemID){
    try {
      $res = $this->cliente->consultar_boleta_pago( $this->uswID,
                                                    $this->ingID,
                                                    $this->munID,
                                                    $cemID,
                                                    $bopCB);
    }catch(\Exception $e) {
        ModoAutonomoLog::create(array('ws' => 'safit-consultar_boleta_pago', 'description' => $e->getMessage()));
        $res = $e->getMessage();
    }
    return $res;
  }
}
