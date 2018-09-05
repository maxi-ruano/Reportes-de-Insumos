<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BoletaSafit;
use App\BoletasSafitLog;
use Validator;

class WsBoletasSafitController extends Controller
{
  private $USWID = 'test';
  private $PASSWORD = 'test';

  function aviso_pago($uswID, $password, $datosBoleta ){
    if(empty($res = $this->validarFormato($uswID, $password, $datosBoleta))){
      if($this->usuarioValido($uswID, $password)){
        if($this->guardarAvisoPago($datosBoleta))
          $response = $this->formatoRespuesta(1, "Insertado correctamente");
      }else{
        $response = $this->formatoRespuesta(0, "usuario no valido");
        $this->guardarLog(json_encode($datosBoleta), json_encode($response));
      }
    }else{
      $response = $this->formatoRespuesta(0, $this->formatearJson($res));
      $this->guardarLog(json_encode($datosBoleta), json_encode($response));
    }

    return $response;
  }

  private function guardarAvisoPago($datosBoleta){
    $boletaSafit = new BoletaSafit();
    $boletaSafit->bop_id = $datosBoleta->bopID;
    $boletaSafit->bop_codigo = $datosBoleta->bopCodigo;
    $boletaSafit->nro_doc = $datosBoleta->nroDoc;
    $boletaSafit->tdc_id = $datosBoleta->tdcID;
    $boletaSafit->sexo = $datosBoleta->sexo;
    $boletaSafit->nombre = $datosBoleta->nombre;
    $boletaSafit->apellido = $datosBoleta->apellido;
    return $boletaSafit->save();
  }

  public function validarFormato($uswID, $password, $datosBoleta){
    $errores = array();
    if(!empty($res = $this->validarFormatoUsuario($uswID, $password)))
      $errores = array_merge($errores, $res);
    if(!empty($res = $this->validarFormatoDatosBoleta((array)$datosBoleta)))
      $errores = array_merge($errores, $res);

    return $errores;
  }

  public function validarFormatoUsuario($uswID, $password){
    $usuario = [
      'uswID' => $uswID,
      'password' => $password,
    ];

    $rules = [
      'uswID' => 'required',
      'password' => 'required',
    ];

    $messages = [
      'uswID.required' => 'El uswID es un dato obligatorio',
      'password.required' => 'El :attribute es un dato obligatorio',
   ];

   $validator = Validator::make($usuario , $rules, $messages);
   return $validator->errors()->messages();
  }

  public function validarFormatoDatosBoleta($datosBoleta){
    $rules = [
      'bopID' => 'required|numeric',
      'bopCodigo' => 'required|numeric',
      'nroDoc' => 'required|numeric',
      'tdcID' => 'required|numeric',
      'sexo' => 'required|in:M,F,m,f',
      'nombre' => 'required',
      'apellido' => 'required'
    ];

    $messages = [
      'bopID.required' => 'El :attribute es un dato obligatorio',
      'bopCodigo.required' => 'El :attribute es un dato obligatorio',
      'nroDoc.required' => 'El :attribute es un dato obligatorio',
      'tdcID.required' => 'El :attribute es un dato obligatorio',
      'bopID.numeric' => 'El campo :attribute no es numerico, debe ingresar un dato numerico',
      'bopCodigo.numeric' => 'El campo :attribute no es numerico, debe ingresar un dato numerico',
      'nroDoc.numeric' => 'El campo :attribute no es numerico, debe ingresar un dato numerico',
      'tdcID.numeric' => 'El campo :attribute no es numerico, debe ingresar un dato numerico',
      'sexo.required' => 'El :attribute es un dato obligatorio',
      'sexo.in' => 'El campo :attribute deberia ser F o M',
      'nombre.required' => 'El :attribute es un dato obligatorio',
      'apellido.required' => 'El :attribute es un dato obligatorio'
   ];
   $validator = Validator::make($datosBoleta , $rules, $messages);
   return $validator->errors()->messages();
  }

  public function formatearJson($errores){
    $res = array();
    foreach ($errores as $key => $value) {
      $res[] = $this->formatoRespuesta($key, $value);
    }
    return json_encode($res);
  }

  public function usuarioValido($uswID, $password){
    return (($uswID == $this->USWID) && ($password == $this->PASSWORD));

  }

  public function formatoRespuesta($rspID, $rspDescrip){
    return [
      'rspID' => $rspID,
      'rspDescrip' => $rspDescrip,
    ];
  }

  public function guardarLog($datosBoleta, $mensaje)
  {
    $boletaSafitLog = new BoletasSafitLog();
    $boletaSafitLog->mensaje_respuesta = $mensaje;
    $boletaSafitLog->datos_recibidos = $datosBoleta;
    return $boletaSafitLog->save();
  }
}
