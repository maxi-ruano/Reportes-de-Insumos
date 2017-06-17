<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesLog extends Model
{
  protected $table = 'tramites_log';
  protected $primaryKey = 'tramite_id';
  protected $fillable = ['tramite_id', 'sucursal'];
}
