<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TramitesAIniciar;

class MicroservicioController extends Controller
{
    public function run(){
      $tramitesAIniciar = TramitesAIniciar::where('estado', 'pendiente');
      foreach ($tramitesAIniciar as $key => $value) {
        $this->iniciarTramite($vale);
      }
      /*$tramites_a_inicar = new TramitesAIniciar();
      $tramites_a_inicar->nombre = 'juan carlos';
      $tramites_a_inicar->sexo = 'm';
      $tramites_a_inicar->tipo_doc = '2';
      $tramites_a_inicar->estado = 'pendiente';
      $tramites_a_inicar->nacionalidad = 'bol';
      $tramites_a_inicar->save();*/
    }

    public function iniciarTramite($contribuyente){

    }
}
