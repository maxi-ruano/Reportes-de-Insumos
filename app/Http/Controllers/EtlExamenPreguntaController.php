<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EtlExamenPregunta;

use App\EtlPreguntaRespuesta;

use App\Http\Controllers\DB;

class EtlExamenPreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preguntas = EtlExamenPregunta::all();
        //$preguntas = 'hola este es el index';
        dd($preguntas);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function getPreguntasExamen($examen_id)
    {
      $preguntas = EtlExamenPregunta::where('examen_id', $examen_id)->get();

      foreach ($preguntas as $key => $value) {
        $value['respuestas'] = EtlPreguntaRespuesta::where('pregunta_id', $value->pregunta_id)->get();
      }

      return View('examen.pregunta')->with('preguntas', $preguntas)
                                    ->with('examen', $examen_id);
    }

    public function guardarRespuesta(Request $request){
      $res = 'No se puedo guardar la respuesta';
      $etlExamenPregunta = EtlExamenPregunta::where('examen_id',$request->input('examen_id'))
                                            ->where('pregunta_id',$request->input('pregunta_id'))->first();

      $etlExamenPregunta->respuesta_id = $request->input('respuesta_id');
      $etlExamenPregunta->save();
      if($etlExamenPregunta->respuesta_id == $request->input('respuesta_id'))
        $res = 'success';
      return response()->json(['res' => $res]);
    }


}
