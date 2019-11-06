<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharlaVirtual extends Model
{
    protected $table = 'charla_virtual';
    protected $fillable = ['id',
             'tramites_a_iniciar_id',
             'nro_doc',
             'fecha_charla',
             'fecha_vencimiento',
             'aprobado',
             'response_ws'];
}
