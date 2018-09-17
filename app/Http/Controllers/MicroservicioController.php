<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\Http\Controllers\TramitesAIniciarController;
use App\Tramites;
use Log;

class MicroservicioController extends Controller
{
    public function __construct(){
      ini_set('default_socket_timeout', 600);
    }

    public function completarTurnosEnTramitesAIniciar(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: completarTurnosEnTramitesAIniciar()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->completarTurnosEnTramitesAIniciar( INICIO );
    }

    public function verificarLibreDeudaDeTramites(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: verificarLibreDeudaDeTramites()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->verificarLibreDeudaDeTramites(INICIO, LIBRE_DEUDA, VALIDACIONES);
    }

    public function completarBoletasEnTramitesAIniciar(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: completarBoletasEnTramitesAIniciar()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->completarBoletasEnTramitesAIniciar( INICIO, SAFIT);
    }

    public function emitirBoletasVirtualPago(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: emitirBoletasVirtualPago()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->emitirBoletasVirtualPago( SAFIT, EMISION_BOLETA_SAFIT,   VALIDACIONES);
    }

    public function verificarBuiTramites(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: verificarBuiTramites()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->verificarBuiTramites( INICIO, BUI, VALIDACIONES);
    }

    public function revisarValidaciones(){
      \Log::info('['.date('h:i:s').'] '.'se inicio: revisarValidaciones()');
      $tramitesAIniciar = new TramitesAInicarController();
      $tramitesAIniciar->revisarValidaciones(VALIDACIONES_COMPLETAS);
    }

    public function run(){
      ini_set('default_socket_timeout', 600);
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
      $precheck='';
      
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
          else
            $precheck = 'No se encontro la Boleta Safit';
        break;
        default:
          $precheck = 'No se realizo ninguna operacion, validation incorrecta';
          break;
      }
      return $precheck;
    }

}