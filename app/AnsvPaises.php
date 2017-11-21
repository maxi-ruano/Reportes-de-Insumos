<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnsvPaises extends Model
{
  protected $table = 'ansv_paises';
  protected $primaryKey = 'id_dgevyl';
  protected $fillable = ['id_dgevyl', 'id_ansv'];
}
