<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sigeci;

class SigeciController extends Controller
{
    //private $prestacionesCursos = ['1543','1604']; //Ignorar los Cursos

    //Consulta general sobre los Turnos asignados en Sigeci
    public function getTurnos($fecha, $viewAll = '') {
        
        $turnos = Sigeci::where('fecha',$fecha);        
        //Solo si requiere listar todos los turnos incluyendo los cursos
        if($viewAll=='')
            $turnos->whereNotIn('idprestacion',$this->prestacionesCursos);
        
        return $turnos->get();
    }
}
