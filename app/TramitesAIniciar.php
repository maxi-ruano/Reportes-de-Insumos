<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciar extends Model
{
  protected $table = 'tramites_a_inicar';
  protected $fillable = ['id', 'nombre', 'tipo_doc', 'nacionalidad', 'sexo'];
}
