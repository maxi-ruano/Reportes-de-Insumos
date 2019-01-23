<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use App\AnsvCelExpedidor;
use phpDocumentor\Reflection\Types\Object_;

class WsClienteSinalicController extends Controller
{
  var $url = SinalicWS_url;
  var $cliente = null;
  var $nombreUsuario = 'microservicio';
  var $numeroFormulario = 999;

  public function __construct(){

  }

  public function iniciarSesion(){
    \Log::info('['.date('h:i:s').'] '.'se procede a iniciarSesion() Sinalic URL: '.$this->url);
    $res = null;
    try {
      if(is_null($this->cliente))
        $this->createClienteSoap();

    }catch(\Exception $e) {
      ModoAutonomoLog::create(array('ws' => 'sinalic-abrir_sesion', 'description' => $e->getMessage()));
    }
    return $res;
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
      }
      catch(\Exception $e) {
        echo $e->getMessage();
      }
  }

  public function IniciarTramiteNuevaLicencia($tramiteAInicar){
    $res = null;
    try {
      $res = $this->cliente->IniciarTramiteNuevaLicencia($tramiteAInicar);
    }catch(\Exception $e) {
      $res = (object) array( 'exception' => $e->getMessage() );
    }
    return $res;
  }

  public function IniciarTramiteRenovarLicencia($tramiteAInicar){
    $res = null;
    try {
      $res = $this->cliente->IniciarTramiteRenovarLicencia($tramiteAInicar);
    }catch(\Exception $e) {
      $res = (object) array( 'exception' => $e->getMessage() );
    }
    return $res;
  }

  public function IniciarTramiteRenovacionConAmpliacion($tramiteAInicar){
    $res = null;
    try {
      $res = $this->cliente->IniciarTramiteRenovacionConAmpliacion($tramiteAInicar);
    }catch(\Exception $e) {
      $res = (object) array( 'exception' => $e->getMessage() );
    }
    return $res;
  }

  public function consulta(){
    return $this->cliente;
  }

  public function parseTramiteParaSinalic($tramiteAInicar){
    $datos = array("tramite"=>
       array(
        "Apellido" => "$tramiteAInicar->apellido",
        "Nombre" => $tramiteAInicar->nombre,
        "IdCelExpedidor" => AnsvCelExpedidor::where('sucursal_id', $tramiteAInicar->sucursal)->first()->id_cel_expedidor,
        "NombreUsuario" => $this->nombreUsuario,
        "TipoDocumento" => $tramiteAInicar->tipo_doc,
        "NumeroDocumento" => $tramiteAInicar->nro_doc,
        "Sexo" => $tramiteAInicar->sexo,
        "FechaNacimiento" => date("d/m/Y", strtotime($tramiteAInicar->fecha_nacimiento)),
        "Nacionalidad" => $tramiteAInicar->nacionalidad,
        "NumeroFormulario" => $this->numeroFormulario,
        "CodigoBarraSafit" => $tramiteAInicar->bop_cb,
        "ImporteAbonadoSafit" => $tramiteAInicar->bop_monto,
        "FechaPagoSafit" => date("d/m/Y", strtotime($tramiteAInicar->bop_fec_pag)),
        "NumeroComprobanteSafit" => $tramiteAInicar->bop_id,
        "Cuil" => ''
      )
    );

    return $datos;
  }

  public function ConsultarLicencias($datos){
    $res = null;
    try {
      $res = $this->cliente->ConsultarLicencias($datos);
    }catch(\Exception $e) {
        echo $e->getMessage();
    }
    return $res;
  }

  public function AnularTramite($datos){
    $res = null;
    try {
      $res = $this->cliente->AnularTramite($datos);
    }catch(\Exception $e) {
        echo $e->getMessage();
    }
    return $res;
  }
}
