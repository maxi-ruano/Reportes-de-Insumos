<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnsvCelExpedidor extends Model
{
  protected $table = 'ansv_cel_expedidor';
  protected $primaryKey = 'sucursal_id';
  protected $fillable = ['sucursal_id', 'id_cel_expedidor'];
}
