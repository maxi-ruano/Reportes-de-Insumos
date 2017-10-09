<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SoapController;

class IniciarTramiteWSController extends Controller
{
  var $client = null;

  public function __construct()
  {
    $this->client = new SoapController();
  }

  public function nuevo(Request $request){
    dd($request->all());
  }

  public function IniciarTramiteNuevaLicencia($Apellido,
                                              $Nombre,
                                              $IdCelExpedidor,
                                              $NombreUsuario,
                                              $TipoDocumento,
                                              $NumeroDocumento,
                                              $Sexo,
                                              $FechaNacimiento,
                                              $Nacionalidad,
                                              $NumeroFormulario,
                                              $CodigoBarraSafit,
                                              $ImporteAbonadoSafit,
                                              $FechaPagoSafit,
                                              $NumeroComprobanteSafit,
                                              $Cuil,
                                              $ws){

    $parametros = $this->iniciarTramiteParametros($Apellido,
                                                  $Nombre,
                                                  $IdCelExpedidor,
                                                  $NombreUsuario,
                                                  $TipoDocumento,
                                                  $NumeroDocumento,
                                                  $Sexo,
                                                  $FechaNacimiento,
                                                  $Nacionalidad,
                                                  $NumeroFormulario,
                                                  $CodigoBarraSafit,
                                                  $ImporteAbonadoSafit,
                                                  $FechaPagoSafit,
                                                  $NumeroComprobanteSafit,
                                                  $Cuil);

    $ws = $this->client->getClienteSoap();
    $response = $ws->IniciarTramiteNuevaLicencia($parametros);
    dd($response);
  }

  public function iniciarTramiteParametros($Apellido, $Nombre, $IdCelExpedidor, $NombreUsuario, $TipoDocumento, $NumeroDocumento,
                                 $Sexo, $FechaNacimiento, $Nacionalidad, $NumeroFormulario, $CodigoBarraSafit, $ImporteAbonadoSafit,
                                 $FechaPagoSafit, $NumeroComprobanteSafit, $Cuil){
     $parametros = array();
     $parametros['Apellido'] = $Apellido;
     $parametros['Nombre'] = $Nombre;
     $parametros['IdCelExpedidor'] = $IdCelExpedidor;
     $parametros['NombreUsuario'] = $NombreUsuario;
     $parametros['TipoDocumento'] = $TipoDocumento;
     $parametros['NumeroDocumento'] = $NumeroDocumento;
     $parametros['Sexo'] = $Sexo;
     $parametros['FechaNacimiento'] = $FechaNacimiento;
     $parametros['Nacionalidad'] = $Nacionalidad;
     $parametros['NumeroFormulario'] = $NumeroFormulario;
     $parametros['CodigoBarraSafit'] = $CodigoBarraSafit;
     $parametros['ImporteAbonadoSafit'] = $ImporteAbonadoSafit;
     $parametros['FechaPagoSafit'] = $FechaPagoSafit;
     $parametros['NumeroComprobanteSafit'] = $NumeroComprobanteSafit;
     $parametros['Cuil'] = $Cuil;

     $parametros = array( 'tramite' => $parametros );
    return $parametros;
  }

  /*
munID ID de la municipalidad asociada al centro de emisión.
cemID ID de centro de emisión.
  TEST INICIO TRAMITE SINALIC

  public function nuevo(){
    return $this->IniciarTramiteNuevaLicencia('MORALES RODRIGUEZ',//Apellido
                                              'DAYANARA',//Nombre
                                              '25',//IdCelExpedidor
                                              'mtorre',//NombreUsuario
                                              '1',//TipoDocumento
                                              '95695314',//NumeroDocumento
                                              'F',//Sexo
                                              '09/01/1975',//FechaNacimiento
                                              '232',//Nacionalidad
                                              '999',//NumeroFormulario
                                              '23184160',//CodigoBarraSafit
                                              '0',//ImporteAbonadoSafit
                                              '19/08/2017',//FechaPagoSafit
                                              '60',//NumeroComprobanteSafit
                                              '',//Cuil
                                              '');
  }
  "{"FechaNacimiento":"19\/09\/1980",
  "nacionalidad":"Argentina",
  "Domicilio":"BARTOLOME MITRE 4154",
  "CodigoPostal":"1201",
  "operadora":"",
  "Numero de Documento":"28453640"}"

"a:1:{s:7:"tramite";
a:10:{s:6:"Nombre";
s:6:"PRUEBA";
s:8:"Apellido";
s:6:"PREUBA";
s:15:"FechaNacimiento";
s:10:"12/11/1980";
s:12:"Nacionalidad";
s:2:"13";
s:4:"Sexo";
s:1:"F";
s:13:"TipoDocumento";
s:1:"1";
s:15:"NumeroDocumento";
s:8:"12121212";
s:16:"NumeroFormulario";
s:6:"148501";
s:14:"IdCelExpedidor";
s:2:"25";
s:13:"NombreUsuario";
s:6:"miquel";}}"
  */
}
