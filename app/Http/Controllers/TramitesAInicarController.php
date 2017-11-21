<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sigeci;
use App\TramitesAIniciar;
use App\Http\Controllers\SoapController;
use App\Http\Controllers\WsClienteSafitController;
use App\AnsvPaises;
use App\SysMultivalue;

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
    foreach ($boletas->datosBoletaPago->datosBoletaPagoParaPersona as $key => $boletaI) {
      if($this->esBoletaValida($boletaI)){
        if(!is_null($boleta)){
          if( date($boletaI->bopFecPag) >= date($boleta->bopFecPag)) // para obtener la boleta mas reciente
            $boleta = $boletaI;
        }else
          $boleta = $boletaI;
      }
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

  public function emitirBoletaVirtualPago(){
    if(is_null($this->wsSafit))
      $this->temporalConstructor();
    $tramitesAIniciar = TramitesAIniciar::where('estado', 2)->get();
    foreach ($tramitesAIniciar as $key => $tramiteAIniciar) {
      $res = $this->wsSafit->emitirBoletaVirtualPago($tramiteAIniciar);
      dd($res);
    }
  }

  public function getIdPais($pais){
    /*$paises = SysMultivalue::where('type', 'PAIS')->get();
    $res = null;
    //dd($paises);
    foreach ($paises as $key => $value){
      //echo similar_text($pais, $value->description).'<br>';
      similar_text(strtolower($value->description), strtolower($pais), $percent);
      if($percent > 50)
      echo strtolower($pais)." ".strtolower($value->description)." ".$percent. "<br>";
        //$res = AnsvPaises::where('id_dgevyl', $value->id)->first();
    }*/
    return 1;
  }
}
