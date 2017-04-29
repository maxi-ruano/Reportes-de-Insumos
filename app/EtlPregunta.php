<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlPregunta extends Model
{
  protected $table = 'etl_pregunta';
  protected $primaryKey = 'etl_pregunta_id';
  protected $fillable = ['etl_pregunta_id', 'texto', 'imagen'];
}
