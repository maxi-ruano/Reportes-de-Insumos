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

  public function getMetadata(){
    $cadena = $this->metadata;
    $cadena = str_replace("\\\u00e1", "á", $cadena);
    $cadena = str_replace("\\\u00e9", "é", $cadena);
    $cadena = str_replace("\\\u00ed", "í", $cadena);
    $cadena = str_replace("\\\u00f3", "ó", $cadena);
    $cadena = str_replace("\\\u00fa", "ú", $cadena);
    $cadena = str_replace("\\\u00f1", "ñ", $cadena);
    $array = json_decode($cadena);
    //var_dump($this->metadata.'   IDCITA: '.$this->idcita.' cadena: '.$cadena); die();
    return $array;
  }

  public function nacionalidad(){
    $metadata = $this->getMetadata();
    if(isset($metadata->nacionalidad))
      return $metadata->nacionalidad;
    else
      return null;
  }

  public function fechaNacimiento(){
    $metadata = $this->getMetadata();
    if(isset($metadata->FechaNacimiento))
      return date($metadata->FechaNacimiento);
    else
      return null;
  }

  public function getSexo(){
    $metadata = $this->getMetadata();
    if(isset($metadata->sexo))
      return $metadata->sexo;
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
