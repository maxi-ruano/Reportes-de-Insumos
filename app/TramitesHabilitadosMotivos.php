<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesHabilitadosMotivos extends Model
{
    protected $table = 'tramites_habilitados_motivos';
    protected $primaryKey = 'id';
    protected $fillable = ['description', 'activo'];
}
