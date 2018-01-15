<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciarErrores extends Model
{
    protected $table = 'tramites_a_iniciar_errores';
    protected $fillable = ['description', 'tramites_a_inicar_id'];
}
