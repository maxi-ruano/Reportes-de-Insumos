<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlSecuenciaInsumos extends Model
{
  protected $table = 'control_secuencia_insumos';
  protected $primaryKey = 'id';
  protected $fillable = ['id', 'insumo_ultimo', 'insumo_intento_insercion', 'sucursal', 'user_justificacion', 'justificado',
                         'fecha_justificacion', 'user_id', 'created_at', 'updated_at'];

   public function user()
   {
     return $this->belongsTo('App\SysUsers', 'user_id', 'id');
   }

}
