<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoletaSafit extends Model
{
  protected $table = 'boletas_safit';
  protected $fillable = ['bopID', 'bopCodigo', 'nroDoc', 'tdcID', 'sexo', 'nombre', 'apellido'];
}
