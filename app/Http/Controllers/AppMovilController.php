<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysUsers;
use App\Tramites;
use App\SysMultivalue;

class AppMovilController extends Controller
{
    public function auth(Request $request)
    {
	$user = SysUsers::where('username',$request->username)->first();
	if($user){
		if(hash('md5',$request->password) == $user->password){
			$response = [
				"login" => true,
				"error" => null,
			];
			return response()->json($response);
		}else{
			$response = [
                                "login" => false,
				"error" => "Error en credenciales",
                        ];
			return response()->json($response);
		}
	}else{
		$response = [
                        "login" => false,
			"error" => "Error en credenciales",
                ];
		return response()->json($response);
	}
    }

	public function buscarTramite(Request $request)
    {
		$codigopais = SysMultivalue::where('description','ILIKE',"%".$request->description."%")
		->select('id')
		->first();

	$codigoelegido = $codigopais->id;

	$tramite = Tramites::where('tramites.nro_doc',$request->nro_doc)
	->select('tramites.tramite_id', 'tramites.nro_doc', 'tramites.sexo' , 'datos_personales.nombre' ,'datos_personales.apellido','tramites.pais')
	->where('tramites.tipo_doc',$request->tipo_doc)
	->where('tramites.sexo',$request->sexo)
	->where('tramites.pais',$codigoelegido)
	->where('estado',93)
	->join('datos_personales','tramites.nro_doc','datos_personales.nro_doc')->orderBy('tramite_id','desc')->first();
		//dd($tramite);

		if($tramite)
		{
			$response = [
				"inicio" => true,
				"tramite" => $tramite
			];
		}else{
			$response = [
				"inicio" => false,
			];
		}
	return response()->json($response);
    }
}
