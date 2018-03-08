<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnsvCelExpedidor extends Model
{
  protected $table = 'ansv_cel_expedidor';
  protected $primaryKey = 'sucursal_id';
  protected $fillable = ['sucursal_id', 'id_cel_expedidor', 'safit_cem_id'];

  public function sysMultivalue(){
    $res = SysMultivalue::where('type', 'SUCU')->where('id', $this->sucursal_id)->first();
    return $res;
  }
}
