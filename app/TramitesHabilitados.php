<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesHabilitados extends Model
{
    protected $table = 'tramites_habilitados';
    protected $primaryKey = 'id';
    protected $fillable = ['fecha', 'apellido','nombre','nro_doc', 'tipo_doc','fecha_nacimiento','sexo','pais','user_id','sucursal','motivo_id'];

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
}
