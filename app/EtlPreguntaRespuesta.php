<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlPreguntaRespuesta extends Model
{
  protected $table = 'etl_pregunta_respuesta';
  protected $primaryKey = 'etl_pregunta_respuesta_id';
  protected $fillable = ['etl_pregunta_respuesta_id', 'pregunta_id','respuesta_id', 'habilitado','correcta','modificacion_date', 'orden'];
}
