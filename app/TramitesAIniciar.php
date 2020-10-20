<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SigeciPrestacion;
use App\SysMultivalue;
use App\TramitesHabilitados;
use App\Sigeci;

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
    $tdoc = SysMultivalue::where('type','TDOC')->where('id', $this->tipo_doc)->first();
    if($tdoc)
      return $tdoc->description;
    else
      return "";
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

  public function sucursalTexto($id){
    $sucursal = SysMultivalue::where('type','SUCU')->where('id', $id)->first();
    if($sucursal)
      return $sucursal->description;
    else
      return "";  
  }

  public function motivo(){
    $motivo = \DB::table('tramites_habilitados')
                    ->join('tramites_habilitados_motivos','tramites_habilitados_motivos.id','tramites_habilitados.motivo_id')
                    ->where('tramites_habilitados.tramites_a_iniciar_id', $this->id)
                    ->orderby('tramites_habilitados','desc')
                    ->first();

    if($motivo)
        return $motivo->description;
    else
        return "";  
  }
 
  public function fechaTurno(){
  	$fecha_sigeci = null;
        if($this->sigeci_idcita){
        	$fecha_sigeci = Sigeci::find($this->sigeci_idcita)->fecha;
        }
        $turno_sath = TramitesHabilitados::where('tramites_a_iniciar_id',$this->id)->orderby('id','DESC')->first();

        if(count($turno_sath)){
                $fecha_th = $turno_sath->fecha;
                $fecha = ($fecha_sigeci > $fecha_th)? $fecha_sigeci : $fecha_th;
        }else{
                $fecha = $fecha_sigeci;
        }
        return $fecha;
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
        $tipoDoc = 4; //CI
        break;
      case  4 :
        $tipoDoc = 6; //PAS -> PAS
        break;
      case  5 :
        $tipoDoc = 7; //INS 
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
        $tipoDoc = 'CI'; //CI
        break;
      case 4 :
        $tipoDoc = 'PAS'; //LC
        break;
      case 5 :
        $tipoDoc = 'INS'; //LC
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
        $tipoDoc = 'CI'; //CI -> CI
        break;
      case 4 :
        $tipoDoc = 'PAS'; //PASS
        break;
      case 5 :
        $tipoDoc = 'INS'; //INS -> DNI
        break;
      default:
        $tipoDoc = 'DNI'; //DNI
        break;
    }

    //Casos DNI con Libreta Enrolamiento o Libreta Civica
    if($this->tipo_doc == 1 && intval($this->nro_doc) < 10000000 ){
      if($this->sexo == 'M')
        $tipoDoc = 'LE';
      if($this->sexo == 'F')
        $tipoDoc = 'LC';
    }
    
    return $tipoDoc;
  }
}
