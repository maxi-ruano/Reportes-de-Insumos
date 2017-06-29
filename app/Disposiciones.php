<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disposiciones extends Model
{
  protected $table = 'disposiciones';
  protected $fillable = ['id','descripcion','estado',
                        'tramite_id','sys_user_id_otorgante'];

  public function user()
  {
    return $this->belongsTo('App\SysUsers', 'sys_user_id_otorgante', 'id');
  }

}
