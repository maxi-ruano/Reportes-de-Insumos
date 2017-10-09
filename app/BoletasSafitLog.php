<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoletasSafitLog extends Model
{
  protected $table = 'boletas_safit_log';
  protected $fillable = ['mensaje_respuesta', 'datos_recibidos'];
}
