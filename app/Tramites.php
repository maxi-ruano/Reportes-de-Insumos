<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tramites extends Model
{
    protected $table = 'tramites';
    protected $primaryKey = 'tramite_id';
    protected $fillable = ['tramite_id', 'nro_doc', 'tipo_doc', 'sexo', 'pais', 'fec_inicio', 'estado', 'tipo_tramite_id', 'clase', 'detenido', 'motivo_detencion', 'sucursal', 'fec_emision', 'fec_vencimiento', 'clase_otorgada', 'modified_by', 'modification_date', 'validez', 'clase_desde', 'end_date'];


      public function SysRptServer()
      {
        return $this->belongsTo('App\SysRptServers', 'sucursal', 'sucursal_id');
      }

}
