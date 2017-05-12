<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SysMultivalue;

use App\Tramites;

class BedelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $paises = SysMultivalue::where('type','PAIS')->orderBy('description', 'asc')->get();
      $tdoc = SysMultivalue::where('type','TDOC')->orderBy('id', 'asc')->get();
      $sexo = SysMultivalue::where('type','SEXO')->orderBy('id', 'asc')->get();
      if (isset($request->doc) && $request->doc != '' && isset($request->sexo) && $request->sexo != '' && isset($request->pais) && $request->pais != '') {
        $peticion = $this->getTramiteExactly($request->doc, $request->sexo, $request->pais);
      }
      $peticion = $peticion ?? array(false);
      return view('bedel.asignacion')->with('paises',$paises)->with('tipo_doc',$tdoc)->with('sexo',$sexo)->with('peticion',$peticion);
    }


    public function getTramiteExactly($nro_doc, $sexo, $pais)
    {
      $response_array = array();
      $posibles = Tramites::where('nro_doc', $nro_doc)
      ->where('sexo', $sexo)
      ->where('pais', $pais)
      ->where('estado', 8)
      ->orderBy('tramite_id', 'desc')
      ->get();
      if ($posibles != null) {
        array_push($response_array,true);
        array_push($response_array,$posibles);
      }
      else {
        array_push($response_array,false);
      }
      return $response_array;
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
}
