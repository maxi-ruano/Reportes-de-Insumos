<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysRptServers extends Model
{
  protected $table = 'sys_rpt_servers';
  protected $primaryKey = 'sucursal_id';
  protected $fillable = ['sucursal_id', 'ip', 'port', 'descricion'];

}
