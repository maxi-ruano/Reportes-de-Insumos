<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoletaBui extends Model
{
  protected $table = 'boletas_bui';
  protected $fillable = ['id_boleta',
             'nro_boleta',
             'cod_barras',
             'importe_total',
             'fecha_pago',
             'lugar_pago',
             'medio_pago',
             'tramite_a_iniciar_id'];
}
