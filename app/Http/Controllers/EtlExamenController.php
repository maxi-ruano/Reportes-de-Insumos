<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\EtlExamen;
use App\EtlPreguntaRespuesta;
use App\EtlExamenPregunta;
use App\EtlParametro;
use App\TeoricoPc;
use App\Http\Controllers\Carbon\Carbon;
use Cache;
use App\Http\Controllers\BedelController;

class EtlExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$examen = EtlExamen::all();
        dd("aca te imprimo todo los examenes");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $examen = EtlExamen::find($id);
        dd($examen);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $examen = EtlExamen::find($id);
        dd($examen);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function guardar_respuesta(Request $request)
    {
      return View('examen.pregunta');
        //dd("aqui te van las preguntas");
    }

    public function calcularYGuardarResultado(Request $request)
    {
      //obtenemos las preguntas y respuestas del examen
      $preguntasYRespuestas = EtlExamenPregunta::where('examen_id', $request->examen_id)
                                               ->orderBy('pregunta_id', 'asc')->get();

      $preguntas_ids = array();
      foreach ($preguntasYRespuestas as $key => $value){
        array_push($preguntas_ids, $value->pregunta_id);
      }
      //obtenemos las respuestas correctas del examen
      $respuestasCorrectas = EtlPreguntaRespuesta::whereIn('pregunta_id', $preguntas_ids)
                                                               ->where('correcta', 'true')
                                                               ->orderBy('pregunta_id', 'asc')->get();
      //calculamos cuantas respuestas son correctas

       $correctas = 0;

       foreach( $preguntasYRespuestas as $key =>  $respuestaExamen)
        foreach( $respuestasCorrectas as $key =>  $respuestaCorrecta)
           if($respuestaExamen->pregunta_id == $respuestaCorrecta->pregunta_id){
             if($respuestaExamen->respuesta_id == $respuestaCorrecta->respuesta_id)
               $correctas++;
             break;
           }
      //calculamos la nota
      $porcentaje = ($correctas/count($preguntasYRespuestas)) * 100;
      $porcentaje = round($porcentaje,2);

      $porcentajeAprovacion = EtlParametro::find(config('global.ID_PORCENTAJE_APROBACION'));

      $BedelController = new BedelController();
      //guardamos el resultado
      $examen = EtlExamen::find($request->examen_id);
      $examen->aprobado = $porcentaje >= $porcentajeAprovacion->valor;
      $examen->porcentaje = $porcentaje;
      $examen->ip = $request->ip;
      $examen->fecha_fin = DB::raw('current_timestamp');
      $examen->save();

      if($examen->aprobado){
        $aprobado = 'true';
        $mensaje = 'Examen <span class="label label-success"> APROBADO </span> con un';
        $categorias = $BedelController->api_get(config('global.API_SERVIDOR'),array(
                    'function' => 'aprobar_examen',
                    'examen_id' => (int)$request->examen_id));
      }else{
        $aprobado = 'false';
        $mensaje = 'En esta ocasión usted <span class="label label-danger"> REPROBO</span> con un';
        $categorias = $BedelController->api_get(config('global.API_SERVIDOR'),array(
                    'function' => 'reprobar_examen',
                    'examen_id' => (int)$request->examen_id));
      }


      $teoricoPc = TeoricoPc::where('examen_id',$request->examen_id)->first();
      $teoricoPc->activo = false;
      $teoricoPc->save();

      $examen->mensaje = $mensaje;
      $examen->cantidadOportunidadesExamen = config('global.CANT_MAX_EXAM_CAT') - $this->getCantidadExamenes($examen->tramite_id, $examen->clase_name);
      $examen->computadora_id = $teoricoPc->id;
      $examen->porcentajeAprovacion = $porcentajeAprovacion->valor;
      return View('layouts.block')->with('examen', $examen);
    }

    public function getCantidadExamenes($tramite, $categoria){
      $cantidad = EtlExamen::where('tramite_id', $tramite)
                        ->where('clase_name', $categoria)
                        ->where(function ($cantidad) {
                          $cantidad->where('anulado', false)
                                ->orWhereNull('anulado');
                          })->get();
      return count($cantidad);
    }
}
