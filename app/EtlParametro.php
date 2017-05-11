<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlParametro extends Model
{
  protected $table = 'etl_parametro';
  protected $primaryKey = 'etl_parametro_id';
  protected $fillable = ['etl_parametro_id','parametro','valor',
                          'tipo_parametro','modificacion_date'];
}
