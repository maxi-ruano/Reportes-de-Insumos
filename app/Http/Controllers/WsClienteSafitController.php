<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoapController;
use SoapClient;
use App\ModoAutonomoLog;
use App\SysConfig;

class WsClienteSafitController extends Controller
{
  private $url;
  private $uswID;
  private $uswPassword;
  private $uswHash;
  private $munID;
  var $ingID = null;
  var $cliente = null;
  var $sesID = null;
  
  public function __construct(){
    $this->crearConstantes();
    $this->url = SafitWS_ws_url;
    $this->uswID = SafitWS_userName;
    $this->uswPassword = SafitWS_userPass;
    $this->uswHash = SafitWS_userHash;
    $this->munID = SafitWS_munID;
  }

  public function iniciarSesion(){
    \Log::info('['.date('h:i:s').'] '.'se procede a iniciarSesion() URL: '.$this->url);
    \Log::info('['.date('h:i:s').'] '.'se procede a iniciarSesion() '.$this->uswID.' | '.$this->uswPassword.' | '.$this->uswHash);
    $res = null;
    try {
      $this->createClienteSoap();
      $res = $this->cliente->abrir_sesion( $this->uswID,
                                           $this->uswPassword,
                                           $this->uswHash );
      $this->sesID = $res->sesID;
      $this->ingID = $res->ingID;
    }catch(\Exception $e) {
      ModoAutonomoLog::create(array('ws' => 'safit-abrir_sesion', 'description' => $e->getMessage()));
    }
    return $res;
  }

  public function cerrarSesion(){
    $this->cliente->cerrar_sesion($this->uswID, $this->ingID);
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

  public function verificarSwSafit(){
    $res = false;
    $this->createClienteSoap();
    $res = $this->cliente->eco();
    if(isset($res->safDisponible))
      if($res->safDisponible == 1)
        $res = true;
    return false;
  }

  //No usado
  /*
  public function createClienteSoapPersistente(){
    //Referencia https://gist.github.com/mawo/32f7ccbe60f7db42fc265899867a64aa
    session_start();
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
    if (isset($_SESSION['soap_cookies'])) {
      foreach ($_SESSION['soap_cookies'] as $cookieName =>$cookieValues) {
        $client->__setCookie($cookieName, $cookieValues[0]);
      }
    } else {
      $client->hello();
      $_SESSION['soap_cookies'] = $client->_cookies;
    }

    public function actualizarUltimoUsoSession(){
    $config = SysConfig::where('name', 'SafitWS')
                       ->where('param', 'ingID')
                       ->update(['modification_date' => date("Y-m-d H:i:s")]);
    
  }
  }
  
  public function existeSession(){
    $res = false;
    $config = SysConfig::where('name', 'SafitWS')
                       ->where('param', 'ingID')
                       ->where('modification_date', '>', date("Y-m-d H:i:s", strtotime('-1 hour')))
                       ->first();
    if($config){
      $res = $config->value != null;
      $this->ingID = $config->value;
    }
    
    return $res;  
  }
  */
}
