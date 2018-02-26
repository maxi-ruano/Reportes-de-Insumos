<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WsBoletasSafit extends Model
{
  function aviso_pago($uswID, $password, $datosBoleta ){
    //in reality, this data would be coming from a database

    //return 'error inesperado '.$uswID.' '.$password.' '.$datosBoleta->sexo; //array('rspID' => 1 , 'rspDescrip' => 'error inesperado');
    $response = array(
      'rspID' => '100',
      'rspDescrip' => $password,
    );
    return $response;
  }
}
