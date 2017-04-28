<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeoricoPc extends Model
{
    protected $table = 'teorico_pcs';
    protected $fillable = ['id', 'ip', 'sucursal_id', 'estado', 'created_at', 'updated_at'];
}