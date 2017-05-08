<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlExamenPregunta extends Model
{

       protected $table = 'etl_examen_pregunta';
       protected $primaryKey = 'etl_examen_pregunta_id';
       protected $fillable = ['etl_examen_pregunta_id','examen_id','modificacion_id',
                               'pregunta_id','respuesta_id'];

       public function etlPregunta()
       {
         return $this->belongsTo('App\EtlPregunta', 'pregunta_id', 'etl_pregunta_id');
       }

       public function etlPreguntaRespuestas()
       {
         return $this->hasMany('App\etlPreguntaRespuesta', 'pregunta_id', 'pregunta_id');
       }
}
