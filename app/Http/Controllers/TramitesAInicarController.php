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
use App\BoletaBui;

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

  private $userBui = "licenciasws";
  private $passwordBui = "lic189";

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

  public function completarBoletasEnTramitesAIniciar($estadoActual, $siguienteEstado){
    /****** ELIMINAR ANTES DE PRODUCCION ******/
    if(is_null($this->wsSafit))
      $this->temporalConstructor();
    /*****/
    $personas = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($personas as $key => $persona) {
      $res = $this->getBoleta($persona);
      //dd($res);
      if(empty($res->error))
        $this->guardarDatosBoleta($persona, $res, $siguienteEstado);
      else {
        $this->guardarError($res, $estadoActual, $persona->id);
      }
    }
  }

  public function guardarError($res, $estado, $tramite){
    TramitesAIniciarErrores::create(['description' => $res->error,
                                      'request_ws' => json_encode($res->request),
                                      'response_ws' => json_encode($res->response),
                                      'estado_error' => $estado,
                                      'tramites_a_inicar_id' => $tramite]);
  }

  public function guardarDatosBoleta($persona, $boleta, $siguienteEstado){
    $persona->bop_cb = $boleta->bopCB;
    $persona->bop_monto = $boleta->bopMonto;
    $persona->bop_fec_pag = $boleta->bopFecPag;
    $persona->bop_id = $boleta->bopID;
    $persona->cem_id = $boleta->cemID;
    $persona->estado = $siguienteEstado;
    $persona->save();
  }

  public function comletarTurnosEnTramitesAIniciar($siguienteEstado){
    $xmasDay = new \DateTime(date("Y-m-d").' + ' . $this->diasEnAdelante . ' day');
    $turnos = $this->getTurnos($xmasDay->format('Y-m-d'));
    $this->guardarTurnosEnTramitesAInicar($turnos, $siguienteEstado);
  }

  public function getTurnos($dia){
    $res = Sigeci::where('fecha', $dia)->get();
    return $res;
  }

  public function guardarTurnosEnTramitesAInicar($turnos, $siguienteEstado){
    foreach ($turnos as $key => $turno) {
      $this->guardarTurnoEnTramitesAInicar($turno, $siguienteEstado);
    }
  }

  public function guardarTurnoEnTramitesAInicar($turno, $siguienteEstado){
    if(empty(TramitesAIniciar::where('sigeci_idcita', $turno->idcita)->first())){
      $tramiteAIniciar = new TramitesAIniciar();
      $tramiteAIniciar->apellido = $turno->apellido;
      $tramiteAIniciar->nombre = $turno->nombre;
      $tramiteAIniciar->tipo_doc = $turno->idtipodoc;
      $tramiteAIniciar->nro_doc = $turno->numdoc;
      $tramiteAIniciar->tipo_tramite_sigeci = $turno->idprestacion;
      $tramiteAIniciar->nacionalidad = $this->getIdPais($turno->nacionalidad());
      $tramiteAIniciar->fecha_nacimiento = $turno->fechaNacimiento();
      $tramiteAIniciar->estado = $siguienteEstado;
      $tramiteAIniciar->sigeci_idcita = $turno->idcita;
      $tramiteAIniciar->save();
      return $tramiteAIniciar;
    }
  }

  public function getBoleta($persona){
    $res = array('error' => '');
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
          $res['error'] = "No existe ninguna boleta valida para esta persona";
        }
      }
    else {
      $res['error'] = $boletas->rspDescrip;
    }

    if(!is_null($boleta)){
      $persona->sexo = $boletas->datosBoletaPago->datosPersonaBoletaPago->oprSexo;
      $res = $boleta;
    }else{
      $res['request'] = $persona;
      $res['response'] = $boletas;
      $res = (object)$res;
    }

    return $res;
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

  public function emitirBoletasVirtualPago($estadoActual, $siguienteEstado){
    if(is_null($this->wsSafit))
      $this->temporalConstructor();
    $tramitesAIniciar = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($tramitesAIniciar as $key => $tramiteAIniciar) {
      $res = $this->wsSafit->emitirBoletaVirtualPago($tramiteAIniciar);
      if($res->rspID == 1){
        $tramiteAIniciar->estado=$siguienteEstado;
        $tramiteAIniciar->save();
      }else{
        $array = array('error' => $res->rspDescrip,
                       'request' => $tramiteAIniciar,
                       'response' => $res);
        $this->guardarError((object)$array, $estadoActual, $tramiteAIniciar->id);
      }
    }
  }

  public function getIdPais($pais){
    $pais = SigeciPaises::where('pais', $pais)->first();
    return $pais->paisAnsv->id_ansv;
  }

  public function enviarTramitesASinalic($estadoActual, $siguienteEstado){
    $tramites = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($tramites as $key => $tramite) {
      $res = null;
      $clienteSinalic = new WsClienteSinalicController();
      $datos = $clienteSinalic->parseTramiteParaSinalic($tramite);

      switch ($tramite->tipoTramite()) {
        case 'IniciarTramiteRenovarLicencia':
          $res = $clienteSinalic->IniciarTramiteRenovarLicencia($datos);
        break;
        case 'IniciarTramiteNuevaLicencia':
          $res = $clienteSinalic->IniciarTramiteNuevaLicencia($datos);
        break;
        case 'IniciarTramiteRenovacionConAmpliacion':
          $res = $clienteSinalic->IniciarTramiteRenovacionConAmpliacion($datos);
        break;
        default:
          # code...
          break;
      }
      dd($res);
      $res = $this->interpretarResultado($res, $datos);
      if(!empty($res->error))
        $this->guardarError($res, $estadoActual, $tramite->id);
      else {
        $tramite->estado = $siguienteEstado;
        //$tramite->tramite_sinalic_id = $res->tramite_sinalic_id;
        $tramite->save();
      }
      break;
    }
  }

  public function interpretarResultado($resultado, $datos){
    if($resultado->CantidadErrores > 0){
      $res = (object)array('error' => $resultado->MensajesRespuesta,
                   'request' => $datos,
                   'response' => $resultado);
    }
    else
      $res = (object)array('mensaje' => $resultado->MensajesRespuesta .' Tramite ID: '.$resultado->NumeroTramite,
                           'tramite_sinalic_id' => $resultado->NumeroTramite);
    return $res;
  }

  public function verificarLibreDeudaDeTramites($estadoActual, $siguienteEstado){
    $tramites = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($tramites as $key => $tramite) {
      $res = $this->verificarLibreDeuda($tramite);
      if( $res !== true){
        $this->guardarError((object)$res, $estadoActual, $tramite->id);
      }else {
        $tramite->estado = $siguienteEstado;
        $tramite->save();
      }
    }
  }

  public function validarInhabilitacion($res){
    return "validarInhabilitacion";
  }

  public function verificarLibreDeuda($tramite){
    $res = array();
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
      $persona = null;
      $libreDeuda = null;
      foreach ($array as $key => $value) {
        if($value['tag'] == 'ERROR' ){
          $res['error'] = $value['value'];
          $res['request'] = $datos;
          $res['response'] = $array;
        }
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
    $libreDeudaHdr->nro_doc = $datos['DOCUMENTO'] ? $datos['DOCUMENTO'] : "";
    $libreDeudaHdr->tipo_doc = $tramite->tipo_doc;
    $libreDeudaHdr->sexo = $tramite->sexo;
    $libreDeudaHdr->pais = $tramite->nacionalidad;
    $libreDeudaHdr->nombre = $datos['NOMBRE'] ? $datos['NOMBRE'] : "";
    $libreDeudaHdr->apellido = $datos['APELLIDO'] ? $datos['APELLIDO'] : "";
    $libreDeudaHdr->tipo_doc_text = $datos['TIPODOC'] ? $datos['TIPODOC'] : "";
    $libreDeudaHdr->calle = $datos['CALLE'] ? $datos['CALLE'] : "";
    $libreDeudaHdr->numero = $datos['NUMERO'] ? $datos['NUMERO'] : "";
    $libreDeudaHdr->piso = $datos['PISO'] ? $datos['PISO'] : "";
    $libreDeudaHdr->depto = $datos['DEPTO'] ? $datos['DEPTO'] : "";
    $libreDeudaHdr->telefono = $datos['TELEFONO'] ? $datos['TELEFONO'] : "";
    $libreDeudaHdr->localidad = $datos['LOCALIDAD'] ? $datos['LOCALIDAD'] : "";
    if($datos['PROVINCIA'])  $libreDeudaHdr->provincia = $datos['PROVINCIA'];
    $libreDeudaHdr->provincia_text = $datos['DESCPROVINCIA'] ? $datos['DESCPROVINCIA'] : "";
    $libreDeudaHdr->codigo_postal = $datos['CODIGOPOSTAL'] ? $datos['CODIGOPOSTAL'] : "";
    if($datos['SALDOPUNTOS']) $libreDeudaHdr->saldopuntos = $datos['SALDOPUNTOS'];
    if($datos['CANTIDADVECESLLEGOA0']) $libreDeudaHdr->cantidadvecesllegoa0 = $datos['CANTIDADVECESLLEGOA0'] ;
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

  public function verificarBuiTramites($estadoActual, $siguienteEstado){
    $tramites = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($tramites as $key => $tramite) {
      $res = $this->verificarBui($tramite);
      if( !empty($res['error']) )
        $this->guardarError((object)$res, $estadoActual, $tramite->id);
      else {
        $tramite->estado = $siguienteEstado;
        $tramite->save();
      }
    }
    return true;
  }

  public function verificarBui($tramite){
    $url = 'http://10.73.100.42:6748/service/api/BUI/GetResumenBoletasPagas';
    $data = array("TipoDocumento" => "DNI",
                  "NroDocumento" => $tramite->nro_doc, //"24571740",
                  "ListaConceptos" => ["07.02.28"],
                  "Ultima" => "true");

    $res = $this->peticionCurl($data, $url, "POST", $this->userBui, $this->passwordBui);
    if(empty($res->boletas))
      $mensaje = $res->mensaje;
    else {
      if($boleta = $this->existeBoletaHabilitada($res->boletas)){
        if(!$this->boletaUtilizada($boleta)){
          $boletaBui = BoletaBui::create(array(
          'id_boleta'=>$boleta->IDBoleta,
          'nro_boleta'=>$boleta->NroBoleta,
          'cod_barras'=>$boleta->CodBarras,
          'importe_total'=>$boleta->ImporteTotal,
          'fecha_pago'=>$boleta->FechaPago,
          'lugar_pago'=>$boleta->LugarPago,
          'medio_pago'=>$boleta->MedioPago,
          'tramite_a_iniciar_id'=>$tramite->id));
          $res = "Se utilizo la Boleta con el Nro: ".$boletaBui->nro_boleta;
        }else{
          $mensaje = "La boleta habilitada ya a sido utilizado en el sistema de la direccion general de licencias";
        }
      }else
        $mensaje = "No dispone de ninguna boleta habilitada";
    }
    if($res !== true)
      $res = array('error' => $mensaje, 'request' => $data, 'response' => $res);
    return $res;
  }

  public function peticionCurl($data, $url, $metodo, $user, $password){
    $data_string = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    curl_close($ch);
    $result = (object)json_decode($result, true);
    return $result;
  }

  public function existeBoletaHabilitada($boletas){
    $res = false;
    foreach ($boletas as $key => $boleta) {
      $boleta = (object)$boleta;
      $vto = substr($boleta->FechaPago,1,10);
      $nuevaFecha = strtotime ( '+1 year' , strtotime ( $vto ) ) ;
      if (date('Y-m-d') < date('Y-m-d',$nuevaFecha)){
          $res = $boleta;
          break;
      }
    }
    return $res;
  }

  public function boletaUtilizada($boleta){
    $res = false;
    $boleta = BoletaBui::where('id_boleta', $boleta->IDBoleta)
                       ->whereNotNull('tramite_a_iniciar_id')
                       ->first();
    if($boleta)
      $res = true;
    return $res;
  }
}
