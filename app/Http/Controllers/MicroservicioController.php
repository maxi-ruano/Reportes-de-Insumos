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
      $tramitesAIniciar->comletarTurnosEnTramitesAIniciar( $this->estados->INICIO );
      // pasa de estado 1 a 2 los tramites
      $tramitesAIniciar->completarBoletasEnTramitesAIniciar( $this->estados->INICIO, $this->estados->SAFIT);
      // pasa de estado 2 a 3 los tramites
      $tramitesAIniciar->emitirBoletasVirtualPago( $this->estados->SAFIT, $this->estados->EMISION_BOLETA_SAFIT);
      // pasa de estado 3 a 4 los tramites
      $tramitesAIniciar->verificarLibreDeudaDeTramites( $this->estados->EMISION_BOLETA_SAFIT, $this->estados->LIBRE_DEUDA);
      // pasa de estado 4 a 5 los tramites
      $tramitesAIniciar->verificarBuiTramites( $this->estados->LIBRE_DEUDA,  $this->estados->BUI);
      // pasa de estado 5 a 6 los tramites
      $tramitesAIniciar->enviarTramitesASinalic( $this->estados->BUI, $this->estados->INICIO_EN_SINALIC);
//*/
    }

    function cargarEstados(){
      $this->estados = (object)$this->estados;
      $this->estados->INICIO = SysMultivalue::where('text_id', 'INICIO')->where('type', 'AUTO')->first()->id;
      $this->estados->SAFIT = SysMultivalue::where('text_id', 'SAFIT')->where('type', 'AUTO')->first()->id;
      $this->estados->EMISION_BOLETA_SAFIT = SysMultivalue::where('text_id', 'EMISION_BOLETA_SAFIT')->where('type', 'AUTO')->first()->id;
      $this->estados->LIBRE_DEUDA = SysMultivalue::where('text_id', 'LIBRE_DEUDA')->where('type', 'AUTO')->first()->id;
      $this->estados->BUI = SysMultivalue::where('text_id', 'BUI')->where('type', 'AUTO')->first()->id;
      $this->estados->INICIO_EN_SINALIC = SysMultivalue::where('text_id', 'INICIO_EN_SINALIC')->where('type', 'AUTO')->first()->id;
    }
}
