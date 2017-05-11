<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlRespuesta extends Model
{
    protected $table = 'etl_respuesta';
    protected $primaryKey = 'etl_respuesta_id';
    protected $fillable = ['etl_respuesta_id', 'texto', 'habilitado', 'modificacion_date'];


}
