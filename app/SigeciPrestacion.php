<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigeciPrestacion extends Model
{
  protected $table = 'sigeci_prestaciones';
  protected $fillable = ['id', 'prestacion_id', 'tipo_tramite_ansv_id', 'descripcion', 'ws'];

}
