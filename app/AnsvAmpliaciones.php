<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnsvAmpliaciones extends Model
{
  protected $table = 'ansv_ampliacion_clases';
  //protected $primaryKey = 'etl_examen_id';
  protected $fillable = ['tramite_id', 'clases_desde', 'clases_hasta', 'clases_dif'];
}
