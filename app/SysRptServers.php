<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysRptServers extends Model
{
  protected $table = 'sys_rpt_servers';
  protected $primaryKey = 'sucursales_id';
  protected $fillable = ['sucursales_id', 'ip', 'port', 'descricion'];

}
