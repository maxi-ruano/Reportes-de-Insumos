<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\Http\Controllers\TramitesAIniciarController;
use App\Tramites;
use Log;

class MicroservicioController extends Controller
{
    private $tramitesAIniciar;

    public function __construct(){
      ini_set('default_socket_timeout', 600);
      $this->tramitesAIniciar = new WsClienteSafitController();
    }

    public function completarTurnosEnTramitesAIniciar(){
      $this->tramitesAIniciar->completarTurnosEnTramitesAIniciar( INICIO );
    }

    public function verificarLibreDeudaDeTramites(){
      $this->tramitesAIniciar->verificarLibreDeudaDeTramites(INICIO, LIBRE_DEUDA, VALIDACIONES);
    }

    public function completarBoletasEnTramitesAIniciar(){
      $this->tramitesAIniciar->completarBoletasEnTramitesAIniciar( INICIO, SAFIT);
    }

    public function emitirBoletasVirtualPago(){
      $this->tramitesAIniciar->emitirBoletasVirtualPago( SAFIT, EMISION_BOLETA_SAFIT,   VALIDACIONES);
    }

    public function verificarBuiTramites(){
      $this->tramitesAIniciar->verificarBuiTramites( INICIO, BUI, VALIDACIONES);
    }

    public function revisarValidaciones(){
      $this->tramitesAIniciar->revisarValidaciones(VALIDACIONES_COMPLETAS);
    }

    public function run(){
      ini_set('default_socket_timeout', 600);
      //$this->cargarEstados();
      $tramitesAIniciar = new TramitesAInicarController();
      //  pasa a estado 1
      $tramitesAIniciar->completarTurnosEnTramitesAIniciar( INICIO );
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

    public function runPrecheck(Request $request){

      ini_set('default_socket_timeout', 600);

      $tramitesAIniciar = new TramitesAInicarController();
      $tramite = TramitesAIniciar::find($request->id);
      
      switch ($request->validation) {
        case 4: //LIBRE DEUDA
          $precheck = $tramitesAIniciar->gestionarLibreDeuda($tramite, LIBRE_DEUDA, VALIDACIONES);
        break;
        case 5: //BUI
          $precheck = $tramitesAIniciar->gestionarBui($tramite, BUI, VALIDACIONES);
        break;
        case 3: //SAFIT
          if($tramitesAIniciar->buscarBoletaSafit($tramite, SAFIT))
            $precheck = $tramitesAIniciar->gestionarBoletaSafit($tramite, EMISION_BOLETA_SAFIT, VALIDACIONES);
            //$precheck = 'Se encontro la Boleta Safit';
          else
            $precheck = 'No se encontro la Boleta Safit';
        break;
        default:
          # code...
          $precheck = 'No se realizo ninguna operacion, validation incorrecta';
          break;
      }
      return $precheck;
    }
    
}
