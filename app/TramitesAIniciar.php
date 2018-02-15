<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SigeciPrestacion;
use App\SysMultivalue;
class TramitesAIniciar extends Model
{
  protected $table = 'tramites_a_inicar';
  protected $fillable = ['id', 'nombre', 'apellido','tipo_doc', 'tipo_tramite_sigeci', 'nro_doc', 'nacionalidad', 'sexo', 'estado',
                         'bop_cb', 'bop_monto', 'bop_fec_pag', 'bop_id', 'cem_id', 'tramite_sinalic_id'];

  public function tipoTramite()
  {
    $sigeciPrestacion = SigeciPrestacion::where('prestacion_id', $this->tipo_tramite_sigeci)->first();
    return $sigeciPrestacion->ws;
  }

  public function tipoDocText(){
    $tipoDocText = SysMultivalue::where('type','TDOC')->where('id', $this->tipo_doc)->first();
    return $tipoDocText->description;
  }
}
