<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlExamen extends Model
{
  protected $table = 'etl_examen';
  protected $primaryKey = 'etl_examen_id';
  protected $fillable = ['etl_examen_id','tramite_id','fecha_inicio',
                        'fecha_fin','aprovado', 'porcentaje', 'anulado',
                         'modificacion_id', 'clase_name', 'ip'];
   public function etlExamenPreguntas()
   {
     return $this->hasMany('App\EtlExamenPregunta', 'examen_id', 'etl_examen_id');
   }

   public function tramite()
   {
     return $this->belongsTo('App\Tramites', 'tramite_id', 'tramite_id');
   }
}
