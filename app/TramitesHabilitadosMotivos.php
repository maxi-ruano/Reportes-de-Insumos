<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesHabilitadosMotivos extends Model
{
    protected $table = 'tramites_habilitados_motivos';
    protected $primaryKey = 'id';
    protected $fillable = ['description', 'activo','created_at','updated_at','deleted_at','deleted_by','limite','sucursal_id'];

    public function sucursalTexto(){
        $sucursal = SysMultivalue::where('type','SUCU')->where('id', $this->sucursal_id)->first();
        if($sucursal)
          return $sucursal->description;
        else
          return "";  
    }
}
