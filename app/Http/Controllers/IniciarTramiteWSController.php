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

  public function nuevo(){
    return $this->IniciarTramiteNuevaLicencia('Delgadillo', 'Juan Carlos', '1', 'dacosta', '1', '95565650',
                                       'M', '24/05/1980', '13', '999', '1', '100',
                                       '01-9-2017', '1', '');
  }

  public function IniciarTramiteNuevaLicencia($Apellido, $Nombre, $IdCelExpedidor, $NombreUsuario, $TipoDocumento, $NumeroDocumento,
                                               $Sexo, $FechaNacimiento, $Nacionalidad, $NumeroFormulario, $CodigoBarraSafit, $ImporteAbonadoSafit,
                                               $FechaPagoSafit, $NumeroComprobanteSafit, $Cuil, $ws){

    $parametros = $this->iniciarTramiteParametros($Apellido, $Nombre, $IdCelExpedidor, $NombreUsuario, $TipoDocumento, $NumeroDocumento,
                                   $Sexo, $FechaNacimiento, $Nacionalidad, $NumeroFormulario, $CodigoBarraSafit, $ImporteAbonadoSafit,
                                   $FechaPagoSafit, $NumeroComprobanteSafit, $Cuil);
                                    
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
}
