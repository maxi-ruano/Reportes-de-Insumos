<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;
use App\Http\Controllers\TramitesAIniciarController;
use App\Tramites;
use Log;

class MicroservicioController extends Controller
{
    public function run(){
      $tramitesAIniciar = new TramitesAInicarController();
      //$tramitesAIniciar->comletarTurnosEnTramitesAIniciar();// pasa de estado 1 a 2 los tramites
      //$tramitesAIniciar->completarBoletasEnTramitesAIniciar();// pasa de estado 2 a 3 los tramites
      //$tramitesAIniciar->emitirBoletasVirtualPago(); // pasa de estado 3 a 4 los tramites
      //$tramitesAIniciar->verificarLibreDeuda(); // pasa de estado 4 a 5 los tramites
      $tramitesAIniciar->enviarTramitesASinalic();// pasa de estado 5 a 6 los tramites
      /*echo '<br>run</br>' ;
      $tramitesAIniciar = TramitesAIniciar::where('estado', 'pendiente')->get();
      foreach ($tramitesAIniciar as $key => $value) {
        $this->iniciarTramite($value);
      }*/
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
