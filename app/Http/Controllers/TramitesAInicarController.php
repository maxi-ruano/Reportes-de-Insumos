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
use App\AnsvCelExpedidor;
use App\EmisionBoletaSafit;
use App\ValidacionesPrecheck;
use App\TramitesHabilitados;

class TramitesAInicarController extends Controller
{
  private $localhost = '192.168.76.33';
  private $diasEnAdelante = 1;
  private $cantidadDias = 3;
  private $fecha_inicio = '';
  private $fecha_fin = '';
  private $munID = 1;
  private $estID = "A";
  private $estadoBoletaNoUtilizada = "N";
  private $estado_final = 6;
  //SIGECI TURNOS
  private $prestacionesCursos = [1604, 1543];

  //LIBRE deuda
  private $userLibreDeuda;
  private $passwordLibreDeuda;
  private $urlLibreDeuda;

  //BUI
  private $conceptoBui = [["07.02.28"], ["07.02.31"], ["07.02.32"], ["07.02.33"], ["07.02.34"], ["07.02.35"]];
  private $userBui;
  private $passwordBui;
  private $urlVerificacionBui;

  //SINALIC
  private $wsSinalic = null;

  //SAFIT
  public $wsSafit = null;

  public function __construct(){
    //WS SAFIT
    $this->wsSafit = new WsClienteSafitController();
    //WS SINALIC
    //$this->wsSinalic = new WsClienteSinalicController();
    ini_set('default_socket_timeout', 600);
    $this->calcularFechas();
    
    $this->userLibreDeuda = LibreDeudaWS_userName;
    $this->passwordLibreDeuda = LibreDeudaWS_userPass;
    $this->urlLibreDeuda = LibreDeudaWS_wsdl_url;

    $this->userBui = BuiWS_userName;
    $this->passwordBui = BuiWS_userPass;
    $this->urlVerificacionBui = BuiWS_ws_url;
  }

  public function getTramitesAIniciar($estado, $fecha_inicio, $fecha_fin){
    $personas = \DB::table('tramites_a_iniciar')
                    ->select('tramites_a_iniciar.*')
                    ->join('sigeci', 'sigeci.tramite_a_iniciar_id', '=', 'tramites_a_iniciar.id')
                    ->whereBetween('sigeci.fecha', [$fecha_inicio, $fecha_fin])
                    ->where('tramites_a_iniciar.estado', $estado)
                    ->get();
    return $personas;
  }

  public function getTramitesAIniciarValidaciones($estado, $estadoValidacion, $fecha_inicio, $fecha_fin){
    $personas = \DB::table('tramites_a_iniciar')
                    ->select('tramites_a_iniciar.*')
                    ->join('sigeci', 'sigeci.tramite_a_iniciar_id', '=', 'tramites_a_iniciar.id')
                    ->join('validaciones_precheck', 'validaciones_precheck.tramite_a_iniciar_id', '=', 'tramites_a_iniciar.id')
                    ->whereBetween('sigeci.fecha', [$fecha_inicio, $fecha_fin])
                    ->where('tramites_a_iniciar.estado', '>=', $estado)
                    ->where('validaciones_precheck.validation_id', $estadoValidacion)
                    ->where('validaciones_precheck.validado', false)
                    ->get();
    return $personas;
  }

  public function guardarError($res, $estado, $tramite){
    TramitesAIniciarErrores::create(['description' => $res->error,
                                      'request_ws' => json_encode($res->request),
                                      'response_ws' => json_encode($res->response),
                                      'estado_error' => $estado,
                                      'tramites_a_iniciar_id' => $tramite]);
  }

  public function guardarDatosBoleta($persona, $boleta, $siguienteEstado){
    $persona = TramitesAIniciar::find($persona->id);
    $persona->bop_cb = $boleta->bopCB;
    $persona->bop_monto = $boleta->bopMonto;
    $persona->bop_fec_pag = $boleta->bopFecPag;
    $persona->bop_id = $boleta->bopID;
    $persona->cem_id = $boleta->cemID;
    $persona->estado = $siguienteEstado;
    return $persona->save();
  }

  //Proceso utilizado en segundo plano mediante Queue
  public function iniciarTramiteEnPrecheck($turno){
    //Verificar si existe un precheck realizado recientemente para vincular con este tramite habilitado
    $tramiteAIniciar = $this->existeTramiteAIniciarConPrecheck($turno);
    if($tramiteAIniciar){
        \Log::info('['.date('h:i:s').'] '.'se vincula con un tramiteAIniciar que existe, '.$turno->id);
        $turno->tramites_a_iniciar_id = $tramiteAIniciar->id;
        $turno->save();
        $this->eliminarVinculosEnTramitesHabilitados($turno->id, $tramiteAIniciar->id);
    }else{
      \Log::info('['.date('h:i:s').'] '.'se creo en tramiteAIniciar, '.$turno->id);
      //1)Registrar datos en tramites_a_iniciar
      $tramiteAIniciar = new TramitesAIniciar();
      $tramiteAIniciar->apellido = $turno->apellido;
      $tramiteAIniciar->nombre = $turno->nombre;
      $tramiteAIniciar->tipo_doc = $turno->tipo_doc;
      $tramiteAIniciar->nro_doc = $turno->nro_doc;
      $tramiteAIniciar->sexo = $turno->sexo;
      $tramiteAIniciar->nacionalidad = AnsvPaises::where('id_dgevyl', $turno->pais)->first()->id_ansv; 
      $tramiteAIniciar->fecha_nacimiento = $turno->fecha_nacimiento;
      $tramiteAIniciar->estado = '1';
      $saved = $tramiteAIniciar->save();

      //Vincular tramites_a_iniciar_id en tramites_habilitados
      $turno->tramites_a_iniciar_id = $tramiteAIniciar->id;
      $turno->save();

      //Crear registros en validaciones_precheck
      if($saved)
        $this->crearValidacionesPrecheck($tramiteAIniciar->id);
        
    }
    
    //2)REALIZAR PRECHECK DEL TRAMITE A INICIAR CREADO
    /**Se ejecuta de forma Async el usuario no debe esperar el tiempo de respuesta */
    \Log::info('['.date('h:i:s').'] '.'inicio validaciones precheck con tramiteAIniciar ID: '.$tramiteAIniciar->id);

      if(!$this->estaValidadoEnValidacionesPrecheck($tramiteAIniciar,LIBRE_DEUDA)){
        \Log::info('['.date('h:i:s').'] '.'> > > gestionarLibreDeuda() tramites_habilitado ID = '.$turno->id);
        $this->gestionarLibreDeuda($tramiteAIniciar, LIBRE_DEUDA, VALIDACIONES);
      }
      
      if(!$this->estaValidadoEnValidacionesPrecheck($tramiteAIniciar,BUI)){
        \Log::info('['.date('h:i:s').'] '.'> > > gestionarBui('.BUI.') tramites_habilitado ID = '.$turno->id);
        $this->gestionarBui($tramiteAIniciar, BUI, VALIDACIONES);        
      }

      \Log::info('['.date('h:i:s').'] '.' comprobando SAFIT '.EMISION_BOLETA_SAFIT);
      if(!$this->estaValidadoEnValidacionesPrecheck($tramiteAIniciar,EMISION_BOLETA_SAFIT)){
        \Log::info('['.date('h:i:s').'] '.'> > > buscarBoletaSafit() tramites_habilitado ID = '.$turno->id);
        if($this->buscarBoletaSafit($tramiteAIniciar, SAFIT)){
          //Ejecutar gestionarBoletaSafit() solo si encuenctra datos en buscarBoloetaSafit()
          \Log::info('['.date('h:i:s').'] '.'> > > gestionarBoletaSafit() tramites_habilitado ID = '.$turno->id);
          $this->gestionarBoletaSafit($tramiteAIniciar, EMISION_BOLETA_SAFIT, VALIDACIONES);
        }
      }

    \Log::info('['.date('h:i:s').'] '.'fin validaciones precheck');
    
    return true;
  }

  public function existeTramiteAIniciarConPrecheck($persona){
    $encontrado = TramitesAIniciar::select('tramites_a_iniciar.*')
                    ->leftjoin('tramites','tramites.tramite_id','tramites_a_iniciar.tramite_dgevyl_id')
                    ->join('ansv_paises','ansv_paises.id_ansv','tramites_a_iniciar.nacionalidad')
                    ->where('ansv_paises.id_dgevyl',$persona->pais)
                    ->where('tramites_a_iniciar.nro_doc',$persona->nro_doc)
                    ->where('tramites_a_iniciar.tipo_doc',$persona->tipo_doc)
                    ->where('tramites_a_iniciar.estado', '!=','8')
                    ->where(function ($query) {
                        $query->where('tramites.estado', '93')
                            ->orWhereNull('tramites.estado');
                    })
                    ->orderBy('tramites_a_iniciar.created_at','DESC')
                    ->first();                
    return $encontrado;
  }

  public function eliminarVinculosEnTramitesHabilitados($turno_id, $tramite_id){
    TramitesHabilitados::where('tramites_a_iniciar_id',$tramite_id)
                        ->where('id','!=',$turno_id)
                        ->update(['tramites_a_iniciar_id'=> null]);
  }

  public function estaValidadoEnValidacionesPrecheck($tramiteAIniciar, $validation_id){    
    $consulta = ValidacionesPrecheck::select('validado')
                    ->where('tramite_a_iniciar_id',$tramiteAIniciar->id)
                    ->where('validation_id',$validation_id)
                    ->first();
    return $consulta->validado;
  }

  /**
   * MicroservicioController: 1) Metodos asociados para completarTurnosEnTramitesAIniciar
   */
  public function completarTurnosEnTramitesAIniciar($siguienteEstado){
    $turnos = $this->getTurnos($this->fecha_inicio, $this->fecha_fin);

    foreach ($turnos as $key => $turno) {
      try{

        if(empty(TramitesAIniciar::where('sigeci_idcita', $turno->idcita)->first()))
          $this->guardarTurnoEnTramitesAInicar($turno, $siguienteEstado);

		  }catch(\Exception $e){
        $array = array('error' => $e->getMessage()." IDCITA: ".$turno->idcita, 'request' => "",'response' => "");
        $this->guardarError((object)$array, $siguienteEstado, 1);
		  }
    }
  }

  public function getTurnos($fecha_inicio, $fecha_fin){
    $res = Sigeci::whereBetween('fecha', [$fecha_inicio, $fecha_fin])
                 ->whereNull('tramite_a_iniciar_id')
                 ->whereNotIn('idprestacion', $this->prestacionesCursos)
                 ->get();
    return $res;
  }

  public function guardarTurnoEnTramitesAInicar($turno, $siguienteEstado){
    
      $tramiteAIniciar = new TramitesAIniciar();
      $tramiteAIniciar->apellido = $turno->apellido;
      $tramiteAIniciar->nombre = $turno->nombre;
      $tramiteAIniciar->tipo_doc = $turno->idtipodoc;
      $tramiteAIniciar->nro_doc = $turno->numdoc;
      $tramiteAIniciar->nacionalidad = $this->getIdPais($turno->nacionalidad());
      $tramiteAIniciar->fecha_nacimiento = $turno->fechaNacimiento();
      if(!$tramiteAIniciar->fecha_nacimiento) 
        $tramiteAIniciar->fecha_nacimiento = $turno->fechanac;
      $tramiteAIniciar->estado = $siguienteEstado;
      $tramiteAIniciar->sigeci_idcita = $turno->idcita;
      $tramiteAIniciar->sexo = $turno->getSexo();
      $saved = $tramiteAIniciar->save();
      $turno->tramite_a_iniciar_id = $tramiteAIniciar->id;
      $turno->save();
      if($saved)
        $this->crearValidacionesPrecheck($tramiteAIniciar->id);
      else
        dd($saved);
      
      return $tramiteAIniciar;
    
  }

  public function crearValidacionesPrecheck($id){
    $validaciones = SysMultivalue::where('type','VALP')->get();
    foreach ($validaciones as $key => $value) {
      $validaciones = new ValidacionesPrecheck();
      $validaciones->tramite_a_iniciar_id = $id;
      $validaciones->validation_id = $value->id;
      $validaciones->validado = false;
      $validaciones->save();
    }
  }

  /**
  * MicroservicioController: 2) Metodos asociados para verificarLibreDeudaDeTramites
  */
  public function verificarLibreDeudaDeTramites($estadoActual, $estadoValidacion, $siguienteEstado){
    $tramites = $this->getTramitesAIniciarValidaciones($estadoActual, $estadoValidacion, $this->fecha_inicio, $this->fecha_fin);
    foreach ($tramites as $key => $tramite) {
      try{
        $this->gestionarLibreDeuda($tramite, $estadoValidacion, $siguienteEstado);
      }catch(\Exception $e){
        $array = array('error' => $e->getMessage(), 'request' => "",'response' => "");
        $this->guardarError((object)$array, $siguienteEstado, $tramite->id);
      }
    }
  }

  public function gestionarLibreDeuda($tramite, $estadoValidacion, $siguienteEstado){
    $res = $this->verificarLibreDeuda($tramite);   
    if(!$res['res']){
      $this->guardarError((object)$res, $estadoValidacion, $tramite->id);
    }else {
      $this->guardarValidacion($tramite, true, $estadoValidacion, $res['comprobante']);
      $this->actualizarEstado($tramite, $siguienteEstado);
    }
  }

  public function verificarLibreDeuda($tramite){
    $tramite = TramitesAIniciar::find($tramite->id);
    $res = array();  
    $datos = "method=getLibreDeuda".
             "&tipoDoc=".$tramite->tipoDocLibreDeuda().
             "&numeroDoc=".$tramite->nro_doc.
             "&userName=".$this->userLibreDeuda.
             "&userPass=".$this->passwordLibreDeuda;
    $wsresult = file_get_contents($this->urlLibreDeuda.$datos, false);
    if ($wsresult == FALSE){
      $res['res'] = false;
      $res['error'] = 'Error en el Ws de Libre Deuda';
      $res['request'] = $datos;
      $res['response'] = null;
      return $res;
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
          $res['res'] = false;
          $res['error'] = ( isset($value['value'])? $value['value'] : "" );
          $res['request'] = $datos;
          $res['response'] = $array;
          return $res;
        }
        else{
          if($value['tag'] == 'PERSONA' )
            $persona = $value['attributes'];
          if($value['tag'] == 'LIBREDEUDA' )
            $libreDeuda = $value['attributes'];
        }
      }
        $libreDeudaHdr = $this->guardarDatosPersonaLibreDeuda($persona, $tramite);
        $this->guardarDatosLibreDeuda($libreDeuda, $libreDeudaHdr);
        $res['res'] = true;
        $res['comprobante'] = $libreDeuda['NUMEROLD'];
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
    $libreDeudaHdr->nro_doc = $datos['DOCUMENTO'] ? $datos['DOCUMENTO'] : $tramite->nro_doc;
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


  /**
  * MicroservicioController: 3) Metodos asociados para completarBoletasEnTramitesAIniciar
  */
  public function completarBoletasEnTramitesAIniciar($estadoActual, $siguienteEstado){
    $personas = $this->getTramitesAIniciar($estadoActual, $this->fecha_inicio, $this->fecha_fin);
    foreach ($personas as $key => $persona)  {
      try {
        $this->buscarBoletaSafit($persona, $siguienteEstado);
      }catch(\Exception $e){
        $array = array('error' => $e->getMessage(), 'request' => "",'response' => "");
        $this->guardarError((object)$array, $siguienteEstado, $persona->id);
      }
    }
  }

  public function  buscarBoletaSafit($persona, $siguienteEstado){
      $persona = TramitesAIniciar::find($persona->id);
      $res = $this->getBoleta($persona);
      if(empty($res->error)){
        $this->guardarDatosBoleta($persona, $res, $siguienteEstado);
        return 1;
      }else {
        $this->guardarError($res, $siguienteEstado, $persona->id);
        return 0;
      }
  }

  public function getBoleta($persona){
    $res = array('error' => '');
    $this->wsSafit->iniciarSesion();
    $boletas = $this->wsSafit->getBoletas($persona);
    $this->wsSafit->cerrarSesion();
    $boleta = null;
    if(!empty($boletas->datosBoletaPago->datosBoletaPagoParaPersona)){
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
    }else {
      if($boletas!=null)
        $res['error'] = $boletas->rspDescrip;
      else
        $res['error'] = "existe un problema con Ws de sinalic";
    }

    if(!is_null($boleta)){
      $persona = TramitesAIniciar::find($persona->id);
      $persona->sexo = $boletas->datosBoletaPago->datosPersonaBoletaPago->oprSexo;
      $persona->save();
      $res = $boleta;
    }else{
      if($boleta = $this->buscarBoletaSafitEnTurnosVencidos($persona)){
        $res['error'] = '';
        $res = $boleta;
      }else{
        $res['request'] = $persona;
        $res['response'] = $boletas;
        $res = (object)$res;
      }
    }

    return $res;
  }

  public function esBoletaValida($boleta){
    $res = false;
    if($boleta->bopEstado == $this->estadoBoletaNoUtilizada)
      if($boleta->munID == $this->munID)
        if($boleta->estID == $this->estID)
          if($this->fechaDeVencimientoValida($boleta->bopFecPag, 3))
            $res = true;
    return $res;
  }

  public function fechaDeVencimientoValida($fecha, $mesesValido){
    $nuevaFecha = strtotime ( '+'.$mesesValido.' month' , strtotime ( $fecha ) ) ;
    if (date('Y-m-d') < date('Y-m-d', $nuevaFecha))
      $res = true;
    else
      $res = false;
    return $res;  
  }


  /**
  * MicroservicioController: 4) Metodos asociados para emitirBoletasVirtualPago
  */
  public function emitirBoletasVirtualPago($estadoActual, $estadoValidacion, $siguienteEstado){
    $tramitesAIniciar = $this->getTramitesAIniciar($estadoActual, $this->fecha_inicio, $this->fecha_fin);
    $this->wsSafit->iniciarSesion();
    foreach ($tramitesAIniciar as $key => $tramiteAIniciar) {
      try{
        $this->gestionarBoletaSafit($tramiteAIniciar, $estadoValidacion, $siguienteEstado);
      }catch(\Exception $e){
        $array = array('error' => $e->getMessage(), 'request' => "",'response' => "");
        $this->guardarError((object)$array, $siguienteEstado, $tramiteAIniciar->id);
      }
    }
    $this->wsSafit->cerrarSesion();
  }

  public function gestionarBoletaSafit($tramiteAIniciar, $estadoValidacion, $siguienteEstado) {
    $demorado = false;
    $tramiteAIniciar = TramitesAIniciar::find($tramiteAIniciar->id);
    $tramiteAIniciar->tipo_doc = $tramiteAIniciar->tipoDocSafit();
    $res = $this->wsSafit->emitirBoletaVirtualPago($tramiteAIniciar);
    if(isset($res->rspID)){
      if($res->rspID == 1){
        if(isset($res->reincidencias->rspReincidente)){
          if($res->reincidencias->rspReincidente == "P"){
            $array = array('error' => "El Cenat esta demorado",
                          'request' => $tramiteAIniciar,
                          'response' => $res);
            $this->guardarError((object)$array, $estadoValidacion, $tramiteAIniciar->id);
            $demorado = true;
          }
        }
        if(!$demorado){
          $this->guardarValidacion($tramiteAIniciar, true, $estadoValidacion, $tramiteAIniciar->bop_id);
          $this->actualizarEstado($tramiteAIniciar, $siguienteEstado);
          $this->guardarEmisionBoleta($tramiteAIniciar->bop_id, $this->localhost);
        }
      }else{
        $array = array('error' => $res->rspDescrip,
                      'request' => $tramiteAIniciar,
                      'response' => $res);
        $this->guardarError((object)$array, $estadoValidacion, $tramiteAIniciar->id);
      }
    }else{
      $array = array('error' => $res,
                    'request' => $tramiteAIniciar,
                    'response' => $res);
      $this->guardarError((object)$array, $estadoValidacion, $tramiteAIniciar->id);
    }
  }

  public function guardarEmisionBoleta($idBoleta, $ip){
    $emision = new EmisionBoletaSafit();
    $emision->numero_boleta = $idBoleta;
    $emision->ip = $ip;
    $emision->save();
  }

  public function getIdPais($pais){
    $pais = SigeciPaises::where('pais', $pais)->first();
    return $pais->paisAnsv->id_ansv;
  }


  /**
  * MicroservicioController: 5) Metodos asociados para verificarBuiTramites
  */
  public function verificarBuiTramites($estadoActual, $estadoValidacion, $siguienteEstado){
    $tramites = $this->getTramitesAIniciarValidaciones($estadoActual, $estadoValidacion, $this->fecha_inicio, $this->fecha_fin);
    foreach ($tramites as $key => $tramite) {
      try{
        $this->gestionarBui($tramite, $estadoValidacion, $siguienteEstado);
      }catch(\Exception $e){
        $array = array('error' => $e->getMessage(), 'request' => "",'response' => "");
        $this->guardarError((object)$array, $siguienteEstado, $tramite->id);
      }
    }
    return true;
  }

  public function gestionarBui($tramite, $estadoValidacion, $siguienteEstado){
    $error = 'ninguno';
      foreach ($this->conceptoBui as $key => $value) {
        $res = $this->verificarBui($tramite, $value);
        if( !empty($res['error']) ){
          if($res['error'] != $error){
            $this->guardarError((object)$res, $estadoValidacion, $tramite->id);
            $error = $res['error'];
          }
        }else {
          $this->guardarValidacion($tramite, true, $estadoValidacion, $res['comprobante']);
          $this->actualizarEstado($tramite, $siguienteEstado);
          break;
        }
      }
    return $error;
  }

  public function verificarBui($tramite, $concepto){

    \Log::info('['.date('h:i:s').'] Se inicia verificarBui() tramiteAIniciar ID: '.$tramite->id); 

    $comprobante = array();
    $tramite = TramitesAIniciar::find($tramite->id);
    $data = array("TipoDocumento" => $tramite->tipoDocBui(),
                  "NroDocumento" => $tramite->nro_doc, //"24571740",//cambiar
                  "ListaConceptos" => $concepto,
                  "Ultima" => "false");
    $res = $this->peticionCurl($data, $this->urlVerificacionBui, "POST", $this->userBui, $this->passwordBui);
    $mensaje = "ha ocurrido un error inesperado";

    if(empty($res->boletas)){
      if(isset($res->mensaje))
        $mensaje = $res->mensaje;
    } else {
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
	        //$res = "Se utilizo la Boleta con el Nro: ".$boletaBui->nro_boleta;
          $comprobante['comprobante'] = $boleta->NroBoleta;
          $res = true;
        }else{
          $mensaje = "La boleta habilitada ya a sido utilizado en el sistema de la direccion general de licencias";
        }
      }else{
        $mensaje = "No dispone de ninguna boleta habilitada";
      }
    }
    if($res !== true)
      $res = array('error' => $mensaje, 'request' => $data, 'response' => $res);
    else
      $res = $comprobante;  

    \Log::info('['.date('h:i:s').'] '.' se ejecuto peticionCurl(), concepto: '.$concepto[0].' mensaje: '.$mensaje); 

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
    //Buscar si existe un tramite que ya uso la boleta en s_requisitos
    $requisito =  \DB::table('s_requisitos')
                  ->where('requisito_id','53')
                  ->where('valor_varchar', $boleta->NroBoleta)
                  ->orWhere('valor_varchar',$boleta->CodBarras)
                  ->count();
    if($requisito){
      $res = true;
    }else{
      //verificar si existe la boleta ya asignado a un tramite_a_iniciar
      $boleta = BoletaBui::where('nro_boleta', $boleta->NroBoleta)
                    ->whereNotNull('tramite_a_iniciar_id')
                    ->count();
      if($boleta)
        $res = true;
    }
    
    return $res;
  }

  /**
   * MicroservicioController: 6) Metodos asociados para revisarValidaciones
   */
  public function revisarValidaciones($siguienteEstado){
    $validaciones = \DB::table('validaciones_precheck')
                  ->select('validaciones_precheck.tramite_a_iniciar_id')  
                  ->join('sigeci', 'sigeci.tramite_a_iniciar_id', '=', 'validaciones_precheck.tramite_a_iniciar_id')
                  ->whereBetween('sigeci.fecha', [$this->fecha_inicio, $this->fecha_fin])
                  ->groupBy('validaciones_precheck.tramite_a_iniciar_id')
                  ->get();

    foreach ($validaciones as $key => $validacion) {
      if($this->validacionesTerminadas($validacion->tramite_a_iniciar_id)){
        $tramite = TramitesAIniciar::find($validacion->tramite_a_iniciar_id);
        $tramite->estado = $siguienteEstado; 
        $tramite->save();
      }
    }   
  }

  public function validacionesTerminadas($id){
      $res = ValidacionesPrecheck::where("tramite_a_iniciar_id", $id)->get();
      foreach($res as $key => $validacion)
        if (!$validacion->validado)
          return false;
      return true;
  }


  /**
   * Otras Funciones
   */

  public function guardarValidacion($tramitesAIniciar, $estado, $validation, $comprobante){
    $validacion = ValidacionesPrecheck::where('validation_id', $validation)
                                      ->where('tramite_a_iniciar_id', $tramitesAIniciar->id)
                                      ->first();
    $validacion->validado = $estado;
    $validacion->comprobante = $comprobante;
    return $validacion->save();
  }

  public function actualizarEstado($tramiteAIniciar, $siguienteEstado){
    $validaciones = ValidacionesPrecheck::where('tramite_a_iniciar_id', $tramiteAIniciar->id)
                                        ->where('validado', false)
                                        ->get();
    if(count($validaciones)==0){
      $tramiteAIniciar = TramitesAIniciar::find($tramiteAIniciar->id);
      $tramiteAIniciar->estado = $siguienteEstado;
      $tramiteAIniciar->save();
    }
  }

  public function enviarTramitesASinalic($estadoActual, $siguienteEstado){
    if(is_null($this->wsSinalic->cliente))
      return "El Ws de Sinalic no responde, por favor revise la conexion, o contactese con Nacion";
    $tramites = TramitesAIniciar::where('estado', $estadoActual)->get();
    foreach ($tramites as $key => $tramite) {
      $this->asignarTipoTramiteAIniciar($tramite);
      $res = null;
      $response = null;
      $datos = $this->wsSinalic->parseTramiteParaSinalic($tramite);

      switch ($tramite->tipo_tramite) {
        case 2: //RENOVACION
          $response = $this->wsSinalic->IniciarTramiteRenovarLicencia($datos);
          $res = $response->IniciarTramiteRenovarLicenciaResult;
        break;
        case 1: //OTORGAMIENTO
          $response = $this->wsSinalic->IniciarTramiteNuevaLicencia($datos);
          $res = $response->IniciarTramiteNuevaLicenciaResult;
        break;
        case 6: //RENOVACION CON AMPLIACION
          $response = $this->wsSinalic->IniciarTramiteRenovacionConAmpliacion($datos);
          $res = $reponse->IniciarTramiteRenovacionConAmpliacionResult;
        break;
        default:
          # code...
          break;
      }

      $res = $this->interpretarResultado($res, $datos);

      if(!empty($res->error)){
        $this->guardarError($res, $siguienteEstado, $tramite->id);
        $tramite->response_ws = json_encode($response);
        $tramite->save();
      }else {
        $tramite->estado = $siguienteEstado;
        $tramite->tramite_sinalic_id = $res->tramite_sinalic_id;
        $tramite->response_ws = json_encode($response);
        $tramite->save();
      }
    }
  }

  public function interpretarResultado($resultado, $datos){
    if(intval($resultado->CantidadErrores) > 0){
      $res = array('error' => $this->getErrores($resultado->MensajesRespuesta),
                   'request' => $datos,
                   'response' => $resultado);
    }
    else
      $res = array('mensaje' => $this->getErrores($resultado->MensajesRespuesta) .' Tramite ID: '.$resultado->NumeroTramite,
                           'tramite_sinalic_id' => $resultado->NumeroTramite);
    return (object)$res;
  }

  public function getErrores($lista){
    $res = '';
    foreach ($lista as $key => $value)
      $res.= $value.' - ';
    return $res;
  }

  public function validarInhabilitacion($res){
    return "validarInhabilitacion";
  }

  public function asignarTipoTramiteAIniciar($tramiteAInicar){
    $ultimaLicencia = $this->getUltimaLicencia($tramiteAInicar);
    $tramiteAInicar->tipo_tramite = $this->getTipoTramite($ultimaLicencia);
    $tramiteAInicar->save();
  }


  public function getUltimaLicencia($tramiteAInicar){
    $licencias = $this->getLicencias($tramiteAInicar);
    $licencias = $licencias->ConsultarLicenciasResult->LicenciaDTO;
    $ultimaLicencia = null;

    if(!empty($licencias))
      if(count($licencias) == 1)
        return $licencias; //Retorna Una sola licencia

      foreach ($licencias as $key => $value) {
        if(!$ultimaLicencia){
          $ultimaLicencia = $value;
          if(count($licencias) == 1)
            break;
        } else {
          $fecha = str_replace("/","-",$ultimaLicencia->FechaVencimiento);
          $fecha2 = str_replace("/","-",$value->FechaVencimiento);
          if(strtotime($fecha) < strtotime($fecha2))
            $ultimaLicencia = $value;
        }
      }
    return $ultimaLicencia;
  }

  public function getLicencias($tramiteAInicar){
    $res = $this->wsSinalic->ConsultarLicencias(array(
             "nroDocumento" => $tramiteAInicar->nro_doc,
             "sexo" => $tramiteAInicar->sexo,
             "tipoDocumento" => $tramiteAInicar->tipo_doc
           ));
    return $res;
  }

  public function getTipoTramite($ultimaLicencia){
    if(!$ultimaLicencia || $this->licenciaVencidaMasDeUnAnio($ultimaLicencia))
      $res = 1;// OTORGAMIENTO
    else{
      if($this->estaEnJurisdiccion($ultimaLicencia, 'C.A.B.A.')){
          $res = 2;
      }else{
          if($this->esNecesarioAmpliacion($ultimaLicencia))
            $res = 6; //RENOVACION CON AMPLIACION
          else
            $res = 2; //RENOVACION
      }
    }
    return $res;
  }

  public function estaEnJurisdiccion($ultimaLicencia, $jurisdiccionTexto){
    $pos = strpos($ultimaLicencia->Domicilio->NombreLocalidad, $jurisdiccionTexto);
    return $pos !== false;
  }

  public function licenciaVencidaMasDeUnAnio($ultimaLicencia){
    $elAnioPasado = strtotime('-1 year');
    $fecha = str_replace("/","-",$ultimaLicencia->FechaVencimiento);
    $fechaVencimiento = strtotime($fecha); //se toma para m/d/YYYY de sinalic viene en d/m/y

    return $fechaVencimiento < $elAnioPasado;
  }

  public function esNecesarioAmpliacion($ultimaLicencia){
    $clases = strtolower($ultimaLicencia->Clases);
    $clases = str_replace("a","", $clases);
    $clases = str_replace("b","", $clases);
    return preg_match("/[a-z]/i", $clases);
  }


  public function consultarBoletaPago(Request $request){
	  $emision = null;
	  if($request->bop_cb < 999999999)
	    $emision = EmisionBoletaSafit::where('numero_boleta', $request->bop_cb)->first();
    
    if ($emision === null) {
      $this->wsSafit->iniciarSesion();
      $res = $this->wsSafit->consultarBoletaPago($request->bop_cb, $request->cem_id);
      $this->wsSafit->cerrarSesion();
      if(isset($res->rspID)){
        if($res->rspID == 1){
          $boleta = (object) array('nro_doc' => $res->datosBoletaPago->datosPersonaBoletaPago->oprDocumento,
                                 'tipo_doc' => $res->datosBoletaPago->datosPersonaBoletaPago->tdcID,
                                 'sexo' => $res->datosBoletaPago->datosPersonaBoletaPago->oprSexo,
                                 'nombre' => $res->datosBoletaPago->datosPersonaBoletaPago->oprNombre,
                                 'apellido' => $res->datosBoletaPago->datosPersonaBoletaPago->oprApellido,
                                 'bop_id' => $res->datosBoletaPago->bopID,
                                 'bop_cb' => $res->datosBoletaPago->bopCB,
                                 'bop_monto' => $res->datosBoletaPago->bopMonto,
                                 'bop_fec_pag' => $res->datosBoletaPago->bopFecPag,
                                 'cem_id' => $request->cem_id);

          return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                             ->with('boleta', $boleta);
        }else{
          return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                             ->with('error', $res->rspDescrip);
        }
      }else{
          return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                           ->with('error', 'Ha ocurrido un error inesperado: '.$res);
      }
	  }else{
	      return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
		                                                ->with('success', 'El Cenat ya fue emitido');
	  }
  }

  public function buscarBoletaPago(Request $request){
    return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores());
  }

  public function getCentrosEmisores(){
    $centrosEmisores = AnsvCelExpedidor::whereNotNull('safit_cem_id')->get();
    foreach ($centrosEmisores as $key => $value) {
      $value->name = "";
      if($value->sysMultivalue())
        $value->name = $value->sysMultivalue()->description;
    }
    return $centrosEmisores;
  }

  public function generarCenat(Request $request){
    $tramiteAInicar = (object) array('nro_doc' => $request->nro_doc,
                             'tipo_doc' => $request->tipo_doc,
                             'sexo' => $request->sexo,
                             'nombre' => $request->nombre,
                             'apellido' => $request->apellido,
                             'fecha_nacimiento' => $request->fecha_nacimiento,
                             'nacionalidad' => $request->nacionalidad,
                             'bop_cb' => $request->bop_cb,
                             'bop_monto' => $request->bop_monto,
                             'bop_fec_pag' => $request->bop_fec_pag,
                             'bop_id' => $request->bop_id,
                             'cem_id' => $request->cem_id);
    $emision = EmisionBoletaSafit::where('numero_boleta', $request->bop_id)->first();
    if ($emision === null) {
        $this->wsSafit->iniciarSesion();
        $res = $this->wsSafit->emitirBoletaVirtualPago($tramiteAInicar);
        $this->wsSafit->cerrarSesion();
      if(isset($res->rspID)){
        if($res->rspID == 1){
    			if(isset($res->reincidencias->rspReincidente))
             if($res->reincidencias->rspReincidente == "P"){
                return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                                     ->with('error', "El Cenat se encuentra Demorado");
             }
		      $this->guardarEmisionBoleta($request->bop_id, $request->ip());
          return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                               ->with('success', $res->rspDescrip);
        }else
          return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                               ->with('boleta', $tramiteAInicar)
                                               ->with('error', $res->rspDescrip);
      }else
        return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                             ->with('boleta', $tramiteAInicar)
                                             ->with('error', 'Ha ocurrido un error inesperado: '.$res);
    }else{
      return View('safit.buscarBoletaPago')->with('centrosEmisores', $this->getCentrosEmisores())
                                           ->with('success', 'El Cenat ya fue emitido.');
    }
  }

  public function calcularFechas(){
    $this->fecha_inicio = new \DateTime(date("Y-m-d").' + ' . $this->diasEnAdelante . ' day');
    $this->fecha_inicio = $this->fecha_inicio->format('Y-m-d');
    $xmasDay = new \DateTime($this->fecha_inicio.' + ' . $this->cantidadDias . ' day');
    $this->fecha_fin = $xmasDay->format('Y-m-d');
  }

  public function testCheckBoletas(Request $request){
    $tramite = TramitesAIniciar::find($request->id);
    $boleta = $this->getBoleta($tramite);
    dd($boleta);
  }

  public function parametros($nroDocumento, $tipoDocumento, $sexo){
    $parametros = array();
    $parametros['nroDocumento'] = $nroDocumento;
    $parametros['tipoDocumento'] = $tipoDocumento;
    $parametros['Sexo'] = $sexo;
    return $parametros;
  }

  
  //FUNCIONES PARA TURNOS VENCIDOS
  public function revisarTurnosVencidos(){
    try{
      //16 dias atras
      $last_date = date('Y-m-d', strtotime('-'.(DIAS_VALIDEZ_TURNO+1).' days', strtotime(date('Y-m-d'))));
      //26 dias atras
      $ini_date = date('Y-m-d', strtotime('-'.(DIAS_VALIDEZ_TURNO+11).' days', strtotime(date('Y-m-d'))));
      $res = TramitesAIniciar::leftJoin('sigeci', 'tramites_a_iniciar.id', '=', 'sigeci.tramite_a_iniciar_id')
                      ->where('sigeci.fecha', '<', $last_date)
                      ->whereNull('tramites_a_iniciar.tramite_dgevyl_id')
                      ->update(['estado' => TURNO_VENCIDO]);
      \Log::info('['.date('h:i:s').'] revisarTurnosVencidos - Se da por TURNO_VENCIDO a los turnos menores a : '.$last_date);                     
    }catch(\Exception $e){
        \Log::warning('['.date('h:i:s').'] revisarTurnosVencidos Error: '.$e->getMessage()); 
    }                    
  }

  public function buscarBoletaSafitEnTurnosVencidos($tramiteAIniciar){
    $fecha_minima_pago = date('Y-m-d', strtotime('-'.(DIAS_VENCIMIENTO_BOLETA_SAFIT).' days', strtotime(date('Y-m-d'))));
    $encontrado = null;
    $res = TramitesAIniciar::where('estado', TURNO_VENCIDO)
                    ->where('bop_fec_pag', '>', $fecha_minima_pago)
                    ->where('nacionalidad', $tramiteAIniciar->nacionalidad)
                    ->where('nro_doc', $tramiteAIniciar->nro_doc)
                    ->where('tipo_doc', $tramiteAIniciar->tipo_doc)
                    //->where('sexo', $tramiteAIniciar->sexo)
                    ->first();
    if($res)
      $encontrado = (object) array( 'bopCB' => $res->bop_cb,
                                    'bopMonto' => $res->bop_monto,
                                    'bopFecPag' => $res->bop_fec_pag,
                                    'bopID' => $res->bop_id,
                                    'cemID' => $res->cem_id);
    return  $encontrado;
  }
}
