<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysMultivalue extends Model
{
  protected $table = 'sys_multivalue';
  protected $primaryKey = 'rowid';
  protected $fillable = ['type','id', 'description', 'text_id', 'rowid'];



  public function sucursales()
  {
    $sucursales = SysMultivalue::where('type','SUCU')
                                ->whereNotIn('id',['2','3','20','80','101','102','104','121','150'])
                                ->pluck('description', 'id');
    return $sucursales;
  }

  public function paises()
  {
    $paises = SysMultivalue::where('type','PAIS')->pluck('description', 'id');
    return $paises;
  }

  public function tipodocs()
  {
    $tipodocs = SysMultivalue::where('type','TDOC')->pluck('description', 'id');
    return $tipodocs;
  }

}