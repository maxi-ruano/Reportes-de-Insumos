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

  public function getSexo(){
    if(isset(json_decode($this->metadata)->sexo))
      return json_decode($this->metadata)->sexo;
    else 
      return null;  
  }

  public function tipoDocLicta(){
    $tipoDoc;
    switch ($this->idtipodoc) {
      case 1 :
        $tipoDoc = 1; //DNI -> DNI
        break;
      case 2 :
        $tipoDoc = 1; //LE -> DNI
        break;
      case 3 :
        $tipoDoc = 1; //LC -> DNI
        break;
      case 4 :
        $tipoDoc = 1; //CI -> DNI
        break;
      case 5 :
        $tipoDoc = 1; //DNI EXT -> DNI
        break;
      case 6 :
        $tipoDoc = 4; //PASS -> PASS
        break;        
      default:
        $tipoDoc = 1; //DNI
        break;
    }
    return $tipoDoc;
  }
}
