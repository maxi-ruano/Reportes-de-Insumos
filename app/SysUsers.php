<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SysUsers extends Authenticatable
{

  protected $table = 'sys_users';
  protected $remember_token = 'api_token';
  protected $primaryKey = 'id';
  const UPDATED_AT = 'fecha_ultima_accion';
  protected $fillable = [
      'username', 'password', 'last_log'
  ];

  protected $hidden = [
      'password', 'api_token',
  ];

  public function setPasswordAttribute($password){
    $this->attributes['password'] = md5($password);
  }

  public function getRememberToken()
  {
    return $this->api_token;
  }

  public function setRememberToken($value)
  {
    $this->api_token = $value;
  }

  public function getRememberTokenName()
  {
    return 'api_token';
  }

  public function SysUserRol()
  {
    return $this->hasMany('App\SysUserRol', 'user_id', 'id');
  }
}
