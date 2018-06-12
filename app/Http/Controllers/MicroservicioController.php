<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\Http\Controllers\TramitesAIniciarController;
use App\Tramites;
use App\SysMultivalue;
use Log;

class MicroservicioController extends Controller
{
    private $estados = array();
    public function run(){
      ini_set('default_socket_timeout', 600);
      $this->cargarEstados();
      $tramitesAIniciar = new TramitesAInicarController();
      //  pasa a estado 1
      //$tramitesAIniciar->comletarTurnosEnTramitesAIniciar( $this->estados->INICIO );
      // pasa de estado 1 a 2 los tramites
      //$tramitesAIniciar->completarBoletasEnTramitesAIniciar( $this->estados->INICIO, $this->estados->SAFIT);
      // Emitir cenat solo si estado 2
      //$tramitesAIniciar->emitirBoletasVirtualPago( $this->estados->SAFIT, $this->estados->EMISION_BOLETA_SAFIT,   $this->estados->VALIDACIONES); //ID validacion 3
      // Emitir cenat solo si estado 1 ya actualiza validaciones_precheck
      //$tramitesAIniciar->verificarLibreDeudaDeTramites($this->estados->INICIO, $this->estados->LIBRE_DEUDA, $this->estados->VALIDACIONES); //ID validacion 4
      // Emitir cenat solo si estado 1 ya actualiza validaciones_precheck
      //$tramitesAIniciar->verificarBuiTramites( $this->estados->INICIO, $this->estados->BUI, $this->estados->VALIDACIONES); //ID validacion 5
      $tramitesAIniciar->revisarValidaciones($this->estados->VALIDACIONES_COMPLETAS, $this->estados->VALIDACIONES);
      // Si bui, cenat y infracciones pasa de estado 2 a 6
      // pasa de estado 6 a 7 los tramites
      //$tramitesAIniciar->enviarTramitesASinalic( $this->estados->VALIDACIONES, $this->estados->INICIO_EN_SINALIC);
      //*/
    }

    function cargarEstados(){
      $this->estados = (object)$this->estados;
      $this->estados->INICIO = SysMultivalue::where('text_id', 'INICIO')->where('type', 'AUTO')->first()->id;
      $this->estados->SAFIT = SysMultivalue::where('text_id', 'SAFIT')->where('type', 'AUTO')->first()->id;
      $this->estados->VALIDACIONES = SysMultivalue::where('text_id', 'VALIDACIONES')->where('type', 'AUTO')->first()->id;
      $this->estados->EMISION_BOLETA_SAFIT = SysMultivalue::where('text_id', 'EMISION_BOLETA_SAFIT')->where('type', 'VALP')->first()->id;
      $this->estados->LIBRE_DEUDA = SysMultivalue::where('text_id', 'LIBRE_DEUDA')->where('type', 'VALP')->first()->id;
      $this->estados->BUI = SysMultivalue::where('text_id', 'BUI')->where('type', 'VALP')->first()->id;
      $this->estados->VALIDACIONES_COMPLETAS = SysMultivalue::where('text_id', 'VALIDACIONES_COMPLETAS')->where('type', 'VALP')->first()->id;
      $this->estados->INICIO_EN_SINALIC = SysMultivalue::where('text_id', 'INICIO_EN_SINALIC')->where('type', 'AUTO')->first()->id;
    }
}
