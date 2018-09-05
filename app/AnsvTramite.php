<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnsvTramite extends Model
{
  protected $table = 'ansv_tramite';
  protected $primaryKey = 'tramite_id';
  protected $fillable = ['tramite_id',
                         'fecha_reporte',
                         'tipo_tramite_id',
                         'tenia_licencia_historica',
                         'numero_tramite_ansv',
                         'estado',
                         'paso_ansv'];
}
