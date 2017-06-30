<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtlTramite extends Model
{
  protected $table = 'etl_tramite';
  protected $primaryKey = 'tramite_id';
  protected $fillable = ['tramite_id', 'fecha_desde', 'fecha_hasta'];
  const UPDATED_AT = 'modification_date';
}
