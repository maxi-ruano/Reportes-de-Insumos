<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sigeci extends Model
{
  protected $table = 'sigeci';
  protected $primaryKey = 'idcita';
  protected $fillable = ['idcita', 'idorganismo', 'idsede', 'descsede', 'idprestacion', 'descprestacion', 'fecha', 'hora', 'estado', 'idtipodoc',
                         'tipodoc', 'numdoc', 'nombre', 'apellido', 'telefono', 'email', 'metadata', 'sucroca', 'tramite_a_iniciar_id'];
  public $timestamps = false;                         

  public function nacionalidad(){
    return json_decode($this->metadata)->nacionalidad;
  }

  public function fechaNacimiento(){
    if(isset(json_decode($this->metadata)->FechaNacimiento))
      return date(json_decode($this->metadata)->FechaNacimiento);
    else 
      return null;  
  }
}
