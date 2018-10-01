<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SigeciPrestacion;
use App\SysMultivalue;
use App\TramitesHabilitados;
class TramitesAIniciar extends Model
{
  protected $table = 'tramites_a_iniciar';
  protected $fillable = ['id', 'nombre', 'apellido','tipo_doc', 'tipo_tramite', 'nro_doc', 'nacionalidad', 'sexo', 'estado',
                         'bop_cb', 'bop_monto', 'bop_fec_pag', 'bop_id', 'cem_id', 'tramite_sinalic_id'];

  public function tipoTramite()
  {
    $sigeciPrestacion = SigeciPrestacion::where('prestacion_id', $this->tipo_tramite_sigeci)->first();
    return $sigeciPrestacion->ws;
  }

  public function tipoDocText(){
    $hablitado = TramitesHabilitados::where('tramites_a_iniciar_id',$this->id)->count();
    if($hablitado){
      $tdoc = SysMultivalue::where('type','TDOC')->where('id', $this->tipo_doc)->first();
      $tipoDocText = $tdoc->description;
    }else{
      $tipoDocText = $this->tipoDocBui();
    }
    
    return $tipoDocText;
  }

  public function nacionalidadTexto(){
    $tipoDocText = \DB::table('sigeci_paises')->join('ansv_paises', 'ansv_paises.id_sigeci_paises', '=', 'sigeci_paises.id')
                                              ->where('ansv_paises.id_ansv', $this->nacionalidad)
                                              ->first();
    if($tipoDocText)
      return $tipoDocText->pais;
    else
      return "";  
  }

  public function sigeci(){
     return $this->hasOne('App\Sigeci','tramite_a_iniciar_id','id');
  }

  public function tipoDocSafit(){
    $tipoDoc;
    switch ($this->tipo_doc) {
      case 1 :
        $tipoDoc = 1; //DNI
        break;
      case 2 :
        $tipoDoc = 2; //LE
        break;
      case 3 :
        $tipoDoc = 3; //LC
        break;
      case  4 :
        $tipoDoc = 1; //CI -> DNI
        break;
      case  5 :
        $tipoDoc = 1; //DNI EXT
        break;        
      case  6 :
        $tipoDoc = 6; //PASAPORTE
        break;      
      default:
        $tipoDoc = 1; //DNI
        break;
    }
    return $tipoDoc;
  }

  public function tipoDocBui(){
    $tipoDoc;
    switch ($this->tipo_doc) {
      case 1 :
        $tipoDoc = 'DNI'; //DNI
        break;
      case 2 :
        $tipoDoc = 'LE'; //LE
        break;
      case 3 :
        $tipoDoc = 'LC'; //LC
        break;
      case 4 :
        $tipoDoc = 'CI'; //LC
        break;
      case 5 :
        $tipoDoc = 'DNI'; //CI -> DNI
        break;
      case 6 :
        $tipoDoc = 'PAS'; //DNI EXT
        break;        
      default:
        $tipoDoc = 'DNI'; //DNI
        break;
    }
    return $tipoDoc;
  }

  public function tipoDocLibreDeuda(){
    $tipoDoc;
    switch ($this->tipo_doc) {
      case 1 :
        $tipoDoc = 'DNI'; //DNI
        break;
      case 2 :
        $tipoDoc = 'LE'; //LE
        break;
      case 3 :
        $tipoDoc = 'LC'; //LC
        break;
      case 4 :
        $tipoDoc = 'CI'; //CI
        break;
      case 5 :
        $tipoDoc = 'DNI'; //EXTrangero se utiliza DNI porque el ws libredeuda no los encuentra con EXT
        break;
      case 6 :
        $tipoDoc = 'PAS'; //DNI EXT
        break;        
      default:
        $tipoDoc = 'DNI'; //DNI
        break;
    }
    return $tipoDoc;
  }
}
