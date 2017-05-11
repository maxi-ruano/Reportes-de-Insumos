<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosPersonales extends Model
{
  protected $table = 'datos_personales';
  //protected $primaryKey = 'etl_examen_id';
  protected $fillable = ['pais', 'tipo_doc', 'nro_doc', 'sexo', 'nombre', 'apellido'];
}
