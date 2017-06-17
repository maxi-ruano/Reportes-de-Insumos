<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysUserRole extends Model
{
  protected $table = 'sys_user_role';
  protected $primaryKey = 'user_id';
  protected $fillable = ['user_id', 'role_id', 'modified_by'];
}
