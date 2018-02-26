<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigeciPaises extends Model
{
  protected $table = 'sigeci_paises';
  protected $primaryKey = 'id';
  protected $fillable = ['id', 'pais'];

  public function paisAnsv(){
    return $this->hasOne('App\AnsvPaises', 'id_sigeci_paises', 'id');
  }
}
