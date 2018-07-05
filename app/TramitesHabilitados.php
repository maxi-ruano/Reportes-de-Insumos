<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesHabilitados extends Model
{
    protected $table = 'tramites_habilitados';
    protected $primaryKey = 'id';
    protected $fillable = ['fecha', 'apellido','nombre','nro_doc', 'tipo_doc','pais'];

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

    public function userTexto(){
        $user = SysUsers::where('id', $this->user_id)->first();
    
        if($user)
            return $user->first_name.' '.$user->last_name;
        else
            return "";  
    }
}
