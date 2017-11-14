<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciar extends Model
{
  protected $table = 'tramites_a_inicar';
  protected $fillable = ['id', 'nombre', 'apellido','tipo_doc', 'nro_doc', 'nacionalidad', 'sexo', 'estado',
                         'bop_cb', 'bop_monto', 'bop_fec_pag', 'bop_id'];
}
