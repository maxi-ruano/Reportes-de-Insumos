<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LibreDeudaLns extends Model
{
  protected $table = 'libredeuda_lns';
  protected $primaryKey = 'libredeuda_lns_id';
  public $timestamps = false;
  protected $fillable = ['libredeuda_lns_id',
                         'libredeuda_hdr_id',
                         'numero_completo',
                         'numero_id',
                         'digito',
                         'codigo_barras',
                         'codigo_barras_encriptado',
                         'username',
                         'importe',
                         'clavesb',
                         'fecha_emision_completa',
                         'hora_emision',
                         'fecha_emision',
                         'fecha_vencimiento_completa',
                         'fecha_vencimiento'
                       ];
}
