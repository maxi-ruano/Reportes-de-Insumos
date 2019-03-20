<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'sucursal', 'sys_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sucursalTexto(){
        $sucursal = SysMultivalue::where('type','SUCU')->where('id', $this->sucursal)->first();

        if($sucursal)
            return $sucursal->description;
        else
            return "";  
    }

    public function usersLicta(){
        $sys_users = \DB::table('sys_users')
                    ->selectRaw(" CONCAT(first_name,' ', last_name,' - ', sys_multivalue.description, ' (', s.description, ') ') as name, sys_users.id")
                    ->join('sys_multivalue', function($join){
                        $join->on('sys_multivalue.id', '=', 'sys_users.sucursal')
                            ->where('sys_multivalue.type', 'SUCU');
                    })
                    ->leftjoin('sys_multivalue as s', function($join){
                        $join->on('s.id', '=', 'sys_users.sector')
                            ->where('s.type', 'STAT');
                    })
                    ->where('locked',false)
                    ->whereNull('sys_users.end_date')
                    ->orderby('sys_users.first_name')
                    ->orderby('sys_users.last_name')
                    ->pluck('name', 'id');

        return $sys_users;  
    }
}