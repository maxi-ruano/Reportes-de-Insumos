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

  public function getCentrosEmisores(){
    $centrosEmisores = AnsvCelExpedidor::selectRaw('ansv_cel_expedidor.*, sys_multivalue.description as name')
                        ->join('sys_multivalue','sys_multivalue.id','ansv_cel_expedidor.sucursal_id')
                        ->where('sys_multivalue.type','SUCU')
                        ->whereRaw("ansv_cel_expedidor.safit_cem_id <> '' ")
                        ->orderby('sys_multivalue.description')
                        ->get();
    
    return $centrosEmisores;
  }
}
