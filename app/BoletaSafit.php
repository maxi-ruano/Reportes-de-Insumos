<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoletaSafit extends Model
{
  protected $table = 'boletas_safit';
  protected $fillable = ['bop_id',
             'bop_codigo',
             'nro_doc',
             'tdc_id',
             'sexo',
             'nombre',
             'apellido'];
}
