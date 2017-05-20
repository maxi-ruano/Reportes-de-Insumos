<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeoricoPc extends Model
{
    protected $table = 'teorico_pcs';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'ip', 'sucursal_id', 'estado', 'activo', 'examen_id', 'created_at', 'updated_at'];
}
