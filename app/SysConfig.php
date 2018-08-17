<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysConfig extends Model
{
    protected $table = 'sys_config';
    protected $primaryKey = ['name', 'param'];
    public $incrementing = false;
    const UPDATED_AT = 'modification_date';
}
