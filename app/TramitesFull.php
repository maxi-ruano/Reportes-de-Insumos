<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesFull extends Model
{
  protected $table = 'tramites_full_tbl_v';
  protected $fillable = ['tramite_id', 'nro_doc', 'sexo', 'clase', 'estado', 'tipo_tramite_id', 'sucursal', 'tipo_doc', 'pais', 'detenido', 'motivo_detencion', 'clase_otorgada', 'clase_desde', 'validez', 'tipo_tramite_value', 'clase_value', 'clase_otorgada_value', 'motivo_detencion_value', 'estado_value', 'fec_emision', 'fec_vencimiento', 'baja'];
}
