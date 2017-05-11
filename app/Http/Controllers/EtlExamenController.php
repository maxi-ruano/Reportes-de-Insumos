<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EtlExamen;
use App\EtlPreguntaRespuesta;
use App\EtlExamenPregunta;
use App\EtlParametro;

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
      //COLOCAR COMO VARIABLE GLOBAL !!!
      $ID_PORCENTAJE_APROBACION = 5;
      $porcentajeAprovacion = EtlParametro::find($ID_PORCENTAJE_APROBACION);

      $aprobado = 'false';
      if($porcentaje >= $porcentajeAprovacion->valor)
        $aprobado = 'true';

      //guardamos el resultado
      $examen = EtlExamen::find($request->examen_id);
      $examen->aprobado = $aprobado;
      $examen->porcentaje = $porcentaje;
      $examen->ip = $request->ip;
      $examen->save();

      return View('layouts.templatebasica')->with('porcentaje', $porcentaje);
      echo $porcentaje;
    }
}
