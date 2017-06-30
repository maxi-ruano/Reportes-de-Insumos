<?php

namespace App\Http\Controllers;

use App\Disposiciones;
use Illuminate\Http\Request;
use App\SysMultivalue;
use App\Tramites;
use App\DatosPersonales;
use App\EtlTramite;
use App\Http\Controllers\DisposicionesController;

class DisposicionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if( session('usuario_rol') == 9 )
        $disposiciones = Disposiciones::orderBy('id', 'desc')->get();
      else
        $disposiciones = Disposiciones::where('sys_user_id_solicitante', session('usuario_id'))->orderBy('id', 'desc')->all();
      return View('disposiciones.index')->with('disposiciones', $disposiciones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $tramite = '';
      $datosPersonales = '';
      if(isset($request->nro_doc)){
        $tramite = Tramites::where('nro_doc',$request->nro_doc)
                            ->where('tipo_doc',$request->tipo_doc)
                            ->where('sexo',$request->sexo)
                            ->where('pais',$request->pais)->first();

        $datosPersonales = DatosPersonales::where('nro_doc',$request->nro_doc)
                            ->where('tipo_doc',$request->tipo_doc)
                            ->where('sexo',$request->sexo)
                            ->where('pais',$request->pais)->first();
      }

      $paises = SysMultivalue::select('id','description')->where('type','PAIS')->orderBy('description', 'asc')->pluck('description','id');
      $tdocs = SysMultivalue::select('id','description')->where('type','TDOC')->orderBy('id', 'asc')->pluck('description','id');
      $sexos = SysMultivalue::select('id','description')->where('type','SEXO')->orderBy('id', 'asc')->pluck('description','id');

      return View('disposiciones.template')->with('paises',$paises)
                                           ->with('tdocs',$tdocs)
                                           ->with('sexos',$sexos)
                                           ->with('tramite',$tramite)
                                           ->with('datosPersonales',$datosPersonales);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->modificarFechaParaDisposicion($request->tramite_id, '-');
        $disposicion = new Disposiciones($request->all());
        $disposicion->save();
        //Flash::info('El Departamento se ha creado correctamente');
        return redirect('/admin/disposiciones');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Disposiciones  $disposiciones
     * @return \Illuminate\Http\Response
     */
    public function show(Disposiciones $disposiciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Disposiciones  $disposiciones
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $disposicion = Disposiciones::find($id);
      return View('disposiciones.template')->with('disposicion', $disposicion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Disposiciones  $disposiciones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Disposiciones $disposiciones)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Disposiciones  $disposiciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Disposiciones $disposiciones)
    {
        //
    }

    public function modificarFechaParaDisposicion($tramite_id, $accion)
    {
      if($accion == '+')
          $disposicion = Disposiciones::where('tramite_id', $tramite_id);

      if( isset($disposicion) || $accion == '-' ){
        $etlTramite = EtlTramite::find($tramite_id)->orderBy('tramite_id', 'desc')->first();
        $nuevaFecha = strtotime ( $accion.config('global.DIAS_RETROCESO_DISPOSICION').' day' , strtotime ( $etlTramite->fecha_desde ) );
        $etlTramite->fecha_desde =  date ( 'Y-m-j H:m:s' , $nuevaFecha );
        $etlTramite->save();
      }
    }
}
