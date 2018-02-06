<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciarErrores extends Model
{
    protected $table = 'tramites_a_iniciar_errores';
    protected $fillable = ['description', 'estado_error', 'request_ws', 'response_ws','tramites_a_inicar_id'];
}
