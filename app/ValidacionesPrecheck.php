<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidacionesPrecheck extends Model
{
    protected $table = 'validaciones_precheck';
    protected $fillable = ['validation_id', 'tramite_a_iniciar_id', 'validado'];    
}
