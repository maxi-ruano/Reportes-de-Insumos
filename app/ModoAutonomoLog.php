<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModoAutonomoLog extends Model
{
    protected $table = 'modo_autonomo_log';
    protected $fillable = ['ws', 'description'];
}
