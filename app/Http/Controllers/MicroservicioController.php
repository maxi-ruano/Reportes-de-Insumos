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

    public function iniciarTramite($contribuyente){
      $tramite = null;
      if(!$this->existeTramite($contribuyente))
        //$tramite = $this->iniciarTramiteEnLicta();
        echo '<br>inicia tramite contribuyente: '.$contribuyente->id.'</br>' ;
      else{
        $tramite = $this->getTramite($contribuyente->nro_doc,
                                     $contribuyente->sexo,
                                     $contribuyente->pais,
                                     $contribuyente->tipo_doc);
        if(!$this->tramiteEnviadoAAnsv($tramite))
          //$this->enviarTramiteAAnsv($tramite);
          echo '<br>inicia tramite en NACION contribuyente: '.$contribuyente->id.'</br>' ;
        else
          echo '<br>el tramite del contribuyente: '.$contribuyente->id. ' ya fue enviado a NACION</br>' ;
      }
    }

    // OJO con esta funcion !! falta validad si estado < 14 esta bien
    public function existeTramite($contribuyente){
      $tramite = Tramites::whereNotNull('end_date')
                        ->where('estado', '<', 14)
                        ->where('nro_doc', $contribuyente->nro_doc)
                        ->where('sexo', $contribuyente->sexo)
                        ->where('tipo_doc', $contribuyente->tipo_doc)
                        ->where('pais', $contribuyente->pais)
                        ->first();
      return !is_null($tramite);
    }

    public function iniciarTramiteEnLicta($contribuyente){
      $tramite = new Tramite();
      $tramite->nro_doc = $contribuyente->nro_doc;
      $tramite->save();
    }

    public function tramiteEnviadoAAnsv($tramite){
      if(!empty($tramite)){
        $ansvTramite = AnsvTramite::where('tramite_id',$tramite->tramite_id)->orderBy('tramite_id', 'desc')->first();
        if(!empty($ansvTramite))
          return true;
      }
      return false;
    }

    public function iniciar(){
      $response = '';
      $data = [
          'CurrencyFrom' => 'USD',
          'CurrencyTo'   => 'EUR',
          'RateDate'     => '2014-06-05',
          'Amount'       => '1000'
      ];
      SoapWrapper::service('currency',function($service) use ($data,&$response) {
          $response = $service->call('GetConversionAmount',$data)->GetConversionAmountResult;
      });
      var_dump($response);
    }

    public function enviarTramiteAAnsv($tramite){
      echo '<br>enviando</br>' ;
    }

    public function getTramite($nro_doc, $sexo, $pais, $tipo_doc){
      $tramite = Tramites::where('nro_doc',$nro_doc)
                          ->where('tipo_doc',$tipo_doc)
                          ->where('sexo',$sexo)
                          ->where('pais',$pais)
                          ->orderBy('tramite_id','desc')->first();
      return $tramite;
    }


}
