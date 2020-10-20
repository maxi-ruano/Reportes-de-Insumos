<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CharlaVirtual extends Model
{
    protected $table = 'charla_virtual';
    protected $fillable = ['id',
             'codigo',
	     'nro_doc',
	     'apellido',
	     'nombre',
	     'sexo',
	     'email',
	     'fecha_nacimiento',
	     'fecha_charla',
	     'fecha_aprobado',
             'fecha_vencimiento',
	     'aprobado',
	     'categoria',
             'response_ws'];
}
