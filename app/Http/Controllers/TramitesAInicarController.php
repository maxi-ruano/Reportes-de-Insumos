<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sigeci;
use App\TramitesAIniciar;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\WsClienteSafitController;
use App\Http\Controllers\WsClienteSinalicController;
use App\AnsvPaises;
use App\SysMultivalue;
use App\SigeciPaises;
use App\TramitesAIniciarErrores;
use App\LibreDeudaLns;
use App\LibreDeudaHdr;

class TramitesAInicarController extends Controller
{
  private $diasEnAdelante = 3;
  private $munID = 1;
  private $estID = "A";
  private $estadoBoletaNoUtilizada = "N";
  public $wsSafit = null;

  //LIBRE deuda
  private $userLibreDeuda = "LICENCIAS01";
  private $passwordLibreDeuda = "TEST1234";

  public function __contruct(){
    $this->wsSafit = new WsClienteSafitController();
    $this->wsSafit->createClienteSoap();
    $this->wsSafit->iniciarSesion();

    //WS SINALIC
    $this->wsSafit = new WsClienteSinalicController();
    $this->wsSafit->createClienteSoap();
    $this->wsSafit->iniciarSesion();
  }

  public function temporalConstructor(){
    $this->wsSafit = new WsClienteSafitController();
    $this->wsSafit->createClienteSoap();
    $this->wsSafit->iniciarSesion();
  }

  public function completarBoletasEnTramitesAIniciar(){
    /****** ELIMINAR ANTES DE PRODUCCION ******/
    if(is_null($this->wsSafit))
      $this->temporalConstructor();
    /*****/
    $personas = TramitesAIniciar::where('estado', 1)->get();
    foreach ($personas as $key => $persona) {
      $boleta = $this->getBoleta($persona);
      if(!is_null($boleta))
        $this->guardarDatosBoleta($persona, $boleta);
    }
    return "listo";//$personas = Sigeci::where('');
  }

  public function guardarDatosBoleta($persona, $boleta){
    $persona->bop_cb = $boleta->bopCB;
    $persona->bop_monto = $boleta->bopMonto;
    $persona->bop_fec_pag = $boleta->bopFecPag;
    $persona->bop_id = $boleta->bopID;
    $persona->cem_id = $boleta->cemID;
    $persona->estado = 2;
    $persona->save();
  }

  public function comletarTurnosEnTramitesAIniciar(){
    $xmasDay = new \DateTime(date("Y-m-d").' + ' . $this->diasEnAdelante . ' day');
    $turnos = $this->getTurnos($xmasDay->format('Y-m-d'));
    $this->guardarTurnosEnTramitesAInicar($turnos);
  }

  public function getTurnos($dia){
    $res = Sigeci::where('fecha', $dia)->get();
    return $res;
  }

  public function guardarTurnosEnTramitesAInicar($turnos){
    foreach ($turnos as $key => $turno) {
      $this->guardarTurnoEnTramitesAInicar($turno);
    }
  }

  public function guardarTurnoEnTramitesAInicar($turno){
    $tramiteAIniciar = new TramitesAIniciar();
    $tramiteAIniciar->apellido = $turno->apellido;
    $tramiteAIniciar->nombre = $turno->nombre;
    $tramiteAIniciar->tipo_doc = $turno->idtipodoc;
    $tramiteAIniciar->nro_doc = $turno->numdoc;
    $tramiteAIniciar->nacionalidad = $this->getIdPais($turno->nacionalidad());
    $tramiteAIniciar->fecha_nacimiento = $turno->fechaNacimiento();
    $tramiteAIniciar->save();
    return $tramiteAIniciar;
  }

  public function getBoleta($persona){
    $boletas = $this->wsSafit->getBoletas($persona);
    $boleta = null;
    if(!empty($boletas->datosBoletaPago))
      foreach ($boletas->datosBoletaPago->datosBoletaPagoParaPersona as $key => $boletaI) {
        if($this->esBoletaValida($boletaI)){
          if(!is_null($boleta)){
            if( date($boletaI->bopFecPag) >= date($boleta->bopFecPag)) // para obtener la boleta mas reciente
              $boleta = $boletaI;
          }else
            $boleta = $boletaI;
        }else{
          TramitesAIniciarErrores::create(['description' => "No existe ninguna boleta valida para esta persona",
                                           'tramites_a_inicar_id' => $persona->id]);
        }
      }
    else {
      TramitesAIniciarErrores::create(['description' => 'No existen Boletas para el tramite '.$persona->nro_doc,
                                       'tramites_a_inicar_id' => $persona->id]);
    }

    if(!is_null($boleta))
      $persona->sexo = $boletas->datosBoletaPago->datosPersonaBoletaPago->oprSexo;
    return $boleta;
  }

  public function esBoletaValida($boleta){
    $res = false;
    if($boleta->bopEstado == $this->estadoBoletaNoUtilizada)
      if($boleta->munID == $this->munID)
        if($boleta->estID == $this->estID)
          $res = true;
    return $res;
  }

  public function parametros($nroDocumento, $tipoDocumento, $sexo){
    $parametros = array();
    $parametros['nroDocumento'] = $nroDocumento;
    $parametros['tipoDocumento'] = $tipoDocumento;
    $parametros['Sexo'] = $sexo;
    return $parametros;
  }

  public function emitirBoletasVirtualPago(){
    if(is_null($this->wsSafit))
      $this->temporalConstructor();
    $tramitesAIniciar = TramitesAIniciar::where('estado', 2)->get();
    foreach ($tramitesAIniciar as $key => $tramiteAIniciar) {
      $res = $this->wsSafit->emitirBoletaVirtualPago($tramiteAIniciar);
      if($res->rspID == 1){
        $tramiteAIniciar->estado=3;
        $tramiteAIniciar->save();
      }else{
        TramitesAIniciarErrores::create(['description' => "rspID: ".$res->rspID." rspDescrip: ".$res->rspDescrip,
                                         'tramites_a_inicar_id' => $tramiteAIniciar->id]);
      }
    }
  }

  public function getIdPais($pais){
    $pais = SigeciPaises::where('pais', $pais)->first();
    return $pais->paisAnsv->id_ansv;
  }

  public function enviarTramitesASinalic(){
    $tramites = TramitesAIniciar::where('estado', 3)->get();
    foreach ($tramites as $key => $tramite) {

      $clienteSinalic = new WsClienteSinalicController();
      $res = $clienteSinalic->iniciarTramiteNuevaLicencia($tramite);
      if( $res->IniciarTramiteNuevaLicenciaResult->CantidadErrores > 0 )
        TramitesAIniciarErrores::create(['description' => "error al mandar a sinalic",
                                       'tramites_a_inicar_id' => $tramite->id]);
    }
  }

  public function verificarLibreDeudaDeTramites(){
    $tramites = TramitesAIniciar::where('estado', 3)->get();
    foreach ($tramites as $key => $tramite) {
      $res = $this->verificarLibreDeuda($tramite);
      if( $res !== true){
        TramitesAIniciarErrores::create(['description' => "error libre deuda: ".$res,
                                       'tramites_a_inicar_id' => $tramite->id]);
      }
      break;
    }
  }

  public function validarInhabilitacion($res){
    return "validarInhabilitacion";
  }

  public function verificarLibreDeuda($tramite){
    $res = null;
    $url = "http://192.168.110.245/LicenciaWS/LicenciaWS?";
    $datos = "method=getLibreDeuda".
             "&tipoDoc=DNI".//$tramite->tipo_doc.
             "&numeroDoc=".$tramite->nro_doc.
             "&userName=".$this->userLibreDeuda.
             "&userPass=".$this->passwordLibreDeuda;
    $wsresult = file_get_contents($url.$datos, false);

    if ($wsresult == FALSE){
      $res = "Error con el Ws Libre Deuda";
    }else{
      $p = xml_parser_create();
      xml_parse_into_struct($p, $wsresult, $vals, $index);
      xml_parser_free($p);
      $json = json_encode($vals);
      $array = json_decode($json,TRUE);
      //print_r($json);
      $persona = null;
      $libreDeuda = null;
      foreach ($array as $key => $value) {
        if($value['tag'] == 'ERROR' )
          $res = $value['value'];
        else{
          if($value['tag'] == 'PERSONA' )
            $persona = $value['attributes'];
          if($value['tag'] == 'LIBREDEUDA' )
            $libreDeuda = $value['attributes'];
        }
      }
      if(is_null($res)){
        $libreDeudaHdr = $this->guardarDatosPersonaLibreDeuda($persona, $tramite);
        $this->guardarDatosLibreDeuda($libreDeuda, $libreDeudaHdr);
        $res = true;
      }
    }
    return $res;
  }

  public function guardarDatosPersonaLibreDeuda($datos, $tramite){
    $libreDeudaHdr = LibreDeudaHdr::where('tipo_doc', $tramite->tipo_doc)
                                  ->where('sexo', $tramite->sexo)
                                  ->where('pais', $tramite->nacionalidad)
                                  ->first();
    if(!$libreDeudaHdr)
      $libreDeudaHdr = new libreDeudaHdr();
    $libreDeudaHdr->nro_doc = $datos['DOCUMENTO'];
    $libreDeudaHdr->tipo_doc = $tramite->tipo_doc;
    $libreDeudaHdr->sexo = $tramite->sexo;
    $libreDeudaHdr->pais = $tramite->nacionalidad;
    $libreDeudaHdr->nombre = $datos['NOMBRE'];
    $libreDeudaHdr->apellido = $datos['APELLIDO'];
    $libreDeudaHdr->tipo_doc_text = $datos['TIPODOC'];
    $libreDeudaHdr->calle = $datos['CALLE'];
    $libreDeudaHdr->numero = $datos['NUMERO'];
    $libreDeudaHdr->piso = $datos['PISO'];
    $libreDeudaHdr->depto = $datos['DEPTO'];
    $libreDeudaHdr->telefono = $datos['TELEFONO'];
    $libreDeudaHdr->localidad = $datos['LOCALIDAD'];
    $libreDeudaHdr->provincia = $datos['PROVINCIA'];
    $libreDeudaHdr->provincia_text = $datos['DESCPROVINCIA'];
    $libreDeudaHdr->codigo_postal = $datos['CODIGOPOSTAL'];
    $libreDeudaHdr->saldopuntos = $datos['SALDOPUNTOS'];
    $libreDeudaHdr->cantidadvecesllegoa0 = $datos['CANTIDADVECESLLEGOA0'];
    $libreDeudaHdr->save();
    return $libreDeudaHdr;
  }

  public function guardarDatosLibreDeuda($datos, $libreDeudaHdr){
    $LibreDeudaLns = LibreDeudaLns::where('libredeuda_hdr_id', $libreDeudaHdr->libredeuda_hdr_id)->first();
    if(!$LibreDeudaLns)
      $LibreDeudaLns = new LibreDeudaLns();
    $LibreDeudaLns->libredeuda_hdr_id = $libreDeudaHdr->libredeuda_hdr_id;
    $LibreDeudaLns->numero_completo = $datos['NUMEROCOMPLETO'];
    $LibreDeudaLns->numero_id = $datos['NUMEROLD'];
    $LibreDeudaLns->digito = $datos['DIGITO'];
    $LibreDeudaLns->codigo_barras = $datos['CODIGOBARRAS'];
    $LibreDeudaLns->codigo_barras_encriptado = $datos['CODIGOBARRASENCRIPTADO'];
    $LibreDeudaLns->username = $datos['USERNAME'];
    $LibreDeudaLns->importe = $datos['IMPORTE'];
    $LibreDeudaLns->clavesb = $datos['CLAVESB'];
    $LibreDeudaLns->fecha_emision_completa = $datos['FECHAEMISIONCOMPLETA'];
    $LibreDeudaLns->hora_emision = $datos['HORAEMISION'];
    $LibreDeudaLns->fecha_emision = $datos['FECHAEMISION'];
    $LibreDeudaLns->fecha_vencimiento_completa = $datos['FECHAVENCIMIENTOCOMPLETA'];
    $LibreDeudaLns->fecha_vencimiento = $datos['FECHAVENCIMIENTO'];
    $LibreDeudaLns->save();
  }


}
