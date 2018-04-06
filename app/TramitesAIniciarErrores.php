<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TramitesAIniciarErrores extends Model
{
    protected $table = 'tramites_a_iniciar_errores';
    protected $fillable = ['description', 'estado_error', 'request_ws', 'response_ws','tramites_a_iniciar_id'];
    public function textEstado(){

      switch ($this->estado_error) {
        case 1:
          $res = 'comletarTurnosEnTramitesAIniciar';
          break;
        case 2:
          $res = 'completarBoletasEnTramitesAIniciar';
          break;
        case 3:
          $res = 'emitirBoletasVirtualPago';
          break;
        case 4:
          $res = 'verificarLibreDeudaDeTramites';
          break;
        case 5:
          $res = 'verificarBuiTramites';
          break;
        case 6:
          $res = 'enviarTramitesASinalic';
          break;
        default:
          $res = 'ninguno';
          break;
      }

      return $res;
    }
}
