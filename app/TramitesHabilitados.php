<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesHabilitados extends Model
{
    protected $table = 'tramites_habilitados';
    protected $primaryKey = 'id';
    protected $fillable = ['fecha', 'apellido','nombre','nro_doc', 'tipo_doc','fecha_nacimiento','sexo','pais','user_id','sucursal','motivo_id','nro_expediente','sigeci_idcita'];

    public function tipoDocText(){
        $tipoDoc = SysMultivalue::where('type','TDOC')->where('id', $this->tipo_doc)->first();
        if($tipoDoc)
          return $tipoDoc->description;
        else
          return "";  
    }

    public function paisTexto(){
    $pais = SysMultivalue::where('type','PAIS')->where('id', $this->pais)->first();

    if($pais)
        return $pais->description;
    else
        return "";  
    }

    public function rolTexto(){
        $rol = \DB::table('roles')->join('model_has_roles','model_has_roles.role_id','roles.id')->where('model_has_roles.model_id', $this->user_id)->first();
        if($rol)
            return $rol->name;
        else
            return "";
    }

    public function userTexto($id){
        $user = User::where('id', $id)->first();
    
        if($user)
            return $user->name;
        else
            return "";  
    }

    public function motivoTexto(){
        $motivo = \DB::table('tramites_habilitados_motivos')->where('id', $this->motivo_id)->first();
    
        if($motivo)
            return $motivo->description;
        else
            return "";  
    }

    public function sucursalTexto(){
        $sucursal = SysMultivalue::where('type','SUCU')->where('id', $this->sucursal)->first();
        if($sucursal)
          return $sucursal->description;
        else
          return "";  
    }

    public function observacion(){
        $res = \DB::table('tramites_habilitados_observaciones')->where('tramite_habilitado_id', $this->id)->first();
    
        if($res)
            return $res->observacion;
        else
            return "";  
    }
}
