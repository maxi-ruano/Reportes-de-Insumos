<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SysRoles extends Model
{
  protected $table = 'sys_roles';
  protected $primaryKey = 'role_id';
  protected $fillable = ['role_id', 'description', 'cte_php', 'is_admin'];
}
