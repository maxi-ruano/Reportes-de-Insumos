<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\Http\Controllers\TramitesAIniciarController;
use App\Tramites;
use Log;

class MicroservicioController extends Controller
{
    private $estados = array();
    public function run(){
      ini_set('default_socket_timeout', 600);
      $this->cargarEstados();
      $tramitesAIniciar = new TramitesAInicarController();
      //  pasa a estado 1
      $tramitesAIniciar->comletarTurnosEnTramitesAIniciar( INICIO );
      // Verificar Libre deuda, pasa a estado 4 en validaciones precheck
      $tramitesAIniciar->verificarLibreDeudaDeTramites(INICIO, LIBRE_DEUDA, VALIDACIONES); //ID validacion 4
      // pasa de estado 1 a 2 los tramites
      $tramitesAIniciar->completarBoletasEnTramitesAIniciar( INICIO, SAFIT);
      // Emitir cenat solo si estado 2
      $tramitesAIniciar->emitirBoletasVirtualPago( SAFIT, EMISION_BOLETA_SAFIT,   VALIDACIONES); //ID validacion 3
      // Emitir cenat solo si estado 1 ya actualiza validaciones_precheck
      $tramitesAIniciar->verificarBuiTramites( INICIO, BUI, VALIDACIONES); //ID validacion 5
      // Si bui, cenat y infracciones pasa de estado 2 a 6
      $tramitesAIniciar->revisarValidaciones(VALIDACIONES_COMPLETAS);
      // pasa de estado 6 a 7 los tramites
      //$tramitesAIniciar->enviarTramitesASinalic( VALIDACIONES, INICIO_EN_SINALIC);
      //
    }

  
}
