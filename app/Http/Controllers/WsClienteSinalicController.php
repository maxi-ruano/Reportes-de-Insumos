<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use App\AnsvCelExpedidor;

class WsClienteSinalicController extends Controller
{
  var $url = 'https://testqa09.seguridadvial.gov.ar/sinalic.services.caba/Sinalic_Basic_WS.asmx?wsdl';
  var $cliente = null;
  var $nombreUsuario = 'microservicio';
  var $numeroFormulario = 999;

  public function __construct(){
      $this->createClienteSoap();
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
    try {
      $res = $this->cliente->IniciarTramiteNuevaLicencia($tramiteAInicar);
    }catch(\Exception $e) {
        echo $e->getMessage();
    }
    return $res;
  }

  public function IniciarTramiteRenovarLicencia($tramiteAInicar){
    try {
      $res = $this->cliente->IniciarTramiteRenovarLicencia($tramiteAInicar);
    }catch(\Exception $e) {
        echo $e->getMessage();
    }
    return $res;
  }

  public function IniciarTramiteRenovacionConAmpliacion($tramiteAInicar){
    try {
      $res = $this->cliente->IniciarTramiteRenovacionConAmpliacion($tramiteAInicar);
    }catch(\Exception $e) {
        echo $e->getMessage();
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
        "IdCelExpedidor" => AnsvCelExpedidor::where('safit_cem_id', $tramiteAInicar->cem_id)->first()->id_cel_expedidor,//$tramiteAInicar->getAnsvCelExpedidor(),
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
    try {
      $res = $this->cliente->ConsultarLicencias($datos);
    }catch(\Exception $e) {
        echo $e->getMessage();
    }
    return $res;
  }
}
