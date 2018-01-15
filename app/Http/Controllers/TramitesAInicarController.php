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

class TramitesAInicarController extends Controller
{
  private $diasEnAdelante = 3;
  private $munID = 1;
  private $estID = "A";
  private $estadoBoletaNoUtilizada = "N";
  public $wsSafit = null;

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
    $tramites = TramitesAIniciar::where('estado', 5)->get();
    foreach ($tramites as $key => $tramite) {
      //dd('hola');
      $clienteSinalic = new WsClienteSinalicController();
      $clienteSinalic->iniciarTramiteNuevaLicencia($tramite);
    }
  }

  public function inicarTramiteEnSinalic($tramite){

  }
}
