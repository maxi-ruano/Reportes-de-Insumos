<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SysUsers;
use App\Tramites;

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
	$tramite = Tramites::where('tramites.nro_doc',$request->nro_doc)->where('tramites.tipo_doc',$request->tipo_doc)->where('tramites.sexo',$request->sexo)->where('tramites.pais',$request->pais)->where('tramites.estado',93)->join('datos_personales',
	'tramites.nro_doc', 'datos_personales.nro_doc')->get();
	dd($tramite);
	if($tramite){
		
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
}
