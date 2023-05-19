<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sys_multivalue';
    protected $primaryKey = 'rowid';
    protected $fillable = ['type','id', 'description', 'text_id', 'rowid'];
}
