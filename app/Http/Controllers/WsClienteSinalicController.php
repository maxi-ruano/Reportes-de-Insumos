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
                'trace' => 1
        );
        $this->cliente = new SoapClient($this->url, $soapClientOptions);
      }
      catch(Exception $e) {
          echo $e->getMessage();
      }
  }

  public function iniciarTramiteNuevaLicencia($tramiteAInicar){
     $datos = array();
     $datos['Apellido'] = $tramiteAInicar->apellido;
     $datos['Nombre'] = $tramiteAInicar->nombre;
     $datos['IdCelExpedidor'] = AnsvCelExpedidor::where('sucursal_id', )->get();//$tramiteAInicar->getAnsvCelExpedidor();
     $datos['NombreUsuario'] = $this->nombreUsuario;
     $datos['TipoDocumento'] = $tramiteAInicar->tipo_doc;
     $datos['NumeroDocumento'] = $tramiteAInicar->nro_doc;
     $datos['Sexo'] = $tramiteAInicar->sexo;
     $datos['FechaNacimiento'] = $tramiteAInicar->fecha_nacimiento;
     $datos['Nacionalidad'] = $tramiteAInicar->nacionalidad;
     $datos['NumeroFormulario'] = $this->numeroFormulario;
     $datos['CodigoBarraSafit'] = $tramiteAInicar->bop_cb;
     $datos['ImporteAbonadoSafit'] = $tramiteAInicar->importe;
     $datos['FechaPagoSafit'] = $tramiteAInicar->bop_fec_pag;
     $datos['NumeroComprobanteSafit'] = $tramiteAInicar->bop_id;
     $datos['Cuil'] = '';
     $res = $this->cliente->IniciarTramiteNuevaLicencia();
     //dd($res);
  }
}
