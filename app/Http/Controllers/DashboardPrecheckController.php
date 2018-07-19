<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPrecheckController extends Controller
{
    public function errores(){
        return "";
    }

    public function resumenVerificaciones(){
        $results = DB::select('select s.fecha as "FECHA", cs.count as "CANT TURNOS"
                                , count ( case when v.validation_id = 1 then 1 else null end) as "EMISIONES SAFIT"
                                , count ( case when v.validation_id = 2 then 1 else null end) as "VERIFICACIONES_LIBRE_DEUDA"
                                , count ( case when v.validation_id = 3 then 1 else null end) as "BUIS_PAGO"
                                
                                from
                                (select count (idcita) from sigeci where fecha = :fecha) cs
                                ,sigeci s
                                ,validaciones_precheck v
                                , tramites_a_iniciar t
                                
                                where
                                t.id = v.tramite_a_iniciar_id
                                and s.tramite_a_iniciar_id = t.id
                                and s.fecha = :fecha
                                and v.validado = true
                                
                                group by s.fecha, cs.count', ['fecha' => '2018-05-09']);    
        return dd($results);
    }
}
