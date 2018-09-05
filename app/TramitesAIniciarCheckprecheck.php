<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciarCheckprecheck extends Model
{
    protected $table = 'tramites_a_iniciar_checkprecheck';
    protected $fillable = ['id', 'tramite_a_inicar_id'];
}
